<?php

//für Admin: Thema löschen

$thema = $_GET["thema"];
session_start();
$nutzername = $_SESSION["user"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$sql = 'SELECT fach FROM thema WHERE thema_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $thema);
$stmt->execute();
$fach_id = $stmt->get_result()->fetch_assoc()["fach"];

$sql = 'SELECT admin FROM benutzer WHERE benutzer_name = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $nutzername);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc()["admin"];

if ($admin == 1)
{
	$thema_sql = 'DELETE FROM thema WHERE thema_id = ?';
	$stmt = $conn->prepare($thema_sql);
	$stmt->bind_param('i', $thema);
	$stmt->execute();
}

$conn->close(); 

session_write_close();

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=fach.php?fach='.$fach_id.'" />
		</head>
	</html>
');
