<?php
session_start();

// Absoluter Pfad zur connect.php
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php';

// Überprüfen, ob Session-Daten da sind
if (!isset($_SESSION['user']['email'])) {
    header("Location: ../login/loginformular.php");
    exit;
}

$passwordold = hash('sha512', $_POST["passwordold"]);
$password = $_POST["password"];
$email = $_SESSION['user']['email'];

// Eingaben prüfen (Komplexität des neuen Passworts)
if (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
    header("Location: passwortAendern.php?error=1");
    exit;
}

// Benutzer aus Datenbank laden
$stmt = $conPDO->prepare("SELECT * FROM user WHERE mail = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Passwort prüfen
if ($user && $passwordold == $user['passwort']) {
    // Neues Passwort hashen
    $passwordHash = hash('sha512', $password);
    
    // Update in der Datenbank
    $stmt = $conPDO->prepare("UPDATE user set passwort= ? WHERE mail=?");
    $stmt->execute([$passwordHash, $email]);

    // Weiterleitung zur Startseite bei Erfolg
    header('Location: /Webprojekt/index.php');
    exit;
}

// KORREKTUR: Falls das alte Passwort falsch war, zurück zur Änderungs-Seite leiten
header('Location: passwortAendern.php?error=wrong_old_pw');
exit;
?>