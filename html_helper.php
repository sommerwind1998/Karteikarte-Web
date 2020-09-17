<?php

function create_html_document ($payload)
{
	session_start();
	$logged_in = isset($_SESSION["user"]);
	if ($logged_in)
	{
		$link_template = '<a class="dropdown-item" href="#">?</a>';
		$links = "";
	
		$params = include("datenbankparameter.php");
		$conn = new mysqli($params["host"], $params["username"], $params["password"], $params["database"]);

		$klasse = $_SESSION["klasse"];
		
		$sql = 'SELECT * FROM fach WHERE klasse = ?;';
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('i', $klasse);
		$stmt->execute();
		$result = $stmt->get_result();
	
		foreach ($result as $fach)
		{
			$id = $fach["fach_id"];
			$name = $fach["fach_name"];
		
			$links .= '<a class="dropdown-item" href="fach.php?fach='.$id.'">'.$name.'</a>';
		}
	
	$view = '
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">
		<img src="logo.png" width="30" height="30" alt="">
		Karteikarten Sharing
	</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="faecher_uebersicht.php?highlight=0">Fächer</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Themen
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          '.$links.'
        </div>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="einladen.php">Mitschüler einladen</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="logout.php">Ausloggen</a>
      </li>
    </ul>
  </div>
</nav>
  
<div class="content">
'.$payload;
	
		$conn->close(); 
	}
	else
	{
		$view = '
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="#">
		<img src="logo.png" width="30" height="30" alt="">
		Karteikarten Sharing
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	<ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login_form.php">Login</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="neue_klasse.php">Neue Klasse erstellen</a>
      </li>
    </ul>
	</div>
</nav>
<div class="content">
'.$payload;
	}
	
session_write_close();
	
	return '
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Karteikarten Sharing</title>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.6/dist/clipboard.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="styles.css">
  </head>
  <body>
  '.$view.'
	</div>
  </body>
</html>
';
}
