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

$sql = 'SELECT thema_name, thema_id FROM thema WHERE fach = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $fach);
$stmt->execute();
$result = $stmt->get_result();

$output = '
<h1>Themen</h1>
	<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Thema</th>
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

while($row = $result->fetch_assoc())
{
	$thema_id = $row["thema_id"];
	$thema_name = $row["thema_name"];
	
	$output .= '
    <tr>
      <td style="width:80%">'.$thema_name.'</td>
      <td style="width:20%">
		<a href="thema.php?thema='.$thema_id.'">
			<img src="link.png" width="30" height="30" alt="">
		</a>
	  </td>';
if ($admin)
{
	$output .= '<th scope="col">
		<a href="loesche_thema.php?thema='.$thema_id.'">
			<img src="delete.png" width="30" height="30" alt="">
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
