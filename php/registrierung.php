<?php

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cockpit Corner Registrierung</title>


  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" 
    integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: url('/Webprojekt/images/pictures/sky.jpg') center/cover no-repeat;
    }
    .register-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      margin-block: 5rem;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

  </style>
  <?php include "include/headimport.php"; ?>
</head>
<body>
<?php if (!empty($mailFehler)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($mailFehler) ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  

  <main class="flex-fill d-flex justify-content-center align-items-center">
    <div class="register-box">
      <form action="registrierungsubmit.php" method="POST" class="needs-validation" novalidate>
        <!-- CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <h2 class="text-center mb-4">Kundenkonto erstellen</h2>

        <!-- Vorname -->
        <div class="form-group">
          <label for="vorname">Vorname</label>
          <input type="text" class="form-control" id="vorname" name="vorname" value="<?= htmlspecialchars($formData['vorname'] ?? '') ?>" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib deinen Vornamen an.</div>
        </div>

        <!-- Nachname -->
        <div class="form-group">
          <label for="nachname">Nachname</label>
          <input type="text" class="form-control" id="nachname" name="nachname" value="<?= htmlspecialchars($formData['nachname'] ?? '') ?>" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib deinen Nachnamen an.</div>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="mail">E-Mail</label>
          <input type="email" class="form-control" id="mail" name="mail" value="<?= htmlspecialchars($formData['mail'] ?? '') ?>" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib eine gültige E-Mail-Adresse an.</div>
        </div>

        <!-- Adresse -->
        <div class="form-group">
          <label for="adresse">Straße und Hausnummer</label>
          <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($formData['adresse'] ?? '') ?>"required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib deine Adresse an.</div>
        </div>

        <!-- PLZ -->
        <div class="form-group">
          <label for="plz">Postleitzahl</label>
          <input type="text" class="form-control" id="plz" name="plz" pattern="\d{5}" maxlength="5" value="<?= htmlspecialchars($formData['plz'] ?? '') ?>"  required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib eine 5-stellige Postleitzahl an.</div>
        </div>

        <!-- Ort -->
        <div class="form-group">
          <label for="ort">Ort</label>
          <input type="text" class="form-control" id="ort" name="ort" value="<?= htmlspecialchars($formData['ort'] ?? '') ?>" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib deinen Wohnort an.</div>
        </div>
        <!-- AGB Checkbox -->
        <div class="form-group form-check" style="display: flex; align-items: center;">
          <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
          <label class="form-check-label" for="terms">
            Ich akzeptiere die <a href="/terms" target="_blank" style="color: #007aff;">AGB</a> und
            <a href="/privacy" target="_blank" style="color: #007aff;">Datenschutzerklärung</a>.
          </label>
          <div class="invalid-feedback">Du musst die AGB und Datenschutzbestimmungen akzeptieren.</div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-block" style="background-color: #007aff; color: white; border: 2px solid #007aff; border-radius: 5px;">Registrieren</button>

        <div class="text-center mt-3">
          Schon ein Konto? <a href="/Webprojekt/loginformular.php" style="color: #007aff;">Hier einloggen</a>
        </div>
      </form>
    </div>
  </main>

  <?php include "include/footimport.php"; ?>

  <script>
    // Bootstrap-Validierung aktivieren
    (function () {
      'use strict';
      window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.forEach.call(forms, function (form) {
          form.addEventListener('submit', function (event) {
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
</body>
</html>
