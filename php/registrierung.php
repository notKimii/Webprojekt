<?php
session_start();
$formData = $_SESSION['form_data'] ?? [];
$mailFehler = $_SESSION['mail_error'] ?? null;
unset($_SESSION['form_data'], $_SESSION['mail_error']);
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cockpit Corner Registrierung</title>

  <!-- Google Fonts & Bootstrap CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" 
    integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-family: 'Roboto', sans-serif;
      background: url('./images/Cockpit Corner/sky.jpg') center/cover no-repeat;
    }
    .register-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      margin: 20px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
  <?php if ($mailFehler): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($mailFehler) ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Schließen">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  <header class="w-100 text-center bg-light py-2">
    <nav>
      <a href="/"><img src="./images/Cockpit Corner/planelogo.png" alt="Logo" style="width: 100px;"></a>
    </nav>
  </header>

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
<!-- 
        Passwort
        <div class="form-group">
          <label for="password">Passwort</label>
          <input type="password" class="form-control" id="password" name="password" minlength="8" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib ein Passwort mit mindestens 8 Zeichen ein.</div>
        </div>

        Passwort bestätigen
        <div class="form-group">
          <label for="password_confirm">Passwort wiederholen</label>
          <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="8" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte wiederhole dein Passwort korrekt.</div>
        </div> -->

        <!-- AGB Checkbox -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
          <label class="form-check-label" for="terms">
            Ich akzeptiere die <a href="/terms" target="_blank">AGB</a> und
            <a href="/privacy" target="_blank">Datenschutzerklärung</a>.
          </label>
          <div class="invalid-feedback">Du musst die AGB und Datenschutzbestimmungen akzeptieren.</div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-block" style="background-color: #092291; color: white; border: 2px solid #092291; border-radius: 0;">Registrieren</button>

        <div class="text-center mt-3">
          Schon ein Konto? <a href="./login1.html">Hier einloggen</a>
        </div>
      </form>
    </div>
  </main>

  <footer class="w-100 text-center bg-light py-2">
    © 2025 Cockpit Corner - Alle Rechte vorbehalten
  </footer>

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

    // Passwort-Abgleich
    // document.querySelector('form').addEventListener('submit', function (e) {
    //   const pw = document.getElementById('password').value;
    //   const pwConfirm = document.getElementById('password_confirm').value;
    //   if (pw !== pwConfirm) {
    //     e.preventDefault();
    //     e.stopPropagation();
    //     alert("Die Passwörter stimmen nicht überein.");
    //     document.getElementById('password_confirm').focus();
    //   }
    // });
  </script>
</body>
</html>
