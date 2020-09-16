<?php

//Logout Funktion

session_start();
$_SESSION["user"] = null;
$_SESSION["klasse"] = null;
	
echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=index.php" />
		</head>
	</html>
	');