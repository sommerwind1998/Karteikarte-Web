<?php

//API zum Import von Daten aus der Desktop-Anwendung

$data = get_post_data();

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

database_insert($data, $conn);

$conn->close(); 

//------------- Helpers ----------------

function database_insert ($data, $conn)
{
	if (!authentifizierung($data, $conn))
	{
		return;
	}
	
	//Bilder
	$bilder_daten = $data["Bild"];
	$bild_id_array = [];
	foreach ($bilder_daten as $bild)
	{
		//daten
		$bild_feld = (string) serialize($bild["BildDaten"]);
		//sql
		$sql = 'INSERT INTO bild (bild_daten) VALUES (?)';
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $bild_feld);
		$stmt->execute();
		
		$my_id = $conn->insert_id;
		$bild_id = $bild["BildID"];
		$bild_id_array[] = [$my_id, $bild_id];
	}
	
	//Antworten
	$antworten_daten = $data["Antwort"];
	$antwort_id_array = [];
	$bild_id = null;
	foreach ($antworten_daten as $antwort)
	{
		//daten
		$text = $antwort["Text"];
		$bild_given_id = $antwort["BildID"];
		if ($bild_given_id != null) //falls ein Bild eingetragen ist....
		{
			foreach ($bild_id_array as $finde_id_array) 
			//durchsuche den Array, in dem die IDs zugeordnet sind...
			{
				if($finde_id_array[1] == $bild_given_id) //... und finde die zugeordnete ID von der Desktop-DB
				{
					$bild_id = $finde_id_array[0]; //(speichere die ID von meiner DB)
					break;
				}
			}
		}
		
		//sql
		$sql = 'INSERT INTO antwort (antwort_text, antwort_bild) VALUES (?, ?);';
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('si', $text, $bild_id);
		$stmt->execute();
		
		$my_id = $conn->insert_id;
		$antwort_id = $antwort["AntwortID"];
		$antwort_id_array[] = [$my_id, $antwort_id];
	}
	
	//Fragen
	$fragen_daten = $data["Frage"];
	$frage_id_array = [];
	$bild_id = null;
	foreach ($fragen_daten as $frage)
	{
		//daten
		$text = $frage["Text"];
		$bild_given_id = $frage["BildID"];
		if ($bild_given_id != null) //falls ein Bild eingetragen ist....
		{
			foreach ($bild_id_array as $finde_id_array) 
			//durchsuche den Array, in dem die IDs zugeordnet sind...
			{
				if($finde_id_array[1] == $bild_given_id) //... und finde die zugeordnete ID von der Desktop-DB
				{
					$bild_id = $finde_id_array[0]; //(speichere die ID von meiner DB)
					break;
				}
			}
		}
		
		//sql
		$sql = 'INSERT INTO frage (frage_text, frage_bild) VALUES (?, ?);';
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('si', $text, $bild_id);
		$stmt->execute();
		
		$my_id = $conn->insert_id;
		$frage_id = $frage["FrageID"];
		$frage_id_array[] = [$my_id, $frage_id];
	}
	
	//Fächer
	$klasse_id = klassen_id ($data, $conn);
	$fächer_daten = $data["Fach"];
	$fach_id_array = [];
	
	$helper_sql = 'SELECT klasse_name FROM klasse WHERE klasse_id=?;';
	$stmt = $conn->prepare($helper_sql);
	$stmt->bind_param('i', $klasse_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$klassen_name = $result->fetch_assoc()["klasse_name"];
	
	foreach ($fächer_daten as $fach)
	{
		//daten
		$fach_name = $fach["Name"];
		$fach_name_lc = strtolower($fach_name);
		$klassen_name_lc = strtolower($klassen_name);
		
		//existiert das Fach bereits? --> nach Namen prüfen (case insensitive)
		$helper_sql = 'SELECT fach_id FROM fach WHERE lower(fach_name)=?;';
		$stmt = $conn->prepare($helper_sql);
		$stmt->bind_param('s', $fach_name_lc);
		$stmt->execute();
		$result = $stmt->get_result();
		$array = $result->fetch_assoc()
		if (isset($array["fach_id"])){
			$fach_id = $array["fach_id"]
			$fach_id_array[] = [$fach_id, $fach["FachID"]];
			continue; 
		}
		else
		{
		//sql
		$sql = 'INSERT INTO fach (fach_name, klasse) VALUES (?, ?);';
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('si', $fach_name, $klasse_id);
		$stmt->execute();
		
		$my_id = $conn->insert_id;
		$fach_id = $fach["FachID"];
		$fach_id_array[] = [$my_id, $fach_id];
		}
	}
	
	//Themen
	$themen_daten = $data["Thema"];
	$thema_id_array = [];
	foreach ($themen_daten as $thema)
	{
		//daten
		$thema_name = $thema["Name"];
		$thema_name_lc = strtolower($thema_name);
		//existiert das Thema bereits? --> nach Namen prüfen (case insensitive)
		$helper_sql = 'SELECT thema_id FROM thema WHERE lower(thema_name)=?;';
		$stmt = $conn->prepare($helper_sql);
		$stmt->bind_param('s', $thema_name_lc);
		$stmt->execute();
		$result = $stmt->get_result();
		$array = $result->fetch_assoc()
		if (isset($array["thema_id"])){
			$thema_id = $array["thema_id"]
			$thema_id_array[] = [$thema_id, $fach["ThemaID"]];
			continue; 
		}
		else
		{
		$code = generiere_download_code($klassen_name, $thema_name);
		$fach_given_id = $thema["ThemaID"];
		if ($fach_given_id != null) //falls ein Fach eingetragen ist....
		{
			foreach ($fach_id_array as $finde_id_array) 
			//durchsuche den Array, in dem die IDs zugeordnet sind...
			{
				if($finde_id_array[1] == $fach_given_id) //... und finde die zugeordnete ID von der Desktop-DB
				{
					$fach_id = $finde_id_array[0]; //(speichere die ID von meiner DB)
					break;
				}
			}
		}
		
		//sql
		$sql = 'INSERT INTO thema (thema_name, fach, code) VALUES (?, ?, ?);';
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ssi', $thema_name, $fach_id, $code);
		$stmt->execute();
		
		$my_id = $conn->insert_id;
		$thema_id = $thema["ThemaID"];
		$thema_id_array[] = [$my_id, $thema_id];
		}
	}
	
	//Karteikarten
	$karteikarten_daten = $data["Karteikarte"];
	foreach ($karteikarten_daten as $karteikarte)
	{
		//daten
		$thema_given_id = $karteikarte["ThemaID"];
		if ($thema_given_id != null) //falls ein Thema eingetragen ist....
		{
			foreach ($thema_id_array as $finde_id_array) 
			//durchsuche den Array, in dem die IDs zugeordnet sind...
			{
				if($finde_id_array[1] == $thema_given_id) //... und finde die zugeordnete ID von der Desktop-DB
				{
					$thema_id = $finde_id_array[0]; //(speichere die ID von meiner DB)
					break;
				}
			}
		}
		$frage_given_id = $karteikarte["FrageID"];
		if ($frage_given_id != null) //falls eine Frage eingetragen ist....
		{
			foreach ($frage_id_array as $finde_id_array) 
			//durchsuche den Array, in dem die IDs zugeordnet sind...
			{
				if($finde_id_array[1] == $frage_given_id) //... und finde die zugeordnete ID von der Desktop-DB
				{
					$frage_id = $finde_id_array[0]; //(speichere die ID von meiner DB)
					break;
				}
			}
		}
		$antwort_given_id = $karteikarte["AntwortID"];
		if ($antwort_given_id != null) //falls eine Antwort eingetragen ist....
		{
			foreach ($antwort_id_array as $finde_id_array) 
			//durchsuche den Array, in dem die IDs zugeordnet sind...
			{
				if($finde_id_array[1] == $antwort_given_id) //... und finde die zugeordnete ID von der Desktop-DB
				{
					$antwort_id = $finde_id_array[0]; //(speichere die ID von meiner DB)
					break;
				}
			}
		}
		//sql
		$sql = 'INSERT INTO karteikarte (thema, frage, antwort) VALUES (?, ?, ?);';
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('iii', $thema_id, $frage_id, $antwort_id);
		$stmt->execute();
	}

}

function authentifizierung ($data, $conn)
{
	$nutzername = $data["Benutzer"][0]["Benutzername"];
	$passwort = $data["Benutzer"][0]["Passwort"];
	
	$sql = 'SELECT passwort_hash FROM benutzer WHERE benutzer_name=?;';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $nutzername);
	$stmt->execute();
	$result = $stmt->get_result();
	$hash = $result->fetch_assoc()["passwort_hash"];
	if (password_verify($passwort, $hash))
	{
		return true; 
	}
	echo ("Authentifizierung fehlgeschlagen");
	return false;
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

function isValidJson($string) {
   json_decode($string);
   return json_last_error() == JSON_ERROR_NONE;
}

function klassen_id ($data, $conn){
	$nutzername = $data["Benutzer"][0]["Benutzername"];
	
	$sql = 'SELECT klasse FROM benutzer WHERE benutzer_name=?;';
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $nutzername);
	$stmt->execute();
	$result = $stmt->get_result();
	return $result->fetch_assoc()["klasse"];
}
