<?php
// HIER GEÄNDERT: Absolute Pfade über DOCUMENT_ROOT
// Das verweist direkt auf C:/xampp/htdocs/Webprojekt/php/include/...
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/debug.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/vendorconnect.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/mailversand.php';

session_start();

// Formulardaten
$vorname = $_POST["vorname"];
$nachname = $_POST["nachname"];
$mail = $_POST["mail"];
$adresse = $_POST["adresse"];
$plz = $_POST["plz"];
$ort = $_POST["ort"];

//Passwort generieren
function generatePassword($length = 10)
{
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $allChars = $lower . $upper . $numbers;

    $password = '';
    $password .= $lower[random_int(0, strlen($lower) - 1)];
    $password .= $upper[random_int(0, strlen($upper) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];

    for ($i = strlen($password); $i < $length; $i++) {
        $password .= $allChars[random_int(0, strlen($allChars) - 1)];
    }

    // Passwort mischen
    $password = str_shuffle($password);

    return $password;
}


try {
    // Prüfung auf doppelten Eintrag
    // HIER GEÄNDERT: Auch hier der absolute Pfad für die Datenbankverbindung
    include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php';
    
    $stmt = $conPDO->prepare("SELECT COUNT(*) FROM user WHERE mail = ?");
    $stmt->execute([$mail]);
    $anzahl = $stmt->fetchColumn();

    if ($anzahl > 0) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['mail_error'] = "Diese E-Mail-Adresse ist bereits registriert.";
        header("Location: registrierung.php");
        exit;
    }

    $plainPassword = generatePassword(10);
    // SHA512
    $password = hash('sha512', $plainPassword);
    $stmt = $conPDO->prepare("INSERT INTO user (vorname, nachname, mail, adresse, plz, ort, passwort, google_secret) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$vorname, $nachname, $mail, $adresse, $plz, $ort, $password, NULL]);
    
    // E-Mail vorbereiten
    $mailer->addAddress($mail);
    $mailer->Subject = 'Willkommen bei Cockpit Corner';
    $body = "<p>Herzlich Willkommen $vorname $nachname! 
            <br><br>
            Sie haben sich erfolgreich bei <i>'Cockpit Corner' </i>registriert.
            <br><br><br> 
            Ihre <strong>Anmeldedaten</strong> lauten wie folgt: <br>
                Vorname: $vorname <br>
                Nachname: $nachname <br>
                E-Mail Adresse: $mail <br>
                Vorl&auml;ufiges Passwort: $plainPassword <br><br>
            Als <strong>Lieferadresse </strong> haben sie folgende Adresse angeben: <br> $adresse <br> $plz $ort
            <br>
            Sie k&ouml;nnen diese Adresse auf unserer Webseite unter der Katergorie Kundenkonto ab&auml;ndern! 

            Bitte verwenden Sie für das erste Login das Vorl&auml;ufiges Passwort und folgen Sie den weiteren Schritte. <br>
            Die Eingabe des 2FA-Codes ist bei der ersten Anmeldung nicht notwendig.<br>
            <br>Viel Spa&szlig; beim Shoppen!<br><br>Happy Landings<br>Ihr CockpitCorner-Team</p>";
    $mailer->Body = $body;

    // E-Mail senden
    $mailer->send();


    header("Location: /Webprojekt/php/login/loginformular.php");
} catch (Exception $e) {

    $_SESSION['form_data'] = $_POST;
    // Kleine Sicherheit: Prüfen ob $mailer überhaupt existiert, falls der include oben schief ging
    $errorInfo = isset($mailer) ? $mailer->ErrorInfo : $e->getMessage();
    
        // Mehr Details: ErrorInfo von PHPMailer und Exception-Message
        $exceptionMessage = $e->getMessage();
        $_SESSION['mail_error'] = "Die E-Mail konnte nicht gesendet werden. Fehler: " . $errorInfo . " | Exception: " . $exceptionMessage;
        error_log('Mailer ErrorInfo: ' . $errorInfo . ' | Exception: ' . $exceptionMessage);
        echo "<script>console.log('Mailer Error: {$errorInfo} | Exception: {$exceptionMessage}');</script>";
    header("Location: registrierung.php");
    exit;
}
?>