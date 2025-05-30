<?php 
		$pdo = new PDO("mysql:host=localhost;dbname=dbpferdeshop","root","");

        $sql  = "SELECT * FROM user"; 
  		$user = $pdo->query($sql);
  		$anzahl = $user->rowCount();

        echo "<h3>Sie sind einer von ". $anzahl . "Kunden. Herzlichen Glückwunsch!!!</h3>";
?> 
