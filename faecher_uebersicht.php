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
      <th scope="col">Details</th>
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
	
	$output .= '
    <tr '.$highlight.'>
      <td style="width:80%">'.$fach_name.'</td>
      <td style="width:20%">
		<a href="fach.php?fach='.$fach_id.'">
			<img src="link.png" width="30" height="30" alt="">
		</a>
	  </td>';
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
