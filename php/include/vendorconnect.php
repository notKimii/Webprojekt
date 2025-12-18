<?php
// 1. Wir laden den allgemeinen Autoloader (wichtig für PHPMailer etc.)
require_once $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/vendor/autoload.php';

// 2. Wir laden den Google Authenticator ZUSÄTZLICH manuell
// Damit zwingen wir PHP, die Datei zu finden, die wir auf deinem Bild im vendor-Ordner sehen.
require_once $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/vendor/PHPGangsta/GoogleAuthenticator.php';
?>