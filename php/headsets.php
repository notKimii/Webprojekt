<?php
include "include/connectcon.php"; 
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Headsets – Cockpit Corner</title>
  
  <?php
    include "include/headimport.php";
  ?>

</head>
<body>

  <!-- Kategorien-Content -->
  <main>
    <div class="container">
      <h1>Headsets</h1>
      <p>Hier findest du unsere Auswahl an hochwertigen Headsets fürs Cockpit.</p>

      <div class="product-grid">
        <?php
        // Headsets aus der Datenbank laden
        $sql = "SELECT * FROM artikel WHERE kategorie = 'Headsets'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo '<div class="product-item">';
            echo   '<a href="/produkt/' . $row['id'] . '">';
            echo     '<img src="https://picsum.photos/seed/' . $row['id'] . '/300/350" alt="' . htmlspecialchars($row['name']) . '">';
            echo     '<h3>' . htmlspecialchars($row['name']) . '</h3>';
            echo     '<p class="price">' . number_format($row['preis'], 2, ',', '.') . ' €</p>';
            echo   '</a>';
            echo   '<button class="add-to-cart-button">In den Warenkorb</button>';
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

  <footer>
    <div class="container">
      <p>&copy; 2024 Cockpit Corner</p>
    </div>
  </footer>

</body>
</html>
