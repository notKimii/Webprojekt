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
      padding: 15px 0;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      background-color: rgba(255, 255, 255, 0.8);
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

    input[type="email"].invalid,
    input[type="password"].invalid,
    input[type="text"].invalid {
      border-color: #dc3545;
    }

    input[type="email"].invalid:focus,
    input[type="password"].invalid:focus,
    input[type="text"].invalid:focus {
      box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .invalid-feedback {
      display: none;
      color: #dc3545;
      font-size: 13px;
      margin-top: 5px;
    }

    input.invalid + .invalid-feedback {
      display: block;
    }

    #form-error {
      color: #dc3545;
      text-align: center;
      margin-bottom: 15px;
      padding: 10px;
      background-color: rgba(220, 53, 69, 0.1);
      border-radius: 6px;
      font-size: 14px;
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
      <a href="/Webprojekt/index.php"><img src="favicon.ico" alt="Logo" role="presentation"></a>
    </nav>
  </header>

  <main>
    <div class="login-box">
<<<<<<< Updated upstream:php/login/loginformular.php
      <form id="formlogin" action="/Webprojekt/php/login/login.php" method="POST" class="needs-validation" novalidate>
=======
      <form id="formlogin" action="/Webprojekt/php/login.php" method="POST">
>>>>>>> Stashed changes:loginformular.php
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <fieldset>
          <legend>In mein Kundenkonto einloggen</legend>
          
          <div class="input-group">
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" pattern=".{5,}.*@.*" required>
            <div class="invalid-feedback">Mindestens 5 Zeichen + @ erforderlich.</div>
          </div>

          <div class="input-group">
            <label for="password">Passwort</label>
            <input type="password" id="password" name="password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{9,}" required>
            <div class="invalid-feedback">Mindestens 9 Zeichen, Groß- und Kleinbuchstaben + Zahlen</div>
          </div>

          <div class="input-group">
            <label for="2fa_code">2FA Code</label>
            <input type="text" id="2fa_code" name="2fa_code" pattern="\d{6}" maxlength="6" placeholder="6-stelliger Code">
          </div>

          <div id="form-error">
            Fehlerhafte Eingabe
          </div>

          <input type="submit" name="login" class="btn" value="Login">

          <div class="bottom-link">
            <a href="/password-reset">Passwort vergessen?</a>
          </div>
        </fieldset>

        <div class="register-box">
          <a href="/Webprojekt/php/registrierung.php" class="btn btn-outline">Jetzt registrieren</a>
        </div>

        <!-- Versteckte Felder -->
        <input type="hidden" id="screen_resolution" name="screen_resolution">
        <input type="hidden" id="operating_system" name="operating_system">
      </form>
    </div>
  </main>

  <?php include("php/include/footimport.php"); ?>

  <script>
    (function () {
      'use strict';
      
      var form = document.getElementById('formlogin');
      var errorDiv = document.getElementById('form-error');
      var inputs = form.querySelectorAll('input[required]');

      // Validierung bei Submit
      form.addEventListener('submit', function (event) {
        var isValid = true;
        
        inputs.forEach(function(input) {
          if (!input.checkValidity()) {
            isValid = false;
            input.classList.add('invalid');
          } else {
            input.classList.remove('invalid');
          }
        });

        if (!isValid) {
          event.preventDefault();
          errorDiv.style.display = 'block';
        } else {
          errorDiv.style.display = 'none';
        }
      });

      // Live-Validierung beim Tippen
      inputs.forEach(function(input) {
        input.addEventListener('input', function() {
          if (this.checkValidity()) {
            this.classList.remove('invalid');
          }
        });

        input.addEventListener('blur', function() {
          if (!this.checkValidity() && this.value !== '') {
            this.classList.add('invalid');
          }
        });
      });

      // Bildschirmauflösung und Betriebssystem erfassen
      document.getElementById('screen_resolution').value = window.screen.width + 'x' + window.screen.height;
      document.getElementById('operating_system').value = navigator.platform;
    })();
  </script>
</body>
</html>