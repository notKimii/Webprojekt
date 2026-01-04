<?php
// PDF-Rechnung erstellen und per E-Mail versenden
// Wird von dank.php aufgerufen

if (!isset($bestellungId)) {
    throw new Exception('Keine Bestellungs-ID übergeben');
}

require_once __DIR__ . '/../../vendor/autoload.php';
// $con ist bereits in dank.php geladen

use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    // Rechnungs-ID aus bestellungId ermitteln
    $stmt = $con->prepare("SELECT id FROM rechnungskopf WHERE bestellID = ? LIMIT 1");
    $stmt->bind_param('i', $bestellungId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Rechnung nicht gefunden für Bestellung ' . $bestellungId);
    }
    
    $rechnungRow = $result->fetch_assoc();
    $rechnungId = (int)$rechnungRow['id'];
    $stmt->close();
    
    // Kundendaten und Bestellstatus laden
    $stmt2 = $con->prepare("
        SELECT u.mail, u.vorname, u.nachname, bk.status
        FROM bestellkopf bk
        JOIN user u ON bk.user_id = u.id
        WHERE bk.id = ?
    ");
    $stmt2->bind_param('i', $bestellungId);
    $stmt2->execute();
    $kundenData = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();
    
    if (!$kundenData) {
        throw new Exception('Kundendaten nicht gefunden');
    }
    
    $kundenEmail = $kundenData['mail'];
    $kundenVorname = $kundenData['vorname'];
    $kundenNachname = $kundenData['nachname'];
    $kundenName = $kundenVorname . ' ' . $kundenNachname;
    $istBezahlt = ($kundenData['status'] === 'bezahlt');
    $bestellungsNr = str_pad($bestellungId, 6, '0', STR_PAD_LEFT);
    
    // HTML-Rechnung generieren
    ob_start();
    include 'bill.php';
    $html = ob_get_clean();
    
    // PDF erstellen mit mPDF
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 10,
        'margin_bottom' => 15,
        'margin_header' => 5,
        'margin_footer' => 5,
        'tempDir' => __DIR__ . '/../../vendor/mpdf/mpdf/tmp',
        'default_font_size' => 9,
        'default_font' => 'dejavusans'
    ]);
    
    $mpdf->WriteHTML($html);
    
    // PDF als String speichern
    $pdfContent = $mpdf->Output('', 'S');
    $rechnungsNr = str_pad($rechnungId, 6, '0', STR_PAD_LEFT);
    $pdfFilename = 'Rechnung_' . $rechnungsNr . '.pdf';
    
    // E-Mail mit PHPMailer versenden
    $mail = new PHPMailer(true);
    
    // SMTP-Konfiguration
    $mail->isSMTP();
    $mail->Host = 'smtp.mailbox.org';
    $mail->SMTPAuth = true;
    $mail->Username = 'info.cockpitcorner@mailbox.org';
    $mail->Password = 'Mailbox.123';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    // Absender
    $mail->setFrom('info.cockpitcorner@mailbox.org', 'Cockpit Corner');
    $mail->addReplyTo('info.cockpitcorner@mailbox.org', 'Cockpit Corner Support');
    
    // Empfänger
    $mail->addAddress($kundenEmail, $kundenName);
    
    // Betreff
    $mail->Subject = 'Ihre Rechnung ' . $rechnungsNr . ' - Cockpit Corner';
    
    $mail->isHTML(true);
    
    // Zahlungsstatus-Box HTML (Outlook-kompatibel)
    if ($istBezahlt) {
        $zahlungsBox = '
                    <!-- Bezahlt Box -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #e6fffa;" bgcolor="#e6fffa">
                                <tr>
                                    <td style="padding: 20px 25px; border: 1px solid #38a169;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #276749; font-size: 15px; font-family: Arial, sans-serif; line-height: 22px;">
                                                    <strong>&#10003; Diese Rechnung wurde bereits bezahlt.</strong><br />
                                                    Vielen Dank f&uuml;r Ihre Zahlung!
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>';
        $zahlungsTextPlain = 'Diese Rechnung wurde bereits bezahlt. Vielen Dank für Ihre Zahlung!';
    } else {
        $zahlungsBox = '
                    <!-- Offene Zahlung Box -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fffaf0;" bgcolor="#fffaf0">
                                <tr>
                                    <td style="padding: 20px 25px; border: 1px solid #fbd38d;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #744210; font-size: 14px; font-family: Arial, sans-serif; line-height: 22px;">
                                                    <strong>&#9888; Zahlungshinweis:</strong> Bitte &uuml;berweisen Sie den Rechnungsbetrag innerhalb von 14 Tagen auf das in der Rechnung angegebene Konto.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>';
        $zahlungsTextPlain = 'Bitte überweisen Sie den Rechnungsbetrag innerhalb von 14 Tagen auf das in der Rechnung angegebene Konto.';
    }
    
    // Outlook-kompatibles HTML E-Mail Template
    $mail->Body = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Ihre Rechnung - Cockpit Corner</title>
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
                                        &#128230;
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #1a365d; font-size: 24px; font-weight: bold; font-family: Arial, sans-serif; padding-bottom: 10px;">
                                        Vielen Dank f&uuml;r Ihre Bestellung!
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #4a5568; font-size: 16px; font-family: Arial, sans-serif; line-height: 24px;">
                                        Hallo <strong>' . htmlspecialchars($kundenVorname) . ' ' . htmlspecialchars($kundenNachname) . '</strong>,<br />
                                        vielen Dank f&uuml;r Ihre Bestellung bei Cockpit Corner.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Rechnungsdetails Box -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <!--[if mso]>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                            <td width="4" bgcolor="#3182ce"></td>
                            <td bgcolor="#e6fffa" style="padding: 25px 30px;">
                            <![endif]-->
                            <!--[if !mso]><!-->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #ebf8ff; border-left: 4px solid #3182ce;">
                                <tr>
                                    <td style="padding: 25px 30px;">
                            <!--<![endif]-->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #1a365d; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; padding-bottom: 20px;">
                                                    &#128196; Ihre Rechnungsdetails
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td width="40%" valign="top" style="padding: 8px 0; color: #718096; font-size: 14px; font-family: Arial, sans-serif;">
                                                                Rechnungsnummer:
                                                            </td>
                                                            <td width="60%" valign="top" style="padding: 8px 0;">
                                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                                    <tr>
                                                                        <td style="background-color: #1a365d; color: #ffffff; padding: 6px 14px; font-family: Courier New, monospace; font-size: 14px; letter-spacing: 1px;">
                                                                            ' . $rechnungsNr . '
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%" valign="top" style="padding: 8px 0; color: #718096; font-size: 14px; font-family: Arial, sans-serif;">
                                                                Bestellnummer:
                                                            </td>
                                                            <td width="60%" valign="top" style="padding: 8px 0;">
                                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                                    <tr>
                                                                        <td style="background-color: #4a5568; color: #ffffff; padding: 6px 14px; font-family: Courier New, monospace; font-size: 14px; letter-spacing: 1px;">
                                                                            ' . $bestellungsNr . '
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
                    
                    ' . $zahlungsBox . '
                    
                    <!-- Anhang Info -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f7fafc;" bgcolor="#f7fafc">
                                <tr>
                                    <td style="padding: 25px 30px; border: 1px solid #e2e8f0;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #1a365d; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; padding-bottom: 15px;">
                                                    &#128206; Anhang
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #4a5568; font-size: 15px; font-family: Arial, sans-serif; line-height: 26px;">
                                                    Im Anhang dieser E-Mail finden Sie Ihre Rechnung als PDF-Datei:<br />
                                                    <strong>' . htmlspecialchars($pdfFilename) . '</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Kontakt Hinweis -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #ffffff;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="color: #4a5568; font-size: 15px; font-family: Arial, sans-serif; line-height: 24px; text-align: center;">
                                        Bei Fragen zu Ihrer Bestellung stehen wir Ihnen gerne zur Verf&uuml;gung.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- CTA Button - Bulletproof für Outlook -->
                    <tr>
                        <td align="center" style="padding: 20px 40px 30px 40px; background-color: #ffffff;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" bgcolor="#3182ce" style="background-color: #3182ce;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://ihre-domain.de/Webprojekt/php/login/loginformular.php" style="height:50px;v-text-anchor:middle;width:260px;" arcsize="8%" strokecolor="#2c5282" fillcolor="#3182ce">
                                        <w:anchorlock/>
                                        <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:16px;font-weight:bold;">
                                        Zum Kundenkonto
                                        </center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="https://ihre-domain.de/Webprojekt/php/login/loginformular.php" target="_blank" style="display: inline-block; background-color: #3182ce; color: #ffffff; text-decoration: none; padding: 16px 40px; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif; border-radius: 6px;">
                                            &#128100; Zum Kundenkonto
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
                                        Vielen Dank f&uuml;r Ihr Vertrauen!
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Bottom Bar -->
                    <tr>
                        <td align="center" bgcolor="#1a365d" style="padding: 20px 40px; background-color: #1a365d;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="color: #bee3f8; font-size: 12px; font-family: Arial, sans-serif; line-height: 20px;">
                                        Cockpit Corner GmbH | Musterstra&szlig;e 123 | 12345 Musterstadt
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #a0aec0; font-size: 12px; font-family: Arial, sans-serif; line-height: 20px; padding-top: 5px;">
                                        Tel: +49 123 456789 | E-Mail: info@cockpitcorner.de
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #718096; font-size: 11px; font-family: Arial, sans-serif; padding-top: 15px;">
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
    
    // Plain-Text Alternative
    $mail->AltBody = 'Vielen Dank für Ihre Bestellung bei Cockpit Corner!

Hallo ' . $kundenName . ',

vielen Dank für Ihre Bestellung bei Cockpit Corner.

IHRE RECHNUNGSDETAILS
---------------------
Rechnungsnummer: ' . $rechnungsNr . '
Bestellnummer: ' . $bestellungsNr . '

' . $zahlungsTextPlain . '

Im Anhang dieser E-Mail finden Sie Ihre Rechnung als PDF-Datei.

Bei Fragen zu Ihrer Bestellung stehen wir Ihnen gerne zur Verfügung.

Happy Landings!
Ihr CockpitCorner-Team

--
Cockpit Corner GmbH
Musterstraße 123 | 12345 Musterstadt
Tel: +49 123 456789 | E-Mail: info@cockpitcorner.de';
    
    // PDF anhängen
    $mail->addStringAttachment($pdfContent, $pdfFilename, 'base64', 'application/pdf');
    
    // E-Mail senden
    if ($mail->send()) {
        error_log('Rechnung ' . $rechnungsNr . ' erfolgreich an ' . $kundenEmail . ' versendet');
    } else {
        error_log('Mail-Versand fehlgeschlagen: ' . $mail->ErrorInfo . ' - PDF wurde trotzdem erstellt');
    }
    
    return true;
    
} catch (Exception $e) {
    error_log('Fehler beim Erstellen/Versenden der Rechnung: ' . $e->getMessage());
    error_log('Stack Trace: ' . $e->getTraceAsString());
    
    throw $e;
}