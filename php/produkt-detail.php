<?php
// Session starten, falls noch nicht geschehen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datenbankverbindung und Head-Importe
include "include/connect.php"; 
include "include/headimport.php";

// Produkt-ID aus URL holen und sicherstellen, dass es eine Zahl ist
$produktId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$produkt = null;

if ($produktId > 0) {
    // Prepared Statements sind sicherer
    $stmt = $conPDO->prepare("SELECT * FROM artikel WHERE id = ?");
    $stmt->execute([$produktId]);
    $produkt = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Standardwerte, falls Produkt nicht gefunden
if (!$produkt) {
    $produkt = [
        'name' => 'Produkt nicht verfügbar',
        'preis' => 0,
        'beschreibung' => 'Die Details für dieses Produkt konnten nicht geladen werden.',
        'marke' => 'Unbekannt',
        'material' => 'k.A.',
        'massstab' => 'k.A.',
        'artikelnummer' => $produktId > 0 ? $produktId : 'Ungültig',
        'bewertung' => 0,          
        'anzahl_bewertungen' => 0, 
        'lagerbestand' => 0
    ];
}

// Bildpfade aus dem Ordner laden
$bilder = [];
// Pfade anpassen an deine Struktur
$relativerWebPfad = '/Webprojekt/images/pictures/productids/' . $produktId . '/';
$absoluterPfad = $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/images/pictures/productids/' . $produktId;

if (is_dir($absoluterPfad)) {
    $dateien = scandir($absoluterPfad);
    foreach ($dateien as $datei) {
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $datei)) {
            $bilder[] = $relativerWebPfad . $datei;
        }
    }
    sort($bilder, SORT_NATURAL | SORT_FLAG_CASE);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produkt['name']); ?> - Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
    /* CSS Styles */
    :root {
        --apple-font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        --text-color-primary: #1d1d1f;
        --text-color-secondary: #6e6e73;
        --accent-color: #0071e3;
        --background-color-light: #f5f5f7;
        --border-color: #d2d2d7;
        --container-width: 1100px;
    }
    body {
        font-family: var(--apple-font);
        margin: 0;
        background-color: #fff;
        color: var(--text-color-primary);
        line-height: 1.6;
    }
    .product-showcase-container {
        max-width: var(--container-width);
        margin: 40px auto;
        padding: 0 20px;
    }
    .product-layout {
        display: flex;
        gap: 50px;
        flex-wrap: wrap;
    }
    .product-image-section {
        flex: 1 1 50%;
        min-width: 300px;
    }
    .main-product-image img {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: contain;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        display: block;
        aspect-ratio: 1/1
    }
    .product-thumbnails {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .product-thumbnails .thumbnail-img {
        width: 80px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.2s ease, transform 0.2s ease;
    }
    .product-thumbnails .thumbnail-img:hover {
        transform: scale(1.05);
    }
    .product-thumbnails .thumbnail-img.active {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 2px var(--accent-color);
    }
    .product-details-section {
        flex: 1 1 45%;
        min-width: 300px;
        display: flex;
        flex-direction: column;
    }
    .product-title {
        font-size: 2.8rem;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.2;
    }
    .product-tagline {
        font-size: 1.2rem;
        color: var(--text-color-secondary);
        margin-bottom: 20px;
        font-weight: 400;
    }
    
    /* Rating Styles */
    .product-rating-summary {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        color: var(--text-color-secondary);
    }
    .product-rating-summary .stars { 
        color: #ffb400; 
        font-size: 1.1rem; 
    }
    .product-rating-summary .stars .far.fa-star,
    .review-stars .far.fa-star {
        color: #d2d2d7 !important;
    }
    
    .product-rating-summary .reviews-count { font-size: 0.9rem; color: var(--accent-color); text-decoration: none; }
    .product-rating-summary .reviews-count:hover { text-decoration: underline; }
    
    .product-price {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-color-primary);
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .tax-info { font-size: 0.85rem; color: var(--text-color-secondary); margin-bottom: 25px; }
    .product-short-description { margin-bottom: 25px; font-size: 1rem; line-height: 1.7; }
    .product-short-description p { margin-bottom: 1em; }
    .product-short-description ul { list-style: none; padding-left: 0; }
    .product-short-description ul li { margin-bottom: 0.6em; padding-left: 22px; position: relative; }
    .product-short-description ul li::before {
        content: '✓'; color: var(--accent-color); font-weight: bold;
        position: absolute; left: 0; top: 1px;
    }
    .add-to-cart-button, .buy-now-button {
        width: 100%; padding: 16px 20px; font-size: 1.1rem; font-weight: 600;
        border-radius: 10px; cursor: pointer; transition: background-color 0.2s ease, transform 0.1s ease;
        text-align: center; margin-bottom: 12px;
    }
    .add-to-cart-button { background-color: var(--accent-color); color: white; border: none; }
    .add-to-cart-button:hover { background-color: #0077ed; }
    .add-to-cart-button:active { transform: scale(0.98); }
    .buy-now-button {
        background-color: var(--background-color-light); color: var(--accent-color);
        border: 1px solid var(--accent-color);
    }
    .buy-now-button:hover { background-color: #e8e8ed; }
    .buy-now-button:active { transform: scale(0.98); }

    /* Quantity Control */
    .cart-actions {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 15px;
        width: 100%;
    }
    .quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--background-color-light);
        height: 52px;
        flex-shrink: 0;
    }
    .qty-btn {
        width: 40px;
        height: 100%;
        border: none;
        background: transparent;
        font-size: 1.2rem;
        cursor: pointer;
        color: var(--text-color-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qty-btn:hover { background-color: rgba(0,0,0,0.05); }
    .qty-input {
        width: 45px;
        height: 100%;
        border: none;
        background: transparent;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 500;
        -moz-appearance: textfield;
        color: var(--text-color-primary);
        padding: 0;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .add-to-cart-form { width: 100%; }
    .add-to-cart-form .buy-now-button { margin-bottom: 0; flex-grow: 1; }

    /* Shipping Info */
    .product-shipping-info { margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); }
    .shipping-detail {
        display: flex; align-items: center; gap: 12px; margin-bottom: 12px;
        font-size: 0.95rem; color: var(--text-color-secondary);
    }
    .shipping-detail i { color: var(--accent-color); font-size: 1.2rem; width: 20px; text-align: center; }
    .additional-actions { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
    .additional-actions a { color: var(--accent-color); text-decoration: none; font-size: 0.9rem; font-weight: 500; }
    .additional-actions a:hover { text-decoration: underline; }
    .additional-actions i { margin-right: 6px; }

    /* Tabs */
    .product-more-info { margin-top: 60px; padding-top: 40px; border-top: 1px solid #e0e0e0; }
    .tabs { display: flex; border-bottom: 1px solid var(--border-color); margin-bottom: 30px; gap: 5px; }
    .tab-link {
        padding: 12px 25px; cursor: pointer; border: none; background-color: transparent;
        font-size: 1.05rem; font-weight: 500; color: var(--text-color-secondary);
        position: relative; border-bottom: 3px solid transparent;
        transition: color 0.2s ease, border-bottom-color 0.2s ease;
    }
    .tab-link:hover { color: var(--text-color-primary); }
    .tab-link.active { color: var(--accent-color); border-bottom-color: var(--accent-color); font-weight: 600; }
    .tab-content { display: none; padding: 10px 0; animation: fadeIn 0.5s; }
    .tab-content.active { display: block; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .tab-content h2 { font-size: 1.8rem; margin-bottom: 20px; font-weight: 600; }
    .tab-content table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
    .tab-content table td { padding: 12px 8px; border-bottom: 1px solid #efefef; }
    .tab-content table td:first-child { font-weight: 500; width: 30%; color: var(--text-color-primary); }
    .tab-content table td:last-child { color: var(--text-color-secondary); }
    
    /* Bewertungs Styles */
    .review { /* CSS für einzelne Review-Blöcke */ }
    .review-header { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap; }
    .review-stars { color: #ffb400; }
    .review-author { font-weight: 600; font-size:0.95rem; }
    .review-date { font-size: 0.85rem; color: var(--text-color-secondary); margin-left:auto;}
    .review-text { font-size: 0.95rem; line-height: 1.7; }
    
    .button-primary, .button-secondary {
        padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;
        cursor: pointer; display: inline-block; text-align: center;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .button-primary { background-color: var(--accent-color); color: white; border: 1px solid var(--accent-color); }
    .button-primary:hover { background-color: #0077ed; }
    .button-secondary { background-color: transparent; color: var(--accent-color); border: 1px solid var(--accent-color); }
    .button-secondary:hover { background-color: rgba(0, 113, 227, 0.05); }

    /* --- NEU: Styles für das Bewertungsformular --- */
    .review-form-container {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 12px;
        margin-top: 20px;
        border: 1px solid #e0e0e0;
        display: none; /* Standardmäßig ausgeblendet */
    }
    .review-form-container.active { display: block; }
    .star-rating-input {
        display: flex;
        flex-direction: row-reverse; /* Damit CSS Hover von rechts nach links funktioniert */
        justify-content: flex-end;
        gap: 5px;
        margin-bottom: 15px;
    }
    .star-rating-input input { display: none; }
    .star-rating-input label {
        cursor: pointer; font-size: 1.5rem; color: #d2d2d7; transition: color 0.2s;
    }
    /* Hover-Effekte: Wenn man über einen Stern fährt, werden er und alle davor gelb */
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label,
    .star-rating-input input:checked ~ label {
        color: #ffb400; 
    }
    .review-textarea {
        width: 100%; padding: 12px; border: 1px solid #d2d2d7;
        border-radius: 8px; font-family: var(--apple-font); font-size: 1rem;
        resize: vertical; min-height: 100px; margin-bottom: 15px;
    }
    .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    /* Media Queries */
    @media (max-width: 992px) {
        .product-layout { gap: 30px; }
        .product-title { font-size: 2.4rem; }
        .product-price { font-size: 2.2rem; }
        .product-image-section, .product-details-section { flex-basis: 100%; }
    }
    @media (max-width: 768px) {
        .product-title { font-size: 2rem; }
        .product-price { font-size: 2rem; }
        .product-tagline { font-size: 1.1rem; }
        .add-to-cart-button, .buy-now-button { font-size: 1rem; padding: 14px 18px; }
        .tabs { flex-direction: column; border-bottom: none; }
        .tab-link { border-bottom: 1px solid var(--border-color); text-align: left; }
        .tab-link.active { border-bottom: 3px solid var(--accent-color); }
        .review-header { flex-direction: column; align-items: flex-start; gap: 5px; }
        .review-date { margin-left: 0; }
    }
    @media (max-width: 480px) {
        .product-title { font-size: 1.8rem; }
        .product-price { font-size: 1.8rem; }
        .cart-actions { flex-wrap: wrap; }
        .quantity-control { width: 100%; justify-content: space-between; margin-bottom: 10px; }
        .qty-input { flex-grow: 1; }
    }
    </style>
</head>
<body>

<?php if (!$produkt || $produkt['name'] === 'Produkt nicht verfügbar'): ?>
    <div class="product-showcase-container">
        <h1>Produkt nicht gefunden</h1>
        <p>Das angeforderte Produkt mit der ID <?php echo htmlspecialchars($produktId); ?> konnte nicht gefunden werden.</p>
        <p><a href="/Webprojekt/index.php">Zurück zur Startseite</a></p>
    </div>
<?php else: ?>
<main class="product-showcase-container">
    <div class="product-layout">

        <div class="product-image-section">
            <div class="main-product-image">
                <?php
                if (!empty($bilder)) {
                    echo '<img src="' . htmlspecialchars($bilder[0]) . '" alt="' . htmlspecialchars($produkt['name']) . ' - Hauptansicht" id="currentProductImage">';
                } else {
                    echo '<img src="https://picsum.photos/seed/' . $produktId . '/800/700" alt="Produktbild Platzhalter" id="currentProductImage">';
                }
                ?>
            </div>
            <?php if (!empty($bilder) && count($bilder) > 1): ?>
            <div class="product-thumbnails">
                <?php foreach ($bilder as $index => $bild):
                    $activeClass = $index === 0 ? 'active' : '';
                    $webPathBild = htmlspecialchars($bild);
                ?>
                    <img src="<?php echo $webPathBild; ?>" alt="Vorschau <?php echo $index + 1; ?>" class="thumbnail-img <?php echo $activeClass; ?>" data-image="<?php echo $webPathBild; ?>">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="product-details-section">
            <h1 class="product-title"><?php echo htmlspecialchars($produkt['name']); ?></h1>
            
            <div class="product-rating-summary">
                <?php 
                $dbRating = isset($produkt['bewertung']) ? floatval($produkt['bewertung']) : 0;
                $dbAnzahl = isset($produkt['anzahl_bewertungen']) ? intval($produkt['anzahl_bewertungen']) : 0;
                ?>
                
                <div class="stars">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($dbRating >= $i) {
                            echo '<i class="fas fa-star"></i>';
                        } elseif ($dbRating >= ($i - 0.5)) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    ?>
                </div>

                <?php if ($dbAnzahl > 0): ?>
                    <a href="#reviews-section" class="reviews-count">(<?php echo $dbAnzahl; ?> Bewertungen)</a>
                <?php else: ?>
                    <span class="reviews-count" style="color: #999; text-decoration: none;">(Noch keine Bewertungen)</span>
                <?php endif; ?>
            </div>
            <p class="product-price"><?php echo number_format(floatval($produkt['preis']), 2, ',', '.'); ?> €</p>
            <p class="tax-info">Inkl. MwSt., zzgl. Versandkosten</p>

            <div class="product-bestand">
                <div class="shipping-detail"><i class="fas fa-box-open"></i><span><?php echo number_format(floatval($produkt['lagerbestand'])); ?> Stück auf Lager</span></div>
            </div>

            <form action="/php/cart-add.php" method="post" class="add-to-cart-form">
                <input type="hidden" name="produkt_id" value="<?php echo $produktId; ?>">
                
                <div class="cart-actions">
                    <div class="quantity-control">
                        <button type="button" class="qty-btn" onclick="updateQty(-1)">−</button>
                        <input type="number" name="anzahl" id="qtyInput" class="qty-input" value="1" min="1" max="<?php echo $produkt['lagerbestand']; ?>">
                        <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                    </div>

                    <button type="submit" class="buy-now-button">In den Warenkorb</button>
                </div>
            </form>
            <div class="product-shipping-info">
                <div class="shipping-detail"><i class="fas fa-truck"></i><span>Kostenloser Versand</span></div>
                <div class="shipping-detail"><i class="fas fa-box-open"></i><span>Lieferung in 1-3 Werktagen</span></div>
                <div class="shipping-detail"><i class="fas fa-undo-alt"></i><span>30 Tage Rückgaberecht</span></div>
            </div>

            <div class="additional-actions">
                <a href="#" class="wishlist-link"><i class="far fa-heart"></i> Zur Wunschliste hinzufügen</a>
            </div>
        </div>
    </div>

    <section class="product-more-info" id="reviews-section">
        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'description-details')">Detaillierte Beschreibung</button>
            <button class="tab-link" onclick="openTab(event, 'specifications')">Spezifikationen</button>
            <button class="tab-link" onclick="openTab(event, 'customer-reviews')">Kundenbewertungen</button>
        </div>

        <div id="description-details" class="tab-content active">
            <h2>Produktbeschreibung im Detail</h2>
            <p><?php echo nl2br(htmlspecialchars($produkt['beschreibung'])); ?></p>
        </div>

        <div id="specifications" class="tab-content">
            <h2>Technische Spezifikationen</h2>
            <table>
                <?php if (!empty($produkt['marke'])): ?>
                    <tr><td>Marke:</td><td><?php echo htmlspecialchars($produkt['marke']); ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($produkt['material'])): ?>
                    <tr><td>Material:</td><td><?php echo htmlspecialchars($produkt['material']); ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($produkt['massstab'])): ?>
                    <tr><td>Maßstab:</td><td><?php echo htmlspecialchars($produkt['massstab']); ?></td></tr>
                <?php endif; ?>
                <tr>
                    <td>Artikelnummer:</td>
                    <td><?php echo htmlspecialchars($produkt['artikelnummer'] ?? $produktId); ?></td>
                </tr>
            </table>
        </div>

        <div id="customer-reviews" class="tab-content">
            <h2>Kundenbewertungen</h2>
            
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="alert alert-success">Vielen Dank! Ihre Bewertung wurde erfolgreich gespeichert.</div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    if ($_GET['error'] == 'already_reviewed') echo "Sie haben dieses Produkt bereits bewertet.";
                    elseif ($_GET['error'] == 'invalid_rating') echo "Bitte wählen Sie 1 bis 5 Sterne.";
                    elseif ($_GET['error'] == 'db_error') echo "Ein Datenbankfehler ist aufgetreten.";
                    else echo "Ein Fehler ist aufgetreten.";
                    ?>
                </div>
            <?php endif; ?>
            <div class="overall-rating-summary" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                <?php if ($dbAnzahl > 0): ?>
                    <p>
                        <strong style="font-size: 1.5rem; color: #1d1d1f;">
                            <?php echo number_format($dbRating, 1, ',', '.'); ?> von 5 Sternen
                        </strong>
                        <br>
                        <span style="color: #6e6e73;">Basierend auf <?php echo $dbAnzahl; ?> Bewertungen</span>
                    </p>
                <?php else: ?>
                    <p>Für dieses Produkt wurde noch keine Bewertung abgegeben.</p>
                <?php endif; ?>
                
                <button class="button-secondary" style="margin-top: 10px;" onclick="toggleReviewForm()">Jetzt bewerten</button>
            </div>

            <div id="reviewForm" class="review-form-container">
                <?php if (isset($_SESSION['temp_user']['id'])): ?>
                    <h3>Ihre Bewertung abgeben</h3>
                    <form action="/Webprojekt/php/bewertung/submit_review.php" method="POST">
                        <input type="hidden" name="produkt_id" value="<?php echo $produktId; ?>">
                        <input type="hidden" name="submit_review" value="1">
                        
                        <div style="margin-bottom: 10px; font-weight: 500;">Wie viele Sterne vergeben Sie?</div>
                        <div class="star-rating-input">
                            <input type="radio" name="rating" id="star5" value="5" required><label for="star5" title="5 Sterne"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="4 Sterne"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="3 Sterne"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="2 Sterne"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="star1" value="1"><label for="star1" title="1 Stern"><i class="fas fa-star"></i></label>
                        </div>

                        <div style="margin-bottom: 10px; font-weight: 500;">Ihr Kommentar:</div>
                        <textarea name="kommentar" class="review-textarea" placeholder="Schreiben Sie hier Ihre Erfahrungen mit dem Produkt..." required></textarea>
                        
                        <button type="submit" class="button-primary">Bewertung absenden</button>
                        <button type="button" class="button-secondary" onclick="toggleReviewForm()">Abbrechen</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger" style="margin:0;">
                        Bitte <a href="/Webprojekt/php/login/loginformular.php" style="color: inherit; font-weight: bold; text-decoration: underline;">melden Sie sich an</a>, um eine Bewertung zu schreiben.
                    </div>
                <?php endif; ?>
            </div>
            <div class="reviews-list">
                <?php
                // Abfrage: Bewertungen + Userdaten holen
                $sqlReviews = "SELECT b.wert, b.kommentar, b.zeitstempel, u.vorname, u.nachname 
                               FROM bewertungen b 
                               LEFT JOIN user u ON b.user_id = u.id 
                               WHERE b.artikel_id = :pid 
                               ORDER BY b.zeitstempel DESC";
                
                // Prepared Statement für Bewertungen
                $stmtRev = $conPDO->prepare($sqlReviews);
                $stmtRev->execute(['pid' => $produktId]);
                $reviews = $stmtRev->fetchAll(PDO::FETCH_ASSOC);

                if (count($reviews) > 0):
                    foreach ($reviews as $review):
                        $userName = !empty($review['vorname']) ? htmlspecialchars($review['vorname'] . ' ' . $review['nachname']) : 'Anonym';
                        $reviewDate = date('d.m.Y', strtotime($review['zeitstempel']));
                        $reviewStars = intval($review['wert']);
                ?>
                    <div class="review" style="border-bottom: 1px solid #eee; padding: 20px 0; margin-bottom: 10px;">
                        <div class="review-header" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <div>
                                <span class="review-author" style="font-weight: 600; margin-right: 10px;"><?php echo $userName; ?></span>
                                <span class="review-stars" style="color: #ffb400;">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($reviewStars >= $i) echo '<i class="fas fa-star"></i>';
                                        else echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </span>
                            </div>
                            <span class="review-date" style="color: #6e6e73; font-size: 0.9rem;"><?php echo $reviewDate; ?></span>
                        </div>
                        
                        <?php if (!empty($review['kommentar'])): ?>
                            <div class="review-text" style="color: #333; line-height: 1.6;">
                                <?php echo nl2br(htmlspecialchars($review['kommentar'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php 
                    endforeach; 
                endif; 
                ?>
            </div>
        </div>
    </section>
</main>
<?php endif; ?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/footimport.php'; ?>

<script>
    const currentProductImage = document.getElementById('currentProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail-img');

    if (currentProductImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                currentProductImage.src = this.dataset.image;
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    function openTab(evt, tabName) {
        const tabcontent = document.getElementsByClassName("tab-content");
        const tablinks = document.getElementsByClassName("tab-link");

        for (let i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            tabcontent[i].classList.remove("active");
        }

        for (let i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        const currentTab = document.getElementById(tabName);
        if (currentTab) {
            currentTab.style.display = "block";
            currentTab.classList.add("active");
        }

        if (evt && evt.currentTarget) {
            evt.currentTarget.classList.add("active");
        }
    }

    // Beim Laden prüfen, ob eine Tab-ID in der URL steht (z.B. ...#reviews-section)
    // Wenn ja, den Reiter öffnen
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') || urlParams.get('error')) {
        // Wenn eine Bewertung abgegeben wurde, Reviews Tab öffnen
        openTab(null, 'customer-reviews');
        // Button des Tabs auch aktiv setzen
        const reviewBtn = document.querySelector("button[onclick*='customer-reviews']");
        if(reviewBtn) {
            document.querySelectorAll('.tab-link').forEach(b => b.classList.remove('active'));
            reviewBtn.classList.add('active');
        }
    } else {
        // Standard Tab öffnen
        const activeTabLink = document.querySelector(".tab-link.active");
        if (activeTabLink) {
            const initialTabId = activeTabLink.getAttribute('onclick').match(/'([^']+)'/)[1];
            openTab(null, initialTabId);
        }
    }

    // NEU: Bewertungsformular ein/ausblenden
    function toggleReviewForm() {
        const form = document.getElementById('reviewForm');
        if (form.classList.contains('active')) {
            form.classList.remove('active');
        } else {
            form.classList.add('active');
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Funktion für Mengenänderung
    function updateQty(change) {
        const input = document.getElementById('qtyInput');
        if (!input) return; 
        
        let currentValue = parseInt(input.value);
        if (isNaN(currentValue)) currentValue = 1;

        const maxStock = input.getAttribute('max') ? parseInt(input.getAttribute('max')) : 999;
        let newValue = currentValue + change;

        if (newValue < 1) newValue = 1;
        if (maxStock > 0 && newValue > maxStock) newValue = maxStock;

        input.value = newValue;
    }
</script>

</body>
</html>