<?php
// Diese Datei generiert die HTML-Rechnung
// Wird von billingmail.php aufgerufen

if (!isset($rechnungId)) {
    die('Fehler: Keine Rechnungs-ID übergeben');
}

include "../include/connectcon.php";

// Rechnungskopf laden
$stmt = $con->prepare("
    SELECT rk.*, bk.bestelldatum, bk.status, u.vorname, u.nachname, u.mail, u.adresse, u.plz, u.ort
    FROM rechnungskopf rk
    JOIN bestellkopf bk ON rk.bestellID = bk.id
    JOIN user u ON bk.user_id = u.id
    WHERE rk.id = ?
");
$stmt->bind_param('i', $rechnungId);
$stmt->execute();
$rechnung = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$rechnung) {
    die('Fehler: Rechnung nicht gefunden');
}

$istBezahlt = ($rechnung['status'] === 'bezahlt');

// Versandart und Kosten ermitteln
$versandarten = [
    1 => ['name' => 'LPD', 'kosten' => 11.90],
    2 => ['name' => 'DHL', 'kosten' => 6.90],
    3 => ['name' => 'DHL Express', 'kosten' => 16.90]
];

$versandartId = isset($rechnung['versandart']) ? (int)$rechnung['versandart'] : 2;
$versandart = isset($versandarten[$versandartId]) ? $versandarten[$versandartId] : $versandarten[2];
$versandkosten = $versandart['kosten'];
$versandname = $versandart['name'];

// Rechnungspositionen laden
$stmt2 = $con->prepare("
    SELECT * FROM rechnungsposition 
    WHERE rechnungsID = ?
    ORDER BY artikel_id
");
$stmt2->bind_param('i', $rechnungId);
$stmt2->execute();
$positionen = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();

// Beträge berechnen
// WICHTIG: Artikelpreise enthalten bereits MwSt (brutto)
$bruttoArtikel = 0;
foreach ($positionen as &$pos) {
    $pos['gesamt'] = $pos['menge'] * $pos['preis']; // Brutto-Gesamtpreis
    $bruttoArtikel += $pos['gesamt'];
}
unset($pos); // Referenz aufheben! Wichtig für die nächste foreach-Schleife

// Artikel: Brutto -> Netto
$nettoArtikel = $bruttoArtikel / 1.19;
$mwstArtikel = $bruttoArtikel - $nettoArtikel;

// Versandkosten enthalten bereits MwSt (brutto)
$versandNettoAnteil = $versandkosten / 1.19;
$versandMwstAnteil = $versandkosten - $versandNettoAnteil;

// Gesamt
$gesamtNetto = $nettoArtikel + $versandNettoAnteil;
$gesamtMwst = $mwstArtikel + $versandMwstAnteil;
$gesamtBrutto = $bruttoArtikel + $versandkosten;

// Für Anzeige
$subtotalNetto = $nettoArtikel;
$mwst = $gesamtMwst;
$brutto = $gesamtBrutto;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rechnung <?php echo $rechnungId; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 15px;
            color: #333;
        }
        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #003366;
            padding-bottom: 20px;
            overflow: hidden;
        }
        .logo {
            float: left;
            width: 50%;
        }
        .logo img {
            max-width: 200px;
            height: auto;
            display: block;
        }
        .company-info {
            float: right;
            width: 50%;
            text-align: right;
            font-size: 9pt;
            color: #666;
            line-height: 1.4;
            padding-top: 70px;
        }
        .invoice-details {
            margin: 30px 0;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 3px 0;
            vertical-align: top;
        }
        .invoice-details .label {
            font-weight: bold;
            width: 150px;
        }
        .customer-address {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border-left: 3px solid #003366;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 8pt;
        }
        .items-table th {
            background: #003366;
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 8pt;
        }
        .items-table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }
        .items-table .number {
            text-align: right;
        }
        .items-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .totals {
            margin-top: 10px;
            float: right;
            width: 40%;
        }
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals td {
            padding: 5px;
            text-align: right;
            font-size: 9pt;
        }
        .totals .label {
            text-align: left;
            font-weight: normal;
        }
        .totals .total-row {
            border-top: 2px solid #003366;
            font-weight: bold;
            font-size: 10pt;
            background: #f0f0f0;
        }
        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        .payment-info {
            margin: 15px 0;
            padding: 10px;
            background: #fffef0;
            border: 1px solid #e6e6a0;
            max-width: 60%;
            font-size: 8pt;
        }
        .payment-info h3 {
            margin-top: 0;
            color: #003366;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../../images/pictures/logo_grey.png" alt="CockpitCorner Logo" width="200">
        </div>
        <div class="company-info">
            <strong>CockpitCorner GmbH</strong><br>
            Musterstraße 123<br>
            12345 Musterstadt<br>
            Tel: +49 123 456789<br>
            E-Mail: info@CockpitCorner.de<br>
            USt-IdNr.: DE123456789
        </div>
    </div>

    <div class="customer-address">
        <strong>Rechnungsempfänger:</strong><br>
        <?php echo htmlspecialchars($rechnung['vorname'] . ' ' . $rechnung['nachname']); ?><br>
        <?php echo htmlspecialchars($rechnung['adresse']); ?><br>
        <?php echo htmlspecialchars($rechnung['plz'] . ' ' . $rechnung['ort']); ?><br>
        E-Mail: <?php echo htmlspecialchars($rechnung['mail']); ?>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <td class="label">Rechnungsnummer:</td>
                <td><?php echo str_pad($rechnungId, 6, '0', STR_PAD_LEFT); ?></td>
                <td class="label" style="padding-left: 40px;">Rechnungsdatum:</td>
                <td><?php echo date('d.m.Y', strtotime($rechnung['rechnungsdatum'])); ?></td>
            </tr>
            <tr>
                <td class="label">Bestellnummer:</td>
                <td><?php echo str_pad($rechnung['bestellID'], 6, '0', STR_PAD_LEFT); ?></td>
                <td class="label" style="padding-left: 40px;">Bestelldatum:</td>
                <td><?php echo date('d.m.Y', strtotime($rechnung['bestelldatum'])); ?></td>
            </tr>
        </table>
    </div>

    <h2 style="color: #003366; margin: 15px 0 10px 0; font-size: 12pt;">Rechnung</h2>

    <table class="items-table">
        <thead>
            <tr>
                <th>Pos.</th>
                <th>Artikel-Nr.</th>
                <th>Beschreibung</th>
                <th class="number">Menge</th>
                <th class="number">Einzelpreis</th>
                <th class="number">MwSt.</th>
                <th class="number">Gesamt</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($positionen as $index => $pos): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($pos['artikel_id']); ?></td>
                <td><?php echo htmlspecialchars($pos['artikel_name']); ?></td>
                <td class="number"><?php echo $pos['menge']; ?></td>
                <td class="number"><?php echo number_format($pos['preis'], 2, ',', '.'); ?> €</td>
                <td class="number"><?php echo number_format($pos['mwst_satz'], 0); ?>%</td>
                <td class="number"><?php echo number_format($pos['gesamt'], 2, ',', '.'); ?> €</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold;">Versand (<?php echo htmlspecialchars($versandname); ?>):</td>
                <td class="number"><?php echo number_format($versandkosten, 2, ',', '.'); ?> € (brutto)</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td class="label">Zwischensumme (Artikel):</td>
                <td><?php echo number_format($subtotalNetto, 2, ',', '.'); ?> €</td>
            </tr>
            <tr>
                <td class="label">Versand (netto):</td>
                <td><?php echo number_format($versandNettoAnteil, 2, ',', '.'); ?> €</td>
            </tr>
            <tr>
                <td class="label">Nettobetrag gesamt:</td>
                <td><?php echo number_format($gesamtNetto, 2, ',', '.'); ?> €</td>
            </tr>
            <tr>
                <td class="label">zzgl. 19% MwSt.:</td>
                <td><?php echo number_format($mwst, 2, ',', '.'); ?> €</td>
            </tr>
            <tr class="total-row">
                <td class="label">Gesamtbetrag:</td>
                <td><?php echo number_format($brutto, 2, ',', '.'); ?> €</td>
            </tr>
        </table>
    </div>

    <div class="payment-info">
        <h3>Zahlungsinformationen</h3>
        <?php if ($istBezahlt): ?>
            <p class="status-paid">
                ✓ Diese Rechnung wurde bereits bezahlt.<br>
                Vielen Dank für Ihre Zahlung!
            </p>
        <?php else: ?>
            <p>
                Bitte überweisen Sie den Rechnungsbetrag innerhalb von 14 Tagen auf folgendes Konto:<br><br>
                <strong>Kontoinhaber:</strong> CockpitCorner GmbH<br>
                <strong>IBAN:</strong> DE00 1234 5678 9012 3456 78<br>
                <strong>BIC:</strong> MUSTDEFFXXX<br>
                <strong>Verwendungszweck:</strong> Rechnung <?php echo str_pad($rechnungId, 6, '0', STR_PAD_LEFT); ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>
            CockpitCorner GmbH | Musterstraße 123 | 12345 Musterstadt<br>
            Geschäftsführer: Max Mustermann | Amtsgericht Musterstadt HRB 12345<br>
            Steuernummer: 123/456/78901 | USt-IdNr.: DE123456789
        </p>
        <p style="margin-top: 15px;">
            Diese Rechnung wurde elektronisch erstellt und ist ohne Unterschrift gültig.
        </p>
    </div>
</body>
</html>
