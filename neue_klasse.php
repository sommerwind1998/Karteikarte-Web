<?php

//Formular, um eine neue Klasse zu erstellen

include "html_helper.php";

$output = '
<form action="insert_klasse.php" method="post">
	<div class="form-group">
		<input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Name der Klasse">
	</div>
	<small id="explanation" class="form-text text-muted">
		Beim Erstellen der Klasse erstellst Du direkt Deinen Benutzeraccount, damit Du sofort der Klasse beitreten und Deine Mitschüler einladen kannst.
	</small>
	<div class="form-row">
		<div class="col">
			<input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nutzername">
		</div>
		<div class="col">
			<input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Passwort">
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-primary">Klasse erstellen</button>
	</div>
</form>
';

echo(create_html_document ($output));