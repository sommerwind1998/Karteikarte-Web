<?php

//Nutzer in Datenbank speichern

$user = $_POST["username"];
$password = $_POST["password"];
$uuid = $_POST["uuid"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
mysqli_set_charset($conn,"utf8");

$klasse_sql = 'SELECT klasse_id FROM klasse WHERE uuid = ?';
$stmt = $conn->prepare($klasse_sql);
$stmt->bind_param('s', $uuid);
$stmt->execute();
$klasse_id = $stmt->get_result()->fetch_assoc()["klasse_id"];

$hash = password_hash($password, PASSWORD_DEFAULT);
$nutzer_sql = 'INSERT INTO benutzer (benutzer_name, passwort_hash, klasse) VALUES (?, ?, ?)';
$stmt = $conn->prepare($nutzer_sql);
$stmt->bind_param('ssi', $user, $hash, $klasse_id);
$stmt->execute();

$conn->close(); 

session_start();
$_SESSION["klasse"] = $klasse_id;
$_SESSION["user"] = $user;
session_write_close();

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=index.php" />
		</head>
	</html>
');