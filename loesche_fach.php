<?php

//für Admin: Fach löschen

include "html_helper.php";

$fach = $_GET["fach"];
session_start();
$nutzername = $_SESSION["user"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
mysqli_set_charset($conn,"utf8");

if (is_admin())
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