<?php
include "include/connectcon.php"; 
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navigation - Cockpit Corner</title>
  <link rel="stylesheet" href="/Webprojekt/produkt.css">

  <?php include "include/headimport.php"; ?>
</head>
<body>

<main>
  <div class="container">
    <h1>Navigation</h1>
    <p>Hier findest du unsere Auswahl an Artikel zur Navigation.</p>

    <div class="product-grid">
      <?php
      $sql = "SELECT * FROM artikel WHERE kategorie = 'Navigation'";
      $result = $con->query($sql);

      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $produktId = $row['id'];
          $produktName = htmlspecialchars($row['name']);
          $preis = number_format($row['preis'], 2, ',', '.');

          // Bilder aus dem Ordner laden
          $bilderOrdner = "../images/pictures/productids/$produktId/";
          $bilder = glob($bilderOrdner . "*.{jpg,JPG,png,PNG,jpeg,JPEG,webp,WEBP}", GLOB_BRACE);

          echo '<div class="product-item" data-id="' . $produktId . '">';
          echo   '<a href="/produkt/' . $produktId . '">';

          // Erstes Bild oder Fallback
          if (!empty($bilder)) {
            echo '<img src="' . $bilder[0] . '" alt="' . $produktName . '" class="product-image">';
          } else {
            echo '<img src="https://picsum.photos/seed/' . $produktId . '/300/350" alt="' . $produktName . '" class="product-image">';
          }

          echo   '</a>';

          // Navigation anzeigen wenn mehr als ein Bild
          if (count($bilder) > 1) {
            echo '<div class="image-nav">';
            echo   '<button class="prev-btn" data-id="' . $produktId . '">&#10094;</button>';
            echo   '<button class="next-btn" data-id="' . $produktId . '">&#10095;</button>';
            echo '</div>';
          }

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
        echo "<p>Keine Produkte in der Kategorie Navigation gefunden.</p>";
      }

      $con->close();
      ?>
    </div>

  </div>
</main>

<?php include "include/footimport.php"; ?>

<script>
// Bildwechsel-Funktion
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.image-nav button').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const productId = this.getAttribute('data-id');
      const images = window.productImages[productId];
      const container = this.closest('.product-item');
      const imgTag = container.querySelector('.product-image');

      if (!images) return;

      // Aktuelles Bild ermitteln
      let currentIndex = images.indexOf(imgTag.getAttribute('src'));

      // Vor oder zurück?
      if (this.classList.contains('prev-btn')) {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
      } else {
        currentIndex = (currentIndex + 1) % images.length;
      }

      imgTag.setAttribute('src', images[currentIndex]);
    });
  });
});
</script>

</body>
</html>
