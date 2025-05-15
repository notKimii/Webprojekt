<?php 

		$pdo = new PDO("mysql:host=localhost;dbname=dbPilotenshop","root","");

        $sql2 = "SELECT * FROM online";
        $user2 = $pdo->query($sql2);
        $anzahl2 = $user2->rowCount();

        echo "User:".$anzahl2;
?> 
