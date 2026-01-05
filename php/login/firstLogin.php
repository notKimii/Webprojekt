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
  <title>Cockpit Corner Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" 
  integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-family: 'Roboto', sans-serif;
      background: url('/Webprojekt/images/pictures/sky.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    header {
      width: 100%;
      text-align: center;
      padding: 15px 0;
      background-color: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    header, footer {
      width: 100%;
      text-align: center;
      padding: 10px 0;
      font-size: 14px;
    }
    header nav img {
      width: 100px;
    }
    main {
      flex: 1;
      width: 100%;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      border-radius: 10px;
    }
    @media (max-width: 420px) {
      .login-box { padding: 20px; }
    }
    fieldset {
      border: none;
      padding: 0;
      margin: 0;
      text-align: center;
    }
    legend {
      font-weight: 700;
      font-size: 26px !important;
      margin-bottom: 20px;
    }
    .input-group {
      margin-bottom: 15px;
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
    input[type="text"]{
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
      border-radius: 8px;
    }
    input:focus {
      border-color: #007aff;
      outline: none;
    }
    .form-control {
      padding: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    .checkbox {
      margin-bottom: 15px;
    }
    .btn {
      width: 100%;
      padding: 12px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      color: white;
      background-color: #007aff;
      text-align: center;
      border-radius: 8px;
      margin-top: 10px;
    }
    .btn-outline {
      background-color: white;
      color: black;
      border: 1px solid #868686;
      width: auto;
      display: inline-block;
      text-decoration: none;
    }
    .btn + .btn-outline {
      margin-top: 30px;
    }
    .register-box {
      text-align: center;
      margin-top: 20px;
    }
    .bottom-link {
      margin-top: 10px;
      font-size: 14px;
      text-align: center;
    }
    .bottom-link a {
      text-decoration: none;
      color: #007aff;
    }
    
    /* Fehlermeldung Styling */
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
      color: #dc3545;
      text-align: center;
      margin-bottom: 10px;
      padding: 12px 15px;
      background-color: rgba(220, 53, 69, 0.1);
      border: 1px solid rgba(220, 53, 69, 0.3);
      border-radius: 8px;
      font-size: 14px;
      display: none;
    }
    
    input.input-error {
      border-color: #dc3545 !important;
      background-color: #fff8f8 !important;
    }
    
    .error-message {
      color: #dc3545;
      font-size: 13px;
      margin-top: 6px;
      display: none;
      text-align: left;
    }
  </style>
</head>
<body>
  <header>
    <nav>
      <a href="/Webprojekt/index.php"><img src="/Webprojekt/favicon.ico" alt="" role="presentation"></a>
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
        
        <!-- Versteckte Felder für Logging -->
        <input type="hidden" id="screen_resolution" name="screen_resolution">
        <input type="hidden" id="operating_system" name="operating_system">
      </form>
    </div>
  </main>
  <footer>
    © 2025 Cockpit Corner - Alle Rechte vorbehalten
  </footer>
  
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
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{9,}$/;
        return passwordRegex.test(value);
      }

      function validate2FA(value) {
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

        if (!validateEmail(emailInput.value)) {
          showError(emailInput, emailError);
          isValid = false;
        } else {
          hideError(emailInput, emailError);
        }

        if (!validatePassword(passwordInput.value)) {
          showError(passwordInput, passwordError);
          isValid = false;
        } else {
          hideError(passwordInput, passwordError);
        }

        if (!validate2FA(tfaInput.value)) {
          showError(tfaInput, tfaError);
          isValid = false;
        } else {
          hideError(tfaInput, tfaError);
        }

        if (!isValid) {
          e.preventDefault();
          formError.style.display = 'block';
          
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