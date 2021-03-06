<?php
	session_start();
	include_once("MySQL.class.php");

	// Connect to the database
	$db = MySQL::getInstance();

	// Identify the visitor
	$visitor_id = @$_COOKIE['visitor_id'];
	if(is_nan($visitor_id) OR !MySQL::exist('visitor', 'id', $visitor_id))
	{
		$db->query("INSERT INTO visitor VALUES (NULL)");
		$visitor_id = $db->lastInsertId();
		setcookie('visitor_id', $visitor_id, time()+60*60*24*365);
	}

	// If a survey has been sent
	if(!empty($_POST) AND !empty($_SESSION['project_id']))
	{
		if(@$_POST['token'] == $_SESSION['token'])
		{
			$grade = @$_POST['grade'];
			$explanation = @$_POST['explanation'];

			if(!is_nan($grade) AND $grade >= 0 AND $grade <= 5)
			{
				$insert_answer = $db->prepare("INSERT INTO answer
												(project_id, grade, visitor_id, explanation, time)
												VALUES(?,?,?,?,?)");
				$insert_answer->bindValue(1, $_SESSION['project_id'], PDO::PARAM_INT);
				$insert_answer->bindValue(2, $grade, PDO::PARAM_INT);
				$insert_answer->bindValue(3, $visitor_id, PDO::PARAM_INT);
				$insert_answer->bindValue(4, $explanation, PDO::PARAM_STR);
				$insert_answer->bindValue(5, time(), PDO::PARAM_INT);
				$insert_answer->execute();
			}
		}
		// If the token is invalid, it considers that the visitor has sent a answer clicking twice
		// so it displays a success sentence for the first answer
		$success = true;
	}

	// Get a project that the visitor hasn't yet answered and which have the fewest nb_answers as possible
	$project = $db->query(
		"SELECT * FROM project
		WHERE NOT EXISTS (SELECT * FROM answer WHERE project.id = answer.project_id AND answer.visitor_id = $visitor_id)
		ORDER BY nb_answers, RAND()
		LIMIT 1")->fetchAll();
	
	// If the visitor has done every projects
	if(empty($project))
	{
		$no_project = true;
	}
	else
	{
		$project = $project[0];
		$_SESSION['project_id'] = $project['id'];
		$_SESSION['token'] = rand();
	}

	include('header.php');
?>

<?php if(empty($no_project)) { ?>
	<div class="container">
		<div class="page-header">
			<h1>
				<a href="https://www.github.com/<?= $project['link_github'] ?>" target="_blank" >
					<?= str_replace("/", " / ", $project['link_github']) ?>
				</a>
			</h1>
		</div>
		<p class="lead">Is the project <strong>maintained</strong>?</p>
		<p>
			<form method="post" action="survey.php" >
				No = 1 ; Yes = 5<br />
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-primary">
						<input type="radio" name="grade" value="0"> Don't know
					</label>
					<?php
						for($i = 1; $i <= 5; $i++)
							echo '
							<label class="btn btn-primary">
								<input type="radio" name="grade" value="'.$i.'"> '.$i.'
							</label>
							';
					?>
				</div>
				<br /><br />
				Explain why (optional)<br />
				<textarea name="explanation" rows="5" cols="70" ></textarea>
				<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
				<br />
				<input type="submit" value="Submit" />
			</form>
		</p>
		<?php
			if(!empty($success))
			{
				echo '<p style="color:green;" >Thanks for answering the survey! Give your opinion about another project!</p>';
			}
		?>
		<?php
			/*// DEBUG
			$answers = $db->query("SELECT * FROM answer")->fetchAll();
			$projects2 = $db->query("SELECT * FROM project")->fetchAll();
			foreach ($projects2 as $i => $project) {
				$projects[$project['id']] = $projects2[$i];
			}
			foreach ($answers as $answer) {
				if($visitor_id == $answer['visitor_id'])
					echo '<p>'.$projects[$answer['project_id']]['link_github'].
					' '.$answer['grade'].
					' '.substr($answer['explanation'],0,30).
					'...</p>';
			}
			//*/
		?>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="survey/bootstrap-3.2.0/js/bootstrap.min.js"></script>
	<script>$('.btn').button();</script>
<?php } else { ?>
	<div class="container">
		<div class="page-header">
			<h1>GitRank</h1>
		</div>
		<p class="lead"><br />You have given your opinion about all projects! Thank you very much!<br /></p>
		<p>If you would contact us about the project, send an email to <em>schadoc_alex@hotmail.fr</em></p>
	</div>
<?php
			}
	echo '<br />';
	include('footer.php');
?>