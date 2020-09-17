<?php

//api für die Fächer

$data = get_post_data();

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$klasse = klassen_id($data, $conn);
$output = get_fächer($klasse, $conn);

echo(json_encode($output));

$conn->close(); 

//------------ Helpers ---------

function klassen_id ($data, $conn){
	$nutzername = $data["Benutzer"][0]["Benutzername"];
	$passwort = $data["Benutzer"][0]["Passwort"];
	
	$sql = 'SELECT klasse, passwort_hash FROM benutzer WHERE benutzer_name=?;';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $nutzername);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$hash = $row["passwort_hash"];
	if(password_verify($passwort, $hash))
	{
		return $row["klasse"];
	}
	return null;
}

function get_fächer($klasse, $conn)
{
	$sql = 'SELECT fach_name FROM fach WHERE klasse = ?';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('i', $klasse);
	$stmt->execute();
	$result = $stmt->get_result();
	$ret = [];
	while ($row = $result->fetch_assoc())
	{
		$ret[] = $row["fach_name"];
	}
	return $ret;
}

function get_post_data ()
{
	$json = file_get_contents('php://input');
	if (!isValidJson($json))
	{
		return false;
	}
	$data = json_decode($json, true);
	return $data;
}

function isValidJson($string) {
   json_decode($string);
   return json_last_error() == JSON_ERROR_NONE;
}