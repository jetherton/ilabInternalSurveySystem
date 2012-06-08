<?php

	//get the latest question from the DB
	$con = mysql_connect("localhost","survey","password");
	//if we didn't connect exit and print the error
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	//chose the database we want to use
	mysql_select_db("survey", $con);
	
	//ask for the most recent question
	$result = mysql_query("SELECT * FROM questions ORDER BY date DESC LIMIT 1");
	//get the data
	$questionText = "";
	while($row = mysql_fetch_array($result))
	{
		$questionText = $row['questionText'];
	}

	mysql_close($con);
	

?>

<html>
	<head>
		<title>iLab Internal Survey System - Ask</title>
	</head>
	<body>
		<h1> Respond to this question:</h1>
		<h2 style="margin:30px;"><?php echo $questionText; ?></h2>
		<br/><br/>
		<form action="responded.php" method="post" enctype="multipart/form-data">
			<input type="radio" name="answer" value="0" /> 0<br />
			<input type="radio" name="answer" value="1" /> 1<br />
			<input type="radio" name="answer" value="2" /> 2<br />
			<input type="radio" name="answer" value="3" /> 3<br />
			<input type="radio" name="answer" value="4" /> 4<br />
			<input type="radio" name="answer" value="5" /> 5<br />
			<input type="radio" name="answer" value="6" /> 6<br />
			<input type="radio" name="answer" value="7" /> 7<br />
			<input type="radio" name="answer" value="8" /> 8<br />
			<input type="radio" name="answer" value="9" /> 9<br />
			<br/>
			<input type="submit"/>
		</form>

	</body>
</html>