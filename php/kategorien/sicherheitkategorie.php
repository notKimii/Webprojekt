<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sicherheitsausrüstung – Cockpit Corner</title>
  <link rel="stylesheet" href="/Webprojekt/produkt.css">


</head>
<body>
  <?php include "../include/connectcon.php"; ?>

  <?php include "../include/headimport.php"; ?>
<main>
  <div class="container">
    <h1>Sicherheitsausrüstung</h1>
    <p>Hier findest du Sicherheitsausrüstung die dich auf alles wappnet</p>

    <div class="product-grid">
      <?php
      $sql = "SELECT * FROM artikel WHERE kategorie = 'Sicherheitsaustrüstung'";
      $result = $con->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $produktId = $row['id'];
          $produktName = htmlspecialchars($row['name']);
          $preis = number_format($row['preis'], 2, ',', '.');

          // Bilder aus dem Ordner laden
          $bilderOrdner = "../../images/pictures/productids/$produktId/";
          $bilder = glob($bilderOrdner . "*.{jpg,JPG,png,PNG,jpeg,JPEG,webp,WEBP}", GLOB_BRACE);

          echo '<div class="product-item" data-id="' . $produktId . '">';
          echo   '<a href="/Webprojekt/php/produkt-detail.php?id=' . $produktId . '">';

          // Erstes Bild oder Fallback
          if (!empty($bilder)) {
            echo '<img src="' . $bilder[0] . '" alt="' . $produktName . '" class="product-image">';
          } else {
            echo '<img src="https://picsum.photos/seed/' . $produktId . '/300/350" alt="' . $produktName . '" class="product-image">';
          }

          echo   '</a>';

          

          echo   '<h3>' . $produktName . '</h3>';
          echo   '<p class="price">' . $preis . ' €</p>';
          echo   '<button class="add-to-cart-button">In den Warenkorb</button>';

          // Bilderliste als versteckte JSON-Data
          if (!empty($bilder)) {
            echo '<script>window.productImages = window.productImages || {}; ';
            echo 'window.productImages["' . $produktId . '"] = ' . json_encode($bilder) . ';</script>';
          }

          echo '</div>';
        }
      } else {
        echo "<p>Keine Produkte in der Kategorie Sicherheitsaustrüstung gefunden.</p>";
      }

      $con->close();
      ?>
    </div>

  </div>
</main>

<?php include "../include/footimport.php"; ?>



</body>
</html>