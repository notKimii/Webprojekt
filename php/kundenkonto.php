<?php
  include 'include/loginpruefung.php';
    //Variablen
    $id = $_SESSION["id"];
    $_SESSION["SArtikelID"] = $SArtikelID;
    $_SESSION["SAnzahl"]= $SAnzahl;
    $_SESSION["SArtikelname"]=$SPreis;
    $_SESSION["SPreis"] = $SPreis;
    
  ?>


<!DOCTYPE html>
<html>
	<head>
		<title>Kundenkonto</title>
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
  		
  		<br>
      <div class="container">
        <h3>Kundenkonto von <?php echo $_SESSION["username"]; ?></h3><br>

        <h4>Aktuelle Lieferadresse: </h4><br>
              
      
             <?php 
             

              $pdo = new PDO('mysql:host=localhost; dbname=dbpferdeshop', 'root', '');

              $sql = "SELECT * FROM user WHERE id = '$id'";
              
              foreach ($pdo -> query($sql) as $row) { 
                $vorname = $row['vorname'];
                $nachname = $row['nachname'];
                $adresse = $row['adresse'];
                $plz = $row['plz'];
                $ort = $row['ort'];

                echo "<table class='table table-bordered'>
                <tr>
                  <th style='background-color: #F2F2F2;'>Vorname</th>
                  <td>".$row['vorname']."</td>
                </tr>
                <tr>
                  <th style='background-color: #F2F2F2;'>Nachname</th>
                  <td>".$row['nachname']."</td>
                </tr>
                <tr>
                  <th style='background-color: #F2F2F2;'>Straße</th>
                  <td>".$row['adresse']."</td>
                </tr>
                <tr>
                  <th style='background-color: #F2F2F2;'>PLZ</th>
                  <td>".$row['plz']."</td>
                </tr>
                <tr>
                  <th style='background-color: #F2F2F2;'>Ort</th>
                  <td>".$row['ort']."</td>
                </tr>
              </table>";
              }

              ?>

              <a href="adressChange.php"><button class="btn btn-outline-success">Lieferadresse abändern</button></a><br><br><br>

              <h3>Hier können Sie Ihre letzte Bestellung sehen: </h3><br>

              <?php 
                $pdo = new PDO('mysql:host=localhost; dbname=dbpferdeshop', 'root', '');
                $sql2 = "SELECT * FROM cart WHERE userID='$id' ORDER BY datum DESC LIMIT 1";

                foreach ($pdo -> query($sql2) as $row) { 
                  $SArtikelID = nl2br($row['artikelID']);
                  $SArtikelname = nl2br($row['produktname']);
                  $SAnzahl = nl2br($row['anzahl']);
                  $SPreis = nl2br($row['preis']);
                  $gesamtkosten = $row['gesamtsumme'];

                
                echo "<table align='center' class='table '> 
                  
                    <tr> 
                      <th style='background-color: #F2F2F2;'>Artikelid</th>
                      <th style='background-color: #F2F2F2;'>Artikelname</th>
                      <th style='background-color: #F2F2F2;'>Anzahl</th>
                      <th style='background-color: #F2F2F2;'>Preis</th>
                    </tr>
                    <tr>  
                      <td>$SArtikelID</td>
                      <td>$SArtikelname</td>
                      <td>$SAnzahl</td>
                      <td>$SPreis</td>
                    </tr>
                      <tr> 
                      <th>Versandkosten </th>
                      <td></td>
                      <td></td>
                      <td>".$row['versandkosten']."</td>
                    </tr>
                    <tr> 
                      <th>Gesamtsumme </th>
                      <th></th>
                      <td></td>
                      <td>".$row['gesamtsumme']."</td>
                    </tr>
                  </table>";


              }
              

              ?>

              <form action="erneutbestellen.php?preis=<?php echo $gesamtkosten?>" method="post">
                <button type="submit" class="btn btn-outline-success">Jetzt erneut bestellen</button>
              </form>        

      </div><br><br>

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