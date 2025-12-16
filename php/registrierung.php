<?php
session_start();
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
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: url('/Webprojekt/images/pictures/sky.jpg') center/cover no-repeat fixed;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      width: 100%;
      text-align: center;
      padding: 10px 0;
      background-color: rgba(255, 255, 255, 0.35);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    header nav {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    header nav img {
      width: 90px;
      transition: transform 0.3s ease;
    }

    header nav img:hover {
      transform: scale(1.05);
    }

    main {
      flex: 1;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0 20px;
    }

    .register-box {
      background-color: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 30px;
      margin-block: 5rem;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-radius: 10px;
      animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    h2 {
      text-align: center;
      font-size: 26px !important;
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #1a1a1a;
    }

    .alert {
      padding: 12px 16px;
      margin-bottom: 1rem;
      border-radius: 5px;
      position: relative;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .alert .close {
      background: none;
      border: none;
      font-size: 24px;
      color: inherit;
      cursor: pointer;
      padding: 0;
      line-height: 1;
      opacity: 0.6;
      transition: opacity 0.3s ease;
    }

    .alert .close:hover {
      opacity: 1;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 400;
      color: #333;
      font-size: 14px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
      border-radius: 8px;
      transition: all 0.3s ease;
      font-family: 'Roboto', sans-serif;
      background-color: white;
    }

    .form-control:focus {
      border-color: #007aff;
      outline: none;
      box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
    }

    .form-control.invalid {
      border-color: #dc3545;
    }

    .form-control.invalid:focus {
      box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .form-control.valid {
      border-color: #28a745;
    }

    .valid-feedback {
      display: none;
      color: #28a745;
      font-size: 13px;
      margin-top: 0.25rem;
    }

    .form-control.valid ~ .valid-feedback {
      display: block;
    }

    .invalid-feedback {
      display: none;
      color: #dc3545;
      font-size: 13px;
      margin-top: 0.25rem;
    }

    .form-control.invalid ~ .invalid-feedback,
    .form-control.invalid ~ .valid-feedback ~ .invalid-feedback {
      display: block;
    }

    .form-check {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1rem;
    }

    .form-check-input {
      width: 18px;
      height: 18px;
      margin-right: 10px;
      margin-top: 3px;
      cursor: pointer;
      flex-shrink: 0;
      accent-color: #007aff;
    }

    .form-check-label {
      font-size: 14px;
      color: #333;
      line-height: 1.5;
      cursor: pointer;
      font-weight: 400;
    }

    .form-check-label a {
      color: #007aff;
      text-decoration: none;
      font-weight: 400;
      transition: color 0.3s ease;
    }

    .form-check-label a:hover {
      color: #0051d5;
      text-decoration: underline;
    }

    .form-check .invalid-feedback {
      margin-left: 28px;
      margin-top: 0.25rem;
    }

    .btn {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      border: 2px solid #007aff;
      color: white;
      background-color: #007aff;
      text-align: center;
      border-radius: 8px;
      transition: all 0.3s ease;
      margin-top: 0.5rem;
    }

    .btn:hover {
      background-color: rgb(0, 97, 201);
      border-color: rgb(0, 97, 201);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 122, 255, 0.4);
    }

    .btn:active {
      transform: translateY(0);
    }

    .text-center {
      text-align: center;
      margin-top: 1rem;
      font-size: 14px;
      color: #666;
    }

    .text-center a {
      color: #007aff;
      text-decoration: none;
      font-weight: 400;
      transition: color 0.3s ease;
    }

    .text-center a:hover {
      color: #0051d5;
      text-decoration: underline;
    }

    footer {
      width: 100%;
      text-align: center;
      padding: 10px 0;
      font-size: 14px;
      background-color: rgba(255, 255, 255, 0.35);
      backdrop-filter: blur(10px);
      color: #666;
    }

    /* 2-spaltige Anordnung für Name und PLZ/Ort */
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-row .form-group {
      margin-bottom: 1rem;
    }

    /* Responsive Design */
    @media (max-width: 767px) {
      .form-row {
        grid-template-columns: 1fr;
        gap: 0;
      }
    }

    @media (max-width: 767px) {
      .register-box {
        padding: 30px 25px;
      }

      h2 {
        font-size: 20px;
      }
    }

    @media (max-width: 480px) {
      main {
        padding: 0 15px;
      }

      .register-box {
        padding: 25px 20px;
        margin-block: 3rem;
      }

      h2 {
        font-size: 18px;
      }

      .form-control {
        padding: 10px;
      }

      .btn {
        padding: 12px;
      }

      header nav img {
        width: 70px;
      }
    }

    @media (max-width: 360px) {
      .register-box {
        padding: 20px 15px;
      }

      .alert {
        padding: 10px 12px;
        font-size: 13px;
      }
    }
  </style>
</head>

<body>
  <?php if (!empty($mailFehler)): ?>
    <div class="alert alert-danger">
      <span><?= htmlspecialchars($mailFehler) ?></span>
      <button type="button" class="close" onclick="this.parentElement.style.display='none'">
        <span>&times;</span>
      </button>
    </div>
  <?php endif; ?>
  
  <header>
    <nav>
      <a href="/Webprojekt/index.php"><img src="/Webprojekt/favicon.ico" alt="Logo" role="presentation"></a>
    </nav>
  </header>

  <main>
    <div class="register-box">
      <form action="registrierungsubmit.php" method="POST" id="registerForm">
        <!-- CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <h2>Kundenkonto erstellen</h2>

        <!-- Vorname & Nachname -->
        <div class="form-row">
          <div class="form-group">
            <label for="vorname">Vorname</label>
            <input type="text" class="form-control" id="vorname" name="vorname" value="<?= htmlspecialchars($formData['vorname'] ?? '') ?>" required>
            <div class="valid-feedback">Korrekt</div>
            <div class="invalid-feedback">Bitte gib deinen Vornamen an.</div>
          </div>

          <div class="form-group">
            <label for="nachname">Nachname</label>
            <input type="text" class="form-control" id="nachname" name="nachname" value="<?= htmlspecialchars($formData['nachname'] ?? '') ?>" required>
            <div class="valid-feedback">Korrekt</div>
            <div class="invalid-feedback">Bitte gib deinen Nachnamen an.</div>
          </div>
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
          <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($formData['adresse'] ?? '') ?>" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib deine Adresse an.</div>
        </div>

        <!-- PLZ & Ort -->
        <div class="form-row">
          <div class="form-group">
            <label for="plz">Postleitzahl</label>
            <input type="text" class="form-control" id="plz" name="plz" pattern="\d{5}" maxlength="5" value="<?= htmlspecialchars($formData['plz'] ?? '') ?>" required>
            <div class="valid-feedback">Korrekt</div>
            <div class="invalid-feedback">Bitte gib eine 5-stellige Postleitzahl an.</div>
          </div>

          <div class="form-group">
            <label for="ort">Ort</label>
            <input type="text" class="form-control" id="ort" name="ort" value="<?= htmlspecialchars($formData['ort'] ?? '') ?>" required>
            <div class="valid-feedback">Korrekt</div>
            <div class="invalid-feedback">Bitte gib deinen Wohnort an.</div>
          </div>
        </div>

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
        <button type="submit" class="btn">Registrieren</button>

<<<<<<< Updated upstream
        <div class="text-center mt-3">
          Schon ein Konto? <a href="/Webprojekt/php/login/loginformular.php" style="color: #007aff;">Hier einloggen</a>
=======
        <div class="text-center">
          Schon ein Konto? <a href="/Webprojekt/loginformular.php">Hier einloggen</a>
>>>>>>> Stashed changes
        </div>
      </form>
    </div>
  </main>

  <?php include "include/footimport.php"; ?>

  <script>
    (function () {
      'use strict';

      var form = document.getElementById('registerForm');
      var inputs = form.querySelectorAll('.form-control, .form-check-input');

      // Validierung bei Submit
      form.addEventListener('submit', function (event) {
        var isValid = true;
        
        inputs.forEach(function(input) {
          validateInput(input);
          if (!input.checkValidity()) {
            isValid = false;
          }
        });

        if (!isValid) {
          event.preventDefault();
        }
      });

      // Live-Validierung
      inputs.forEach(function(input) {
        // Beim Tippen
        input.addEventListener('input', function() {
          if (this.value !== '' || this.type === 'checkbox') {
            validateInput(this);
          }
        });

        // Beim Verlassen des Feldes
        input.addEventListener('blur', function() {
          if (this.value !== '' || this.type === 'checkbox') {
            validateInput(this);
          }
        });
      });

      function validateInput(input) {
        if (input.checkValidity()) {
          input.classList.remove('invalid');
          input.classList.add('valid');
        } else {
          input.classList.remove('valid');
          input.classList.add('invalid');
        }
      }
    })();
  </script>
</body>
</html>