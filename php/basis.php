<?php

  //Überprüfung 
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

  //echo $_SESSION["id"];
  //echo $_SESSION["username"];


  //Anzeige: Anzahl aktuell registrierter Nutzer (AJAX)
  $pdo = new PDO("mysql:host=localhost;dbname=dbpferdeshop","root","");

  $sql  = "SELECT * FROM user"; 
  $user = $pdo->query($sql);
  $anzahl = $user->rowCount();

  


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Startpage</title>
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="../css/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/css/layout.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="../css/js/jquery-2.1.4.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function() 
        {
          setInterval(function()
          {
             $.get("useronline.php",
                { auswahl:1},
                function(daten)
                {
                  $('#ausgabe').html(daten);
                });
          },100);
        });
      
    </script>
    <script type="text/javascript">

        $(document).ready(function() 
        {
          setInterval(function()
          {
             $.get("anzahlregistriert.php",
                { auswahl:1},
                function(daten)
                {
                  $('#ausgaberegistriert').html(daten);
                });
          },100);
        });
      
    </script>
    <style>
      .mySlides {display:none;}

      ul.topnav {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #0A7724;
        position: -webkit-sticky; /* Safari */
        position: sticky;
        top: 0;
      }

      ul.topnav li {
        float: left;
      }

      ul.topnav li a, .dopbtn {
        display: inline-block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
      }

      ul.topnav li a:hover, .dropdown:hover .dropbtn (.active) {
        background-color: #0A7724;
      }

      ul.topnav li a.active {
       background-color: white;
      color: #0A7724;
      }

      ul.topnav li.right {float: right;}

      li.dropdown {
        display: inline-block;
      }

      .dropdown-content {
        display: none;
        position: absolute;
        background-color: #0A7724;
        min-width: 50%;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
      }

      .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
      }

      .dropdown-content a:hover {background-color: #0A7724}

      .dropdown:hover .dropdown-content {
        display: block;
      }

      @media screen and (max-width: 600px) {
        ul.topnav li.right, 
        ul.topnav li {float: none;}
    </style>
    
	</head>
	<body>
		<!--Kopf-->	

      <ul class="topnav">
        <li><a href="basis.php"><img src="../images/logo.png" width="250px" height="45px"></a></li>
        <li><a href="fuerpferde.php"> <h4> <i class="fas fa-horse-head"></i> Pferde</h4></a></li>
        <li><a href="fuerreiter.php"> <h4> <i class="fas fa-award"></i> Reiter</h4></a></li>
        <li><a href="kundenkonto.php"><h4><i class="fas fa-user"></i> Kundenkonto</h4></a></li>
        <li><a href="cart.php"><h4><i class="fas fa-shopping-cart"></i> Warenkorb</h4></a></li>
        <li><a href="logout.php"><h4><i class="fas fa-sign-out-alt"></i> Logout</h4></a></li>
        <li style="float: right; color: white;"><a href=""><i class="fas fa-globe-europe"></i><div id="ausgabe"></div></a></li>
        
      </ul><br>

  		<!-- body, alles in diesen Container rein schreiben-->

  		
      <h1>Herzlich Willkommen <?php echo($_SESSION["username"]); ?></h1><br>
      
      <h3>Sie sind unser <?php echo $anzahl ?> registrierter Kunde. Herzlichen Glückwunsch!!!</h3><br>
      

       
  		<!--Karussell-->
  		<div class="w3-content w3-section" style="max-width:54%">
		  <img class="mySlides" src="../images/titel/titel7.jpg" style="width:100%">
		  <img class="mySlides" src="../images/titel/titel3.jpg" style="width:100%">
		  <img class="mySlides" src="../images/titel/titel4.jpg" style="width:100%">
		  <img class="mySlides" src="../images/titel/titel5.jpg" style="width:100%">
		</div>

		<!-- JavaScript für Karusell -->
		<script>
		var myIndex = 0;
		carousel();

		function carousel() {
		  var i;
		  var x = document.getElementsByClassName("mySlides");
		  for (i = 0; i < x.length; i++) {
		    x[i].style.display = "none";  
		  }
		  myIndex++;
		  if (myIndex > x.length) {myIndex = 1}    
		  x[myIndex-1].style.display = "block";  
		  setTimeout(carousel, 4000); // Change image every 2 seconds
		}
		</script>
    <!--Seite aktualisieren-->
    
		<!-- Kurze Vorstellung/Beschreibung des Shops -->
		  <br><h4><br>Hier bei 'Der Pferdeshop' finden Sie professionelles Equipment für den Pferdesport. <br>
			Dabei finden nicht nur Dressur- und Springreiter hier im Pferdeshop, was sie für sich, ihren Sport und ihr Pferd brauchen. <br> Auch für Freizeitreiter, Westernreiter oder Fahrer gibt es vieles zu entdecken!</h4><br><br>


  	
  		
  	<!--Fußzeile-->
      <br><br>
  		<center><footer class="col-sm" style="background-color:  #D8D8D8;"><br>
  			<a href="" style="color: black;">- Zurück nach oben -</a>
        <p>&copy; Janine Reiff  &amp; Ellena Schorpp &middot; 
        <a href="#" style="color: black;">Datenschutz</a> &middot; <a href="#" style="color: black;">AGBs</a> &middot; 
        <a href="kontakt.php" style="color: black;">Kontakt</a></p><br>
      </footer></center>
    
	</body>
</html>
