<?php
	session_start();
	include_once("MySQL.class.php");

	// Connect to the database
	$db = MySQL::getInstance();

	$projects = $db->query(
		"SELECT link_github AS name,
		COUNT(*) AS nb_answers,
		SUM(IF(grade=0, 1, 0)) AS nb_answers_dont_know,
		SUM(grade) AS sum_grade
		FROM answer, project
		WHERE answer.project_id = project.id
		GROUP BY answer.project_id
		ORDER BY sum_grade DESC")->fetchAll();

	include('header.php');
?>
<div class="container">
	<div class="page-header">
		<h1>GitRank - Results</h1>
	</div>
	<table width="100%">
		<tr>
			<th width="40%" >Name</th>
			<th width="20%" >Answers</th>
			<th width="20%" >Average grade</th>
			<th width="20%" >Don't know</th>
		</tr>
		<?php
			foreach ($projects as $p) {
				$nb_answers_1_5 = $p['nb_answers'] - $p['nb_answers_dont_know'];
				if($nb_answers_1_5 > 0)
					$average_grade = $p['sum_grade'] / $nb_answers_1_5;
				else
					$average_grade = 0;

				echo '<tr>
						<td>'.$p['name'].'</td>
						<td>'.$nb_answers_1_5.'</td>
						<td>'.$average_grade.'</td>
						<td>'.$p['nb_answers_dont_know'].'</td>
					</tr>';
			}
		?>
	</table>
</div>
<?php
	include('footer.php');
?>