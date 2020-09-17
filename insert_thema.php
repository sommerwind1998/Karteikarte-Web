<?php

//Thema in Datenbank speichern

$thema = $_POST["thema"];
$fach_id = $_POST["fach"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$sql = 'INSERT INTO thema (thema_name, fach) VALUES (?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $thema, $fach_id);
$stmt->execute();

$id = $conn->insert_id;

$conn->close(); 

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=fach.php?fach='.$fach_id.'&highlight='.$id.'" />
		</head>
	</html>
');