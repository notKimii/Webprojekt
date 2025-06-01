
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Headsets – Cockpit Corner</title>

  <?php include "include/connectcon.php"; ?>

  <?php include "include/headimport.php"; ?>
  <style>
    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .product-item {
      position: relative;
      width: 350px;
    }
    .product-item img {
      width: 300px;
      height: 300px;
      object-fit: cover;
      display: block;
    }
    .product-item .image-nav {
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      display: flex;
      justify-content: space-between;
      padding: 0 10px;
      transform: translateY(-50%);
    }
    .image-nav button {
      background: rgba(0,0,0,0.5);
      color: #fff;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }
    .product-item h3, .product-item p {
      margin: 10px 0 0;
    }
    .add-to-cart-button {
      margin-top: 10px;
      padding: 8px 12px;
      background-color: #333;
      color: #fff;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

<main>
  <div class="container">
    <h1>Headsets</h1>
    <p>Hier findest du unsere Auswahl an hochwertigen Headsets fürs Cockpit.</p>

    <div class="product-grid">
      <?php
      $sql = "SELECT * FROM artikel WHERE kategorie = 'Headsets'";
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
        echo "<p>Keine Produkte in der Kategorie Headsets gefunden.</p>";
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
