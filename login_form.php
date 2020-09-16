<?php

//login form

include "html_helper.php";

$output = '
<form action="login.php" method="post">
	<div class="form-group">
    <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Nutzername">
  </div>
  <div class="form-group">
    <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Passwort">
  </div>
  <button type="submit" class="btn btn-primary">Anmelden</button>
</form>
';

echo(create_html_document ($output));