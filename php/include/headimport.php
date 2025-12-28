<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/connectcon.php';
?>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    
    <style>
    /* Basis Header Styles */
    .announcement-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        text-align: center;
        padding: 10px 15px;
        font-size: 14px;
    }

    .announcement-bar p {
        margin: 0;
        font-weight: 500;
    }

    header {
        position: sticky;
        top: 0;
        z-index: 100;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .header-main {
        background: #fff;
    }

    .header-main .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .logo {
        display: flex;
        align-items: center;
    }

    .logo img {
        max-height: 85px;
        width: auto;
        transition: transform 0.3s ease;
    }

    .logo img:hover {
        transform: scale(1.05);
    }

    /* Desktop Search Bar */
    .desktop-search {
        flex: 1;
        max-width: 500px;
        margin: 0 30px;
    }

    /* Suche diesen Block: */
    .desktop-search form {
        display: flex !important;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        transition: border-color 0.3s ease;
        position: relative;
    }

    .desktop-search form:focus-within {
        border-color: #667eea;
    }

    .desktop-search input[type="search"] {
        flex: 1 !important;
        padding: 12px 20px !important;
        border: none !important;
        outline: none !important;
        font-size: 11px !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        height: auto !important;
        min-height: auto !important;
        line-height: 1.5 !important;
    }

    .desktop-search input[type="search"]::-webkit-input-placeholder {
        font-size: 11px !important;
    }

    .desktop-search input[type="search"]::placeholder {
        font-size: 11px !important;
    }

    .desktop-search button {
        padding: 12px 25px !important;
        background: #667eea !important;
        color: white !important;
        border: none !important;
        cursor: pointer;
        font-weight: 600 !important;
        transition: background 0.3s ease !important;
        font-size: 11px !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        height: auto !important;
        min-height: auto !important;
        line-height: 1.5 !important;
    }

    .desktop-search button:hover {
        background: #5568d3;
    }

    /* AGGRESSIVE BOOTSTRAP OVERRIDE */
    .desktop-search .search-wrapper {
        flex: 1 !important;
        display: flex !important;
    }

    .desktop-search form input {
        font-size: 11px !important;
    }

    /* Desktop Header Actions */
    .desktop-actions {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .header-action-item a {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #333;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        /* Fix: sichtbare Icons/Labels, falls andere Styles die Farbe überschreiben */
        color: #333 !important;
    }

    .header-action-item a svg {
        color: inherit;
        stroke: currentColor;
    }

    .header-action-item a:hover {
        background: #f5f5f5;
        color: #667eea;
    }

    .header-action-item svg {
        width: 22px;
        height: 22px;
    }

    .cart-count {
        background: #007aff;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: bold;
        min-width: 18px;
        text-align: center;
    }

    .points-badge {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #333;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: bold;
        min-width: 18px;
        text-align: center;
    }

    .online-users {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: bold;
        min-width: 18px;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
    }

    .online-indicator {
        width: 5px;
        height: 5px;
        background: #fff;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { 
            opacity: 1;
        }
        50% { 
            opacity: 0.5;
        }
    }

    /* Burger Menu Button */
    .burger-menu {
        display: none;
        flex-direction: column;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .burger-menu:hover {
        background: #f5f5f5;
    }

    .burger-menu span {
        width: 28px;
        height: 3px;
        background-color: #333;
        margin: 3px 0;
        transition: all 0.3s ease;
        border-radius: 3px;
    }

    .burger-menu.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-7px, 6px);
        background-color: #667eea;
    }

    .burger-menu.active span:nth-child(2) {
        opacity: 0;
    }

    .burger-menu.active span:nth-child(3) {
        transform: rotate(45deg) translate(-7px, -6px);
        background-color: #667eea;
    }

    /* Desktop Navigation */
    .desktop-navigation {
        background: #f8f9fa;
        border-top: 1px solid #e0e0e0;
    }

    .desktop-navigation .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .desktop-navigation ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        justify-content: center;
        flex-wrap: wrap;
    }

    .desktop-navigation ul li a {
        display: block;
        padding: 15px 20px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }

    .desktop-navigation ul li a:hover {
        color: #667eea;
        border-bottom-color: #667eea;
        background: #fff;
    }

    /* Mobile Navigation Overlay */
    .mobile-nav-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mobile-nav-overlay.active {
        display: block;
        opacity: 1;
    }

    /* Mobile Menu */
    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 320px;
        max-width: 85%;
        height: 100vh;
        background: #fff;
        box-shadow: -5px 0 20px rgba(0, 0, 0, 0.2);
        transition: right 0.3s ease-in-out;
        z-index: 999;
        overflow-y: auto;
    }

    .mobile-menu.active {
        right: 0;
    }

    .mobile-menu-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mobile-menu-header h3 {
        margin: 0;
        font-size: 18px;
    }

    .mobile-menu-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 28px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .mobile-menu-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Mobile Menu Content */
    .mobile-menu-content {
        padding: 20px;
    }

    /* Mobile Search */
    .mobile-search {
        margin-bottom: 25px;
    }

    /* Suche diesen Block: */
    .mobile-search form {
        display: flex;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        position: relative; 
    }

    .mobile-search input[type="search"] {
        flex: 1;
        padding: 12px 15px;
        border: none;
        outline: none;
        font-size: 14px;
    }

    .mobile-search button {
        padding: 12px 20px;
        background: #667eea;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    /* Mobile User Section */
    .mobile-user-section {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .mobile-user-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        margin-bottom: 8px;
        background: white;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
    }

    .mobile-user-item:last-child {
        margin-bottom: 0;
    }

    .mobile-user-item:hover {
        border-color: #667eea;
        transform: translateX(5px);
    }

    .mobile-user-item svg {
        width: 24px;
        height: 24px;
        color: #667eea;
        flex-shrink: 0;
    }

    .mobile-user-item .item-content {
        flex: 1;
    }

    .mobile-user-item .item-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 2px;
    }

    .mobile-user-item .item-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .mobile-user-item .badge {
        background: #667eea;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
        flex-shrink: 0;
    }

    .mobile-user-item .points-badge-mobile {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #333;
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: bold;
        font-size: 14px;
        flex-shrink: 0;
    }

    .mobile-user-item .online-badge {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        padding: 6px 12px;
        border-radius: 15px;
        font-weight: bold;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
    }

    .mobile-user-item .online-indicator {
        width: 8px;
        height: 8px;
        background: #fff;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Mobile Navigation Links */
    .mobile-nav-section {
        margin-bottom: 20px;
    }

    .mobile-nav-section h4 {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        padding-left: 5px;
    }

    .mobile-nav-links {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .mobile-nav-links li {
        margin-bottom: 5px;
    }

    .mobile-nav-links li a {
        display: flex;
        align-items: center;
        padding: 14px 15px;
        color: #333;
        text-decoration: none;
        background: #f8f9fa;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .mobile-nav-links li a:hover {
        background: #667eea;
        color: white;
        border-left-color: #764ba2;
        padding-left: 20px;
    }

    /* Tablet and Mobile Responsive */
    @media screen and (max-width: 1024px) {
        .desktop-search {
            max-width: 350px;
            margin: 0 20px;
        }

        .desktop-navigation ul {
            gap: 5px;
        }

        .desktop-navigation ul li a {
            padding: 15px;
            font-size: 13px;
        }
    }

    @media screen and (max-width: 768px) {
        .announcement-bar {
            font-size: 12px;
            padding: 8px 10px;
        }

        .announcement-bar .extra-text {
            display: none;
        }

        .header-main .container {
            padding: 20px 15px;
            min-height: 70px;
            align-items: center;
        }

        /* Logo größer und vertikal mittig auf Mobile */
        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            max-height: 60px;
            display: block;
        }

        /* Burger Menu rechts und vertikal mittig */
        .burger-menu {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .desktop-search,
        .desktop-actions,
        .desktop-navigation {
            display: none !important;
        }

        .mobile-menu {
            display: block;
        }
    }

    @media screen and (max-width: 480px) {
        .mobile-menu {
            width: 100%;
            max-width: 100%;
        }

        .header-main .container {
            padding: 18px 15px;
            min-height: 65px;
        }

        .logo img {
            max-height: 55px;
        }
    }

    @media screen and (max-width: 360px) {
        .header-main .container {
            padding: 15px 12px;
            min-height: 60px;
        }

        .logo img {
            max-height: 50px;
        }
    }
    /* Live-Suche Vorschlagsliste */
    .search-wrapper {
        position: relative; /* Wichtig, damit die Liste am Input klebt */
        width: 100%;
    }

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 1000;
        display: none; /* Standardmäßig versteckt */
        overflow: hidden;
    }

    .suggestion-item {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background-color: #f5f5f5;
        color: #667eea;
    }

    .suggestion-price {
        font-weight: bold;
        color: #667eea;
    }
    /* Button Ecken rechts abrunden */
    .desktop-search button, 
    .mobile-search button {
        border-radius: 0 22px 22px 0; /* Rechts oben/unten rund */
    }

    /* Input Feld Ecken links abrunden */
    .desktop-search input[type="search"],
    .mobile-search input[type="search"] {
        border-radius: 22px 0 0 22px; /* Links oben/unten rund */
    }
    </style>

    <div class="announcement-bar">
        <p>🎉 Sommer Sale: Bis zu 25% Rabatt auf ausgewählte Artikel! Nur für kurze Zeit! <span class="extra-text">| Rabattcode: SOMMER25 🎉</span></p>
    </div>

    <header>
        <!-- Header Main -->
        <div class="header-main">
            <div class="container">
                <div class="logo">
                    <a href="/Webprojekt/index.php">
                        <img src="/Webprojekt/images/pictures/logo_grey.png" alt="CockpitCornerLogo">
                    </a>
                </div>

                <!-- Desktop Search -->
                <div class="desktop-search">
                    <form action="/Webprojekt/php/suche.php" method="GET">
                        <div class="search-wrapper">
                            <input type="search" name="q" id="desktopSearchInput" placeholder="Produkte suchen..." autocomplete="off" required>
                            <div id="desktopSuggestions" class="search-suggestions"></div>
                        </div>
                        <button type="submit">Suchen</button>
                    </form>
                </div>

                <!-- Desktop Actions -->
                <div class="desktop-actions">
                    <div class="header-action-item">
                        <?php if (isset($_SESSION['temp_user'])): ?>
                            <a href="/Webprojekt/php/Kundenkonto.php">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Mein Konto</span>
                            </a>
                        <?php else: ?>
                            <a href="/Webprojekt/php/login/loginformular.php">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3"></path>
                                </svg>
                                <span>Anmelden</span>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="header-action-item">
                        <a href="/Webprojekt/php/bestellung/warenkorb.php">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span>Warenkorb</span>
                            <span class="cart-count"><?php
                                $cartCount = 10;
                                if (isset($_SESSION['temp_user'])) {
                                    $userID_cart = (int)$_SESSION['temp_user']['id'];
                                    if (isset($con) && !$con->connect_error) {
                                        $sql = "SELECT COUNT(wp.artikel_id) AS cnt
                                                FROM warenkorbkopf wk
                                                LEFT JOIN warenkorbposition wp ON wp.warenkorb_id = wk.id
                                                WHERE wk.kunde_id = ?";
                                        $stmt = $con->prepare($sql);
                                        if ($stmt) {
                                            $stmt->bind_param('i', $userID_cart);
                                            $stmt->execute();
                                            $stmt->bind_result($cnt);
                                            $stmt->fetch();
                                            $cartCount = (int)($cnt ?? 0);
                                            $stmt->close();
                                        }
                                    }
                                }
                                echo $cartCount;
                            ?></span>
                        </a>
                    </div>
                    
                    <div class="header-action-item">
                        <a href="/Webprojekt/php/punkte_historie.php">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <span>Punkte</span>
                            <span class="points-badge">
                                <?php
                                if (isset($_SESSION['temp_user'])) {
                                    if (isset($con) && !$con->connect_error) {
                                        $userID = $_SESSION['temp_user']['id'];
                                        $stmt = $con->prepare("SELECT punktestand FROM punkte WHERE user_id = ?");
                                        if ($stmt) {
                                            $stmt->bind_param("i", $userID);
                                            $stmt->execute();
                                            $stmt->bind_result($punktestand);
                                            $stmt->fetch();
                                            echo $punktestand ?? '0';
                                            $stmt->close();
                                        } else {
                                            echo '0';
                                        }
                                    } else {
                                        echo '0';
                                    }
                                } else {
                                    echo '0';
                                }
                                ?>
                            </span>
                        </a>
                    </div>
                    <div class="header-action-item">
                        <a href="">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Online</span>
                            <span class="online-users">
                                <span class="online-indicator"></span>
                                <?php
                                if (isset($_SESSION['temp_user'])) {
                                    if (isset($con) && !$con->connect_error) {
                                        $result = $con->query("SELECT COUNT(*) AS anzahl FROM user WHERE online=1");
                                        if ($result) {
                                            $row = $result->fetch_assoc();
                                            echo $row['anzahl'];
                                        }
                                    } else {
                                        echo '0';
                                    }
                                } else {
                                    echo '0';
                                }
                                ?>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Burger Menu Button -->
                <div class="burger-menu" id="burgerMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <nav class="desktop-navigation">
            <div class="container">
                <ul>
                    <li><a href="/Webprojekt/php/kategorien/saleskategorie.php">🔥 Sales</a></li>
                    <li><a href="/Webprojekt/php/kategorien/headsetskategorie.php">Headsets</a></li>
                    <li><a href="/Webprojekt/php/kategorien/navigationkategorie.php">Navigation</a></li>
                    <li><a href="/Webprojekt/php/kategorien/kleidungkategorie.php">Kleidung & Accessoires</a></li>
                    <li><a href="/Webprojekt/php/kategorien/flugtaschenkategorie.php">Flugtaschen</a></li>
                    <li><a href="/Webprojekt/php/kategorien/lernmaterialkategorie.php">Lernmaterial</a></li>
                    <li><a href="/webprojekt/php/kategorien/zubehoerkategorie.php">Flugzeugzubehör</a></li>
                    <li><a href="/webprojekt/php/kategorien/sicherheitkategorie.php">Sicherheitsausrüstung</a></li>
                    <li><a href="/ueber-uns">Über uns</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <h3>Menü</h3>
            <button class="mobile-menu-close" id="mobileMenuClose">&times;</button>
        </div>

        <div class="mobile-menu-content">
            <!-- Mobile Search -->
            <div class="mobile-search">
                <form action="/Webprojekt/php/suche.php" method="GET">
                    <div class="search-wrapper">
                        <input type="search" name="q" id="mobileSearchInput" placeholder="Produkte suchen..." autocomplete="off" required>
                        <div id="mobileSuggestions" class="search-suggestions"></div>
                    </div>
                    <button type="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Mobile User Section -->
            <div class="mobile-user-section">
                <?php if (isset($_SESSION['temp_user'])): ?>
                    <a href="/mein-konto.php" class="mobile-user-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <div class="item-content">
                            <div class="item-label">Konto</div>
                            <div class="item-value">Mein Konto</div>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="/Webprojekt/php/login/loginformular.php" class="mobile-user-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3"></path>
                        </svg>
                        <div class="item-content">
                            <div class="item-label">Nicht angemeldet</div>
                            <div class="item-value">Jetzt anmelden</div>
                        </div>
                    </a>
                <?php endif; ?>

                <a href="/Webprojekt/php/bestellung/warenkorb.php" class="mobile-user-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <div class="item-content">
                        <div class="item-label">Einkaufswagen</div>
                        <div class="item-value">Warenkorb</div>
                    </div>
                    <span class="badge"><?php
                        $cartCount_mobile = 0;
                        if (isset($_SESSION['temp_user'])) {
                            $userID_cart = (int)$_SESSION['temp_user']['id'];
                            if (isset($con) && !$con->connect_error) {
                                $sql = "SELECT COUNT(wp.artikel_id) AS cnt
                                        FROM warenkorbkopf wk
                                        LEFT JOIN warenkorbposition wp ON wp.warenkorb_id = wk.id
                                        WHERE wk.kunde_id = ?";
                                $stmt = $con->prepare($sql);
                                if ($stmt) {
                                    $stmt->bind_param('i', $userID_cart);
                                    $stmt->execute();
                                    $stmt->bind_result($cnt);
                                    $stmt->fetch();
                                    $cartCount_mobile = (int)($cnt ?? 0);
                                    $stmt->close();
                                }
                            }
                        }
                        echo $cartCount_mobile;
                    ?></span>
                </a>

                <a href="/Webprojekt/php/Kundenkonto.php" class="mobile-user-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <div class="item-content">
                        <div class="item-label">Treuepunkte</div>
                        <div class="item-value">Dein Punktestand</div>
                    </div>
                    <span class="points-badge-mobile">
                        <?php
                        if (isset($_SESSION['temp_user'])) {
                            if (isset($con) && !$con->connect_error) {
                                $userID = $_SESSION['temp_user']['id'];
                                $stmt = $con->prepare("SELECT punktestand FROM punkte WHERE user_id = ?");
                                if ($stmt) {
                                    $stmt->bind_param("i", $userID);
                                    $stmt->execute();
                                    $stmt->bind_result($punktestand);
                                    $stmt->fetch();
                                    echo $punktestand ?? '0';
                                    $stmt->close();
                                } else {
                                    echo '0';
                                }
                            } else {
                                echo '0';
                            }
                        } else {
                            echo '0';
                        }
                        ?>
                    </span>
                </a>

                <a href="/Webprojekt/php/Kundenkonto.php" class="mobile-user-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <div class="item-content">
                        <div class="item-label">Community</div>
                        <div class="item-value">Aktive User</div>
                    </div>
                    <span class="online-badge">
                        <span class="online-indicator"></span>
                        <?php
                        if (isset($_SESSION['temp_user'])) {
                            if (isset($con) && !$con->connect_error) {
                                $result = $con->query("SELECT COUNT(*) AS anzahl FROM user WHERE online=1");
                                if ($result) {
                                    $row = $result->fetch_assoc();
                                    echo $row['anzahl'];
                                }
                            } else {
                                echo '0';
                            }
                        } else {
                            echo '0';
                        }
                        ?>
                    </span>
                </a>
            </div>

            <!-- Mobile Navigation -->
            <div class="mobile-nav-section">
                <h4>Kategorien</h4>
                <ul class="mobile-nav-links">
                    <li><a href="/Webprojekt/php/kategorien/saleskategorie.php">🔥 Sales & Angebote</a></li>
                    <li><a href="/Webprojekt/php/kategorien/headsetskategorie.php">Headsets</a></li>
                    <li><a href="/Webprojekt/php/kategorien/navigationkategorie.php">Navigation</a></li>
                    <li><a href="/Webprojekt/php/kategorien/kleidungkategorie.php">Kleidung & Accessoires</a></li>
                    <li><a href="/Webprojekt/php/kategorien/flugtaschenkategorie.php">Flugtaschen</a></li>
                    <li><a href="/Webprojekt/php/kategorien/lernmaterialkategorie.php">Lernmaterial</a></li>
                    <li><a href="/webprojekt/php/kategorien/zubehoerkategorie.php">Flugzeugzubehör</a></li>
                    <li><a href="/webprojekt/php/kategorien/sicherheitkategorie.php">Sicherheitsausrüstung</a></li>
                </ul>
            </div>

            <div class="mobile-nav-section">
                <h4>Informationen</h4>
                <ul class="mobile-nav-links">
                    <li><a href="/ueber-uns">Über uns</a></li>
                    <li><a href="/kontakt">Kontakt</a></li>
                    <li><a href="/hilfe">Hilfe & Support</a></li>
                </ul>
            </div>
        </div>
    </div>

<script>
// Mobile Menu Funktionalität
document.addEventListener('DOMContentLoaded', function() {
    const burgerMenu = document.getElementById('burgerMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    const body = document.body;

    function openMenu() {
        burgerMenu.classList.add('active');
        mobileMenu.classList.add('active');
        mobileNavOverlay.classList.add('active');
        body.style.overflow = 'hidden';
    }

    function closeMenu() {
        burgerMenu.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileNavOverlay.classList.remove('active');
        body.style.overflow = '';
    }

    burgerMenu.addEventListener('click', function() {
        if (mobileMenu.classList.contains('active')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    mobileMenuClose.addEventListener('click', closeMenu);
    mobileNavOverlay.addEventListener('click', closeMenu);

    // Schließt Menü bei Klick auf Links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });

    // Schließt bei Fenstergrößenänderung
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && mobileMenu.classList.contains('active')) {
            closeMenu();
        }
    });

    // ESC-Taste zum Schließen
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMenu();
        }
    });



    updateCartCount();
});

// --- DEBUG LIVE SUCHE START ---
    
    function setupLiveSearch(inputId, suggestionsId) {
        const input = document.getElementById(inputId);
        const suggestionsBox = document.getElementById(suggestionsId);

        // Debugging: Prüfen ob Elemente gefunden wurden
        if (!input) {
            console.error("FEHLER: Input-Feld mit ID '" + inputId + "' nicht gefunden!");
            return;
        }
        if (!suggestionsBox) {
            console.error("FEHLER: Vorschlags-Box mit ID '" + suggestionsId + "' nicht gefunden!");
            return;
        }

        input.addEventListener('input', function() {
            const searchTerm = this.value.trim();

            if (searchTerm.length < 1) {
                suggestionsBox.style.display = 'none';
                suggestionsBox.innerHTML = '';
                return;
            }

            // Fetch starten
            const url = '/Webprojekt/php/suche_live.php?term=' + encodeURIComponent(searchTerm);
            console.log("Rufe URL auf:", url); // Zeigt in der Konsole (F12) was passiert

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Netzwerk-Antwort war nicht ok");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Daten empfangen:", data); // Zeigt Daten in Konsole
                    suggestionsBox.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(item => {
                            const link = document.createElement('a');
                            link.href = '/Webprojekt/php/produkt-detail.php?id=' + item.id;
                            link.classList.add('suggestion-item');
                            
                            // Preis formatieren
                            let preis = parseFloat(item.preis).toFixed(2).replace('.', ',');

                            link.innerHTML = `
                                <span style="font-weight:500;">${item.name}</span>
                                <span class="suggestion-price" style="color:#667eea; font-weight:bold;">${preis} €</span>
                            `;
                            
                            suggestionsBox.appendChild(link);
                        });
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Fetch Fehler:', error);
                });
        });

        // Schließen bei Klick außerhalb
        document.addEventListener('click', function(e) {
            if (e.target !== input && e.target !== suggestionsBox) {
                suggestionsBox.style.display = 'none';
            }
        });
    }

    // Erst ausführen, wenn die Seite geladen ist
    document.addEventListener('DOMContentLoaded', function() {
        setupLiveSearch('desktopSearchInput', 'desktopSuggestions');
        setupLiveSearch('mobileSearchInput', 'mobileSuggestions');
    });
    // --- DEBUG LIVE SUCHE ENDE ---

    // Funktion zum Aktualisieren des Header-Cart-Counts
    window.updateHeaderCartCount = function() {
        fetch('/Webprojekt/php/include/cart_count.php')
            .then(res => res.json())
            .then(data => {
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    el.textContent = data.count;
                });
            })
            .catch(err => console.error('Cart count update failed:', err));
    };
</script>