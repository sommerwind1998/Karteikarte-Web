<?php

//Thema verschieben form für admin

include "html_helper.php";
$thema_id = $_GET["thema"];

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

$sql = 'SELECT thema_name, fach FROM thema WHERE thema_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $thema_id);
$stmt->execute();
$thema = $stmt->get_result()->fetch_assoc();
$thema_name = $thema["thema_name"];
$fach_id_selected = $thema["fach"];

$fächer = [];
$sql = 'SELECT fach_id, fach_name FROM fach';
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) 
{
	$fächer[] = [$row["fach_id"], $row["fach_name"]];
}

$output = '
<form action="update_thema.php" method="post">
	<div class="form-group">
	<div class="row" style="margin:inherit">
		<label for="fach">'.$thema_name.'</label>
		<select class="form-control" name="fach" id="fach">';
		
foreach ($fächer as $fach)
{
	$selected = '';
	if ($fach_id_selected == $fach[0])
	{
		$selected = 'selected="selected"';
	}
	$output .= '<option value="'.$fach[0].'" '.$selected.'>'.$fach[1].'</option>';
}		


$output .= '</select>
		</div>
	</div>
	<input type="hidden" name="thema" id="thema" value="'.$thema_id.'">
  <button type="submit" class="btn btn-primary">Verschieben</button>
</form>
';

echo(create_html_document ($output));