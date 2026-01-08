<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flugtaschen - Cockpit Corner</title>
  <link rel="stylesheet" href="/Webprojekt/produkt.css">
  <style>
    .price-container {
      display: flex;
      flex-direction: column;
      gap: 5px;
      margin: 10px 0;
    }
    .old-price {
      text-decoration: line-through;
      color: #999;
      font-size: 0.9em;
    }
    .new-price {
      color: #e53935;
      font-weight: bold;
      font-size: 1.2em;
    }
    .discount-badge {
      background: #e53935;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: bold;
      display: inline-block;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>
  <?php include "../include/connectcon.php"; ?>

  <?php include "../include/headimport.php"; ?>
<main>
  <div class="container">
    <h1>Flugtaschen</h1>
    <p>Hier findest du unsere Auswahl an Flugtaschen.</p>

    <div class="product-grid">
      <?php
      $sql = "SELECT * FROM artikel WHERE kategorie = 'Flugtaschen & Koffer'";
      $result = $con->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $produktId = $row['id'];
          $produktName = htmlspecialchars($row['name']);
          $alterPreis = $row['preis'];
          $rabatt = $row['rabatt'];
          $hatRabatt = ($rabatt !== null && $rabatt > 0);
          
          if ($hatRabatt) {
            $neuerPreis = $alterPreis * (1 - $rabatt / 100);
            $alterPreisFormatiert = number_format($alterPreis, 2, ',', '.');
            $neuerPreisFormatiert = number_format($neuerPreis, 2, ',', '.');
          } else {
            $preis = number_format($alterPreis, 2, ',', '.');
          }

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
          if ($hatRabatt) {
            echo   '<div class="price-container">';
            echo     '<span class="discount-badge">-' . $rabatt . '%</span>';
            echo     '<span class="old-price">' . $alterPreisFormatiert . ' €</span>';
            echo     '<span class="new-price">' . $neuerPreisFormatiert . ' €</span>';
            echo   '</div>';
          } else {
            echo   '<p class="price">' . $preis . ' €</p>';
          }
          
          echo   '<button class="add-to-cart-button">In den Warenkorb</button>';

          // Bilderliste als versteckte JSON-Data
          if (!empty($bilder)) {
            echo '<script>window.productImages = window.productImages || {}; ';
            echo 'window.productImages["' . $produktId . '"] = ' . json_encode($bilder) . ';</script>';
          }

          echo '</div>';
        }
      } else {
        echo "<p>Keine Produkte in der Kategorie Flugtaschen & Koffer gefunden.</p>";
      }

      $con->close();
      ?>
    </div>

  </div>
</main>

<?php include "../include/footimport.php"; ?>


</body>
</html>
