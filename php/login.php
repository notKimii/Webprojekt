<?php
session_start();


// DB-Verbindung mit Fehler-Reporting
include 'include/connect.php';
include 'include/debug.php';

$email    = trim($_POST['email']);
$code = $_POST['2fa_code'] ?? '';
$password = $_POST["password"];
$screen_resolution = $_POST['screen_resolution']; // Bildschirmauflösung
$operating_system = $_POST['operating_system']; // Betriebssystem

// Eingaben prüfen
if (strlen($email) < 5 || strpos($email, '@') === false || empty($password)) {
    header("Location: ../loginformular.php");
    exit;
}

if (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
    header("Location: ../loginformular.php");
    exit;
}

// Wenn alle Prüfungen bestanden: Passwort hashen
$password = hash('sha512', $_POST["password"]);

include 'include/vendorconnect.php';
$gAuth = new PHPGangsta_GoogleAuthenticator();


// User laden 
$stmt = $con->prepare("SELECT * FROM user WHERE mail = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Passwort prüfen
if ($user && $password == $user['passwort']) {
    $_SESSION['temp_user'] = [
        'id'            => $user['id'],
        'vorname'       => $user['vorname'],
        'nachname'      => $user['nachname'],
        'adresse'       => $user['adresse'],
        'plz'           => $user['plz'],
        'ort'           => $user['ort'],
        'email'         => $user['mail'],        
        'google_secret' => $user['google_secret']
    ];

    //Aktuelle Zeit
    $login_time = date('Y-m-d H:i:s'); 

    // Log-Daten in der Datenbank speichern
    $stmt = $con->prepare("INSERT INTO logs (user_id, login_time, screen_resolution, operating_system) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user['id'], $login_time, $screen_resolution, $operating_system]);

    $stmt = $con->prepare("UPDATE user online=1");
    $stmt->execute([$user['id'], $login_time, $screen_resolution, $operating_system]);


    if ($user['google_secret'] == NULL) {
        //Secret erstellen 
        $secret = $gAuth->createSecret();

        //Secret in DB ablegen
        $stmt = $con->prepare("UPDATE user SET google_secret = ? WHERE mail = ?");
        $stmt->execute([$secret, $email]);

        //weiter zum QRCode
        header("Location: qr2fa.php");
        exit;
    }
    $checkResult = $gAuth->verifyCode($user['google_secret'], $code, 2);
    if ($checkResult) {
        header("Location: ../index.php");
        exit;
    } else {
        header("Location: ../loginformular.php");
        exit;
    }
} else {
    header("Location: ../loginformular.php");
    exit;
}

