<?php

//für Admin: Fach löschen

$fach = $_GET["fach"];
session_start();
$nutzername = $_SESSION["user"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$sql = 'SELECT admin FROM benutzer WHERE benutzer_name = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $nutzername);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc()["admin"];

if ($admin == 1)
{
	$fach_sql = 'DELETE FROM fach WHERE fach_id = ?';
	$stmt = $conn->prepare($fach_sql);
	$stmt->bind_param('i', $fach);
	$stmt->execute();
}

$conn->close(); 

session_write_close();

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=faecher_uebersicht.php?highlight=0" />
		</head>
	</html>
');
