<?php
//get the incoming data and save it to the DB.

	if($_POST)
	{
		$message = "";
		
		//get the question from the HTTP POST variables
		$answer = $_POST['answer'];	
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
		//get the IP address of the client. We want to make sure the client isn't submitting more than once.
		$ipAddress = null;
		 foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) 
		 {
			if (array_key_exists($key, $_SERVER) === true) 
			{
				foreach (explode(',', $_SERVER[$key]) as $ip) 
				{
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) 
					{
						$ipAddress = $ip;
						break;
					}
				}
				if($ipAddress != null)
				{
					break;
				}
			}
		}

		//now query the DB and make sure this ip address hasn't submitted anything for this question id
		
		$result = mysql_query("SELECT * FROM response WHERE question_id = $questionId AND ipaddress = '$ipAddress'");
		$alreadyThere = false;
		while($row = mysql_fetch_array($result))
		{
			$alreadyThere = true;
			break;
		}
		
		if($alreadyThere)
		{
			$message = "You've already answered the current question";
			$url = str_replace("responded.php", "results.php", $_SERVER["REQUEST_URI"]);
			$subMessage = 'View the results <a href="'.$url.'">here</a>.';
		}
		else
		{ //they haven't ansered the question so inert the question
			$answer = intval($_POST['answer']);
			mysql_query("INSERT INTO response (question_id, ipaddress, response) VALUES ('$questionId', '$ipAddress', $answer)");
			$message = 'Thanks for the response';
			$url = str_replace("responded.php", "results.php", $_SERVER["REQUEST_URI"]);
			$subMessage = 'View the results <a href="'.$url.'">here</a>.';
		}
		
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