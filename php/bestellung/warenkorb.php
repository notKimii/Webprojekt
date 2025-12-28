<?php 
// include "include/loginpruef.php";
session_start(); // Session muss gestartet sein, falls nicht schon in headimport passiert
?>

<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../include/connectcon.php';

// Session sicherstellen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bildfunktion
function getImg($id) {
    $p = "/Webprojekt/images/pictures/productids/$id/";
    $img = $p . "main.jpg";
    $dir = $_SERVER['DOCUMENT_ROOT'] . $p;
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), ['.', '..']);
        if ($files) $img = $p . reset($files);
    }
    return $img;
}

// Versandarten mit Kosten
// DHL: 6,9€
// LPD: 5€ teurer als DHL = 11,9€
// DHL Express: DHL + 10€ = 16,9€
$shippingMethods = [
    'lpd' => ['name' => 'LPD', 'cost' => 11.90],
    'dhl' => ['name' => 'DHL', 'cost' => 6.90],
    'dhl-express' => ['name' => 'DHL Express', 'cost' => 16.90]
];

// Hilfsfunktion: Warenkorb laden und Summen berechnen
function loadCartData(mysqli $con, int $kundenId, string $shippingMethod = 'dhl'): array {
    global $shippingMethods;
    
    $sql = "SELECT wp.*, p.name, p.preis FROM warenkorbposition wp
            LEFT JOIN artikel p ON wp.artikel_id = p.id
            WHERE wp.warenkorb_id = (
                SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1
            )";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $kundenId);
    $stmt->execute();
    $res = $stmt->get_result();

    $items = [];
    $subtotal = 0;
    while ($row = $res->fetch_assoc()) {
        // Defaults für fehlende Artikel-Daten
        $row['name'] = $row['name'] ?? 'Unbekanntes Produkt';
        $row['preis'] = $row['preis'] ?? 0.0;
        $row['zeilensumme'] = $row['preis'] * $row['menge'];
        $subtotal += $row['zeilensumme'];
        $items[(int)$row['artikel_id']] = $row;
    }
    
    // Versandkosten basierend auf Versandart
    $shipping = ($subtotal > 0 && isset($shippingMethods[$shippingMethod])) 
        ? $shippingMethods[$shippingMethod]['cost'] 
        : 0;
    $total = $subtotal + $shipping;

    return [
        'items' => $items,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $total,
        'count' => count($items)
    ];
}

// Session prüfen
$kundenId = null;
if (isset($_SESSION['temp_user']['id'])) {
    $kundenId = (int)$_SESSION['temp_user']['id'];
} elseif (isset($_SESSION['user']['id'])) {
    $kundenId = (int)$_SESSION['user']['id'];
} elseif (isset($_SESSION['user_id'])) {
    $kundenId = (int)$_SESSION['user_id'];
}
$isAjax = isset($_POST['ajax']);

// Kein Login
if ($kundenId === null) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'no_login']);
        exit;
    }
    echo '<p>Fehler: Kein Kunde angemeldet.</p>';
    exit;
}

// Aktiven Warenkorb laden
$cartId = null;
$stmtCart = $con->prepare('SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1');
$stmtCart->bind_param('i', $kundenId);
$stmtCart->execute();
$stmtCart->bind_result($cid);
if ($stmtCart->fetch()) {
    $cartId = (int)$cid;
}
$stmtCart->close();

// Aktionen: Menge per +/- ändern oder Artikel löschen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Warenkorb erstellen, falls nicht vorhanden
    if ($cartId === null) {
        $stmtInsert = $con->prepare('INSERT INTO warenkorbkopf (kunde_id) VALUES (?)');
        $stmtInsert->bind_param('i', $kundenId);
        $stmtInsert->execute();
        $cartId = $con->insert_id;
        $stmtInsert->close();
    }

    $action = $_POST['action'] ?? '';
    $artikelId = isset($_POST['artikel_id']) ? (int)$_POST['artikel_id'] : 0;
    $success = false;

    if ($artikelId > 0) {
        if ($action === 'inc_qty') {
             $stmtUpd = $con->prepare('UPDATE warenkorbposition SET menge = menge + 1 WHERE warenkorb_id = ? AND artikel_id = ?');
            $stmtUpd->bind_param('ii', $cartId, $artikelId);
            $success = $stmtUpd->execute();
            $stmtUpd->close();
        } elseif ($action === 'dec_qty') {
            $stmtUpd = $con->prepare('UPDATE warenkorbposition SET menge = menge - 1 WHERE warenkorb_id = ? AND artikel_id = ?');
            $stmtUpd->bind_param('ii', $cartId, $artikelId);
            $success = $stmtUpd->execute();
            $stmtUpd->close();
            // Entfernen, falls Menge <= 0
            $stmtDelZero = $con->prepare('DELETE FROM warenkorbposition WHERE warenkorb_id = ? AND artikel_id = ? AND menge <= 0');
            $stmtDelZero->bind_param('ii', $cartId, $artikelId);
            $stmtDelZero->execute();
            $stmtDelZero->close();
        } elseif ($action === 'delete_item') {
            $stmtDel = $con->prepare('DELETE FROM warenkorbposition WHERE warenkorb_id = ? AND artikel_id = ?');
            $stmtDel->bind_param('ii', $cartId, $artikelId);
            $success = $stmtDel->execute();
            $stmtDel->close();
        }
    }

    $cartData = loadCartData($con, $kundenId);
    $currentItem = $cartData['items'][$artikelId] ?? null;

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'artikel_id' => $artikelId,
            'menge' => $currentItem['menge'] ?? 0,
            'zeilensumme' => $currentItem['zeilensumme'] ?? 0,
            'subtotal' => $cartData['subtotal'],
            'shipping' => $cartData['shipping'],
            'total' => $cartData['total'],
            'count' => $cartData['count']
        ]);
        exit;
    }

    header('Location: /Webprojekt/php/bestellung/warenkorb.php');
    exit;
}

// Warenkorbinhalte initial laden (GET)
$cartData = loadCartData($con, $kundenId);
$result = array_values($cartData['items']);
$subtotal = $cartData['subtotal'];
$shipping = $cartData['shipping'];
$total = $cartData['total'];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Warenkorb - Mein Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="warenkorb.css">
</head>

<body>
    <?php include "../include/headimport.php"; ?>
    <main>
        <?php
        // Body rendering uses the already loaded $cartData from above.
        // Removed duplicated session/post/SQL logic and rely on top-level handling (incl. AJAX support).
        $result = array_values($cartData['items']);
        $subtotal = $cartData['subtotal'];
        $shipping = $cartData['shipping'];
        $total = $cartData['total'];
        ?>
                <section class="h-100 gradient-custom" style="background:#f5f5f5;">
                    <div class="container py-5">
                        <div class="row d-flex justify-content-center my-4">
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header py-3">
                                        <h5 class="mb-0">Warenkorb - <span id="cart-count"><?php echo $cartData['count']; ?></span> Artikel</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 <?php echo empty($result) ? '' : 'd-none'; ?>" id="cart-empty">Dein Warenkorb ist leer.</p>
                                        <?php if (!empty($result)): ?>
                                            <div id="cart-items">
                                            <?php foreach ($result as $position): ?>
                                                <?php $id = $position['artikel_id']; $img = getImg($id); ?>
                                                <div class="row align-items-center mb-4" data-artikel-id="<?php echo $id; ?>">
                                                    <div class="col-lg-3 col-md-12 mb-3 mb-lg-0">
                                                        <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                                                            <img src="<?php echo htmlspecialchars($img); ?>" class="w-100" alt="<?php echo htmlspecialchars($position['name']); ?>" onerror="this.src='/Webprojekt/images/pictures/placeholder.jpg'" />
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-5 col-md-6 mb-3 mb-lg-0">
                                                        <p class="mb-1"><strong><?php echo htmlspecialchars($position['name']); ?></strong></p>
                                                        <p class="mb-1">Artikel-Nr.: <?php echo $id; ?></p>
                                                        <div class="d-flex gap-2">
                                                            <form method="post" class="m-0 ajax-cart">
                                                                <input type="hidden" name="artikel_id" value="<?php echo $id; ?>">
                                                                <input type="hidden" name="action" value="delete_item">
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Entfernen"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                                                        <div class="d-flex mb-2" style="max-width: 220px;">
                                                            <form method="post" class="m-0 ajax-cart">
                                                                <input type="hidden" name="artikel_id" value="<?php echo $id; ?>">
                                                                <input type="hidden" name="action" value="dec_qty">
                                                                <button type="submit" class="btn btn-primary px-3 me-2"><i class="fas fa-minus"></i></button>
                                                            </form>

                                                            <input type="number" class="form-control text-center" value="<?php echo $position['menge']; ?>" readonly id="qty-<?php echo $id; ?>">

                                                            <form method="post" class="m-0 ajax-cart">
                                                                <input type="hidden" name="artikel_id" value="<?php echo $id; ?>">
                                                                <input type="hidden" name="action" value="inc_qty">
                                                                <button type="submit" class="btn btn-primary px-3 ms-2"><i class="fas fa-plus"></i></button>
                                                            </form>
                                                        </div>
                                                        <p class="text-start text-md-center mb-0"><strong><?php echo number_format($position['preis'], 2, ',', '.'); ?> €</strong></p>
                                                        <p class="text-start text-md-center">Gesamt: <strong id="line-<?php echo $id; ?>"><?php echo number_format($position['zeilensumme'], 2, ',', '.'); ?> €</strong></p>
                                                    </div>
                                                </div>
                                                <hr class="my-4" />
                                            <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <p class="mb-2"><strong>Versandart</strong></p>
                                        <select id="shipping-method" class="form-select">
                                            <option value="lpd">LPD - 11,90 €</option>
                                            <option value="dhl" selected>DHL - 6,90 €</option>
                                            <option value="dhl-express">DHL Express - 16,90 €</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card mb-4 mb-lg-0">
                                    <div class="card-body">
                                        <p class="mb-1"><strong>Akzeptierte Zahlarten</strong></p>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border">Visa</span>
                                            <span class="badge bg-light text-dark border">Mastercard</span>
                                            <span class="badge bg-light text-dark border">PayPal</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header py-3">
                                        <h5 class="mb-0">Zusammenfassung</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                                Produkte
                                                <span id="summary-subtotal"><?php echo number_format($subtotal, 2, ',', '.'); ?> €</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                Versand
                                                <span id="summary-shipping"><?php echo $shipping > 0 ? number_format($shipping, 2, ',', '.') . ' €' : 'Gratis'; ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                                <div>
                                                    <strong>Gesamt</strong>
                                                    <p class="mb-0">(inkl. MwSt.)</p>
                                                </div>
                                                <span><strong id="summary-total"><?php echo number_format($total, 2, ',', '.'); ?> €</strong></span>
                                            </li>
                                        </ul>
                                        <?php if (!empty($result)): ?>
                                            <form action="checkout.php" method="post">
                                                <input type="hidden" name="shipping_method" id="shipping_method_input" value="dhl">
                                                <button type="submit" class="btn btn-primary btn-lg btn-block w-100">Zur Kasse</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
    </main>
    <?php include "../include/footimport.php"; ?>
    <script>
    (function(){
        const fmt = new Intl.NumberFormat('de-DE', {minimumFractionDigits:2, maximumFractionDigits:2});
        function formatCurrency(v){ return fmt.format(Number(v || 0)) + ' €'; }

        async function submitAjax(form){
            const fd = new FormData(form);
            fd.append('ajax', '1');
            try{
                const res = await fetch(location.pathname, { method: 'POST', body: fd, credentials: 'same-origin' });
                if(!res.ok) throw new Error('Netzwerkfehler');
                const data = await res.json();
                console.log('AJAX Response:', data);
                if(!data) return;
                const id = data.artikel_id;
                const qtyEl = document.querySelector('#qty-' + id);
                if(qtyEl) qtyEl.value = data.menge ?? 0;
                const lineEl = document.querySelector('#line-' + id);
                if(lineEl) lineEl.textContent = formatCurrency(data.zeilensumme ?? 0);
                const subtotalEl = document.querySelector('#summary-subtotal');
                if(subtotalEl) subtotalEl.textContent = formatCurrency(data.subtotal ?? 0);
                const shippingEl = document.querySelector('#summary-shipping');
                if(shippingEl) shippingEl.textContent = (Number(data.shipping) > 0) ? formatCurrency(data.shipping) : 'Gratis';
                const totalEl = document.querySelector('#summary-total');
                if(totalEl) totalEl.textContent = formatCurrency(data.total ?? 0);
                const countEl = document.querySelector('#cart-count');
                if(countEl) countEl.textContent = data.count ?? 0;
                
                // Update auch den Header-Cart-Count
                const headerCartCountEls = document.querySelectorAll('.cart-count');
                headerCartCountEls.forEach(el => {
                    el.textContent = data.count ?? 0;
                });

                if((data.menge ?? 0) === 0){
                    const row = document.querySelector('[data-artikel-id="' + id + '"]');
                    if(row) {
                        // Nächstes <hr> Element finden und löschen
                        const nextHr = row.nextElementSibling;
                        if(nextHr && nextHr.tagName === 'HR') {
                            nextHr.remove();
                        }
                        // Artikel-Reihe löschen
                        row.remove();
                    }
                    if((data.count ?? 0) === 0){
                        const empty = document.querySelector('#cart-empty');
                        if(empty) empty.classList.remove('d-none');
                    }
                }
            }catch(err){
                console.error('AJAX-Warenkorb Fehler:', err);
            }
        }

        document.addEventListener('submit', function(e){
            const form = e.target;
            console.log('Submit event:', form.className);
            if(form && form.classList && form.classList.contains('ajax-cart')){
                console.log('AJAX-Cart form detected');
                e.preventDefault();
                submitAjax(form);
            }
        });

        // Handler für Versandart-Änderung
        const shippingMethodSelect = document.getElementById('shipping-method');
        if(shippingMethodSelect) {
            shippingMethodSelect.addEventListener('change', function() {
                const method = this.value;
                const shippingCosts = {
                    'lpd': 11.90,
                    'dhl': 6.90,
                    'dhl-express': 16.90
                };
                
                // Update Hidden Input für Checkout
                const hiddenInput = document.getElementById('shipping_method_input');
                if(hiddenInput) {
                    hiddenInput.value = method;
                }
                
                const newShippingCost = shippingCosts[method] || 0;
                const subtotalEl = document.querySelector('#summary-subtotal');
                const subtotal = subtotalEl ? parseFloat(subtotalEl.textContent.replace(' €', '').replace(',', '.')) : 0;
                const newTotal = subtotal + newShippingCost;
                
                // Update Versand und Gesamtbetrag
                const shippingEl = document.querySelector('#summary-shipping');
                if(shippingEl) {
                    shippingEl.textContent = newShippingCost.toFixed(2).replace('.', ',') + ' €';
                }
                
                const totalEl = document.querySelector('#summary-total');
                if(totalEl) {
                    totalEl.textContent = newTotal.toFixed(2).replace('.', ',') + ' €';
                }
            });
        }
    })();
    </script>
</body>
</html>
