<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    
    <title>Kontakt</title>
    
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



    <center>
    <div class="container">
     <h3>Hier kannst du uns eine Nachricht senden</h3> 
     <form action="senden.php" method="post" class="needs-validation" novalidate>
      <div>
        <div>
          <label for="validationCustom01"></label>
          <input type="text" class="form-control" name="vorname" id="vorname" placeholder="Vorname" required>
          <div class="valid-feedback">
            Korrekt
          </div>
          <div class="invalid-feedback">
            Bitte gebe deinen Vorname an 
          </div>
        </div>
        <div>
          <label for="validationCustom02"></label>
          <input type="text" class="form-control" name="nachname" id="nachname" placeholder="Nachname" required>
          <div class="valid-feedback">
            Korrekt
          </div>
          <div class="invalid-feedback">
            Bitte gebe deinen Nachname an 
          </div>
        </div>
      </div>
      <div>
        <div>
          <label for="validationCustomUsername"></label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroupPrepend">@</span>
            </div>
            <input type="email" class="form-control" name="mail" id="mail" placeholder="name@example.de" aria-describedby="inputGroupPrepend" required>
            <div class="invalid-feedback">  Bitte gebe deine Email an  </div>
            <div class="valid-feedback"> Korrekt </div>
          </div>
        </div> 
         
       </div>  
      <div>
        <div>
          <label for="validationCustom03"></label>
          <input type="text" class="form-control" name="betreff" id="betreff" placeholder="Betreff" required>
          <div class="valid-feedback">
            Korrekt
          </div>
          <div class="invalid-feedback">
            Bitte gebe deinen Betreff an
          </div>
        </div>
      </div>      
      <div>
        <label for="exampleFormControlTextarea1"></label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="nachricht" id="nachricht" placeholder="Deine Nachricht" required></textarea>
        <div class="valid-feedback">
            Korrekt
        </div>
        <div class="invalid-feedback">
            Bitte gebe deine Nachricht an 
        </div>

      </div> 
       </div><br>
    <input type="submit" name="kontakt" class="btn btn-outline-success" value="Senden">
    
</form>
</center>

    </div>
    

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
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
