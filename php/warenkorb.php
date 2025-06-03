<?php $kunden_id = $_SESSION['kunden_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Warenkorb - Mein Shop</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <?php include "include/headimport.php"; ?> 

    <style>
      
h1{
    font-size: 2rem;
}    

.cart-section {
    padding: calc(var(--spacing-unit) * 4) 0;
    background: var(--light-color);
    min-height: 70vh;
}

.cart-items-list {
    display: flex;
    flex-direction: column;
    gap: calc(var(--spacing-unit) * 1.5);
    margin-bottom: calc(var(--spacing-unit) * 3);
}

.cart-item {
    display: flex;
    align-items: center;
    gap: calc(var(--spacing-unit) * 1.5);
    background: #fff;
    border-radius: var(--border-radius-soft);
    border: 1px solid rgba(0,0,0,0.07);
    padding: calc(var(--spacing-unit) * 1.2);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.cart-item-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--border-radius-soft);
    border: 1px solid #e9e9e9;
}

.cart-item-info {
    flex: 1;
}

.cart-item-info h3 {
    margin-bottom: 0.4em;
    font-size: 1.1rem;
    font-weight: 600;
}

.cart-item-price {
    color: var(--primary-color);
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.5em;
}

.cart-item-qty {
    display: flex;
    align-items: center;
    gap: 8px;
}

.cart-item-qty input[type="number"] {
    width: 50px;
    padding: 4px 6px;
    border-radius: var(--border-radius-pill);
    border: 1px solid #ccc;
    font-size: 1rem;
}

.remove-cart-item {
    background: var(--light-color);
    color: var(--secondary-color);
    border: none;
    padding: 7px 12px;
    border-radius: var(--border-radius-soft);
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.2s;
}
.remove-cart-item:hover {
    background: #eee;
    color: var(--primary-color);
}

.cart-summary {
    background: #fff;
    border-radius: var(--border-radius-soft);
    border: 1px solid rgba(0,0,0,0.07);
    padding: calc(var(--spacing-unit) * 2);
    max-width: 350px;
    margin-left: auto;
}

.cart-summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.7em 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 1rem;
}

.cart-summary-total {
    font-size: 1.15rem;
    border-bottom: none;
    margin-top: 1em;
    margin-bottom: 1em;
}

.cart-summary .cta-button {
    width: 100%;
    margin-top: 1em;
    font-size: 1.05rem;
    padding: 12px 0;
}

    </style>
</head>
<body>
    <main>
        <section class="cart-section">
            <div class="container">
                <h1>Dein Warenkorb</h1>
                <div class="cart-items-list">
                    <!-- Beispielprodukt 1 -->
                    <div class="cart-item">
                        <img src="produkt1.jpg" alt="Produkt 1" class="cart-item-img">
                        <div class="cart-item-info">
                            <h3>Flugzeugmodell A320</h3>
                            <p class="cart-item-price">59,99 €</p>
                            <div class="cart-item-qty">
                                <label>Menge:</label>
                                <input type="number" value="1" min="1">
                            </div>
                        </div>
                        <button class="remove-cart-item">Entfernen</button>
                    </div>
                    <!-- Beispielprodukt 2 -->
                    <div class="cart-item">
                        <img src="produkt2.jpg" alt="Produkt 2" class="cart-item-img">
                        <div class="cart-item-info">
                            <h3>Pilotenbrille Classic</h3>
                            <p class="cart-item-price">39,99 €</p>
                            <div class="cart-item-qty">
                                <label>Menge:</label>
                                <input type="number" value="1" min="1">
                            </div>
                        </div>
                        <button class="remove-cart-item">Entfernen</button>
                    </div>
                </div>
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="cart-summary-row">
                        <span>Zwischensumme</span>
                        <span>99,98 €</span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Versand</span>
                        <span>4,99 €</span>
                    </div>
                    <div class="cart-summary-row cart-summary-total">
                        <strong>Gesamt</strong>
                        <strong>104,97 €</strong>
                    </div>
                    <?php if (!empty($positionen)): ?>
                        <form action="bestellung_abschliessen.php" method="post">
                            <button type="submit">✅ Jetzt kaufen</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php include "include/footimport.php"; ?>
</body>
</html>
