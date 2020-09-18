<?php

//Hier bekommt man den Einladungslink

include "html_helper.php";

$params = include("datenbankparameter.php");
$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

session_start();
$id = $_SESSION["klasse"];

$sql = 'SELECT uuid FROM klasse WHERE klasse_id=?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$uuid = $result->fetch_assoc()["uuid"];
$uuid = urlencode($uuid);

$link = "http://".$_SERVER['HTTP_HOST']."/create_user_form.php?uuid=".$uuid;

$output = '
	<script>
		new ClipboardJS(".btn");
	</script>

	<h1>Lade Deine Mitschüler ein!</h1>
	<p>Schicke ihnen einfach diesen Link:</p>
	
	<form>
		<div class="row">
			<div class="col">
				<input class="form-control form-control-lg" type="text" value="'.$link.'" id="link">
			</div>
			<div class="col">
				<button class="btn btn-primary btn-lg" data-clipboard-target="#link">Kopieren</button>
			</div>
		</div>
	</form>
';

session_write_close();

if (!is_logged_in())
{
	$output = '<h1>Fehler!</h1>
	<p>Bitte logge Dich ein, um diese Seite zu sehen.</p>';
}

echo(create_html_document ($output));
