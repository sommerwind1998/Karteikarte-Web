<?php

//für Admin: Karteikarten löschen

include "html_helper.php";

$id = $_GET["id"];
session_start();
$nutzername = $_SESSION["user"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
mysqli_set_charset($conn,"utf8");

$sql = 'SELECT t.thema_id FROM thema t 
		INNER JOIN  karteikarte k ON t.thema_id = k.thema
		WHERE k.karteikarte_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$thema = $stmt->get_result()->fetch_assoc()["thema_id"];


if (is_admin())
{
	$sql = 'DELETE FROM karteikarte WHERE karteikarte_id = ?';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('i', $id);
	$stmt->execute();
}

$conn->close(); 

session_write_close();

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=thema.php?thema='.$thema.'" />
		</head>
	</html>
');