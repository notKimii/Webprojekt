<?php

  //Überprüfung 
  include 'include/loginpruef.php';

  include 'php/connect.php';
  $userID = $_SESSION["id"];
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Logout</title>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="../css/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/css/layout.css">

</head>
<body>
	<center>
		<img src="../images/logo.png"style="width:50%"> <br><br>
		<?php
			$_SESSION = array();
			
				unset($_SESSION['cart']);
                unset($_SESSION['shopping_cart']);
                unset($_SESSION["Aid"]);
                unset($_SESSION["name"]);
                unset($_SESSION["anzahl"]);
                unset($_SESSION["GPreisArtikel"]);
                unset($_SESSION["SPreis"]);
                unset($_SESSION["SArtikelID"]);
                unset($_SESSION["SArtikelname"]);
                unset($_SESSION["SAnzahl"]);


                $sql = "DELETE FROM online WHERE userID='$userID'";
                $pdo->query($sql); 
		            

				session_destroy();

 
			echo "<h3> Logout erfolgreich</h3> <br> \n";
			echo "<h3>Wir freuen uns dich bald wieder zu sehen! </h3> <br> \n";
		?>

		<br><br><h4>Zurück zur Anmeldeseite</h4>
		<form action="../login.html" method="post" > <input type="submit" name="Login" class="btn btn-outline-success" value="Login"></form>


      <!--Fußzeile-->
      <br><br><br>
      <center><footer class="col-sm" style="background-color:  #D8D8D8;"><br>
        <a href="../index.html" style="color: black;">- Zurück zur Indexseite -</a>
        <p>&copy; Janine Reiff  &amp; Ellena Schorpp &middot; 
        <a href="#" style="color: black;">Datenschutz</a> &middot; <a href="#" style="color: black;">AGBs</a> &middot; 
        <a href="kontakt.php" style="color: black;">Kontakt</a></p><br>
      </footer></center>
      
</body>
</html>