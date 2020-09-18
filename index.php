<?php

//Das ist die Startseite

include "html_helper.php";

$output = '
	<h1>Willkommen!</h1>
	<hr>
';

if (!is_logged_in())
{
	$output .= '
		<p>
			Hast Du schon einen Account? Dann Kannst Du Dich 
			<a href="login_form.php">
				hier 
			</a>
			<b>einloggen</b>.
		</p>
		<p>
			Möchtest Du eine <b>neue Klasse</b> erstellen? Dann klicke bitte 
			<a href="neue_klasse.php">
				hier! 
			</a>
		</p>
		<p>
			Alle Optionen findest Du natürlich auch in der <b>Navigation</b> oben auf der Seite.
		</p>
	';
}
else
{
	$output .= '
		<p>
			Du möchtest Karteikarten mit Deinen Mitschülern teilen? Aus Deiner Desktop-App
			heraus kannst Du sie <b>exportieren</b>! Bitte hinterlege dafür zuerst Deine <b>Zugangsdaten</b>
			für diese Website in der Desktop-Anwendung.
		</p>
		<p>
			Wenn Du Karteikarten <b>herunterladen</b> möchtest, stöbere gerne in der gut sortierten
			Auswahl Deiner Klasse. In der Navigation kannst Du alle Fächer ansehen. 
		</p>
		<p>
			Auf den <b>Detailseiten</b> der Fächer sind die Themen, die ihr lernen müsst.
			Du kannst alle Karteikarten aus den Themen herunterladen, indem Du auf der Detailseite 
			des Themas den <b>Code</b> kopierst und ihn in Deiner Desktop-App eingibst.
		</p>
		<p>
			Möchtest Du Deine <b>Mitschüler</b> einladen? Verschicke einfach einen <a href="einladen.php">Einladungslink!</a>
		</p>
	';
	if (is_admin())
	{
		$output .= '
		<p>
			Als <b>Administrator</b> der Klasse kannst Du zusätzlich Themen verschieben,
			sowie Fächer, Themen und einzelne Karteikarten löschen.
		</p>
		';
	}
}

echo(create_html_document ($output));