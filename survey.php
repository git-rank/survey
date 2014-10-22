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
		$grade = @$_POST['grade'];
		$explanation = @$_POST['explanation'];

		if(!is_nan($grade) AND $grade >= 1 AND $grade <= 5)
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

			$success = true;
		}
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
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>GitRank Survey</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap-3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<link href="bootstrap-3.2.0/css/bootstrap-theme.min.css" rel="stylesheet">

		<link href="sticky-footer.css" rel="stylesheet">

		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		<?php if(empty($no_project)) { ?>
			<div class="container">
				<div class="page-header">
					<h1>
						<a href="https://www.github.com/<?= $project['link_github'] ?>" target="_blank" >
							<?= str_replace("/", " / ", $project['link_github']) ?>
						</a>
					</h1>
				</div>
				<p class="lead">How maintainable is this project?</p>
				<p>
					<form method="post" action="survey.php" >
						Worst
						<div class="btn-group" data-toggle="buttons">
							<?php
								for($i = 1; $i <= 5; $i++)
									echo '
									<label class="btn btn-primary">
										<input type="radio" name="grade" value="'.$i.'"> '.$i.'
									</label>
									';
							?>
						</div>
						Best
						<br /><br />
						Explain why (optionnal)<br />
						<textarea name="explanation" rows="5" cols="70" ></textarea>
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
				
			</div>
			<div class="footer">
				<div class="container">
					<p class="text-muted">This survey complements the GitRank project
						realized by University of Technology of Compiègne's students.</p>
				</div>
			</div>

			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
			<script src="bootstrap-3.2.0/js/bootstrap.min.js"></script>
			<script>$('.btn').button();</script>
		<?php } else { ?>
			<div class="container">
				<div class="page-header">
					<h1>GitRank</h1>
				</div>
				<p class="lead"><br />You have given your opinion about all projects! Thank you very much!<br /></p>
				<p>If you would contact us about the project, send an email to <em>schadoc_alex@hotmail.fr</em></p>
			</div>
			<div class="footer">
				<div class="container">
					<p class="text-muted">This survey complements the GitRank project
						realized by University of Technology of Compiègne's students.</p>
				</div>
			</div>
		<?php } ?>
	</body>
</html>