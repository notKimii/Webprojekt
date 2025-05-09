<?php

	//Login-Angaben mit DB überprüfen
	session_start();
	
	$gefunden=false;

	$user_mail = $_POST["mail"];
	$user_passwort = hash('sha256', $_POST["password"]); //Passwort mit sha256 verschlüsselt

	//Verbindung zur DB aufbauen
	$pdo = new PDO('mysql:host=localhost;dbname=dbpferdeshop', 'root', '');

	$sql = "SELECT * FROM user";
	foreach ($pdo->query($sql) as $row) {
   	   	if($user_mail == $row['mail']) 
   		{
   			if ($user_passwort == $row['passwort']) //Überprüfung ob eingegebener Hash-Wert mit Hash-Wert in DB übereinstimmt
   			{
   				
   				$gefunden=true;
   				$_SESSION["login"]=1;
   				$_SESSION["id"] = $row['id'];
   				$id = $_SESSION["id"];
   				$_SESSION["username"] = $row['vorname'];
          $_SESSION["nachname"] = $row['nachname'];
          $_SESSION["adresse"] = $row['adresse'];
          $_SESSION["plz"] = $row['plz'];
          $_SESSION["ort"] = $row['ort'];
          $_SESSION["mail"] = $row['mail'];
          //echo $id;
          $insert_query="INSERT INTO online(userID, timeonline) VALUES($id, NOW())";
          if ($pdo->query($insert_query)) {
            echo "Hallo";
          }




                
   				break;
   			}
   		}
   	}

	if($gefunden)
	{	
		header("Location: basis.php");
	}

	else
	{
		header("Location: ../login1.html");
	}

?>