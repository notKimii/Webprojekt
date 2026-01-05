<?php
// 1. Namespaces (müssen ganz oben stehen)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2. Fehleranzeige aktivieren (für Debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. PHPMailer über Composer laden (eine Ebene hoch zum vendor-Ordner)
require_once __DIR__ . '/../vendor/autoload.php';

// 4. Datenbank-Verbindung einbinden
require_once __DIR__ . '/../db_config.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $mysqli->real_escape_string($_POST['email']);

    // Prüfen, ob die E-Mail existiert
    $stmt = $mysqli->prepare("SELECT id FROM user WHERE mail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token & Ablauf generieren
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // In Datenbank speichern
        $update = $mysqli->prepare("UPDATE user SET reset_token = ?, reset_expiry = ? WHERE mail = ?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        // 5. E-Mail Versand - IDENTISCH zu billingmail.php
        $mail = new PHPMailer(true);

        try {
            // SMTP-Konfiguration (exakt wie in billingmail.php)
            $mail->isSMTP();
            $mail->Host       = 'smtp.mailbox.org';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info.cockpitcorner@mailbox.org';
            $mail->Password   = 'Mailbox.123';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Absender (wie in billingmail.php)
            $mail->setFrom('info.cockpitcorner@mailbox.org', 'Cockpit Corner');
            $mail->addReplyTo('info.cockpitcorner@mailbox.org', 'Cockpit Corner Support');
            $mail->addAddress($email); 

            // Inhalt
            $mail->isHTML(true);
            $mail->Subject = 'Passwort zurücksetzen - Cockpit Corner';
            
            // WICHTIG: URL ohne Sonderzeichen! Datei liegt im gleichen Ordner
            $reset_link = "http://localhost/Webprojekt/php/passwortzuruecksetzen.php?token=" . $token;
            
            $mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="margin: 0; padding: 0; background-color: #f7fafc; font-family: Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f7fafc">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="max-width: 600px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#1a365d" style="padding: 30px 40px; background-color: #1a365d;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Cockpit Corner</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1a365d; margin: 0 0 20px 0;">Passwort vergessen?</h2>
                            <p style="color: #4a5568; font-size: 16px; line-height: 24px; margin: 0 0 30px 0;">
                                Kein Problem! Klicke auf den Button unten, um ein neues Passwort festzulegen.
                            </p>
                            
                            <!-- Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" bgcolor="#3182ce" style="border-radius: 6px;">
                                        <a href="' . $reset_link . '" target="_blank" style="display: inline-block; padding: 16px 32px; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold;">
                                            Passwort zurücksetzen
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="color: #718096; font-size: 14px; line-height: 22px; margin: 30px 0 0 0;">
                                Der Link ist <strong>1 Stunde</strong> gültig.<br>
                                Falls du diese E-Mail nicht angefordert hast, kannst du sie ignorieren.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#f7fafc" style="padding: 20px 40px; background-color: #f7fafc;">
                            <p style="color: #a0aec0; font-size: 12px; margin: 0;">
                                &copy; ' . date('Y') . ' Cockpit Corner. Alle Rechte vorbehalten.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';

            // Plain-Text Alternative
            $mail->AltBody = 'Passwort vergessen?

Kein Problem! Öffne folgenden Link, um ein neues Passwort festzulegen:

' . $reset_link . '

Der Link ist 1 Stunde gültig.

Falls du diese E-Mail nicht angefordert hast, kannst du sie ignorieren.

--
Cockpit Corner';

            $mail->send();
            $message = "<div class='greeting' style='border-color: #10b981;'><span class='greeting-text' style='color: #10b981;'>✓ Eine E-Mail wurde an " . htmlspecialchars($email) . " versandt.</span></div>";
            
        } catch (Exception $e) {
            $message = "<div class='greeting' style='border-color: #ef4444;'><span class='greeting-text' style='color: #ef4444;'>✗ Versandfehler: " . htmlspecialchars($mail->ErrorInfo) . "</span></div>";
            error_log('Mail-Versand fehlgeschlagen: ' . $mail->ErrorInfo);
        }
    } else {
        // Aus Sicherheitsgründen gleiche Meldung wie bei Erfolg
        $message = "<div class='greeting' style='border-color: #10b981;'><span class='greeting-text' style='color: #10b981;'>✓ Falls ein Account existiert, wurde eine E-Mail versandt.</span></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <title>Passwort vergessen - Cockpit Corner</title>
</head>
<body>

    <header class="hero-section">
        <div class="hero-banner">
            <div class="hero-content">
                <span class="greeting-text">Sicherheit</span>
                <h1>Passwort <span class="hero-highlight">vergessen?</span></h1>
                <p>Gib deine E-Mail-Adresse ein, um einen Reset-Link zu erhalten.</p>
                
                <?php echo $message; ?>

                <form method="POST" style="width: 100%; max-width: 400px; margin-top: 2rem;">
                    <input type="email" name="email" placeholder="Deine E-Mail Adresse" required 
                           style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); color: white; margin-bottom: 1rem;">
                    <button type="submit" class="cta-button-primary" style="width: 100%;">Link anfordern</button>
                </form>
                
                <p style="margin-top: 2rem;">
                    <a href="login/loginformular.php" style="color: #90cdf4;">← Zurück zum Login</a>
                </p>
            </div>
        </div>
    </header>

</body>
</html>