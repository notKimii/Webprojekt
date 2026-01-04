<?php
session_start();

// 1. KORREKTUR: Absoluter Pfad zur connect.php
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php';

// Überprüfen, ob Session-Daten da sind (verhindert Fehler, falls Session abgelaufen ist)
if (!isset($_SESSION['user']['email'])) {
    header("Location: ../login/loginformular.php");
    exit;
}

$passwordold = hash('sha512', $_POST["passwordold"]);
$password = $_POST["password"];
$email = $_SESSION['user']['email'];

// Eingaben prüfen
if (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
    // Pfad hier eventuell anpassen, falls passwortAendern.php woanders liegt
    header("Location: passwortAendern.php?error=1");
    exit;
}

// 2. KORREKTUR: $conPDO statt $con verwenden
$stmt = $conPDO->prepare("SELECT * FROM user WHERE mail = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Passwort prüfen
if ($user && $passwordold == $user['passwort']) {
    // Neues Passwort hashen
    $passwordHash = hash('sha512', $password);
    
    // 3. KORREKTUR: Auch hier $conPDO statt $con
    $stmt = $conPDO->prepare("UPDATE user set passwort= ? WHERE mail=?");
    $stmt->execute([$passwordHash, $email]);

    // Optional: Session aktualisieren oder User ausloggen
    // Weiterleitung zur Startseite
    header('Location: /Webprojekt/index.php');
    exit;
}

// Falls das alte Passwort falsch war:
// Hinweis: Checke, ob die Datei 'loginform.php' oder 'loginformular.php' heißt!
header('Location: ../login/loginformular.php?error=wrong_old_pw');
exit;
?>