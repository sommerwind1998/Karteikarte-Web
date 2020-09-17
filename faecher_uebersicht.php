<?php

//Übersicht der Fächer

include "html_helper.php";

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

session_start();
$klasse = $_SESSION["klasse"];
$user = $_SESSION["user"];

$admin=false;
$sql = 'SELECT admin FROM benutzer WHERE benutzer_name = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user);
$stmt->execute();
$result = $stmt->get_result();
if ($result->fetch_assoc()["admin"] == 1)
{
	$admin=true;
}

$anzahl_karteikarten = [];
$sql = 'SELECT COUNT(k.karteikarte_id), f.fach_id FROM karteikarte k
		INNER JOIN thema t ON k.thema = t.thema_id
		INNER JOIN fach f ON t.fach = f.fach_id
		WHERE f.klasse = ?
		GROUP BY t.fach
		';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $klasse);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc())
{
	$anzahl_karteikarten[(int)$row["fach_id"]] = $row["COUNT(k.karteikarte_id)"];
	}
$sql = 'SELECT fach_id, fach_name FROM fach WHERE klasse = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $klasse);
$stmt->execute();
$result = $stmt->get_result();

$output = '
<h1>Fächer</h1>
	<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Fach</th>
	  <th scope="col">Karteikarten</th>
	  ';
if ($admin)
{
	$output .= '<th scope="col">Löschen</th>';
}	
$output .= '
    </tr>
  </thead>
  <tbody>
';

$highlight_id = $_GET["highlight"];

while($row = $result->fetch_assoc())
{
	$fach_id = $row["fach_id"];
	$fach_name = $row["fach_name"];
	
	$highlight = '';
	
	if ($fach_id == $highlight_id)
	{
		$highlight = 'class="table-success"';
	}
	
	$anzahl = 0;
	if (isset($anzahl_karteikarten[$fach_id]))
	{
		$anzahl = $anzahl_karteikarten[$fach_id];
	}
	
	$output .= '
    <tr '.$highlight.'>
      <td style="width:70%"><a href="fach.php?fach='.$fach_id.'">'.$fach_name.'</a></td>
	  <td style="width:20%">'.$anzahl.'</td>
	  ';
if ($admin)
{
	$output .= '<th scope="col">
		<a href="loesche_fach.php?fach='.$fach_id.'">
			<img src="delete.png" width="30" height="30" alt="Löschen">
		</a>
	</th>';
}	
$output .= '</tr>
	';
	
}

$output .= '
		</tbody>
	</table>
		<a href="neues_fach_form.php"> <button class="btn btn-primary btn-lg">Fach hinzufügen</button> </a>
';


session_write_close();
$conn->close(); 

echo(create_html_document ($output));
