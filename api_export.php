<?php

//api for exporting to the desktop app

$data = get_post_data();

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$code = $data["Code"];

$output = [];
$output["Thema"] = get_thema_name($code, $conn);
$output["Karteikarten"] = get_karteikarten($code, $conn);

echo(json_encode($output));

$conn->close(); 

//------------- Helpers ----------------

function get_karteikarten ($code, $conn)
{
	$sql = 'SELECT f.frage_text, a.antwort_text, f.frage_bild, a.antwort_bild 
			FROM thema t 
			INNER JOIN karteikarte k ON t.thema_id=k.thema 
			INNER JOIN frage f ON f.frage_id=k.frage 
			INNER JOIN antwort a ON a.antwort_id=k.antwort 
			WHERE t.code = ?';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $code);
	$stmt->execute();
	$result = $stmt->get_result();
		
	$ret = [];	
		
	while($row = $result->fetch_assoc()){
		$frage = $row["frage_text"];
		$antwort = $row["antwort_text"];
		if ($row["frage_bild"] != null)
		{
			$sql_frage = 'SELECT bild_daten FROM bild WHERE bild_id = ?';
			$stmt_frage = $conn->prepare($sql_frage);
			$stmt_frage->bind_param('i', $row["frage_bild"]);
			$stmt_frage->execute();
			$res_f = $stmt_frage->get_result();
			$frage_bild = $res_f->fetch_assoc()["bild_daten"];
		}
		
		if ($row["antwort_bild"] != null)
		{
			$sql_antwort = 'SELECT bild_daten FROM bild WHERE bild_id = ?';
			$stmt_antwort = $conn->prepare($sql_frage);
			$stmt_antwort->bind_param('i', $row["antwort_bild"]);
			$stmt_antwort->execute();
			$res_a = $stmt_antwort->get_result();
			$antwort_bild = $res_a->fetch_assoc()["bild_daten"];
		}
		
		$array = ["Frage" => $frage,
					"Antwort" => $antwort,
					"Bild Frage" => unserialize($frage_bild),
					"Bild Antwort" => unserialize($antwort_bild)];
		$ret[] = $array;
	}
	
	return $ret;
}

function get_thema_name ($code, $conn)
{
		$sql = 'SELECT thema_name FROM thema WHERE code = ?';
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $code);
		$stmt->execute();
		$result = $stmt->get_result();
		$name = $result->fetch_assoc()["thema_name"];
		return ["Thema" => $name];
}

function get_post_data ()
{
	$json = file_get_contents('php://input');
	if (!isValidJson($json))
	{
		return;
	}
	$data = json_decode($json, true);
	return $data;
}

function isValidJson($string) {
   json_decode($string);
   return json_last_error() == JSON_ERROR_NONE;
}