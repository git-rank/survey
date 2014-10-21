<?php
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

	// Get a project that the visitor hasn't yet answered and which have the fewest nb_answers as possible
	$project = $db->query(
		"SELECT * FROM project
		WHERE NOT EXISTS (SELECT * FROM answer WHERE project.id = answer.project_id AND answer.visitor_id = $visitor_id)
		ORDER BY nb_answers, RAND()
		LIMIT 1")->fetchAll()[0];
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
				<form type="post" action="survey.php" >
					Worst
					<div class="btn-group" data-toggle="buttons">
						<?php
							for($i = 1; $i <= 5; $i++)
								echo '
								<label class="btn btn-primary">
									<input type="radio" name="options" id="option1" checked> '.$i.'
								</label>
								';
						?>
					</div>
					Best
					<br /><br />
					Explain why (optionnal)<br />
					<textarea rows="5" cols="70" ></textarea>
					<br />
					<input type="submit" value="Submit" />
				</form>
			</p>
			
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
	</body>
</html>