<?php

//Thema in Datenbank updaten (zu anderem Fach verschieben)

$thema_id = $_POST["thema"];
$fach_id = $_POST["fach"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$sql = 'UPDATE thema SET fach = ? WHERE thema_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $fach_id, $thema_id);
$stmt->execute();

$id = $conn->insert_id;

$conn->close(); 

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=fach.php?fach='.$fach_id.'&highlight='.$thema_id.'" />
		</head>
	</html>
');

//----------- Helpers --------

function get_fach_id ($fach_name, $conn)
{
	$sql = 'SELECT fach_id FROM fach WHERE fach_name = ?';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('i', $fach_name);
	$stmt->execute();
	return $stmt->get_result()->fetch_assoc()["fach_id"];
}