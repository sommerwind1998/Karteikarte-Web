<?php

//login processing function

$nutzername = $_POST["username"];
$passwort = $_POST["password"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
mysqli_set_charset($conn,"utf8");

if ($klasse = authentifizierung($nutzername, $passwort, $conn))
{
	session_start();
	$_SESSION["user"] = $nutzername;
	$_SESSION["klasse"] = $klasse;
	
	echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=index.php" />
		</head>
	</html>
	');
}
else
{
	echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=login_form.php" />
		</head>
		<body>
			<script>alert("Nutzername oder Passwort falsch!")</script>
		</body>
	</html>
	');
}

$conn->close(); 

//---------------- Helpers ----------------------

function authentifizierung ($nutzername, $passwort, $conn)
{	
	$sql = 'SELECT klasse, passwort_hash FROM benutzer WHERE benutzer_name=?;';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $nutzername);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$hash = $row["passwort_hash"];
	if (password_verify($passwort, $hash))
	{
		return $row["klasse"]; 
	}
	return false;
}
