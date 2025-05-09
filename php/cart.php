<?php
  error_reporting(0);

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

    //Variablen 
    $cart = array();
    
    
  ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Warenkorb</title>
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
      <a href="fuerpferde.php" style="color: black; " align="right">Zurück zu den Pferden-Artikel</a> <br>
      <a href="fuerreiter.php" style="color: black;">Zurück zu den Reiter-Artikel</a> <br><br>
        <?php
      
  

 
  require 'connect.php';
  require 'item.php';
  
  //Initialisiert die Klasse
  
  if (isset($_GET['id'])) {
    $result = mysqli_query($con, 'SELECT * FROM artikel WHERE id='.$_GET['id']);
    $product = mysqli_fetch_object($result);
    $item = new Item();
    $item->id = $product->id;
    $item->name = $product->name;
    $item->beschreibung = $product->beschreibung;
    $item->groesse = $product->groesse;
    $item->preis = $product->preis;
    $item->anzahl = 1;
    
    //Prüft ob Produkt bereits in Warenkorb
    $index = -1;
    $cart = unserialize(serialize($_SESSION['cart']));
    
    for ($i=0; $i<count($cart); $i++) { 
      if ($cart[$i]->id == $_GET['id']) {
        $index = $i;
        break;
     }
    }
    if ($index == -1) {
      $_SESSION['cart'][] = $item;
    }
    else{
      $cart[$index]->anzahl++;
      $_SESSION['cart'] = $cart;



    }
  }
  //Löscht Produkt im Warenkorb 
  if (isset($_GET['index'])) {

      
      $cart = unserialize(serialize($_SESSION['cart']));
      unset($cart[$_GET['index']]);
      $cart = array_values($cart);
      $_SESSION['cart'] = $cart; 
      

      

    } 
?>
<table class="table">
  <tr style="background-color: #F2F2F2;">
    <th scope="col">Artikel löschen</th>
    <th scope="col">ID</th>
    <th scope="col">Name</th>
    <th scope="col">Größe</th>
    <th scope="col">Preis</th>
    <th scope="col">Anzahl</th>
    <th scope="col">Gesamtsumme</th>
  </tr>
  <?php 
    
    $s=0;
    $index = 0;
    
    for ($i=0; $i < count($_SESSION['cart']); $i++) { 
    	$cart = unserialize(serialize($_SESSION['cart']));
    	$s += $cart[$i]->preis*$cart[$i]->anzahl;
  ?>
    <tr>
      <td scope="row"><a style="color: black" href="cart.php?index=<?php echo $index; ?>" onclick="return confirm('Bist du sicher, dass du den Artikel ganz entfernen möchtest?')">X</a></td>
      <td><?php echo $cart[$i]->id; ?></td>
      <td><?php echo $cart[$i]->name; ?></td>
      <td><?php echo $cart[$i]->groesse; ?></td>
      <td><?php echo $cart[$i]->preis; ?></td>
      <td><?php echo $cart[$i]->anzahl; ?></td>
      <td><?php echo $cart[$i]->preis*$cart[$i]->anzahl; ?></td>

      <?php 
      	

      ?>
     	
    </tr>
    <?php 
      $index++;
      $_SESSION['shopping_cart'][$index] = array(
      'Aid' => $cart[$i]->id ,
      'name' => $cart[$i]->name,
      'anzahl' => $cart[$i]->anzahl,
      'preis' => $cart[$i]->preis); 


    }
   
    ?>
    
    <td> </td>
      <td> </td>
      <td> </td>
      <td> </td>
      <td></td>
      <td>Zwischensumme</td>
      <td><?php echo $s; ?></td>
</table>


<br><br>
	<script language="JavaScript">
      var max=1;                                                         // maximale Anzahl gewählter Checkboxen
      function check(boxnr)
      {
        var objekte_gewaehlt=0;                                          // Anzahl gewählter Checkboxen zurücksetzen
        for(var i=0; i<document.formular.box.length; i++)                // alle Checkboxen durchgehen
          if(document.formular.box[i].checked==true) objekte_gewaehlt++; // gewählte Checkboxen zählen
        if(objekte_gewaehlt > max)                                       // wenn Anzahl gewählter Checkboxen zu hoch...
        {
          document.formular.box[boxnr].checked=false;                    // gerade gewählte Checkboxen zurücksetzen
          alert("Es darf maximal "+max+" Versandart ausgewählt werden! \nbitte Auswahl Entfernen und ändern"); // Hinweis ausgeben
        }
      }
    </script>    

    	


		<h3>Du kannst zwei Versandarten auswählen:</h3><br>
		<form action="overview.php?preis=<?php echo $s; ?>" method="post" name="formular">
			
      	<input type="radio" name="versand" value="5,00" checked=""> Standard Versand; Lieferung 3 Werktage; 5,00€ <br>
        <input type="radio" name="versand" value="9,00"> Express Versand; Lieferung 1 Werktag; 9,00€<br><br>
    		
      		
      		<input type="submit" name="zur Übersicht" class="btn btn-outline-success" value="zur Übersicht">
    	</form>
    </div>	
    <br><br>


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