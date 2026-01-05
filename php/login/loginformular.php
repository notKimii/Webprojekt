<?php
session_start();
$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']); // Fehlermeldung nach dem Auslesen löschen
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Cockpit Corner</title>
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
      padding: 20px;
      margin-top: -40px;
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 40px;
      max-width: 440px;
      width: 100%;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
      border-radius: 10px;
      animation: fadeInUp 0.5s ease;
      margin-top: 75px;
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

    fieldset {
      border: none;
    }

    legend {
      font-weight: 700;
      font-size: 24px;
      margin-bottom: 30px;
      color: #1a1a1a;
      text-align: center;
    }

    .input-group {
      margin-bottom: 20px;
      position: relative;
    }

    .input-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
      font-size: 14px;
    }

    input[type="email"],
    input[type="password"],
    input[type="text"] {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      font-size: 16px;
      border-radius: 8px;
      transition: all 0.3s ease;
      font-family: 'Roboto', sans-serif;
    }

    input[type="email"]:focus,
    input[type="password"]:focus,
    input[type="text"]:focus {
      border-color: #007aff;
      outline: none;
      box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
    }

    input.input-error {
      border-color: #dc3545 !important;
      background-color: #fff8f8 !important;
    }

    input.input-error:focus {
      box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
    }

    .error-message {
      color: #dc3545;
      font-size: 13px;
      margin-top: 6px;
      display: none;
      padding-left: 2px;
    }

    #form-error,
    .server-error {
      color: #dc3545;
      text-align: center;
      margin-bottom: 20px;
      padding: 12px 15px;
      background-color: rgba(220, 53, 69, 0.1);
      border: 1px solid rgba(220, 53, 69, 0.3);
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
    }

    #form-error {
      display: none;
    }

    .btn {
      width: 100%;
      padding: 14px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      color: white;
      background: linear-gradient(135deg, #007aff 0%, #0051d5 100%);
      text-align: center;
      margin-top: 10px;
      border-radius: 8px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn:hover {
      background: linear-gradient(135deg, #0051d5 0%, #003d9e 100%);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 122, 255, 0.4);
    }

    .btn:active {
      transform: translateY(0);
    }

    .btn-outline {
      background: white;
      color: #333;
      border: 2px solid #e0e0e0;
      width: auto;
      display: inline-block;
    }

    .btn-outline:hover {
      background: #f5f5f5;
      border-color: #ccc;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .register-box {
      text-align: center;
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #e0e0e0;
    }

    .bottom-link {
      margin-top: 15px;
      font-size: 14px;
      text-align: center;
    }

    .bottom-link a {
      text-decoration: none;
      color: #007aff;
      transition: color 0.3s ease;
      font-weight: 500;
    }

    .bottom-link a:hover {
      color: #0051d5;
      text-decoration: underline;
    }

    footer {
      width: 100%;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      background-color: rgba(255, 255, 255, 0.35);
      backdrop-filter: blur(10px);
      color: #666;
    }

    /* Responsive Design */
    @media (max-width: 480px) {
      .login-box {
        padding: 30px 20px;
      }

      legend {
        font-size: 20px;
        margin-bottom: 25px;
      }

      input[type="email"],
      input[type="password"],
      input[type="text"] {
        padding: 10px 12px;
        font-size: 15px;
      }

      .btn {
        padding: 12px;
        font-size: 15px;
      }

      header nav img {
        width: 70px;
      }
    }

    @media (max-width: 360px) {
      main {
        padding: 15px;
      }

      .login-box {
        padding: 25px 15px;
      }
    }
  </style>
</head>

<body>
<header>
    <nav>
      <a href="/Webprojekt/index.php"><img src="/Webprojekt/favicon.ico" alt="Logo" role="presentation"></a>
    </nav>
  </header>

  <main>
    <div class="login-box">

      <form id="formlogin" action="/Webprojekt/php/login/login.php" method="POST" novalidate>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <fieldset>
          <legend>In mein Kundenkonto einloggen</legend>

          <?php if (!empty($login_error)): ?>
          <div class="server-error">
            ⚠️ <?= htmlspecialchars($login_error) ?>
          </div>
          <?php endif; ?>

          <div id="form-error">
            ⚠️ Bitte korrigieren Sie die markierten Felder.
          </div>
          
          <div class="input-group">
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" required>
            <div class="error-message" id="email-error">Bitte geben Sie eine gültige E-Mail-Adresse ein.</div>
          </div>

          <div class="input-group">
            <label for="password">Passwort</label>
            <input type="password" id="password" name="password" required>
            <div class="error-message" id="password-error">Mindestens 9 Zeichen, Groß- und Kleinbuchstaben + Zahl erforderlich.</div>
          </div>

          <div class="input-group">
            <label for="2fa_code">2FA Code</label>
            <input type="text" id="2fa_code" name="2fa_code" maxlength="6" placeholder="6-stelliger Code" required>
            <div class="error-message" id="2fa-error">Bitte geben Sie Ihren 6-stelligen 2FA-Code ein.</div>
          </div>

          <input type="submit" name="login" class="btn" value="Login">

          <div class="bottom-link">
            <a href="/Webprojekt/php/passwort_vergessen.php">Passwort vergessen?</a>
          </div>
        </fieldset>

        <div class="register-box">
          <a href="/Webprojekt/php/registrierung/registrierung.php" class="btn btn-outline">Jetzt registrieren</a>
        </div>

        <!-- Versteckte Felder -->
        <input type="hidden" id="screen_resolution" name="screen_resolution">
        <input type="hidden" id="operating_system" name="operating_system">
      </form>
    </div>
  </main>

  <?php include $_SERVER['DOCUMENT_ROOT'] . "/Webprojekt/php/include/footimport.php"; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      
      var form = document.getElementById('formlogin');
      var formError = document.getElementById('form-error');
      
      var emailInput = document.getElementById('email');
      var emailError = document.getElementById('email-error');
      
      var passwordInput = document.getElementById('password');
      var passwordError = document.getElementById('password-error');
      
      var tfaInput = document.getElementById('2fa_code');
      var tfaError = document.getElementById('2fa-error');

      // Validierungsfunktionen
      function validateEmail(value) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return value.length >= 5 && emailRegex.test(value);
      }

      function validatePassword(value) {
        // Mindestens 9 Zeichen, Groß- und Kleinbuchstaben + Zahl
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{9,}$/;
        return passwordRegex.test(value);
      }

      function validate2FA(value) {
        // Genau 6 Ziffern
        var tfaRegex = /^\d{6}$/;
        return tfaRegex.test(value);
      }

      // Fehler anzeigen/verstecken
      function showError(input, errorDiv) {
        input.classList.add('input-error');
        errorDiv.style.display = 'block';
      }

      function hideError(input, errorDiv) {
        input.classList.remove('input-error');
        errorDiv.style.display = 'none';
      }

      // Formular-Submit
      form.addEventListener('submit', function(e) {
        var isValid = true;

        // E-Mail prüfen
        if (!validateEmail(emailInput.value)) {
          showError(emailInput, emailError);
          isValid = false;
        } else {
          hideError(emailInput, emailError);
        }

        // Passwort prüfen
        if (!validatePassword(passwordInput.value)) {
          showError(passwordInput, passwordError);
          isValid = false;
        } else {
          hideError(passwordInput, passwordError);
        }

        // 2FA prüfen
        if (!validate2FA(tfaInput.value)) {
          showError(tfaInput, tfaError);
          isValid = false;
        } else {
          hideError(tfaInput, tfaError);
        }

        // Wenn ungültig, Formular nicht absenden
        if (!isValid) {
          e.preventDefault();
          formError.style.display = 'block';
          
          // Zum ersten Fehler scrollen
          var firstError = form.querySelector('.input-error');
          if (firstError) {
            firstError.focus();
          }
        } else {
          formError.style.display = 'none';
        }
      });

      // Live-Validierung bei Eingabe
      emailInput.addEventListener('input', function() {
        if (validateEmail(this.value)) {
          hideError(this, emailError);
          checkAllValid();
        }
      });

      passwordInput.addEventListener('input', function() {
        if (validatePassword(this.value)) {
          hideError(this, passwordError);
          checkAllValid();
        }
      });

      tfaInput.addEventListener('input', function() {
        // Nur Zahlen erlauben
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (validate2FA(this.value)) {
          hideError(this, tfaError);
          checkAllValid();
        }
      });

      // Validierung wenn Feld verlassen wird
      emailInput.addEventListener('blur', function() {
        if (this.value !== '' && !validateEmail(this.value)) {
          showError(this, emailError);
        }
      });

      passwordInput.addEventListener('blur', function() {
        if (this.value !== '' && !validatePassword(this.value)) {
          showError(this, passwordError);
        }
      });

      tfaInput.addEventListener('blur', function() {
        if (this.value !== '' && !validate2FA(this.value)) {
          showError(this, tfaError);
        }
      });

      // Prüfen ob alle Felder gültig sind
      function checkAllValid() {
        var allValid = validateEmail(emailInput.value) && 
                       validatePassword(passwordInput.value) && 
                       validate2FA(tfaInput.value);
        
        if (allValid) {
          formError.style.display = 'none';
        }
      }

      // Bildschirmauflösung und Betriebssystem erfassen
      document.getElementById('screen_resolution').value = window.screen.width + 'x' + window.screen.height;
      document.getElementById('operating_system').value = navigator.platform;
    });
  </script>
</body>
</html>