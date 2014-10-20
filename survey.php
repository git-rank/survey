<?php
	include_once("MySQL.class.php");

	// Connexion to the database
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

	echo '<pre>';
	var_dump($project);
	echo '</pre>';
?>