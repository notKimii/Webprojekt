<?php
    include 'include/loginpruef.php';
    $mailFehler = null;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cockpit Corner Passwort</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <style>
        body{
            background-image: url(/Webprojekt/images/pictures/sky.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .register-box {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            margin: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        button{
            border-radius: 5px !important;
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
            <a href="/Webprojekt/index.php"><img src="../favicon.ico" alt="Logo" style="width: 100px;"></a>
        </nav>
    </header>

    <div class="container mt-3">
        <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 1) {
                echo "<div class='alert alert-danger text-center'>Das Passwort muss mindestens 9 Zeichen lang sein und mindestens einen Großbuchstaben, einen Kleinbuchstaben und eine Zahl enthalten.</div>";
            } elseif ($_GET['error'] == 'wrong_old_pw') {
                echo "<div class='alert alert-danger text-center'>Das eingegebene <strong>alte Passwort</strong> ist leider nicht korrekt.</div>";
            }
        }
        ?>
    </div>

    <main class="flex-fill d-flex justify-content-center align-items-center">
        <div class="register-box">
            <form action="passwortsubmit.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <h2 class="text-center mb-4">Passwort ändern</h2>

                <div class="form-group">
                    <label for="passwordold">Altes Passwort</label>
                    <input type="password" class="form-control" id="passwordold" name="passwordold" required>
                    <div class="invalid-feedback">Bitte gib dein aktuelles Passwort ein.</div>
                </div>

                <div class="form-group">
                    <label for="password">Neues Passwort</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="9" required>
                    <div class="invalid-feedback">Mindestens 9 Zeichen erforderlich.</div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Neues Passwort wiederholen</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="9" required>
                    <div class="invalid-feedback">Bitte wiederhole dein neues Passwort korrekt.</div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="background-color: #007aff; border: none;">Passwort speichern</button>
            </form>
        </div>
    </main>

    <footer class="w-100 text-center bg-light py-2">
        © 2025 Cockpit Corner - Alle Rechte vorbehalten
    </footer>

    <script>
        // Passwort-Abgleich Clientseitig
        document.querySelector('form').addEventListener('submit', function(e) {
            const pw = document.getElementById('password').value;
            const pwConfirm = document.getElementById('password_confirm').value;
            if (pw !== pwConfirm) {
                e.preventDefault();
                alert("Die neuen Passwörter stimmen nicht überein.");
                document.getElementById('password_confirm').focus();
            }
        });
    </script>
</body>
</html>