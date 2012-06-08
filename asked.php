<?php
//get the incoming data and save it to the DB.

	if($_POST)
	{
		//get the question from the HTTP POST variables
		$question = $_POST['question'];
		//turn the line returns into <br/>
		$question = str_replace("\n", "<br/>", $question);
		//connect to the database
		$con = mysql_connect("localhost","survey","password");
		//if we didn't connect exit and print the error
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		//chose the database we want to use
		mysql_select_db("survey", $con);
		
		//insert the question into the database.
		mysql_query("INSERT INTO questions (questionText, open) VALUES ('$question', 1)");
		//update the message
		$message = 'Question:<br/><br/><div style="margin-left:30px;">'.$question.'</div><br/> has been asked.';
		//setup the URL where the results will be
		$url = str_replace("asked.php", "results.php", $_SERVER["REQUEST_URI"]);
		$subMessage = 'View the results <a href="'.$url.'">here</a>.';
		mysql_close($con);
	}
	else //not a result of a submission
	{
		$message = "you must first submit a question";
		$subMessage ="";
	}

?>
<html>
	<head>
		<title>iLab Internal Survey System - Ask</title>
	</head>
	<body>
		<h2> <?php echo $message;?></h2>			
		<br/>
		<?php echo $subMessage;?>
	</body>
</html>