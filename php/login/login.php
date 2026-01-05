<?php
// login.php

// Debugging (Später auf 0 setzen im Live-Betrieb)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/vendorconnect.php';

// POST-Daten abrufen
$email = trim($_POST['email'] ?? '');
$passwordInput = $_POST["password"] ?? '';
$code = $_POST['2fa_code'] ?? '';
$screen_resolution = $_POST['screen_resolution'] ?? 'Unbekannt';
$operating_system = $_POST['operating_system'] ?? 'Unbekannt';

// 3. Eingaben validieren
if (strlen($email) < 5 || strpos($email, '@') === false || empty($passwordInput)) {
    $_SESSION['error_msg'] = "Bitte überprüfen Sie Ihre Eingaben.";
    header("Location: /Webprojekt/php/login/loginformular.php"); 
    exit;
}

// Passwort-Komplexität prüfen (Sicherheitshalber)
if (strlen($passwordInput) < 9 || 
    !preg_match('/[A-Z]/', $passwordInput) || 
    !preg_match('/[a-z]/', $passwordInput) || 
    !preg_match('/\d/', $passwordInput)) {
    
    $_SESSION['error_msg'] = "Bitte überprüfen Sie Ihre Eingaben.";
    header("Location: /Webprojekt/php/login/loginformular.php");
    exit;
}

// Passwort hashen (SHA512)
$passwordHash = hash('sha512', $passwordInput);

$gAuth = new PHPGangsta_GoogleAuthenticator();

try {
    // 4. User laden
    $stmt = $conPDO->prepare("SELECT * FROM user WHERE mail = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 5. Passwort prüfen
    if ($user && $passwordHash === $user['passwort']) {
        
        // 2FA-Check: Bevor wir einloggen, erst 2FA prüfen!
        // (Logik leicht angepasst: Erst prüfen, dann Session setzen, ist sicherer)
        
        if ($user['google_secret'] == NULL) {
            // Sonderfall: Ersteinrichtung
             $_SESSION['temp_user_email'] = $email; // Zwischenspeichern für QR
             
             $secret = $gAuth->createSecret();
             $stmt = $conPDO->prepare("UPDATE user SET google_secret = ? WHERE mail = ?");
             $stmt->execute([$secret, $email]);

             header("Location: ../qr2fa.php"); 
             exit;
        }

        // Code prüfen
        $checkResult = $gAuth->verifyCode($user['google_secret'], $code, 2);
        
        if ($checkResult) {
            // ALLES OKAY -> Login durchführen
            $_SESSION['user'] = [
                'id'            => $user['id'],
                'vorname'       => $user['vorname'],
                'nachname'      => $user['nachname'],
                'email'         => $user['mail'],        
                'google_secret' => $user['google_secret']
            ];

            // Logs schreiben
            $login_time = date('Y-m-d H:i:s'); 
            $stmt = $conPDO->prepare("INSERT INTO logs (user_id, login_time, screen_resolution, operating_system) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $login_time, $screen_resolution, $operating_system]);

            // Online-Status
            $stmt = $conPDO->prepare("UPDATE user SET online=1 WHERE mail=?");
            $stmt->execute([$user['mail']]);

            // Weiterleitung Dashboard
            header("Location: ../../index.php");
            exit;

        } else {
            // 2FA Falsch
            $_SESSION['error_msg'] = "Bitte überprüfen Sie Ihre Eingaben.";
            header("Location: /Webprojekt/php/login/loginformular.php"); 
            exit;
        }

    } else {
        // Passwort falsch oder User existiert nicht
        // Wichtig: Gleiche Meldung wie oben aus Sicherheitsgründen
        $_SESSION['error_msg'] = "Bitte überprüfen Sie Ihre Eingaben.";
        header("Location: /Webprojekt/php/login/loginformular.php"); 
        exit;
    }

} catch (Exception $e) {
    die("Datenbankfehler: " . $e->getMessage());
}
?>