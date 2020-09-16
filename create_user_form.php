<?php

//Formular, um eine neue Klasse zu erstellen

include "html_helper.php";

$uuid = $_GET["uuid"];

$output = '
<form action="insert_user.php" method="post">
	<div class="form-row">
		<div class="col">
			<input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nutzername">
		</div>
		<div class="col">
			<input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Passwort">
		</div>
		<input type="hidden" id="uuid" name="uuid" value="'.$uuid.'">
	</div>
	<small id="explanation" class="form-text text-muted">
		Bitte speichere Deine Zugangsdaten direkt in der Desktop-App.
	</small>
	<div class="form-group">
		<button type="submit" class="btn btn-primary">Registrieren</button>
	</div>
</form>
';

session_write_close();

echo(create_html_document ($output));