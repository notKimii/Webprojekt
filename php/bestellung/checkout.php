<?php
ob_start();
include "../include/connectcon.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Promocode-Verarbeitung via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promo_code']) && isset($_POST['ajax'])) {
  ob_end_clean();
  header('Content-Type: application/json');
  
  try {
    $promoCode = trim($_POST['promo_code']);
    
    if (empty($promoCode)) {
      echo json_encode(['success' => false, 'message' => 'Bitte geben Sie einen Promocode ein.']);
      exit();
    }
    
    // Kunden-ID ermitteln
    $kundenId = null;
    if (isset($_SESSION['temp_user']['id'])) {
      $kundenId = (int)$_SESSION['temp_user']['id'];
    } elseif (isset($_SESSION['user']['id'])) {
      $kundenId = (int)$_SESSION['user']['id'];
    } elseif (isset($_SESSION['user_id'])) {
      $kundenId = (int)$_SESSION['user_id'];
    }
    
    if (!$kundenId) {
      echo json_encode(['success' => false, 'message' => 'Sie müssen angemeldet sein.']);
      exit();
    }
    
    // Prüfen ob Artikel existiert
    $stmt = $con->prepare("SELECT id, name, rabatt FROM artikel WHERE name = ? AND kategorie = 'Code' LIMIT 1");
    if (!$stmt) {
      echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $con->error]);
      exit();
    }
    
    $stmt->bind_param('s', $promoCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
      $artikel = $result->fetch_assoc();
      $artikelId = (int)$artikel['id'];
      $rabatt = (int)$artikel['rabatt'];
      
      // Warenkorb-ID ermitteln
      $stmt2 = $con->prepare("SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1");
      $stmt2->bind_param('i', $kundenId);
      $stmt2->execute();
      $res2 = $stmt2->get_result();
      
      if ($res2->num_rows > 0) {
        $warenkorbRow = $res2->fetch_assoc();
        $warenkorbId = (int)$warenkorbRow['id'];
        
        // Prüfen ob Promocode bereits im Warenkorb
        $stmt3 = $con->prepare("SELECT artikel_id FROM warenkorbposition WHERE warenkorb_id = ? AND artikel_id = ?");
        $stmt3->bind_param('ii', $warenkorbId, $artikelId);
        $stmt3->execute();
        $res3 = $stmt3->get_result();
        
        if ($res3->num_rows > 0) {
          echo json_encode(['success' => false, 'message' => 'Promocode bereits verwendet.']);
        } else {
          // Promocode zum Warenkorb hinzufügen
          $stmt4 = $con->prepare("INSERT INTO warenkorbposition (warenkorb_id, artikel_id, menge) VALUES (?, ?, 1)");
          $stmt4->bind_param('ii', $warenkorbId, $artikelId);
          if ($stmt4->execute()) {
            echo json_encode([
              'success' => true, 
              'message' => 'Promocode angewendet! ' . $rabatt . '% Rabatt',
              'reload' => true
            ]);
          } else {
            echo json_encode(['success' => false, 'message' => 'Fehler beim Anwenden: ' . $con->error]);
          }
          $stmt4->close();
        }
        $stmt3->close();
      } else {
        echo json_encode(['success' => false, 'message' => 'Warenkorb nicht gefunden.']);
      }
      $stmt2->close();
    } else {
      echo json_encode(['success' => false, 'message' => 'Ungültiger Promocode.']);
    }
    
    $stmt->close();
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
  }
  exit();
}

$promoMessage = '';
$promoError = '';

// Versandarten mit Kosten
$shippingMethods = [
    'lpd' => ['name' => 'LPD', 'cost' => 11.90],
    'dhl' => ['name' => 'DHL', 'cost' => 6.90],
    'dhl-express' => ['name' => 'DHL Express', 'cost' => 16.90]
];

// Rabattstaffeln berechnen basierend auf der höchsten Menge aller Artikel
function getDiscountRate($maxQuantity) {
    if ($maxQuantity >= 10) {
        return 0.10; // 10% ab Menge 10
    } elseif ($maxQuantity >= 5) {
        return 0.05; // 5% ab Menge 5
    }
    return 0.00; // Kein Rabatt
}

// Versandart aus POST oder Standard
$shippingMethod = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : 'dhl';
if (!isset($shippingMethods[$shippingMethod])) {
  $shippingMethod = 'dhl';
}

// determine customer id from session
$kundenId = null;
if (isset($_SESSION['temp_user']['id'])) {
  $kundenId = (int)$_SESSION['temp_user']['id'];
} elseif (isset($_SESSION['user']['id'])) {
  $kundenId = (int)$_SESSION['user']['id'];
} elseif (isset($_SESSION['user_id'])) {
  $kundenId = (int)$_SESSION['user_id'];
}

function loadCartData(mysqli $con, int $kundenId, string $shippingMethod = 'dhl'): array {
  global $shippingMethods;
  
  $sql = "SELECT wp.*, p.name, p.preis, p.kategorie, p.rabatt FROM warenkorbposition wp
      LEFT JOIN artikel p ON wp.artikel_id = p.id
      WHERE wp.warenkorb_id = (
        SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1
      )";
  $stmt = $con->prepare($sql);
  $stmt->bind_param('i', $kundenId);
  $stmt->execute();
  $res = $stmt->get_result();

  $items = [];
  $maxQuantity = 0;
  $subtotalVorRabatt = 0;
  $promoCodeItems = []; // Promocode-Artikel separat speichern
  
  // Erste Iteration: Alle Items laden und maximale Menge ermitteln
  while ($row = $res->fetch_assoc()) {
    $row['name'] = $row['name'] ?? 'Unbekanntes Produkt';
    $row['preis'] = $row['preis'] ?? 0.0;
    $row['menge'] = $row['menge'] ?? 0;
    $row['kategorie'] = $row['kategorie'] ?? '';
    
    // Prüfen ob es ein Promocode-Artikel ist
    if ($row['kategorie'] === 'Code') {
      $promoCodeItems[(int)$row['artikel_id']] = $row;
    } else {
      $items[(int)$row['artikel_id']] = $row;
      $maxQuantity = max($maxQuantity, (int)$row['menge']);
      $subtotalVorRabatt += $row['preis'] * $row['menge'];
    }
  }
  
  // Rabattsatz basierend auf maximaler Menge ermitteln
  $discountRate = getDiscountRate($maxQuantity);
  
  // Zweite Iteration: Mengenrabatt auf alle normalen Items anwenden
  $subtotal = 0;
  $totalDiscount = 0;
  
  foreach ($items as &$item) {
    $zeilensummeVorRabatt = $item['preis'] * $item['menge'];
    $rabattBetrag = $zeilensummeVorRabatt * $discountRate;
    $item['zeilensumme'] = $zeilensummeVorRabatt - $rabattBetrag;
    $item['rabatt_prozent'] = $discountRate * 100;
    $item['rabatt_betrag'] = $rabattBetrag;
    
    $subtotal += $item['zeilensumme'];
    $totalDiscount += $rabattBetrag;
  }
  
  // Promocode-Rabatte nacheinander anwenden
  $promoCodeDiscountAmount = 0;
  foreach ($promoCodeItems as &$promo) {
    $rabattProzent = (int)$promo['rabatt'];
    $rabattBetrag = $subtotal * ($rabattProzent / 100);
    $promo['rabatt_betrag'] = $rabattBetrag; // Speichere Betrag für Anzeige
    $subtotal -= $rabattBetrag;
    $promoCodeDiscountAmount += $rabattBetrag;
  }
  unset($promo); // Referenz aufheben
  
  $totalDiscount += $promoCodeDiscountAmount;
  
  // Versandkosten basierend auf Versandart
  $shipping = ($subtotal > 0 && isset($shippingMethods[$shippingMethod])) 
    ? $shippingMethods[$shippingMethod]['cost'] 
    : 0;
  $total = $subtotal + $shipping;

  return [
    'items' => $items,
    'promoCodeItems' => $promoCodeItems,
    'subtotal' => $subtotal,
    'totalDiscount' => $totalDiscount,
    'promoCodeDiscountAmount' => $promoCodeDiscountAmount,
    'shipping' => $shipping,
    'total' => $total,
    'count' => count($items),
    'shippingMethod' => $shippingMethod
  ];
}

$cartData = ['items'=>[], 'promoCodeItems'=>[], 'subtotal'=>0, 'totalDiscount'=>0, 'promoCodeDiscountAmount'=>0, 'shipping'=>0, 'total'=>0, 'count'=>0, 'shippingMethod'=>'dhl'];
if ($kundenId !== null) {
  $cartData = loadCartData($con, $kundenId, $shippingMethod);
}
// use a distinct variable name to avoid collisions with included files
$cartItems = array_values($cartData['items']);
$promoCodeItems = array_values($cartData['promoCodeItems']);
$subtotal = $cartData['subtotal'];
$totalDiscount = $cartData['totalDiscount'];
$promoCodeDiscountAmount = $cartData['promoCodeDiscountAmount'];
$shipping = $cartData['shipping'];
$total = $cartData['total'];
$count = $cartData['count'];

?>

<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      /* Optional: Eigene Styles hier */
      .site-max{ max-width:1400px; margin:0 auto; padding:0 1rem; }
      /* Abstand zwischen Header (sticky) und Seiteninhalt */
      main{ padding-top: 50px; }
      @media (max-width: 768px){ main{ padding-top: 24px; } }
    </style>
  </head>
  <body class="bg-light">
  <?php include "../include/headimport.php"; ?>
  <div class="site-max">
    <div class="container">
      <main>

        <?php if (isset($_GET['debug']) && $_GET['debug']): ?>
          <pre style="background:#f8f9fa;padding:12px;border:1px solid #e6e6e6;overflow:auto;"><?php var_dump($cartItems); ?></pre>
        <?php endif; ?>

        <div class="row g-5">
          <div class="col-md-5 col-lg-4 order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-primary">Your cart</span>
              <span class="badge bg-primary rounded-pill"><?php echo $count; ?></span>
            </h4>
            <ul class="list-group mb-3">
              <?php if (empty($cartItems)): ?>
                <li class="list-group-item">Dein Warenkorb ist leer.</li>
              <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                  <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                      <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                      <small class="text-muted">Artikel-Nr.: <?php echo (int)$item['artikel_id']; ?> &middot; Menge: <?php echo (int)$item['menge']; ?></small>
                      <?php if ($item['rabatt_betrag'] > 0): ?>
                        <small class="text-danger d-block">Rabatt: -<?php echo number_format($item['rabatt_betrag'], 2, ',', '.'); ?> € (<?php echo (int)$item['rabatt_prozent']; ?>%)</small>
                      <?php endif; ?>
                    </div>
                    <span class="text-muted"><?php echo number_format($item['zeilensumme'], 2, ',', '.'); ?> €</span>
                  </li>
                <?php endforeach; ?>
                <?php if ($totalDiscount > 0): ?>
                  <li class="list-group-item d-flex justify-content-between bg-light text-danger">
                    <span>Mengenrabatt</span>
                    <strong>-<?php echo number_format($totalDiscount - $promoCodeDiscountAmount, 2, ',', '.'); ?> €</strong>
                  </li>
                <?php endif; ?>
                <?php if ($promoCodeDiscountAmount > 0): ?>
                  <?php foreach ($promoCodeItems as $promo): ?>
                    <li class="list-group-item d-flex justify-content-between bg-success text-white">
                      <div>
                        <h6 class="my-0"><?php echo htmlspecialchars($promo['name']); ?></h6>
                        <small>Promocode-Rabatt: <?php echo (int)$promo['rabatt']; ?>%</small>
                      </div>
                      <strong>-<?php echo number_format($promo['rabatt_betrag'], 2, ',', '.'); ?> €</strong>
                    </li>
                  <?php endforeach; ?>
                <?php endif; ?>
                <li class="list-group-item d-flex justify-content-between bg-light">
                  <div class="text-success">
                    <h6 class="my-0">Versand</h6>
                    <small>Standard</small>
                  </div>
                  <span class="text-success"><?php echo ($shipping > 0) ? number_format($shipping, 2, ',', '.') . ' €' : 'Gratis'; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Gesamt</span>
                  <strong><?php echo number_format($total, 2, ',', '.'); ?> €</strong>
                </li>
              <?php endif; ?>
            </ul>

            <?php if (!empty($promoMessage)): ?>
              <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($promoMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($promoError)): ?>
              <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($promoError); ?></div>
            <?php endif; ?>

            <form class="card p-2" method="POST" id="promoForm">
              <div class="input-group">
                <input type="text" name="promo_code" class="form-control" placeholder="Promo code" required>
                <button type="submit" class="btn btn-secondary">Einlösen</button>
              </div>
              <div id="promoMessage" class="mt-2" style="display: none;"></div>
            </form>
          </div>
          <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Billing address</h4>
            <form class="needs-validation" novalidate>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="firstName" class="form-label">First name</label>
                  <input type="text" class="form-control" id="firstName" placeholder="" value="" required>
                  <div class="invalid-feedback">
                    Valid first name is required.
                  </div>
                </div>

                <div class="col-sm-6">
                  <label for="lastName" class="form-label">Last name</label>
                  <input type="text" class="form-control" id="lastName" placeholder="" value="" required>
                  <div class="invalid-feedback">
                    Valid last name is required.
                  </div>
                </div>

                <div class="col-12">
                  <label for="username" class="form-label">Username</label>
                  <div class="input-group has-validation">
                    <span class="input-group-text">@</span>
                    <input type="text" class="form-control" id="username" placeholder="Username" required>
                  <div class="invalid-feedback">
                      Your username is required.
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label">Email <span class="text-muted">(Optional)</span></label>
                  <input type="email" class="form-control" id="email" placeholder="you@example.com">
                  <div class="invalid-feedback">
                    Please enter a valid email address for shipping updates.
                  </div>
                </div>

                <div class="col-12">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" id="address" placeholder="1234 Main St" required>
                  <div class="invalid-feedback">
                    Please enter your shipping address.
                  </div>
                </div>

                <div class="col-12">
                  <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                  <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                </div>

                <div class="col-md-5">
                  <label for="country" class="form-label">Country</label>
                  <select class="form-select" id="country" required>
                    <option value="">Choose...</option>
                    <option>United States</option>
                  </select>
                  <div class="invalid-feedback">
                    Please select a valid country.
                  </div>
                </div>

                <div class="col-md-4">
                  <label for="state" class="form-label">State</label>
                  <select class="form-select" id="state" required>
                    <option value="">Choose...</option>
                    <option>California</option>
                  </select>
                  <div class="invalid-feedback">
                    Please provide a valid state.
                  </div>
                </div>

                <div class="col-md-3">
                  <label for="zip" class="form-label">Zip</label>
                  <input type="text" class="form-control" id="zip" placeholder="" required>
                  <div class="invalid-feedback">
                    Zip code required.
                  </div>
                </div>
              </div>

              <hr class="my-4">

              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="privacy-policy" required>
                <label class="form-check-label" for="privacy-policy">
                  Ich akzeptiere die Datenschutzerklärung und Allgemeinen Geschäftsbedingungen
                </label>
                <div class="invalid-feedback">
                  Sie müssen den Datenschutzbedingungen zustimmen.
                </div>
              </div>

              <hr class="my-4">

              <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php include "../include/footimport.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="form-validation.js"></script>
    <script>
      // Bootstrap form validation
      (function () {
        'use strict';
        window.addEventListener('load', function () {
          var forms = document.querySelectorAll('.needs-validation');
          Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        });
      })();
      
      // Promocode-Formular Handler
      document.addEventListener('DOMContentLoaded', function() {
        const promoForm = document.getElementById('promoForm');
        const promoMessage = document.getElementById('promoMessage');
        
        if (promoForm) {
          promoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(promoForm);
            formData.append('ajax', '1');
            
            fetch(window.location.pathname, {
              method: 'POST',
              body: formData
            })
            .then(response => {
              if (!response.ok) {
                return response.text().then(text => {
                  console.error('Server Error:', text);
                  throw new Error('Server-Fehler (Status: ' + response.status + ')');
                });
              }
              return response.json();
            })
            .then(data => {
              if (data.success) {
                promoMessage.textContent = data.message;
                promoMessage.className = 'mt-2 text-success fw-bold';
                promoMessage.style.display = 'block';
                
                // Seite neu laden wenn reload: true
                if (data.reload) {
                  setTimeout(function() {
                    window.location.reload();
                  }, 1000);
                }
              } else {
                promoMessage.textContent = data.message;
                promoMessage.className = 'mt-2 text-danger fw-bold';
                promoMessage.style.display = 'block';
              }
            })
            .catch(error => {
              console.error('Error:', error);
              promoMessage.textContent = 'Fehler: ' + error.message;
              promoMessage.className = 'mt-2 text-danger fw-bold';
              promoMessage.style.display = 'block';
            });
          });
        }
      });
    </script>
  </body>
</html>