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
  <title>Cockpit Corner Passwort</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" 
    integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <style>
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
<body class="d-flex flex-column min-vh-100">

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
      <a href="/"><img src="../favicon.ico" alt="Logo" style="width: 100px;"></a>
    </nav>
  </header>

  <main class="flex-fill d-flex justify-content-center align-items-center">
    <div class="register-box">
      <form action="passwortsubmit.php" method="POST" class="needs-validation" novalidate>
        <!-- CSRF -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <h2 class="text-center mb-4">Passwort ändern</h2>

            <div class="form-group">
          <label for="passwordold">Altes Passwort</label>
          <input type="passwordold" class="form-control" id="passwordold" name="passwordold" minlength="9" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib ein Passwort mit mindestens 9 Zeichen ein.</div>
        </div>

        <!-- Passwort -->
        <div class="form-group">
          <label for="password">Passwort</label>
          <input type="password" class="form-control" id="password" name="password" minlength="9" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte gib ein Passwort mit mindestens 9 Zeichen ein.</div>
        </div>

        <!-- Passwort bestätigen -->
        <div class="form-group">
          <label for="password_confirm">Passwort wiederholen</label>
          <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="9" required>
          <div class="valid-feedback">Korrekt</div>
          <div class="invalid-feedback">Bitte wiederhole dein Passwort korrekt.</div>
        </div> 

    
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-block" style="background-color: #007aff; color: white; border: 2px solid #007aff; border-radius: 0;">Passwort speichern</button>
      </form>
    </div>
  </main>

  <footer class="w-100 text-center bg-light py-2">
    © 2025 Cockpit Corner - Alle Rechte vorbehalten
  </footer>

  <script>
 

    // Passwort-Abgleich
    document.querySelector('form').addEventListener('submit', function (e) {
      const pw = document.getElementById('password').value;
      const pwConfirm = document.getElementById('password_confirm').value;
      if (pw !== pwConfirm) {
        e.preventDefault();
        e.stopPropagation();
        alert("Die Passwörter stimmen nicht überein.");
        document.getElementById('password_confirm').focus();
      }
    });
  </script>
</body>
</html>
