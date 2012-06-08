<?php 
	//figure out the URL
	$url = $_SERVER["REQUEST_URI"]."respond.php";
?>
<html>
	<head>
		<title>iLab Internal Survey System</title>
	</head>
	<body>
		<h1>iLab Internal Survey System</h1>
		
		<h3>
			To respond to the current question go <a href="<?php echo $url ; ?>">here</a>
		</h3>

	</body>
</html>