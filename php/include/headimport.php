<?php
session_start();
?>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    
    <div class="announcement-bar">
        <p>🎉 Sommer Sale: Bis zu 50% Rabatt auf ausgewählte Artikel! Nur für kurze Zeit! <span class="extra-text">| Kostenloser Versand ab 50€ Bestellwert 🎉</span></p>
    </div>

    <header>
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
                                <p>Mein Konto</p>
                            </a>
                        <?php else: ?>
                            <a href="/Webprojekt/php/login/loginformular.php" id="login-button">
                                <p>Anmelden</p>
                            </a>
                        <?php endif; ?>
                </div>
               <div class="header-action-item">
                    <a href="/Webprojekt/php/bestellung/warenkorb.php" id="cart-button">
                        <p>Warenkorb</p>
                    <span class="cart-count">0</span> 
                    </a>
              </div>
              <div class="header-action-item">
                    <a href="/Webprojekt/php/Kundenkonto.php" id="cart-button">
                    <span alt="Punkte">⭐</span>
                    <?php
            
                    if (isset($_SESSION['temp_user'])) {
                        // Datenbankverbindung herstellen
                        $con = new mysqli('localhost', 'root', '', 'dbpilotenshop');

                        // Fehler abfangen
                        if ($con->connect_error) {
                            die("Verbindung fehlgeschlagen: " . $con->connect_error);
                        }


                        $userID = $_SESSION['temp_user']['id'];
            
                        $stmt = $con->prepare("SELECT punktestand FROM punkte WHERE user_id = ?");
                        $stmt->bind_param("i", $userID); 
                        $stmt->execute();
                        $stmt->bind_result($punktestand);
                        $stmt->fetch();

                        //$stmt->close();
                        //$con->close();
                        echo $punktestand;
                    } else {
                        echo "-";
                    }
                    ?>
                   </a>
                   <a href="/Webprojekt/php/Kundenkonto.php" id="online-button">
                   <?php
                     if (isset($_SESSION['temp_user'])) {
                        include 'connectcon.php';

                        $result = $con->query("SELECT COUNT(*) AS anzahl FROM user WHERE online=1");

                        if ($result) {
                            $row = $result->fetch_assoc();
                            $anzahl = $row['anzahl'];
                        }
                    }

                    ?>
                    </a>
              </div>
        </div>
    </div>
    
    <nav class="main-navigation">
        <div class="container">
            <ul>
                <li><a href="/Webprojekt/php/kategorien/saleskategorie.php">Sales</a></li>
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

