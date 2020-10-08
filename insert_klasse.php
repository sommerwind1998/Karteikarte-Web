<?php

//klasse in Datenbank speichern und Link generieren

$name = $_POST["name"];
$user = $_POST["username"];
$password = $_POST["password"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
mysqli_set_charset($conn,"utf8");

$uuid = generate_uuid_v4();

$sql = 'INSERT INTO klasse (klasse_name, uuid) VALUES (?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $name, $uuid);
$stmt->execute();
$klasse_id = $conn->insert_id;

$hash = password_hash($password, PASSWORD_DEFAULT);
$nutzer_sql = 'INSERT INTO benutzer (benutzer_name, passwort_hash, klasse, admin) VALUES (?, ?, ?, 1)';
$stmt = $conn->prepare($nutzer_sql);
$stmt->bind_param('ssi', $user, $hash, $klasse_id);
$stmt->execute();

$conn->close(); 

session_start();
$_SESSION["klasse"] = $klasse_id;
$_SESSION["user"] = $user;
session_write_close();

echo ('
	<html>
		<head>
			<meta http-equiv="refresh" content="0; url=einladen.php" />
		</head>
	</html>
');

//------------ Helpers --------------


//UUID Generator Quelle: https://www.php.net/manual/de/function.uniqid.php
function generate_uuid_v4 ()
{
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

		// 32 bits for "time_low"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),

		// 16 bits for "time_mid"
		mt_rand(0, 0xffff),

		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand(0, 0x0fff) | 0x4000,

		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand(0, 0x3fff) | 0x8000,

		// 48 bits for "node"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
}
