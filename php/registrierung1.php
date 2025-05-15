<?php
require_once __DIR__ . '/vendor/autoload.php';
use PHPGangsta_GoogleAuthenticator;

$pdo = new PDO('mysql:host=localhost;dbname=dbpferdeshop', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Eingaben prüfen
if (empty($_POST['mail']) || empty($_POST['password'])) {
    die("Bitte alle Felder ausfüllen.");
}

// Passwort sicher hashen
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Google Authenticator Secret generieren
$gAuth = new PHPGangsta_GoogleAuthenticator();
$secret = $gAuth->createSecret();

// QR-Code URL für Google Authenticator
$websiteName = 'PferdeShop'; // Oder dein Projektname
$qrCodeUrl = $gAuth->getQRCodeGoogleUrl($websiteName, $secret);

// User speichern
$stmt = $pdo->prepare("
    INSERT INTO user (vorname, nachname, adresse, plz, ort, mail, passwort, google_secret)
    VALUES (:vorname, :nachname, :adresse, :plz, :ort, :mail, :passwort, :google_secret)
");
$stmt->execute([
    'vorname' => $_POST['vorname'],
    'nachname' => $_POST['nachname'],
    'adresse' => $_POST['adresse'],
    'plz' => $_POST['plz'],
    'ort' => $_POST['ort'],
    'mail' => $_POST['mail'],
    'passwort' => $hashedPassword,
    'google_secret' => $secret
]);

echo "<h2>Registrierung erfolgreich!</h2>";
echo "<p>Bitte scanne diesen QR-Code mit deiner Google Authenticator App:</p>";
echo "<img src='$qrCodeUrl' alt='QR-Code'>";
echo "<p>Oder gib diesen Schlüssel manuell ein: <strong>$secret</strong></p>";
echo "<a href='login.html'>Jetzt einloggen</a>";
?>
