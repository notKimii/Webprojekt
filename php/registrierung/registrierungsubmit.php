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
    
    // Outlook-kompatibles HTML E-Mail Template
    $body = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Willkommen bei Cockpit Corner</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <style type="text/css">
        table { border-collapse: collapse; }
        td { font-family: Arial, sans-serif; }
        a { text-decoration: none; }
    </style>
    <![endif]-->
    <style type="text/css">
        body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: Arial, Helvetica, sans-serif;">
    
    <!-- Wrapper Table -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f7fa;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Main Container -->
                <!--[if mso]>
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="width: 600px;">
                <tr>
                <td>
                <![endif]-->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff;">
                    
                    <!-- HEADER -->
                    <tr>
                        <td align="center" style="background-color: #1a365d; padding: 40px 30px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="font-size: 40px; line-height: 1; padding-bottom: 15px; color: #ffffff;">
                                        &#9992;
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #ffffff; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif; letter-spacing: 1px;">
                                        COCKPIT CORNER
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #bee3f8; font-size: 12px; font-family: Arial, sans-serif; letter-spacing: 2px; text-transform: uppercase; padding-top: 10px;">
                                        Ihr Aviation Shop
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Welcome Message -->
                    <tr>
                        <td align="center" style="padding: 40px 40px 20px 40px; background-color: #ffffff;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="font-size: 36px; line-height: 1; padding-bottom: 15px;">
                                        &#127881;
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #1a365d; font-size: 24px; font-weight: bold; font-family: Arial, sans-serif; padding-bottom: 10px;">
                                        Willkommen an Bord!
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #4a5568; font-size: 16px; font-family: Arial, sans-serif; line-height: 24px;">
                                        Hallo <strong>' . htmlspecialchars($vorname) . ' ' . htmlspecialchars($nachname) . '</strong>,<br />
                                        Sie haben sich erfolgreich bei Cockpit Corner registriert.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Anmeldedaten Box -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <!--[if mso]>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                            <td width="4" bgcolor="#3182ce"></td>
                            <td bgcolor="#e6fffa" style="padding: 25px 30px;">
                            <![endif]-->
                            <!--[if !mso]><!-->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #e6fffa; border-left: 4px solid #3182ce;">
                                <tr>
                                    <td style="padding: 25px 30px;">
                            <!--<![endif]-->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #1a365d; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; padding-bottom: 20px;">
                                                    &#128274; Ihre Anmeldedaten
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td width="40%" valign="top" style="padding: 8px 0; color: #718096; font-size: 14px; font-family: Arial, sans-serif;">
                                                                E-Mail Adresse:
                                                            </td>
                                                            <td width="60%" valign="top" style="padding: 8px 0; color: #2d3748; font-size: 14px; font-weight: bold; font-family: Arial, sans-serif;">
                                                                ' . htmlspecialchars($mail) . '
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%" valign="middle" style="padding: 8px 0; color: #718096; font-size: 14px; font-family: Arial, sans-serif;">
                                                                Vorl&auml;ufiges Passwort:
                                                            </td>
                                                            <td width="60%" valign="middle" style="padding: 8px 0;">
                                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                                    <tr>
                                                                        <td style="background-color: #1a365d; color: #ffffff; padding: 6px 14px; font-family: Courier New, monospace; font-size: 14px; letter-spacing: 1px;">
                                                                            ' . htmlspecialchars($plainPassword) . '
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                            <!--[if !mso]><!-->
                                    </td>
                                </tr>
                            </table>
                            <!--<![endif]-->
                            <!--[if mso]>
                            </td>
                            </tr>
                            </table>
                            <![endif]-->
                        </td>
                    </tr>
                    
                    <!-- Lieferadresse Box -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f7fafc;" bgcolor="#f7fafc">
                                <!--[if mso]>
                                <tr>
                                <td style="border: 1px solid #e2e8f0; padding: 25px 30px;">
                                <![endif]-->
                                <!--[if !mso]><!-->
                                <tr>
                                    <td style="padding: 25px 30px; border: 1px solid #e2e8f0;">
                                <!--<![endif]-->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #1a365d; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; padding-bottom: 15px;">
                                                    &#128230; Ihre Lieferadresse
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #4a5568; font-size: 15px; font-family: Arial, sans-serif; line-height: 26px;">
                                                    ' . htmlspecialchars($vorname) . ' ' . htmlspecialchars($nachname) . '<br />
                                                    ' . htmlspecialchars($adresse) . '<br />
                                                    ' . htmlspecialchars($plz) . ' ' . htmlspecialchars($ort) . '
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #718096; font-size: 13px; font-family: Arial, sans-serif; font-style: italic; padding-top: 15px;">
                                                    Sie k&ouml;nnen diese Adresse jederzeit in Ihrem Kundenkonto &auml;ndern.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Wichtiger Hinweis -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fffaf0;" bgcolor="#fffaf0">
                                <tr>
                                    <td style="padding: 20px 25px; border: 1px solid #fbd38d;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #744210; font-size: 14px; font-family: Arial, sans-serif; line-height: 22px;">
                                                    <strong>&#9888; Wichtig:</strong> Bitte verwenden Sie f&uuml;r das erste Login das vorl&auml;ufige Passwort und folgen Sie den weiteren Schritten. Die Eingabe des 2FA-Codes ist bei der ersten Anmeldung nicht notwendig.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- CTA Button - Bulletproof für Outlook -->
                    <tr>
                        <td align="center" style="padding: 30px 40px; background-color: #ffffff;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" bgcolor="#3182ce" style="background-color: #3182ce;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://ihre-domain.de/Webprojekt/php/login/loginformular.php" style="height:50px;v-text-anchor:middle;width:220px;" arcsize="8%" strokecolor="#2c5282" fillcolor="#3182ce">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:16px;font-weight:bold;">
                                        Jetzt einloggen
                                        </center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="https://ihre-domain.de/Webprojekt/php/login/loginformular.php" target="_blank" style="display: inline-block; background-color: #3182ce; color: #ffffff; text-decoration: none; padding: 16px 40px; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; border-radius: 6px;">
                                            &#9992; Jetzt einloggen
                                        </a>
                                        <!--<![endif]-->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="border-top: 1px solid #e2e8f0; font-size: 1px; line-height: 1px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#f7fafc" style="padding: 30px 40px; background-color: #f7fafc;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="color: #1a365d; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; padding-bottom: 5px;">
                                        Happy Landings! &#9992;
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #718096; font-size: 14px; font-family: Arial, sans-serif; padding-bottom: 20px;">
                                        Ihr CockpitCorner-Team
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #a0aec0; font-size: 12px; font-family: Arial, sans-serif;">
                                        Viel Spa&szlig; beim Shoppen!
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Bottom Bar -->
                    <tr>
                        <td align="center" bgcolor="#1a365d" style="padding: 15px 40px; background-color: #1a365d;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="color: #a0aec0; font-size: 11px; font-family: Arial, sans-serif;">
                                        &copy; ' . date('Y') . ' Cockpit Corner. Alle Rechte vorbehalten.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
                <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->
                
            </td>
        </tr>
    </table>
    
</body>
</html>';
    
    $mailer->Body = $body;
    $mailer->isHTML(true);
    
    // Plain-Text Alternative für E-Mail-Clients ohne HTML
    $mailer->AltBody = "Willkommen bei Cockpit Corner!\n\n" .
        "Hallo $vorname $nachname,\n" .
        "Sie haben sich erfolgreich registriert.\n\n" .
        "Ihre Anmeldedaten:\n" .
        "E-Mail: $mail\n" .
        "Vorläufiges Passwort: $plainPassword\n\n" .
        "Ihre Lieferadresse:\n" .
        "$adresse\n$plz $ort\n\n" .
        "Wichtig: Bitte verwenden Sie für das erste Login das vorläufige Passwort.\n" .
        "Die 2FA-Eingabe ist bei der ersten Anmeldung nicht notwendig.\n\n" .
        "Happy Landings!\n" .
        "Ihr CockpitCorner-Team";

    // E-Mail senden
    $mailer->send();

    header("Location: /Webprojekt/php/login/FirstLogin.php");
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