<?php

//Formular, um ein neues Thema zu erstellen

include "html_helper.php";

$fach_id = $_GET["fach"];

$output = '
<form action="insert_thema.php" method="post">
	<div class="form-row">
		<div class="col">
			<input type="text" class="form-control form-control-lg" id="thema" name="thema" placeholder="Name des Themas">
		</div>
		<input type="hidden" name="fach" id="fach" value="'.$fach_id.'">
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-lg">Hinzufügen</button>
	</div>
</form>
';

echo(create_html_document ($output));