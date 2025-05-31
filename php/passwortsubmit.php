<?php

session_start();
include 'include/connect.php';

$passwordold = hash('sha512', $_POST["passwordold"]);
$password = $_POST["password"];
$email = $_SESSION['temp_user']['email'];

// Eingaben prüfen
if (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
    header("Location: passwortAendern.php?error=1");
    exit;
}


$stmt = $con->prepare("SELECT * FROM user WHERE mail = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Passwort prüfen
if ($user && $passwordold == $user['passwort']) {
    //sha512
    $password = hash('sha512', $password);
    $stmt = $con->prepare("UPDATE user set passwort= ? WHERE mail=?");
    $stmt->execute([$password, $email]);

    header('Location: ../login.html');
    exit;
}

header('Location: ../basis.html');
exit;
