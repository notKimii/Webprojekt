<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use PHPGangsta_GoogleAuthenticator;

if (!isset($_SESSION['temp_user'])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $secret = $_SESSION['temp_user']['google_secret'];

    $gAuth = new PHPGangsta_GoogleAuthenticator();
    $isValid = $gAuth->verifyCode($secret, $code, 2); // 2 = Zeitfenster-Toleranz (2 * 30s)

    if ($isValid) {
        // 2FA erfolgreich → Vollständige Session setzen
        $_SESSION['login'] = 1;
        $_SESSION['id'] = $_SESSION['temp_user']['id'];
        $_SESSION['username'] = $_SESSION['temp_user']['vorname'];
        $_SESSION['nachname'] = $_SESSION['temp_user']['nachname'];
        $_SESSION['adresse'] = $_SESSION['temp_user']['adresse'];
        $_SESSION['plz'] = $_SESSION['temp_user']['plz'];
        $_SESSION['ort'] = $_SESSION['temp_user']['ort'];
        $_SESSION['mail'] = $_SESSION['temp_user']['mail'];
        unset($_SESSION['temp_user']); // Sicherheit: temporäre Daten löschen

        // Benutzer als online markieren
        $pdo = new PDO('mysql:host=localhost;dbname=dbpferdeshop', 'root', '');
        $stmt = $pdo->prepare("INSERT INTO online (userID, timeonline) VALUES (:id, NOW())");
        $stmt->execute(['id' => $_SESSION['id']]);

        header("Location: basis.php");
        exit();
    } else {
        $error = "Ungültiger Code";
    }
}
?>

<!-- HTML: TOTP-Code eingeben -->
<h2>Google Authenticator Code eingeben</h2>
<form method="post">
    <input type="text" name="code" pattern="\d{6}" required placeholder="123456">
    <button type="submit">Bestätigen</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
