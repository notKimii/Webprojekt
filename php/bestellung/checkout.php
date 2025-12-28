<?php
ob_start();
include "../include/connectcon.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Versandarten mit Kosten
$shippingMethods = [
    'lpd' => ['name' => 'LPD', 'cost' => 11.90],
    'dhl' => ['name' => 'DHL', 'cost' => 6.90],
    'dhl-express' => ['name' => 'DHL Express', 'cost' => 16.90]
];

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
    $row['name'] = $row['name'] ?? 'Unbekanntes Produkt';
    $row['preis'] = $row['preis'] ?? 0.0;
    $row['zeilensumme'] = $row['preis'] * $row['menge'];
    $subtotal += $row['zeilensumme'];
    $items[] = $row;
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
    'count' => count($items),
    'shippingMethod' => $shippingMethod
  ];
}

$cartData = ['items'=>[], 'subtotal'=>0, 'shipping'=>0, 'total'=>0, 'count'=>0, 'shippingMethod'=>'dhl'];
if ($kundenId !== null) {
  $cartData = loadCartData($con, $kundenId, $shippingMethod);
}
// use a distinct variable name to avoid collisions with included files
$cartItems = $cartData['items'];
$subtotal = $cartData['subtotal'];
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
                    </div>
                    <span class="text-muted"><?php echo number_format($item['zeilensumme'], 2, ',', '.'); ?> €</span>
                  </li>
                <?php endforeach; ?>
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

            <form class="card p-2">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Promo code">
                <button type="submit" class="btn btn-secondary">Redeem</button>
              </div>
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
                <input type="checkbox" class="form-check-input" id="same-address">
                <label class="form-check-label" for="same-address">Shipping address is the same as my billing address</label>
              </div>

              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="save-info">
                <label class="form-check-label" for="save-info">Save this information for next time</label>
              </div>

              <hr class="my-4">

              <h4 class="mb-3">Payment</h4>

              <div class="my-3">
                <div class="form-check">
                  <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
                  <label class="form-check-label" for="credit">Credit card</label>
                </div>
                <div class="form-check">
                  <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
                  <label class="form-check-label" for="debit">Debit card</label>
                </div>
                <div class="form-check">
                  <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required>
                  <label class="form-check-label" for="paypal">PayPal</label>
                </div>
              </div>

              <div class="row gy-3">
                <div class="col-md-6">
                  <label for="cc-name" class="form-label">Name on card</label>
                  <input type="text" class="form-control" id="cc-name" placeholder="" required>
                  <small class="text-muted">Full name as displayed on card</small>
                  <div class="invalid-feedback">
                    Name on card is required
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="cc-number" class="form-label">Credit card number</label>
                  <input type="text" class="form-control" id="cc-number" placeholder="" required>
                  <div class="invalid-feedback">
                    Credit card number is required
                  </div>
                </div>

                <div class="col-md-3">
                  <label for="cc-expiration" class="form-label">Expiration</label>
                  <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                  <div class="invalid-feedback">
                    Expiration date required
                  </div>
                </div>

                <div class="col-md-3">
                  <label for="cc-cvv" class="form-label">CVV</label>
                  <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                  <div class="invalid-feedback">
                    Security code required
                  </div>
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
  </body>
</html>