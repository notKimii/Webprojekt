<?php
// Überprüfung der Authentifizierung

include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/loginpruef.php';

include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php'; 


// Benutzer-ID aus Session abrufen
$userID = $_SESSION['user']['id'];

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

// Bestellungen abrufen mit Versandart
$sqlBestellungen = "SELECT * FROM bestellkopf WHERE user_id = ? ORDER BY bestelldatum DESC";
$stmtBestellungen = $conPDO->prepare($sqlBestellungen);
$stmtBestellungen->execute([$userID]);
$bestellungen = $stmtBestellungen->fetchAll(PDO::FETCH_ASSOC);

// Versandarten-Namen
$versandarten = [
    1 => 'LPD',
    2 => 'DHL',
    3 => 'DHL Express'
];

// Bestellpositionen für jede Bestellung abrufen (mit Rabattartikeln)
$bestellPositionen = [];
foreach ($bestellungen as $bestellung) {
    $sqlPositionen = "SELECT bp.*, a.name, a.preis, a.kategorie FROM bestellposition bp 
                      LEFT JOIN artikel a ON bp.artikel_id = a.id 
                      WHERE bp.bestellung_id = ?";
    $stmtPositionen = $conPDO->prepare($sqlPositionen);
    $stmtPositionen->execute([$bestellung['id']]);
    $positionen = $stmtPositionen->fetchAll(PDO::FETCH_ASSOC);
    
    // Wenn Artikel gelöscht wurde (name ist NULL), ersetze durch "Rabatt"
    foreach ($positionen as &$position) {
        if ($position['name'] === null) {
            $position['name'] = 'Rabatt';
            $position['kategorie'] = 'Code';
        }
    }
    
    $bestellPositionen[$bestellung['id']] = $positionen;
}

// Benutzer als online markieren
$sqlOnline = "UPDATE user SET online = 1 WHERE id = ?";
$stmtOnline = $conPDO->prepare($sqlOnline);
$stmtOnline->execute([$userID]);

// Nachrichten aus Session abrufen
$successMessage = $_SESSION['profile_success'] ?? null;
$errors = $_SESSION['profile_errors'] ?? [];
unset($_SESSION['profile_success'], $_SESSION['profile_errors']);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mein Konto - Cockpit Corner</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <style>
        /* Kundenkonto spezifische Styles - angepasst an Hauptdesign */
        .account-container {
            max-width: var(--container-max-width);
            margin: 0 auto;
            padding: calc(var(--spacing-unit) * 3) calc(var(--spacing-unit) * 1.5);
        }

        .account-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 50%, #334155 100%);
            color: white;
            padding: calc(var(--spacing-unit) * 3) calc(var(--spacing-unit) * 2);
            margin-bottom: calc(var(--spacing-unit) * 3);
            border-radius: var(--border-radius-xl);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .account-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
        }

        .account-header h1 {
            margin: 0 0 10px 0;
            font-size: clamp(1.75rem, 4vw, 2.25rem);
            color: white;
            position: relative;
            z-index: 1;
        }

        .account-header p {
            margin: 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, 0.85);
        }

        .account-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: calc(var(--spacing-unit) * 2);
            margin-bottom: calc(var(--spacing-unit) * 3);
        }

        @media (max-width: 768px) {
            .account-grid {
                grid-template-columns: 1fr;
            }
        }

        .account-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: calc(var(--spacing-unit) * 2);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .account-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .account-card h2 {
            color: var(--primary-color);
            margin-top: 0;
            padding-bottom: calc(var(--spacing-unit));
            border-bottom: 2px solid var(--accent-color);
            font-size: 1.25rem;
            margin-bottom: calc(var(--spacing-unit) * 1.5);
        }

        .user-info-row {
            display: flex;
            justify-content: space-between;
            padding: calc(var(--spacing-unit) * 0.75) 0;
            border-bottom: 1px solid var(--light-color);
            align-items: center;
        }

        .user-info-row:last-of-type {
            border-bottom: none;
        }

        .user-info-label {
            font-weight: 600;
            color: var(--text-light);
            min-width: 130px;
            font-size: 0.9rem;
        }

        .user-info-value {
            color: var(--text-color);
            text-align: right;
            font-size: 0.95rem;
        }

        .points-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: calc(var(--spacing-unit) * 1.5);
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            border-radius: var(--border-radius);
            margin: calc(var(--spacing-unit) * 1.5) 0;
        }

        .points-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: white;
        }

        .points-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .points-icon {
            font-size: 2rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 50%;
        }

        .points-info-box {
            background: var(--light-color);
            padding: calc(var(--spacing-unit) * 1.25);
            border-radius: var(--border-radius);
            margin-top: calc(var(--spacing-unit) * 1.5);
            border: 1px solid var(--border-color);
        }

        .points-info-box h3 {
            margin: 0 0 calc(var(--spacing-unit) * 0.75) 0;
            color: var(--primary-color);
            font-size: 0.95rem;
            font-weight: 600;
        }

        .points-info-box ul {
            margin: 0;
            padding-left: calc(var(--spacing-unit) * 1.25);
            color: var(--text-light);
            font-size: 0.875rem;
            list-style: disc;
        }

        .points-info-box ul li {
            margin-bottom: 6px;
        }

        .points-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: calc(var(--spacing-unit));
            color: var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .points-link:hover {
            color: var(--accent-hover);
            gap: 12px;
        }

        .edit-button-container {
            margin-top: calc(var(--spacing-unit) * 1.5);
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-logout {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-logout {
            background: white;
            color: #ef4444;
            border: 2px solid #ef4444;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
        }

        /* Edit Form Styles */
        .hidden-form {
            display: none;
        }

        .edit-form {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: calc(var(--spacing-unit) * 2);
            margin-bottom: calc(var(--spacing-unit) * 3);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        .edit-form h2 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: calc(var(--spacing-unit) * 2);
            font-size: 1.35rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: calc(var(--spacing-unit));
        }

        @media (max-width: 480px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: calc(var(--spacing-unit) * 1.25);
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition);
            background: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .save-button {
            padding: 14px 28px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .save-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .cancel-button {
            background: var(--secondary-color) !important;
        }

        /* Orders Section */
        .orders-section {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: calc(var(--spacing-unit) * 2);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .orders-section h2 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: calc(var(--spacing-unit) * 2);
            font-size: 1.35rem;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: calc(var(--spacing-unit) * 1.5);
        }

        .order-item {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
        }

        .order-item:hover {
            border-color: var(--accent-color);
            box-shadow: var(--shadow-sm);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: calc(var(--spacing-unit) * 1.25);
            background: var(--light-color);
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .order-info-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .shipping-badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .order-id {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1rem;
        }

        .order-date {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .order-status {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-shipped {
            background: #dbeafe;
            color: var(--accent-color);
        }

        .status-completed {
            background: #d1fae5;
            color: #059669;
        }

        .order-items {
            padding: calc(var(--spacing-unit));
        }

        .order-item-row {
            display: grid;
            grid-template-columns: 60px 1fr auto auto; 
            gap: calc(var(--spacing-unit));
            padding: calc(var(--spacing-unit) * 0.75) 0;
            border-bottom: 1px solid var(--light-color);
            align-items: center;
        }

        
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: contain; 
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            background-color: white;
            padding: 2px;
        }

        .order-item-row:last-child {
            border-bottom: none;
        }

        .order-item-name {
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .order-item-qty {
            color: var(--text-light);
            font-size: 0.875rem;
        }

        .order-item-price {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.95rem;
        }

        .order-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: calc(var(--spacing-unit) * 1.25);
            background: var(--primary-color);
            color: white;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-total-label {
            font-weight: 500;
        }

        .order-total-value {
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .btn-reorder {
            padding: 8px 16px;
            background: white;
            color: var(--primary-color);
            border: none;
            border-radius: var(--border-radius);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        
        .btn-reorder:hover {
            background: #f8fafc;
            transform: translateY(-1px);
            color: var(--primary-color);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: calc(var(--spacing-unit) * 4) calc(var(--spacing-unit) * 2);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: calc(var(--spacing-unit));
            opacity: 0.5;
        }

        .empty-state h3 {
            color: var(--primary-color);
            margin-bottom: calc(var(--spacing-unit) * 0.75);
            font-size: 1.25rem;
        }

        .empty-state p {
            color: var(--text-light);
            margin-bottom: calc(var(--spacing-unit) * 1.5);
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-shop {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .btn-shop:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        /* Custom Footer für diese Seite */
        .account-footer {
            background: linear-gradient(180deg, var(--primary-color), var(--primary-light));
            color: #cbd5e1;
            padding: calc(var(--spacing-unit) * 2) 0;
            margin-top: calc(var(--spacing-unit) * 4);
            text-align: center;
            font-size: 0.9rem;
        }

        .account-footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .account-footer a:hover {
            color: white;
        }

        /* Notification Messages */
        .notification {
            padding: calc(var(--spacing-unit) * 1.5);
            border-radius: var(--border-radius);
            margin-bottom: calc(var(--spacing-unit) * 2);
            display: flex;
            align-items: start;
            gap: 12px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-success {
            background: #d1fae5;
            border: 2px solid #059669;
            color: #065f46;
        }

        .notification-error {
            background: #fee2e2;
            border: 2px solid #dc2626;
            color: #991b1b;
        }

        .notification-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .notification-list {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }

        .notification-list li {
            margin-bottom: 4px;
        }

        .notification-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0;
            color: inherit;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .notification-close:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
<?php include 'include/headimport.php'; ?>
    <main>
        <div class="account-container">
            <!-- Header -->
            <div class="account-header">
                <h1>👋 Willkommen, <?php echo htmlspecialchars($userData['vorname']); ?>!</h1>
                <p>Verwalte dein Konto, sieh deine Bestellungen und sammle Treuepunkte.</p>
            </div>

            <!-- Success Message -->
            <?php if ($successMessage): ?>
                <div class="notification notification-success" id="successNotification">
                    <span class="notification-icon">✅</span>
                    <div class="notification-content">
                        <div class="notification-title">Erfolg!</div>
                        <div><?php echo htmlspecialchars($successMessage); ?></div>
                    </div>
                    <button class="notification-close" onclick="closeNotification('successNotification')">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
                <div class="notification notification-error" id="errorNotification">
                    <span class="notification-icon">⚠️</span>
                    <div class="notification-content">
                        <div class="notification-title">Fehler beim Speichern</div>
                        <?php if (count($errors) === 1): ?>
                            <div><?php echo htmlspecialchars($errors[0]); ?></div>
                        <?php else: ?>
                            <ul class="notification-list">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <button class="notification-close" onclick="closeNotification('errorNotification')">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Profil und Punkte Grid -->
            <div class="account-grid">
                <!-- Profil Card -->
                <div class="account-card">
                    <h2>👤 Mein Profil</h2>
                    
                    <div class="user-info-row">
                        <span class="user-info-label">Name:</span>
                        <span class="user-info-value"><?php echo htmlspecialchars($userData['vorname'] . ' ' . $userData['nachname']); ?></span>
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
                        <div class="points-icon">🎁</div>
                    </div>
                    
                    <div class="points-info-box">
                        <h3>So funktioniert's:</h3>
                        <ul>
                            <li>Erhalte Punkte bei jedem Kauf</li>
                            <li>Sammle Punkte über Zeit</li>
                            <li>Tausche Punkte gegen Rabatte ein</li>
                            <li>Erhöhe deinen Status im Shop</li>
                        </ul>
                    </div>

                    <a href="/Webprojekt/php/punkte_historie.php" class="points-link">
                        📊 Punkte-Historie ansehen →
                    </a>
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
                            <button type="button" class="save-button cancel-button" onclick="toggleEditForm()">❌ Abbrechen</button>
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
                                        <?php 
                                        $versandartId = $bestellung['versandart'] ?? 2;
                                        $versandartName = $versandarten[$versandartId] ?? 'DHL';
                                        $versandkostenMap = [1 => 11.90, 2 => 6.90, 3 => 16.90];
                                        $versandkostenBetrag = $versandkostenMap[$versandartId] ?? 6.90;
                                        ?>
                                        <div class="shipping-badge">
                                            📦 Versand: <?php echo htmlspecialchars($versandartName); ?> (<?php echo number_format($versandkostenBetrag, 2, ',', '.'); ?> €)
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
                                        <div class="order-item-image">
                                        <?php 
                                            $artikelID = $position['artikel_id'];
                                            
                                            $suchMuster = '../images/pictures/productids/' . $artikelID . '/*';
                                            
                                            $gefundeneBilder = glob($suchMuster);
                                            
                                            if ($gefundeneBilder && count($gefundeneBilder) > 0) {
                                                $bildPfad = $gefundeneBilder[0];
                                                
                                                echo '<img src="' . htmlspecialchars($bildPfad) . '" alt="' . htmlspecialchars($position['name']) . '" class="product-thumb">';
                                            } else {
                                                echo '<div class="product-thumb" style="display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:#94a3b8;font-size:20px;">📷</div>';
                                            }
                                        ?>
                                    </div>
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
                                    <div>
                                        <span class="order-total-label">Gesamtbetrag:</span>
                                        <span class="order-total-value">
                                            <?php echo number_format($bestellung['gesamtbetrag'], 2, ',', '.'); ?> €
                                        </span>
                                    </div>
                                    <a href="/Webprojekt/php/bestellung/erneut_bestellen.php?bestellung_id=<?php echo $bestellung['id']; ?>" class="btn-reorder">
                                        🔄 Erneut bestellen
                                    </a>
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
    <footer class="account-footer">
        <div style="max-width: var(--container-max-width); margin: 0 auto; padding: 0 calc(var(--spacing-unit) * 1.5);">
            <p>&copy; 2024 Cockpit Corner - Alles für Piloten · 
                <a href="#">Datenschutz</a> · 
                <a href="#">AGBs</a> · 
                <a href="#">Kontakt</a>
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

        function closeNotification(id) {
            const notification = document.getElementById(id);
            if (notification) {
                notification.style.animation = 'slideUp 0.3s ease-out';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Auto-hide success notifications nach 5 Sekunden
        const successNotification = document.getElementById('successNotification');
        if (successNotification) {
            setTimeout(() => {
                closeNotification('successNotification');
            }, 5000);
        }

        // Formular automatisch öffnen wenn Fehler vorhanden sind
        <?php if (!empty($errors)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                toggleEditForm();
            });
        <?php endif; ?>
    </script>
</body>
</html>