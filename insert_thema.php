<?php

//Thema in Datenbank speichern

$thema = $_POST["thema"];
$fach_id = $_POST["fach"];
session_start();
$klasse_id = $_SESSION["klasse"];
session_write_close();

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
mysqli_set_charset($conn,"utf8");

$klasse = get_klasse_name($klasse_id, $conn);
$code = generiere_download_code ($klasse, $thema) ;

$sql = 'INSERT INTO thema (thema_name, fach, code) VALUES (?, ?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('sis', $thema, $fach_id, $code);
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

//----------- Helpers --------

function get_klasse_name ($klasse_id, $conn)
{
	$sql = 'SELECT klasse_name FROM klasse WHERE klasse_id = ?';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('i', $klasse_id);
	$stmt->execute();
	return $stmt->get_result()->fetch_assoc()["klasse_name"];
}

//generiert Code für die Datenbank zum späteren Download aus Zeitstempel, Fach und Thema
function generiere_download_code ($klasse, $thema) 
{
	$code = (string) time();
	
	$string = $klasse.$thema;
	for ($pos=0; $pos<strlen($string); $pos++)
	{
		$ascii = (string) ord(substr($string, $pos));
	}
	//Quersumme
	$quersumme = 0;
	for ($pos=0; $pos<strlen($ascii); $pos++)
	{
		$number = intval(substr($string, $pos));
		$quersumme += $number;
	}
	$code .= $ascii;
	return $code;
}
