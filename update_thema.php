<?php

//Thema in Datenbank updaten (zu anderem Fach verschieben)

include "html_helper.php";

$thema_id = $_POST["thema"];
$fach_id = $_POST["fach"];

if (is_admin() AND is_logged_in())
{
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
}

else {
	echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=fach.php?fach='.$fach_id.'&highlight="0" />
		</head>
	</html>
');
}