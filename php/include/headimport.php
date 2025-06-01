<?php
session_start();
?>
    <link rel="stylesheet" href="../style.css">
    
    <div class="announcement-bar">
        <p>🎉 Sommer Sale: Bis zu 50% Rabatt auf ausgewählte Artikel! Nur für kurze Zeit! | Kostenloser Versand ab 50€ Bestellwert 🎉</p>
    </div>


    <header>
    <div class="header-top">
        <div class="container">
            <div class="header-top-left">
                <a href="/hilfe">Hilfe & FAQ</a> |
                <a href="/versand">Versand & Lieferung</a>
            </div>
            <div class="header-top-right">
                <a href="/mein-konto">Mein Konto</a> |
                <a href="/wunschliste">Wunschliste ❤️</a>
            </div>
        </div>
    </div>

    <div class="header-main">
        <div class="container">
            <div class="logo">
                <a href="/">
                    <img src="images/pictures/logo_grey.png" alt="CockpitCornerLogo">
                </a>
            </div>

            <div class="search-bar">
                <form action="/suche" method="get">
                    <input type="search" name="query" placeholder="Produkte suchen...">
                    <button type="submit">Suchen</button>
                </form>
            </div>

            <div class="header-actions">
                <div class="header-action-item">
                        <?php if (isset($_SESSION['temp_user'])): ?>
                            <a href="/mein-konto.php" id="login-button">
                                <p>Mein Konto</p>
                            </a>y
                        <?php else: ?>
                            <a href="/login.html" id="login-button">
                                <p>Anmelden</p>
                            </a>
                        <?php endif; ?>
                </div>
               <div class="header-action-item">
                    <a href="./php/cart.php" id="cart-button">
                        <p>Warenkorb</p>
                    <span class="cart-count">0</span> 
                    </a>
              </div>
        </div>
    </div>

    <nav class="main-navigation">
        <div class="container">
            <ul>
                <li><a href="/kategorie/neuheiten">Neuheiten</a></li>
                <li><a href="/Webprojekt/php/headsets.php">Headsets</a></li>
                <li><a href="/Webprojekt/php/navigationkategorie.php">Navigation</a></li>
                <li><a href="/Webprojekt/php/pilotenkleidung.php">Kleidung & Accessoires</a></li>
                <li><a href="/angebote">Flugtaschen</a></li>
                <li><a href="/angebote">Lernmaterial</a></li>
                <li><a href="/kategorie/kategorie-3">Flugzeugzubehör</a></li>
                <li><a href="/angebote">Sicherheitsaustrüstung</a></li>
                <li><a href="/ueber-uns">Über uns</a></li>
            </ul>
        </div>
    </nav>
</header>

