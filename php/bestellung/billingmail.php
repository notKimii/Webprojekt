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
    $kundenName = $kundenData['vorname'] . ' ' . $kundenData['nachname'];
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
    $mail = new PHPMailer(true); // Exception-Modus aktiviert
    
    // SMTP-Konfiguration (aus mailversand.php übernommen)
    $mail->isSMTP();
    $mail->Host = 'smtp.mailbox.org';
    $mail->SMTPAuth = true;
    $mail->Username = 'info.cockpitcorner@mailbox.org';
    $mail->Password = 'Mailbox.123';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    
    // Absender
    $mail->setFrom('info.cockpitcorner@mailbox.org', 'Pilotenshop - Cockpit Corner');
    $mail->addReplyTo('info.cockpitcorner@mailbox.org', 'Pilotenshop Support');
    
    // Empfänger
    $mail->addAddress($kundenEmail, $kundenName);
    
    // Betreff und Nachricht
    $mail->Subject = 'Ihre Rechnung ' . $rechnungsNr . ' - Pilotenshop';
    
    $mail->isHTML(true);
    
    // E-Mail-Text basierend auf Zahlungsstatus
    if ($istBezahlt) {
        $zahlungsText = '<p style="background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50;">✓ <strong>Diese Rechnung wurde bereits bezahlt.</strong><br>Vielen Dank für Ihre Zahlung!</p>';
        $zahlungsTextPlain = 'Diese Rechnung wurde bereits bezahlt. Vielen Dank für Ihre Zahlung!';
    } else {
        $zahlungsText = '<p style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;">Bitte überweisen Sie den Rechnungsbetrag innerhalb von 14 Tagen auf das in der Rechnung angegebene Konto.</p>';
        $zahlungsTextPlain = 'Bitte überweisen Sie den Rechnungsbetrag innerhalb von 14 Tagen auf das in der Rechnung angegebene Konto.';
    }
    
    $mail->Body = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
            .header { background: #003366; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .footer { background: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Vielen Dank für Ihre Bestellung!</h1>
        </div>
        <div class="content">
            <p>Sehr geehrte/r ' . htmlspecialchars($kundenName) . ',</p>
            
            <p>vielen Dank für Ihre Bestellung bei Pilotenshop. Im Anhang finden Sie Ihre Rechnung <strong>' . $rechnungsNr . '</strong> zur Bestellnummer <strong>' . $bestellungsNr . '</strong>.</p>
            
            ' . $zahlungsText . '
            
            <p>Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
            
            <p>Mit freundlichen Grüßen<br>
            Ihr Pilotenshop-Team</p>
        </div>
        <div class="footer">
            <p>Pilotenshop GmbH | Musterstraße 123 | 12345 Musterstadt<br>
            Tel: +49 123 456789 | E-Mail: info@pilotenshop.de</p>
        </div>
    </body>
    </html>
    ';
    
    $mail->AltBody = 'Sehr geehrte/r ' . $kundenName . ',

vielen Dank für Ihre Bestellung bei Pilotenshop. Im Anhang finden Sie Ihre Rechnung ' . $rechnungsNr . ' zur Bestellnummer ' . $bestellungsNr . '.

' . $zahlungsTextPlain . '

Bei Fragen stehen wir Ihnen gerne zur Verfügung.

Mit freundlichen Grüßen
Ihr Pilotenshop-Team';
    
    // PDF anhängen
    $mail->addStringAttachment($pdfContent, $pdfFilename, 'base64', 'application/pdf');
    
    // E-Mail senden (nur wenn SMTP konfiguriert ist)
    if ($mail->send()) {
        error_log('Rechnung ' . $rechnungsNr . ' erfolgreich an ' . $kundenEmail . ' versendet');
    } else {
        error_log('Mail-Versand fehlgeschlagen: ' . $mail->ErrorInfo . ' - PDF wurde trotzdem erstellt');
    }
    
    return true;
    
} catch (Exception $e) {
    // Fehlerbehandlung
    error_log('Fehler beim Erstellen/Versenden der Rechnung: ' . $e->getMessage());
    error_log('Stack Trace: ' . $e->getTraceAsString());
    
    throw $e; // Fehler weitergeben für besseres Debugging
}
