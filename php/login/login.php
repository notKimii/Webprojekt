<?php
// 1. Debugging aktivieren (damit du Fehler siehst, falls noch welche da sind)
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
// Prüfung auf leere Felder
if (strlen($email) < 5 || strpos($email, '@') === false || empty($passwordInput)) {
    $_SESSION['login_error'] = "Bitte füllen Sie alle Felder korrekt aus.";
    header("Location: loginformular.php");
    exit;
}

// Passwort-Komplexität prüfen (optional an dieser Stelle, spart DB-Abfrage)
if (strlen($passwordInput) < 9 || 
    !preg_match('/[A-Z]/', $passwordInput) || 
    !preg_match('/[a-z]/', $passwordInput) || 
    !preg_match('/\d/', $passwordInput)) {
    $_SESSION['login_error'] = "E-Mail oder Passwort ist falsch.";
    header("Location: loginformular.php");
    exit;
}

// Passwort hashen (SHA512 wie bei der Registrierung)
$passwordHash = hash('sha512', $passwordInput);


$gAuth = new PHPGangsta_GoogleAuthenticator();

try {
    // 4. User laden
    // WICHTIG: Hier $conPDO statt $con verwenden (passend zur connect.php)
    $stmt = $conPDO->prepare("SELECT * FROM user WHERE mail = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 5. Passwort prüfen
    if ($user && $passwordHash === $user['passwort']) {
        
        // Session setzen
        $_SESSION['user'] = [
            'id'            => $user['id'],
            'vorname'       => $user['vorname'],
            'nachname'      => $user['nachname'],
            'adresse'       => $user['adresse'],
            'plz'           => $user['plz'],
            'ort'           => $user['ort'],
            'email'         => $user['mail'],        
            'google_secret' => $user['google_secret']
        ];

        // Log-Daten speichern
        $login_time = date('Y-m-d H:i:s'); 
        $stmt = $conPDO->prepare("INSERT INTO logs (user_id, login_time, screen_resolution, operating_system) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user['id'], $login_time, $screen_resolution, $operating_system]);

        // Online-Status setzen
        $stmt = $conPDO->prepare("UPDATE user SET online=1 WHERE mail=?");
        $stmt->execute([$user['mail']]);

        // 2FA-Check
        if ($user['google_secret'] == NULL) {
            // Secret erstellen, wenn noch keins da ist
            $secret = $gAuth->createSecret();

            $stmt = $conPDO->prepare("UPDATE user SET google_secret = ? WHERE mail = ?");
            $stmt->execute([$secret, $email]);

            // Weiter zum QR-Code Scan
            header("Location: ../qr2fa.php"); // Pfad evtl. anpassen, je nachdem wo qr2fa.php liegt
            exit;
        }

        // Code prüfen
        // Hinweis: Wenn das Feld leer ist, schlägt verifyCode fehl.
        // Falls 2FA beim ersten Login übersprungen werden soll, muss hier Logik rein.
        // Aktuell wird der Code geprüft, wenn ein Secret existiert.
        
        $checkResult = $gAuth->verifyCode($user['google_secret'], $code, 2);
        
        if ($checkResult) {
            // Login erfolgreich
            // Session wurde bereits oben gesetzt, keine zusätzliche user_id nötig
            
            // Weiterleitung zum Dashboard
            header("Location: ../../index.php");
            exit;
        } else {
            // 2FA Code falsch
            $_SESSION['login_error'] = "Der 2FA-Code ist ungültig oder abgelaufen.";
            header("Location: loginformular.php");
            exit;
        }

    } else {
        // Passwort falsch oder User nicht gefunden
        $_SESSION['login_error'] = "E-Mail oder Passwort ist falsch.";
        header("Location: loginformular.php");
        exit;
    }

} catch (Exception $e) {
    die("Datenbankfehler: " . $e->getMessage());
}
?>