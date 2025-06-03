<?php
session_start();
?>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    
    <div class="announcement-bar">
        🎉 Sommer Sale: Bis zu 50% Rabatt auf ausgewählte Artikel! Nur für kurze Zeit! <span class="extra-text">| Kostenloser Versand ab 50€ Bestellwert 🎉</span>
    </div>


    <!-- <header>
    <div class="header-top">
        <div class="container">
            <div class="header-top-left">
                <a href="/hilfe">Hilfe & FAQ</a> 
                <a href="/versand">Versand & Lieferung</a>
            </div>
            <div class="header-top-right">
                <a href="/mein-konto">Mein Konto</a>
                <a href="/wunschliste">Wunschliste ❤️</a>
            </div>
        </div>
    </div> -->

    <div class="header-main">
        <div class="container">
            <div class="logo">
                <a href="/Webprojekt/index.php">
                    <img src="/Webprojekt/images/pictures/logo_grey.png" alt="CockpitCornerLogo">
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
                                Mein Konto
                            </a>
                        <?php else: ?>
                            <a href="/Webprojekt/loginformular.php" id="login-button">
                                Anmelden
                            </a>
                        <?php endif; ?>
                </div>
               <div class="header-action-item">
                    <a href="/Webprojekt/php/warenkorb.php" id="cart-button">
                        Warenkorb
                    <span class="cart-count">0</span> 
                    </a>
              </div>
        </div>
    </div>

    <nav class="main-navigation">
        <div class="container">
            <ul>
                <li><a href="/kategorie/neuheiten">Neuheiten</a></li>
                <li><a href="/Webprojekt/php/kategorien/headsetskategorie.php">Headsets</a></li>
                <li><a href="/Webprojekt/php/kategorien/navigationkategorie.php">Navigation</a></li>
                <li><a href="/Webprojekt/php/kategorien/kleidungkategorie.php">Kleidung & Accessoires</a></li>
                <li><a href="/Webprojekt/php/kategorien/flugtaschenkategorie.php">Flugtaschen</a></li>
                <li><a href="/Webprojekt/php/kategorien/lernmaterialkategorie.php">Lernmaterial</a></li>
                <li><a href="/webprojekt/php/kategorien/zubehoerkategorie.php">Flugzeugzubehör</a></li>
                <li><a href="/webprojekt/php/kategorien/sicherheitkategorie.php">Sicherheitsaustrüstung</a></li>
                <li><a href="/ueber-uns">Über uns</a></li>
            </ul>
        </div>
    </nav>
</header>

