<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Cockpit Corner</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
    integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      font-family: 'Roboto', sans-serif;
      background: url('/Webprojekt/images/pictures/sky.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    
    nav{
      display: flex;
      justify-content: center;
      align-items: center;
    }

    header,
    footer {
      width: 100%;
      text-align: center;
      padding: 10px 0;
      font-size: 14px;
      background-color:rgba(255, 255, 255, 0.35) !important;
    }

    header nav img {
      width: 90px;
    }

    main {
      flex: 1;
      width: 100%;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      transform: translateY(-80px);
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-radius: 5px;
    }

    @media (max-width: 420px) {
      .login-box {
        padding: 20px;
      }
    }

    fieldset {
      border: none;
      padding: 0;
      margin: 0;
    }

    legend {
      font-weight: 700;
      font-size: 22px;
      margin-bottom: 20px;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      margin-bottom: 8px;
    }

    input[type="email"],
    input[type="password"],
    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      font-size: 16px;
      border-radius: 5px;
    }

    input:focus {
      border-color: #081e83;
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
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      color: white;
      background-color: #007aff;
      text-align: center;
      margin-top: 1rem;
    }

    .btn:hover {
      background-color:rgb(0, 97, 201);
      color: white;
    }

    .btn-outline {
      background-color: white;
      color: black;
      border: .4px solid rgb(155, 155, 155);
      width: auto;
      display: inline-block;
      text-decoration: none;
    }

    .btn+.btn-outline {
      margin-top: 20px;
    }

    .register-box {
      text-align: center;
      margin-top: 20px;
    }
    .register-box a {
      background-color:rgb(245, 245, 245);
      color:rgb(59, 59, 59);
    }

    .register-box a:hover {
      background-color:rgb(227, 227, 227);
      color:rgb(59, 59, 59);
    }

    .bottom-link {
      margin-top: 10px;
      font-size: 14px;
      text-align: center;
    }

    .bottom-link a:hover{
      text-decoration: underline;
    }

    .bottom-link a {
      text-decoration: none;
      color: #007aff;
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
      <form id="formlogin" action="/Webprojekt/php/login.php" method="POST" class="needs-validation" novalidate>
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
            <div class="invalid-feedback">Mindestens 9 Zeichen, Groß- und Kleinbuchstaben + Zahlen </div>
          </div>
          <div class="input-group">
            <label for="2fa_code">2FA Code</label>
            <input type="text" id="2fa_code" name="2fa_code" pattern="\d{6}" maxlength="6"
              placeholder="6-stelliger Code">
          </div>
          <div id="form-error" style="color: red; text-align: center; margin-bottom: 10px; display: none;">
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
        <!-- Versteckte Felder-->
        <input type="hidden" id="screen_resolution" name="screen_resolution">
        <input type="hidden" id="operating_system" name="operating_system">
      </form>

    </div>
  </main>
  <?php include("php/include/footimport.php"); ?>
  <script>
    (function () {
      'use strict';
      window.addEventListener('load', function () {
        var form = document.querySelector('.needs-validation');
        var errorDiv = document.getElementById('form-error');

        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            errorDiv.style.display = 'block';
          } else {
            errorDiv.style.display = 'none';
          }
        }, false);
      }, false);
    })();

    // Bildschirmauflösung und Betriebssystem erfassen
    document.getElementById('screen_resolution').value = window.screen.width + 'x' + window.screen.height;
    document.getElementById('operating_system').value = navigator.platform;
  </script>
</body>
</body>

</html>