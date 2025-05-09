<?php
 
  session_start();

  $gefunden=false;

  if($_SESSION["login"] ==1)
  {
      $gefunden=true;
  }

  if($gefunden==false)
  {
    header("Location: ../login.html");
  }
  	//Inhalt aus Formular holen
  	$id = $_SESSION["id"]; 
 	$user = $_SESSION["username"];
 	$adresse = $_POST["adresse"];
	$plz = $_POST["plz"];
	$ort = $_POST["ort"];
 
	//Verbindung zur DB aufbauen
	$pdo = new PDO("mysql:host=localhost;dbname=dbpferdeshop","root","");
	
	$change = "UPDATE user SET adresse = '$adresse', plz = '$plz', ort = '$ort' WHERE id = '$id'";

	if ($pdo->query($change))
	{
		echo "Adresse erfolgreich geändert!";
		header("Location: kundenkonto.php");
	}
	else
	{
		echo "Hoppla! Irgendwas ist schief gelaufen";
	}
?>
