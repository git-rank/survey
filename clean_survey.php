<?php
	session_start();
	include_once("MySQL.class.php");

	// Connect to the database
	$db = MySQL::getInstance();

	$db->query("DELETE FROM answer WHERE 1");
	$projects = $db->query("SELECT link_github FROM project WHERE 1")->fetchAll();
	foreach ($projects as $project) {
		echo $project['link_github'] . '<br />';
	}