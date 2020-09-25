<?php

//Übersicht über ein Thema

include "html_helper.php";

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);
$thema = $_GET["thema"];

$admin=is_admin();

if (is_logged_in()){

$user = $_SESSION["user"];

$sql = 'SELECT thema_name, code FROM thema WHERE thema_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $thema);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$thema_name = $result["thema_name"];
$code = $result["code"];

$output = '
<h1>'.$thema_name.'</h1>
<hr>
<script>
	new ClipboardJS(".btn");
</script>
	
	<h3>Download-Code</h3>
	
		<div class="row">
			<div class="col">
				<input class="form-control form-control-lg" type="text" value="'.$code.'" id="link">
			</div>
			<div class="col">
				<button class="btn btn-primary btn-lg" data-clipboard-target="#link">Kopieren</button>
			</div>
		</div>

	<p style="margin-top:10px">Kopiere diesen Download-Code, um das Thema mit allen Karteikarten in Deine Desktop-App zu laden!</p>
<hr>
	<h3>Karteikarten in diesem Thema</h3>
	<button style="margin:10px" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#karteikarten" aria-expanded="false" aria-controls="karteikarten">
		Auf-/Einklappen
	</button>
<table class="table table-hover collapse" id="karteikarten">
  <thead>
    <tr>
      <th scope="col">Frage</th>
      <th scope="col">Antwort</th>
	  ';
	  
	if (is_admin())
	{
		$output .= '<th scope="col">Löschen</th>';
	}
	  
	$output .=  '
    </tr>
  </thead>
  <tbody>
';

$sql = 'SELECT f.frage_text, a.antwort_text, k.karteikarte_id FROM karteikarte k
		INNER JOIN frage f ON k.frage=f.frage_id
		INNER JOIN antwort a ON k.antwort=a.antwort_id
		WHERE k.thema = ?
		';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $thema);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc())
{
	$frage = $row["frage_text"];
	$antwort = $row["antwort_text"];
	$id = $row["karteikarte_id"];
	
	$output .= '
    <tr>
      <td style="width:45%">'.$frage.'</td>
      <td style="width:45%">'.$antwort.'</td>
	';
	
	if ($admin)
	{
		$output .= '<th scope="col">
		<a href="loesche_karteikarte.php?id='.$id.'">
			<img src="delete.png" width="30" height="30" alt="Löschen">
		</a>
		</th>';
	}
	
	$output .= '
    </tr>
	';
	
}

$output .= '</tbody>
</table>';

$conn->close(); 


}
else {
	$output = '<h1>Fehler!</h1>
	<p>Bitte logge Dich ein, um diese Seite zu sehen.</p>';
}
echo(create_html_document ($output));
