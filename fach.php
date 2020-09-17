<?php

//Übersicht der Themen eines Faches

include "html_helper.php";

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

session_start();
$fach = $_GET["fach"];
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

$sql = 'SELECT fach_name FROM fach WHERE fach_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $fach);
$stmt->execute();
$fach_name = $stmt->get_result()->fetch_assoc()["fach_name"];

$output = '
<h1>'.$fach_name.'</h1>
	<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Thema</th>
	  ';
if ($admin)
{
	$output .= '<th scope="col">Löschen</th>
				<th scope="col">Verschieben</th>';
}	
$output .= '
    </tr>
  </thead>
  <tbody>
';

$sql = 'SELECT thema_name, thema_id FROM thema WHERE fach = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $fach);
$stmt->execute();
$result = $stmt->get_result();

$highlight = "";
$highlight_id = 0;
if (isset($_GET["highlight"]))
{
	$highlight_id = $_GET["highlight"];
}

while($row = $result->fetch_assoc())
{
	$thema_id = $row["thema_id"];
	$thema_name = $row["thema_name"];
	
	if ($highlight_id == $thema_id)
	{
		$highlight = 'class="table-success"';
	}
	
	$output .= '
    <tr '.$highlight.'>
      <td style="width:80%">
		<a href="thema.php?thema='.$thema_id.'">
			'.$thema_name.'
		</a>
	  </td>';
if ($admin)
{
	$output .= '<th scope="col">
		<a href="loesche_thema.php?thema='.$thema_id.'">
			<img src="delete.png" width="30" height="30" alt="">
		</a>
	</th>
	<th scope="col">
		<a href="thema_verschieben_form.php?thema='.$thema_id.'">
			<img src="link.png" width="30" height="30" alt="">
		</a>
	</th>';
}	
$output .= '</tr>
	';
	
}

$output .= '</tbody>
</table>';

$output .= '
		</tbody>
	</table>
		<a href="neues_thema_form.php?fach='.$fach.'"> <button class="btn btn-primary btn-lg">Thema hinzufügen</button> </a>
';

session_write_close();
$conn->close(); 

echo(create_html_document ($output));
