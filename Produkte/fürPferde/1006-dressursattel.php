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
		<title>Dressursattel</title>
		<meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="../../css/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../css/css/layout.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="../../css/js/jquery-2.1.4.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function() 
        {
          setInterval(function()
          {
             $.get("../../php/useronline.php",
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
        <li><a href="../../php/basis.php"><img src="../../images/logo.png" width="250px" height="45px"></a></li>
        <li><a href="../../php/fuerpferde.php"> <h4> <i class="fas fa-horse-head"></i> Pferde</h4></a></li>
        <li><a href="../../php/fuerreiter.php"> <h4> <i class="fas fa-award"></i> Reiter</h4></a></li>
        <li><a href="../../php/kundenkonto.php"><h4><i class="fas fa-user"></i> Kundenkonto</h4></a></li>
        <li><a href="../../php/cart.php"><h4><i class="fas fa-shopping-cart"></i> Warenkorb</h4></a></li>
        <li><a href="../../php/logout.php"><h4><i class="fas fa-sign-out-alt"></i> Logout</h4></a></li>
        <li style="float: right; color: white;"><a href=""><i class="fas fa-globe-europe"></i><div id="ausgabe"></div></a></li>
        
      </ul><br><br>

  		<!-- body, alles in diesen Container rein schreiben-->
  		<div class="container">
  			<form action="../../php/warenkorb.php" method="post">
            <div class="media">
              <img src="../../images/Für Pferde/Dressursattel.jpg" class="mr-3" width="370" height="370" border="0" alt="Dressursattel">
                <div class="media-body">
                  <h3 class="mt-0">Dressursattel</h3><br><br>
                    Der Dressursattel Prince ist aus strapazierfähigem, schwarzem Rindsleder mit stabilen Nähten gefertigt. Sitz, Sattelkissen und Knielage aus pflegeleichtem Kunstleder. Der Dressursattel für Großpferde verfügt über lange Gurtstrippen. Die gute Passform und der tiefer Sitz überzeugen.<br><br>
                    Produkt-ID: 1006 <br>
                    Größe: S = 17,5 Zoll <br>
                    Preis: 200€ <br><br>
                    <a href="../../php/cart.php?id=1006" style="color: green;">In den Warenkorb</a>
                </div>

            </div>
          </form>
  		</div>
  		

	


  		

    <!--Fußzeile-->
      <br><br>
      <center><footer class="col-sm" style="background-color:  #D8D8D8;"><br>
        <a href="../../php/fuerpferde.php" style="color: black;">- Zurück nach oben -</a>
        <p>&copy; Janine Reiff  &amp; Ellena Schorpp &middot; 
        <a href="#" style="color: black;">Datenschutz</a> &middot; <a href="#" style="color: black;">AGBs</a> &middot; 
        <a href="kontakt.php" style="color: black;">Kontakt</a></p><br>
      </footer></center>

	</body>
</html>