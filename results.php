<?php
//get the incoming data and save it to the DB.

error_reporting(E_ALL); 
ini_set( 'display_errors','1');

	$message = "";
	

	//connect to the database
	$con = mysql_connect("localhost","survey","password");
	//if we didn't connect exit and print the error
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	
	//chose the database we want to use
	mysql_select_db("survey", $con);
	//first get the id of the most recent question
	$result = mysql_query("SELECT * FROM questions ORDER BY date DESC LIMIT 1");
	//get the data
	$questionText = "";
	$questionId = null;
	while($row = mysql_fetch_array($result))
	{
		$questionText = $row['questionText'];
		$questionId = $row['id'];
	}
	
	//init the results array
	$results = array();
	$totalResponses = 0;
	
	//now loop from 0 to 9 and query the results
	for($i = 0; $i < 10; $i++)
	{
		//query the DB
		$query = mysql_query("SELECT count(id) as responseCount FROM response WHERE question_id = $questionId AND response = $i");
		while($row = mysql_fetch_array($query))
		{
			$results[$i] = $row['responseCount'];
			$totalResponses += $row['responseCount'];
		}
	}
	
	mysql_close($con);	

?>
<html>
	<head>
		<title>iLab Internal Survey System - Ask</title>
		<style type="text/css">
			table{
				border-collapse:collapse;
			}
		</style>
	</head>
	<body>
		<h1>Responses to question:</h1>			
		<h2 style="margin-left:30px;"><?php echo $questionText; ?></h2>
		<br/>
		<br/>
		<table>
			<tr>
				<?php
					for($i = 0; $i < 10; $i++)
					{
						echo "<td style=\"border: solid 1px black;background:#ccccff;\">Answered: $i</td>";
					}
				?>
			</tr>
			<?php
				for($j = 100; $j >= 0; $j--)
				{
					echo "<tr>";
					for($i = 0; $i < 10; $i++)
					{
						$percentage = intval(100 * (floatval($results[$i]) / floatval($totalResponses)));
						$backGroundColor = "#fff";
						if($percentage >= $j)
						{
							$backGroundColor = "#ffcccc";
						}
						echo "<td style=\"height:2px;border-left: solid 1px black;border-right: solid 1px black; background:$backGroundColor;\"></td>";
					}
					echo "</tr>";
					
				}
			?>
			<tr>
				<?php
					for($i = 0; $i < 10; $i++)
					{
						$numberReponses = $results[$i];
						echo "<td style=\"border: solid 1px black;background:#ccffcc;\">Users: $numberReponses</td>";
					}
				?>
			</tr>
		</table>
		
		<br/>
		Total number of responses: <?php echo $totalResponses; ?>
	</body>
</html>