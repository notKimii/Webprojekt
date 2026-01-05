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
      width: auto;              /* Override full-width */
      display: inline-block;    /* Shrink to content */
      text-decoration: none;
    }
    .btn + .btn-outline {
      margin-top: 30px;         /* Mehr Abstand nach oben */
    }
    .register-box {
      text-align: center;
      margin-top: 20px;         /* Noch etwas Luft oben */
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
  </style>
</head>
<body>
  <header>
    <nav>
      <a href="/"><img src="favicon.ico" alt="" role="presentation"></a>
    </nav>
  </header>
  <main>
    <div class="login-box">
      <form action="./php/login.php" method="POST" class="needs-validation" novalidate>
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
            <input type="password" id="password" name="password"  pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{9,}" required>
            <div class="invalid-feedback">Mindestens 9 Zeichen, Groß- und Kleinbuchstaben + Zahlen </div>
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
          <a href="./php/registrierung.php" class="btn btn-outline">Jetzt registrieren</a>
        </div>
      </form>
    </div>
  </main>
  <footer>
    © 2025 Cockpit Corner - Alle Rechte vorbehalten
  </footer>
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
</script>
</body>
</html>
