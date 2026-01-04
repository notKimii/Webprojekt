<?php
// Überprüfung der Authentifizierung
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/loginpruef.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php';

// Nur POST-Anfragen erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Webprojekt/php/kundenkonto.php');
    exit;
}

// Benutzer-ID aus Session abrufen
$userID = $_SESSION['user']['id'];

// POST-Daten validieren und bereinigen
$vorname = trim($_POST['vorname'] ?? '');
$nachname = trim($_POST['nachname'] ?? '');
$mail = trim($_POST['mail'] ?? '');
$adresse = trim($_POST['adresse'] ?? '');
$plz = trim($_POST['plz'] ?? '');
$ort = trim($_POST['ort'] ?? '');

// Validierung
$errors = [];

if (empty($vorname)) {
    $errors[] = 'Vorname darf nicht leer sein.';
}

if (empty($nachname)) {
    $errors[] = 'Nachname darf nicht leer sein.';
}

if (empty($mail) || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Bitte gib eine gültige E-Mail-Adresse ein.';
}

if (empty($adresse)) {
    $errors[] = 'Adresse darf nicht leer sein.';
}

if (empty($plz) || !preg_match('/^\d{5}$/', $plz)) {
    $errors[] = 'Bitte gib eine gültige 5-stellige PLZ ein.';
}

if (empty($ort)) {
    $errors[] = 'Ort darf nicht leer sein.';
}

// Prüfen ob E-Mail bereits von einem anderen Benutzer verwendet wird
if (empty($errors)) {
    $sqlCheckMail = "SELECT id FROM user WHERE mail = ? AND id != ?";
    $stmtCheckMail = $conPDO->prepare($sqlCheckMail);
    $stmtCheckMail->execute([$mail, $userID]);
    
    if ($stmtCheckMail->fetch()) {
        $errors[] = 'Diese E-Mail-Adresse wird bereits von einem anderen Konto verwendet.';
    }
}

// Wenn Fehler vorhanden sind, zurück zum Formular mit Fehlermeldung
if (!empty($errors)) {
    $_SESSION['profile_errors'] = $errors;
    $_SESSION['profile_data'] = $_POST;
    header('Location: /Webprojekt/php/kundenkonto.php?error=validation');
    exit;
}

// Benutzerdaten aktualisieren
try {
    $sqlUpdate = "UPDATE user 
                  SET vorname = ?, nachname = ?, mail = ?, adresse = ?, plz = ?, ort = ? 
                  WHERE id = ?";
    $stmtUpdate = $conPDO->prepare($sqlUpdate);
    $result = $stmtUpdate->execute([$vorname, $nachname, $mail, $adresse, $plz, $ort, $userID]);
    
    if ($result) {
        // Session-Daten aktualisieren
        $_SESSION['user']['vorname'] = $vorname;
        $_SESSION['user']['nachname'] = $nachname;
        $_SESSION['user']['email'] = $mail;
        $_SESSION['user']['adresse'] = $adresse;
        $_SESSION['user']['plz'] = $plz;
        $_SESSION['user']['ort'] = $ort;
        
        // Erfolgsmeldung setzen
        $_SESSION['profile_success'] = 'Dein Profil wurde erfolgreich aktualisiert!';
        header('Location: /Webprojekt/php/kundenkonto.php?success=1');
        exit;
    } else {
        $_SESSION['profile_errors'] = ['Ein Fehler ist beim Speichern aufgetreten. Bitte versuche es erneut.'];
        header('Location: /Webprojekt/php/kundenkonto.php?error=database');
        exit;
    }
} catch (Exception $e) {
    error_log("Fehler beim Aktualisieren des Profils: " . $e->getMessage());
    $_SESSION['profile_errors'] = ['Ein unerwarteter Fehler ist aufgetreten. Bitte versuche es später erneut.'];
    header('Location: /Webprojekt/php/kundenkonto.php?error=exception');
    exit;
}
