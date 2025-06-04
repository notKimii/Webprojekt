<?php

include 'include/loginpruefung.php';
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kontaktdaten Ändern</title>


  <style>
    main {
      display: flex;
      justify-content: center;
      /* horizontal mittig */
      align-items: center;
      /* vertikal mittig */
      padding: 0px;
      /* etwas Abstand an den Seiten */
      background-color: #f8f9fa;
      /* leichter Hintergrund, optional */
    }

    .change-box {
      display: flex;
      flex-direction: column;
      /* wichtig für vertikale Anordnung */
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      margin-block: 5rem;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
    }

    .change-box .form-group {
      margin-bottom: 16px;
    }

    .change-box label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      color: #333;
    }

    .change-box input[type="text"] {
      width: 100%;
      padding: 8px 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    .change-box .btn {
      display: block;
      width: 100%;
      padding: 10px;
      font-size: 15px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
  </style>
</head>

<body>

  <?php include 'include/headimport.php'; ?>

  <main>
    <div class="change-box">
      <form action="" method="POST">

        <h3 class="text-center mb-4">Adresse ändern</h3>

        <!-- Vorname -->
        <div class="form-group">
          <label for="vorname">Vorname</label>
          <input type="text" class="form-control" id="vorname" name="vorname" value="<?= htmlspecialchars($formData['vorname'] ?? '') ?>" required>
        </div>

        <!-- Nachname -->
        <div class="form-group">
          <label for="nachname">Nachname</label>
          <input type="text" class="form-control" id="nachname" name="nachname" value="<?= htmlspecialchars($formData['nachname'] ?? '') ?>" required>
        </div>

        <!-- Adresse -->
        <div class="form-group">
          <label for="adresse">Straße und Hausnummer</label>
          <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($formData['adresse'] ?? '') ?>" required>
        </div>

        <!-- PLZ -->
        <div class="form-group">
          <label for="plz">Postleitzahl</label>
          <input type="text" class="form-control" id="plz" name="plz" pattern="\d{5}" maxlength="5" value="<?= htmlspecialchars($formData['plz'] ?? '') ?>" required>
        </div>

        <!-- Ort -->
        <div class="form-group">
          <label for="ort">Ort</label>
          <input type="text" class="form-control" id="ort" name="ort" value="<?= htmlspecialchars($formData['ort'] ?? '') ?>" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-block">Daten ändern</button>

      </form>
    </div>
  </main>
  <?php include "include/footimport.php"; ?>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = $_SESSION['temp_user']['email'];
  include 'include/connectcon.php';

  $vorname=$_POST["vorname"];
  $nachname = $_POST["nachname"];
  $adresse = $_POST["adresse"];
  $plz = $_POST["plz"];
  $ort = $_POST["ort"];

  // Prüfen ob es Felder zum Updaten gibt

  $sql = "UPDATE user SET vorname=? ,nachname=?, adresse=?, plz=?, ort=? WHERE mail= ?";
  $stmt = $con->prepare($sql);
  $stmt->execute([$vorname, $nachname, $adresse, $plz, $ort, $email]);
}
?>