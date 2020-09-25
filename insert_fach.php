<?php

//Fach in Datenbank speichern

$fach = $_POST["fach"];
session_start();
$klasse = $_SESSION["klasse"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
mysqli_set_charset($conn,"utf8");

$sql = 'INSERT INTO fach (fach_name, klasse) VALUES (?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $fach, $klasse);
$stmt->execute();

$id = $conn->insert_id;

$conn->close(); 

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=faecher_uebersicht.php?highlight='.$id.'" />
		</head>
	</html>
');