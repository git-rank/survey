<?php
	session_start();
	include_once("MySQL.class.php");

	// Connect to the database
	$db = MySQL::getInstance();

	$projects = $db->query(
		"SELECT link_github AS name, COUNT(*) AS nb_answers, AVG(grade) AS average_grade
		FROM answer, project
		WHERE answer.project_id = project.id
		AND grade >=1 AND grade <=5
		GROUP BY answer.project_id
		ORDER BY average_grade DESC")->fetchAll();

	include('header.php');
?>
<div class="container">
	<div class="page-header">
		<h1>GitRank - Results</h1>
	</div>
	<table>
		<tr>
			<th width="25%" >Name</td>
			<th width="25%" >Answers : 1-5</td>
			<th width="25%" >Average grade (1-5)</td>
			<th width="25%" >Answers : Don't know</td>
		</tr>
		<?php
			foreach ($projects as $p) {
				echo '<tr>
						<td>'.$p['name'].'</td>
						<td>'.$p['nb_answers'].'</td>
						<td>'.$p['average_grade'].'</td>
						<td>0</td>
					</tr>';
			}
		?>
	</table>
</div>
<?php
	include('footer.php');
?>