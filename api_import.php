<?php

//API zum Import von Daten aus der Desktop-Anwendung

$json = serialize(get_post_data());
$data = json_decode($json);

var_dump ($data);

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$sql = make_sql ($data);
$conn->query($sql);

$conn->close(); 

//------------- Helpers ----------------

function make_sql ($data)
{
	$sql = "";
	
	//Bilder
	$bilder_daten = $data["Bild"];
	foreach ($bilder_daten as $bild)
	{
		$sql .= 'INSERT INTO  ("bild_daten") VALUES ('.serialize($bild["BildDaten"]).');';
	}
	
	//Antworten
	$_daten = $data["Antwort"];
	//$sql .= 'INSERT INTO  ("antwort_text", "antwort_bild") VALUES ();';
	
	//Fragen
	$_daten = $data["Frage"];
	//$sql .= 'INSERT INTO  ("frage_text", "frage_bild") VALUES ();';
	
	//Karteikarten
	$karteikarten_daten = $data["Karteikarte"];
	//$sql .= 'INSERT INTO karteikarte ("thema", "frage", "antwort") VALUES ();';
	
	//Fächer
	$_daten = $data["Fach"];
	//$sql .= 'INSERT INTO  ("fach_name", "klasse") VALUES ();';
	
	//Themen
	$_daten = $data["Thema"];
	//$sql .= 'INSERT INTO  ("thema_name", "fach", "code") VALUES ();';
	
	return $sql;
}

function get_post_data ()
{
	$json = file_get_contents('php://input');
	if (!isValidJson($json))
	{
		return;
	}
	$data = json_decode($json);
	return $data;
}

//generiert Code für die Datenbank zum späteren Download aus Zeitstempel, Fach und Thema
function topic_download_code ($group, $topic) 
{
	$code = (string) time();
	
	$string = $group.$topic;
	for ($pos=0; $pos<strlen($string); $pos++)
	{
		$ascii = (string) ord(substr($string, $pos));
	}
	//Quersumme
	$crossfoot = 0;
	for ($pos=0; $pos<strlen($ascii); $pos++)
	{
		$number = intval(substr($string, $pos));
		$crossfoot += $number;
	}
	$code .= $ascii;
	return $code;
}

function isValidJson($string) {
   json_decode($string);
   return json_last_error() == JSON_ERROR_NONE;
}