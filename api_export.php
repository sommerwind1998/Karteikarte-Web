<?php

//api for exporting to the desktop app

if ($data = get_post_data())
{
	$params = include("datenbankparameter.php");
	$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
	mysqli_set_charset($conn,"utf8");

	$code = $data["Code"];

	$output = [];
	$output["Kontext"] = get_thema($code, $conn);
	$output["Karteikarten"] = get_karteikarten($code, $conn);

	echo(json_encode($output));

	$conn->close(); 
}
else
{
	echo("INVALID JSON");
}

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
		$frage_bild = null;
		
		if ($row["frage_bild"] != null)
		{
			$sql_frage = 'SELECT bild_daten FROM bild WHERE bild_id = ?';
			$stmt_frage = $conn->prepare($sql_frage);
			$stmt_frage->bind_param('i', $row["frage_bild"]);
			$stmt_frage->execute();
			$res_f = $stmt_frage->get_result();
			$frage_bild = $res_f->fetch_assoc()["bild_daten"];
		}
		
		$antwort_bild = null;
		if ($row["antwort_bild"] != null)
		{
			$sql_antwort = 'SELECT bild_daten FROM bild WHERE bild_id = ?';
			$stmt_antwort = $conn->prepare($sql_antwort);
			$stmt_antwort->bind_param('i', $row["antwort_bild"]);
			$stmt_antwort->execute();
			$res_a = $stmt_antwort->get_result();
			$antwort_bild = $res_a->fetch_assoc()["bild_daten"];
		}
		
		$array = ["Frage" => $frage,
					"Antwort" => $antwort,
					"BildFrage" => unserialize($frage_bild),
					"BildAntwort" => unserialize($antwort_bild)];
		$ret[] = $array;
	}
	
	return $ret;
}

function get_thema ($code, $conn)
{
		$sql = 'SELECT t.thema_name, f.fach_name FROM thema t INNER JOIN fach f ON t.fach=f.fach_id WHERE t.code = ?';
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $code);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$thema = $row["thema_name"];
		$fach = $row["fach_name"];
		return ["Thema" => $thema, "Fach" => $fach];
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
