<?php

//Formular, um ein neues Fach zu erstellen

include "html_helper.php";

$output = '
<form action="insert_fach.php" method="post">
	<div class="form-row">
		<div class="col">
			<input type="text" class="form-control form-control-lg" id="fach" name="fach" placeholder="Name des Faches">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-lg">Hinzufügen</button>
		</div>
</form>
';

session_write_close();

echo(create_html_document ($output));