<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "../include/connectcon.php";

// Rechnungs-E-Mail versenden, falls Bestellung-ID übergeben wurde
if (isset($_GET['bestellung_id'])) {
    $bestellungId = (int)$_GET['bestellung_id'];
    
    // Prüfen ob E-Mail bereits versendet wurde (Session-Check)
    if (!isset($_SESSION['rechnung_versendet_' . $bestellungId])) {
        try {
            // E-Mail nur einmal versenden
            include 'billingmail.php';
            
            // Markieren als versendet
            $_SESSION['rechnung_versendet_' . $bestellungId] = true;
        } catch (Exception $e) {
            // Fehler loggen, aber Seite trotzdem anzeigen
            error_log('Fehler beim Rechnungsversand: ' . $e->getMessage());
        }
    }
}
?>

<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bestellung abgeschlossen - Pilotenshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .site-max { max-width: 1400px; margin: 0 auto; padding: 0 1rem; }
        main { padding-top: 80px; padding-bottom: 60px; }
        .thank-you-card {
            max-width: 700px;
            margin: 0 auto;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            background-color: #198754;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-icon svg {
            width: 50px;
            height: 50px;
            color: white;
        }
        .points-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: bold;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-light">
    <?php include "../include/headimport.php"; ?>
    
    <div class="site-max">
        <main class="container">
            <div class="thank-you-card bg-white">
                <!-- Success Icon -->
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <!-- Thank You Message -->
                <h1 class="text-center mb-4">Vielen Dank für Ihre Bestellung!</h1>
                
                <div class="text-center mb-4">
                    <p class="lead mb-3">Ihre Bestellung wurde erfolgreich aufgegeben.</p>
                    <p class="text-muted">
                        Sie erhalten in Kürze eine Bestätigungs-E-Mail mit Ihrer Rechnung 
                        und allen Details zu Ihrer Bestellung.
                    </p>
                </div>
                
                <!-- Points Badge -->
                <div class="text-center my-4">
                    <div class="points-badge">
                        🎉 +50 Treuepunkte
                    </div>
                    <p class="text-muted mt-2">
                        Für diese Bestellung erhalten Sie 50 Treuepunkte!
                    </p>
                </div>
                
                <!-- Info Boxes -->
                <div class="row g-3 mt-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-envelope-check text-primary mb-2" viewBox="0 0 16 16">
                                    <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z"/>
                                    <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686"/>
                                </svg>
                                <h5 class="card-title mt-2">E-Mail verschickt</h5>
                                <p class="card-text small text-muted">
                                    Rechnung und Bestelldetails sind unterwegs
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-box-seam text-success mb-2" viewBox="0 0 16 16">
                                    <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2zm3.564 1.426L5.596 5 8 5.961 14.154 3.5zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/>
                                </svg>
                                <h5 class="card-title mt-2">Versand</h5>
                                <p class="card-text small text-muted">
                                    Ihre Bestellung wird schnellstmöglich bearbeitet
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-5">
                    <a href="/Webprojekt/index.php" class="btn btn-primary btn-lg">
                        Weiter einkaufen
                    </a>
                    <a href="/Webprojekt/php/kundenkonto.php" class="btn btn-outline-secondary btn-lg">
                        Zu meinem Konto
                    </a>
                </div>
            </div>
        </main>
    </div>
    
    <?php include "../include/footimport.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>