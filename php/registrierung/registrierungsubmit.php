<?php
// HIER GEÄNDERT: Absolute Pfade über DOCUMENT_ROOT
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

    $password = str_shuffle($password);
    return $password;
}


try {
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
    $password = hash('sha512', $plainPassword);
    $stmt = $conPDO->prepare("INSERT INTO user (vorname, nachname, mail, adresse, plz, ort, passwort, google_secret) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$vorname, $nachname, $mail, $adresse, $plz, $ort, $password, NULL]);
    
    // E-Mail vorbereiten
    $mailer->addAddress($mail);
    $mailer->Subject = 'Willkommen bei Cockpit Corner';
    
    // Professioneller HTML E-Mail Body
    $body = '
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f7fa;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f7fa; padding: 40px 20px;">
            <tr>
                <td align="center">
                    <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden;">
                        
                        <!-- Header -->
                        <tr>
                            <td style="background: linear-gradient(135deg, #1a365d 0%, #2c5282 50%, #3182ce 100%); padding: 40px 30px; text-align: center;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center">
                                            <!-- Flugzeug Icon -->
                                            <div style="font-size: 50px; margin-bottom: 15px;">✈️</div>
                                            <h1 style="color: #ffffff; margin: 0; font-size: 32px; font-weight: 700; letter-spacing: 1px;">COCKPIT CORNER</h1>
                                            <p style="color: #bee3f8; margin: 10px 0 0 0; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">Ihr Aviation Shop</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!-- Welcome Message -->
                        <tr>
                            <td style="padding: 40px 40px 20px 40px; text-align: center;">
                                <div style="font-size: 40px; margin-bottom: 15px;">🎉</div>
                                <h2 style="color: #1a365d; margin: 0 0 10px 0; font-size: 26px; font-weight: 600;">Willkommen an Bord!</h2>
                                <p style="color: #4a5568; margin: 0; font-size: 16px; line-height: 1.6;">
                                    Hallo <strong>' . htmlspecialchars($vorname) . ' ' . htmlspecialchars($nachname) . '</strong>,<br>
                                    Sie haben sich erfolgreich bei Cockpit Corner registriert.
                                </p>
                            </td>
                        </tr>
                        
                        <!-- Anmeldedaten Box -->
                        <tr>
                            <td style="padding: 20px 40px;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: linear-gradient(135deg, #ebf8ff 0%, #e6fffa 100%); border-radius: 10px; border-left: 4px solid #3182ce;">
                                    <tr>
                                        <td style="padding: 25px 30px;">
                                            <h3 style="color: #1a365d; margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">
                                                🔐 Ihre Anmeldedaten
                                            </h3>
                                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td style="padding: 8px 0; color: #718096; font-size: 14px; width: 40%;">E-Mail Adresse:</td>
                                                    <td style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: 600;">' . htmlspecialchars($mail) . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 8px 0; color: #718096; font-size: 14px;">Vorläufiges Passwort:</td>
                                                    <td style="padding: 8px 0;">
                                                        <span style="background-color: #1a365d; color: #ffffff; padding: 6px 14px; border-radius: 6px; font-family: monospace; font-size: 15px; letter-spacing: 1px;">' . htmlspecialchars($plainPassword) . '</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!-- Lieferadresse Box -->
                        <tr>
                            <td style="padding: 20px 40px;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f7fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                                    <tr>
                                        <td style="padding: 25px 30px;">
                                            <h3 style="color: #1a365d; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                                                📦 Ihre Lieferadresse
                                            </h3>
                                            <p style="color: #4a5568; margin: 0; font-size: 15px; line-height: 1.8;">
                                                ' . htmlspecialchars($vorname) . ' ' . htmlspecialchars($nachname) . '<br>
                                                ' . htmlspecialchars($adresse) . '<br>
                                                ' . htmlspecialchars($plz) . ' ' . htmlspecialchars($ort) . '
                                            </p>
                                            <p style="color: #718096; margin: 15px 0 0 0; font-size: 13px; font-style: italic;">
                                                Sie können diese Adresse jederzeit in Ihrem Kundenkonto ändern.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!-- Wichtiger Hinweis -->
                        <tr>
                            <td style="padding: 20px 40px;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #fffaf0; border-radius: 10px; border: 1px solid #fbd38d;">
                                    <tr>
                                        <td style="padding: 20px 25px;">
                                            <p style="color: #744210; margin: 0; font-size: 14px; line-height: 1.6;">
                                                <strong>⚠️ Wichtig:</strong> Bitte verwenden Sie für das erste Login das vorläufige Passwort und folgen Sie den weiteren Schritten. Die Eingabe des 2FA-Codes ist bei der ersten Anmeldung nicht notwendig.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <!-- CTA Button -->
                        <tr>
                            <td style="padding: 30px 40px; text-align: center;">
                                <a href="https://ihre-domain.de/Webprojekt/php/login/loginformular.php" style="display: inline-block; background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 8px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 15px rgba(49, 130, 206, 0.4);">
                                    🚀 Jetzt einloggen
                                </a>
                            </td>
                        </tr>
                        
                        <!-- Divider -->
                        <tr>
                            <td style="padding: 0 40px;">
                                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 0;">
                            </td>
                        </tr>
                        
                        <!-- Footer -->
                        <tr>
                            <td style="padding: 30px 40px; text-align: center; background-color: #f7fafc;">
                                <p style="color: #1a365d; margin: 0 0 5px 0; font-size: 16px; font-weight: 600;">Happy Landings! ✈️</p>
                                <p style="color: #718096; margin: 0 0 20px 0; font-size: 14px;">Ihr CockpitCorner-Team</p>
                                <p style="color: #a0aec0; margin: 0; font-size: 12px;">
                                    Viel Spaß beim Shoppen!
                                </p>
                            </td>
                        </tr>
                        
                        <!-- Bottom Bar -->
                        <tr>
                            <td style="background-color: #1a365d; padding: 15px 40px; text-align: center;">
                                <p style="color: #a0aec0; margin: 0; font-size: 11px;">
                                    © ' . date('Y') . ' Cockpit Corner. Alle Rechte vorbehalten.
                                </p>
                            </td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    
    $mailer->Body = $body;
    $mailer->isHTML(true);

    // E-Mail senden
    $mailer->send();

    header("Location: /Webprojekt/php/login/loginformular.php");
} catch (Exception $e) {

    $_SESSION['form_data'] = $_POST;
    $errorInfo = isset($mailer) ? $mailer->ErrorInfo : $e->getMessage();
    $exceptionMessage = $e->getMessage();
    $_SESSION['mail_error'] = "Die E-Mail konnte nicht gesendet werden. Fehler: " . $errorInfo . " | Exception: " . $exceptionMessage;
    error_log('Mailer ErrorInfo: ' . $errorInfo . ' | Exception: ' . $exceptionMessage);
    echo "<script>console.log('Mailer Error: {$errorInfo} | Exception: {$exceptionMessage}');</script>";
    header("Location: registrierung.php");
    exit;
}
?>