<?php
// Überprüfung der Authentifizierung

include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/loginpruef.php';

include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php'; 


// Benutzer-ID aus Session abrufen
$userID = $_SESSION['temp_user']['id'];

// Benutzerdaten abrufen
$sqlUser = "SELECT * FROM user WHERE id = ?";
$stmtUser = $conPDO->prepare($sqlUser);
$stmtUser->execute([$userID]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Punkte abrufen
$sqlPunkte = "SELECT punktestand FROM punkte WHERE user_id = ?";
$stmtPunkte = $conPDO->prepare($sqlPunkte);
$stmtPunkte->execute([$userID]);
$punkteData = $stmtPunkte->fetch(PDO::FETCH_ASSOC);
$punktestand = $punkteData['punktestand'] ?? 0;

// Bestellungen abrufen
$sqlBestellungen = "SELECT * FROM bestellkopf WHERE user_id = ? ORDER BY bestelldatum DESC";
$stmtBestellungen = $conPDO->prepare($sqlBestellungen);
$stmtBestellungen->execute([$userID]);
$bestellungen = $stmtBestellungen->fetchAll(PDO::FETCH_ASSOC);

// Bestellpositionen für jede Bestellung abrufen
$bestellPositionen = [];
foreach ($bestellungen as $bestellung) {
    $sqlPositionen = "SELECT bp.*, a.name, a.preis FROM bestellposition bp 
                      JOIN artikel a ON bp.artikel_id = a.id 
                      WHERE bp.bestellung_id = ?";
    $stmtPositionen = $conPDO->prepare($sqlPositionen);
    $stmtPositionen->execute([$bestellung['id']]);
    $bestellPositionen[$bestellung['id']] = $stmtPositionen->fetchAll(PDO::FETCH_ASSOC);
}

// Benutzer als online markieren
$sqlOnline = "UPDATE user SET online = 1 WHERE id = ?";
$stmtOnline = $conPDO->prepare($sqlOnline);
$stmtOnline->execute([$userID]);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mein Konto - Cockpit Corner</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <style>
        /* Kundenkonto spezifische Styles */
        .account-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .account-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 40px;
            border-radius: 10px;
            text-align: center;
        }

        .account-header h1 {
            margin: 0 0 10px 0;
            font-size: 32px;
        }

        .account-header p {
            margin: 0;
            opacity: 0.9;
        }

        .account-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .account-grid {
                grid-template-columns: 1fr;
            }
        }

        .account-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }

        .account-card h2 {
            color: #333;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 2px solid #667eea;
            font-size: 22px;
        }

        .user-info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }

        .user-info-row:last-child {
            border-bottom: none;
        }

        .user-info-label {
            font-weight: 600;
            color: #555;
            min-width: 150px;
        }

        .user-info-value {
            color: #333;
            text-align: right;
        }

        .points-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            border-radius: 8px;
            margin: 20px 0;
        }

        .points-value {
            font-size: 36px;
            font-weight: bold;
            color: #333;
        }

        .points-label {
            font-size: 14px;
            color: #555;
        }

        .edit-button-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-logout {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #667eea;
            color: white;
        }

        .btn-edit:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-logout {
            background: #f44336;
            color: white;
        }

        .btn-logout:hover {
            background: #da190b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
        }

        .orders-section {
            margin-top: 40px;
        }

        .orders-section h2 {
            color: #333;
            padding-bottom: 15px;
            border-bottom: 2px solid #667eea;
            font-size: 22px;
            margin-top: 0;
        }

        .orders-list {
            margin-top: 20px;
        }

        .order-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .order-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-id {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .order-date {
            color: #666;
            font-size: 14px;
        }

        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-shipped {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-items {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .order-item-row:last-child {
            border-bottom: none;
        }

        .order-item-name {
            flex: 1;
            color: #333;
            font-weight: 500;
        }

        .order-item-qty {
            color: #666;
            margin: 0 20px;
            text-align: center;
            min-width: 50px;
        }

        .order-item-price {
            color: #667eea;
            font-weight: 600;
            min-width: 100px;
            text-align: right;
        }

        .order-total {
            display: flex;
            justify-content: flex-end;
            padding-top: 10px;
            border-top: 2px solid #667eea;
            margin-top: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .order-total-label {
            margin-right: 20px;
        }

        .order-total-value {
            color: #667eea;
            min-width: 120px;
            text-align: right;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            margin-bottom: 20px;
        }

        .btn-shop {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-shop:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .two-column {
                grid-template-columns: 1fr;
            }
        }

        .edit-form {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .save-button {
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .save-button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .hidden-form {
            display: none;
        }

        .breadcrumb {
            margin-bottom: 30px;
            padding: 15px 0;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
            margin-right: 10px;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            color: #999;
            margin: 0 5px;
        }
    </style>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/headimport.php'; ?>

    <main>
        <div class="account-container">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="/Webprojekt/index.php">Home</a>
                <span>›</span>
                <span>Mein Konto</span>
            </div>

            <!-- Account Header -->
            <div class="account-header">
                <h1>👤 Willkommen, <?php echo htmlspecialchars($userData['vorname']); ?>!</h1>
                <p>Verwalte deine Kontoinformationen, Bestellungen und Punkte</p>
            </div>

            <!-- Benutzerinformationen und Punkte -->
            <div class="account-grid">
                <!-- Benutzerdaten Card -->
                <div class="account-card">
                    <h2>📋 Meine Kontoinformationen</h2>
                    
                    <div class="user-info-row">
                        <span class="user-info-label">Vorname:</span>
                        <span class="user-info-value"><?php echo htmlspecialchars($userData['vorname']); ?></span>
                    </div>
                    
                    <div class="user-info-row">
                        <span class="user-info-label">Nachname:</span>
                        <span class="user-info-value"><?php echo htmlspecialchars($userData['nachname']); ?></span>
                    </div>
                    
                    <div class="user-info-row">
                        <span class="user-info-label">E-Mail:</span>
                        <span class="user-info-value"><?php echo htmlspecialchars($userData['mail']); ?></span>
                    </div>
                    
                    <div class="user-info-row">
                        <span class="user-info-label">Adresse:</span>
                        <span class="user-info-value"><?php echo htmlspecialchars($userData['adresse']); ?></span>
                    </div>
                    
                    <div class="user-info-row">
                        <span class="user-info-label">PLZ:</span>
                        <span class="user-info-value"><?php echo htmlspecialchars($userData['plz']); ?></span>
                    </div>
                    
                    <div class="user-info-row">
                        <span class="user-info-label">Ort:</span>
                        <span class="user-info-value"><?php echo htmlspecialchars($userData['ort']); ?></span>
                    </div>
                    
                    <div class="edit-button-container">
                        <button class="btn-edit" onclick="toggleEditForm()">
                            ✏️ Profil bearbeiten
                        </button>
                        <a href="/Webprojekt/php/Logout.php" class="btn-logout">
                            🚪 Abmelden
                        </a>
                    </div>
                </div>

                <!-- Punkte Card -->
                <div class="account-card">
                    <h2>⭐ Treuepunkte</h2>
                    
                    <div class="points-display">
                        <div>
                            <div class="points-value"><?php echo $punktestand; ?></div>
                            <div class="points-label">Punkte verfügbar</div>
                        </div>
                        <div>
                            <div style="font-size: 24px;">🎁</div>
                        </div>
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <h3 style="margin-top: 0; color: #333; font-size: 16px;">So funktioniert's:</h3>
                        <ul style="margin: 10px 0; padding-left: 20px; color: #666; font-size: 14px;">
                            <li>Erhalte Punkte bei jedem Kauf</li>
                            <li>Sammle Punkte über Zeit</li>
                            <li>Tausche Punkte gegen Rabatte ein</li>
                            <li>Erhöhe deinen Status im Shop</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Edit Form (versteckt) -->
            <div id="editFormContainer" class="hidden-form">
                <div class="edit-form">
                    <h2>✏️ Profil bearbeiten</h2>
                    <form method="POST" action="/Webprojekt/php/aktualisiere-profil.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="vorname">Vorname *</label>
                                <input type="text" id="vorname" name="vorname" value="<?php echo htmlspecialchars($userData['vorname']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="nachname">Nachname *</label>
                                <input type="text" id="nachname" name="nachname" value="<?php echo htmlspecialchars($userData['nachname']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mail">E-Mail-Adresse *</label>
                            <input type="email" id="mail" name="mail" value="<?php echo htmlspecialchars($userData['mail']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="adresse">Straße und Hausnummer *</label>
                            <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($userData['adresse']); ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="plz">Postleitzahl *</label>
                                <input type="text" id="plz" name="plz" value="<?php echo htmlspecialchars($userData['plz']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="ort">Ort *</label>
                                <input type="text" id="ort" name="ort" value="<?php echo htmlspecialchars($userData['ort']); ?>" required>
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="save-button">💾 Änderungen speichern</button>
                            <button type="button" class="save-button" onclick="toggleEditForm()" style="background: #999;">❌ Abbrechen</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bestellhistorie -->
            <div class="orders-section">
                <h2>📦 Meine Bestellungen</h2>
                
                <?php if (count($bestellungen) > 0): ?>
                    <div class="orders-list">
                        <?php foreach ($bestellungen as $bestellung): ?>
                            <div class="order-item">
                                <div class="order-header">
                                    <div>
                                        <div class="order-id">Bestellung #<?php echo $bestellung['id']; ?></div>
                                        <div class="order-date">
                                            📅 <?php echo date('d.m.Y H:i', strtotime($bestellung['bestelldatum'])); ?> Uhr
                                        </div>
                                    </div>
                                    <span class="order-status <?php 
                                        $status = strtolower($bestellung['status'] ?? 'pending');
                                        echo 'status-' . ($status === 'versendet' ? 'shipped' : ($status === 'abgeschlossen' ? 'completed' : 'pending'));
                                    ?>">
                                        <?php echo htmlspecialchars($bestellung['status'] ?? 'Ausstehend'); ?>
                                    </span>
                                </div>

                                <?php if (isset($bestellPositionen[$bestellung['id']]) && count($bestellPositionen[$bestellung['id']]) > 0): ?>
                                    <div class="order-items">
                                        <?php foreach ($bestellPositionen[$bestellung['id']] as $position): ?>
                                            <div class="order-item-row">
                                                <div class="order-item-name">
                                                    <?php echo htmlspecialchars($position['name']); ?>
                                                </div>
                                                <div class="order-item-qty">
                                                    Menge: <strong><?php echo $position['menge']; ?></strong>
                                                </div>
                                                <div class="order-item-price">
                                                    <?php echo number_format($position['menge'] * $position['einzelpreis'], 2, ',', '.'); ?> €
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="order-total">
                                    <span class="order-total-label">Gesamtbetrag:</span>
                                    <span class="order-total-value">
                                        <?php echo number_format($bestellung['gesamtbetrag'], 2, ',', '.'); ?> €
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>Noch keine Bestellungen</h3>
                        <p>Du hast noch keine Bestellungen aufgegeben. Stöbere in unserem Shop und finde großartige Produkte!</p>
                        <a href="/Webprojekt/index.php" class="btn-shop">🛍️ Zum Shop</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer style="background-color: #f8f9fa; border-top: 1px solid #e0e0e0; padding: 30px 20px; margin-top: 60px; text-align: center; color: #666;">
        <div style="max-width: 1400px; margin: 0 auto;">
            <p>&copy; 2024 Cockpit Corner - Alles für Piloten · 
                <a href="#" style="color: #667eea; text-decoration: none;">Datenschutz</a> · 
                <a href="#" style="color: #667eea; text-decoration: none;">AGBs</a> · 
                <a href="#" style="color: #667eea; text-decoration: none;">Kontakt</a>
            </p>
        </div>
    </footer>

    <script>
        function toggleEditForm() {
            const editForm = document.getElementById('editFormContainer');
            editForm.classList.toggle('hidden-form');
            
            // Scroll zum Formular wenn es angezeigt wird
            if (!editForm.classList.contains('hidden-form')) {
                editForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>
</body>
</html>