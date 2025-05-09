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
?>

  <!DOCTYPE html>
<html>
	<head>
		<title>Für Pferde</title>
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
        min-width: 160px;
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
        
      </ul><br><br>

  		<!-- body-->
      
      <div class="container">
        <center><h1>Alles was das Pferd benötigt</h1></center> <br><br><br>
        <center><h2>Grundausstattung </h2></center><br>
          <div class="img150">
            <a href="../Produkte/fürPferde/1001-halfter.php" style="color: black;"><img src="../images\Für Pferde\halfter.jpg" width="370" height="370" border="0" alt="produkt1">
              <center><h5> Halfter</h5></center></a>
          </div> 

          <div class="img150">
            <a href="../Produkte/fürPferde/1002-olivenkopfgebiss.php"style="color: black;"><img src="../images\Für Pferde\Olivenkopfgebiss.jpg" width="370" height="370" border="0" alt="produkt2">
              <center><h5> Olivenkopfgebiss</h5></center> </a>
          </div> 

          <div class="img150">
            <a href="../Produkte/fürPferde/1003-fliegenhaube.php"style="color: black;"><img src="../images\Für Pferde\fliegenhaube.jpg" width="370" height="370" border="0" alt="produkt3">
              <center><h5> Fliegenhaube</h5></center></a> 
          </div>

          <br style="clear: both;">

          <br><br><br>

          <center><h2>Zubehör</h2></center><br>
          <div class="img150">
            <a href="../Produkte/fürPferde/1004-paradedecke.php"style="color: black;"><img src="../images\Für Pferde\Paradedecke.jpg" width="370" height="370" border="0" alt="produkt1">
              <center><h5> Paradedecke</h5></center></a>
          </div> 

          <div class="img150">
            <a href="../Produkte/fürPferde/1005-schabracke.php"style="color: black;"><img src="../images\Für Pferde\Schabracke.jpg" width="370" height="370" border="0" alt="produkt2">
              <center><h5> Schabracke</h5></center></a>
          </div> 

          <div class="img150">
            <a href="../Produkte/fürPferde/1006-dressursattel.php" style="color: black;"><img src="../images\Für Pferde\dressursattel.jpg" width="370" height="370" border="0" alt="produkt3">
              <center><h5> Dressursattel</h5></center></a>
          </div>

          <br style="clear: both;">
          <br><br><br>



              
      </div>

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