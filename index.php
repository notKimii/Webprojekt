<!DOCTYPE html>
<html lang="de">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Cockpit Corner - Alles für Piloten: Flugtaschen, Zubehör & mehr</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'php/include/headimport.php'; ?>
    <main>

        <section class="hero-section">
            <div class="hero-banner">
                <div class="container">
                    <div class="hero-content">
                        <?php
                        if (isset($_SESSION['user'])) {
                            $name = $_SESSION['user']['vorname'];
                            $nachname = $_SESSION['user']['nachname'];
                            $user_id = $_SESSION['user']['id'];
                            
                            // Datenbankverbindung für letzte Anmeldung
                            // Passe die Zugangsdaten an deine Konfiguration an
                            try {
                                $db = new PDO('mysql:host=localhost;dbname=dbpilotenshop;charset=utf8mb4', 'root', '');
                                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                
                                // Letzte Anmeldung aus logs-Tabelle holen (OFFSET 1 = vorherige Anmeldung)
                                $stmt = $db->prepare("SELECT login_time FROM logs WHERE user_id = ? ORDER BY login_time DESC LIMIT 1 OFFSET 1");
                                $stmt->execute([$user_id]);
                                $lastLogin = $stmt->fetch();
                                
                                $loginText = $lastLogin ? date('d.m.Y, H:i', strtotime($lastLogin['login_time'])) . ' Uhr' : 'Erste Anmeldung';
                            } catch (PDOException $e) {
                                $loginText = '';
                            }
                            
                            echo "<div class='greeting'>
                                    <span class='greeting-text'>Willkommen zurück, $name $nachname!</span>";
                            if (!empty($loginText)) {
                                echo "<span class='last-login'>Letzte Anmeldung: $loginText</span>";
                            }
                            echo "</div>";
                        } else {
                            echo "<div class='greeting'>
                                    <span class='greeting-text'>Premium Aviation Equipment</span>
                                  </div>";
                        }
                        ?>
                        
                        <h1>
                            <span class="hero-highlight">Next-Level</span><br>
                            Equipment fürs Cockpit
                        </h1>
                        
                        <p>
                            Entdecke hochwertiges Pilotenequipment von führenden Marken. 
                            Professionelle Headsets, GPS-Systeme, Flugtaschen und mehr für anspruchsvolle Piloten.
                        </p>
                        
                        <div class="hero-cta-group">
                            <a href="/kategorie/neuheiten" class="cta-button-primary">
                                Jetzt Entdecken
                            </a>
                            <a href="/angebote" class="cta-button-secondary">
                                Zu den Angeboten
                            </a>
                        </div>                
                    </div>
                </div>
            </div>
        </section>

        <section class="usp-bar">
            <div class="container">
                <div class="usp-item">Kostenloser Versand ab 50€</div>
                <div class="usp-item">Schnelle Lieferung</div>
                <div class="usp-item">30 Tage Rückgaberecht</div>
                <div class="usp-item">Sichere Bezahlung</div>
            </div>
        </section>

        <section class="featured-categories">
            <div class="container">
                <h2>Unsere Top-Kategorien</h2>
                <div class="category-grid">
                    <div class="category-item">
                        <a href="/Webprojekt/php/kategorien/headsetskategorie.php">
                            <img src="/Webprojekt/images/pictures/indexpics/headset.png" alt="Bose">
                            <h3>Headsets</h3>
                        </a>
                    </div>
                    <div class="category-item">
                        <a href="/Webprojekt/php/kategorien/headsetskategorie.php">
                            <img src="./images/pictures/indexpics/Sonnenbrille.png" alt="Schuhe">
                            <h3>Sonnenbrillen</h3>
                        </a>
                    </div>
                    <div class="category-item">
                        <a href="/Webprojekt/php/kategorien/flugtaschenkategorie.php">
                            <img src="./images/pictures/indexpics/Flugtasche.png" alt="Accessoires">
                            <h3>Flugtaschen</h3>
                        </a>
                    </div>
                    <div class="category-item">
                        <a href="/Webprojekt/php/kategorien/flugtaschenkategorie.php">
                            <img src="./images/pictures/indexpics/Clipboard.png" alt="Wohnen">
                            <h3>Kneeboards & Clipboards</h3>
                        </a>
                    </div>
                </div>
                <div class="view-all-link">
                    <a href="/kategorie/alle" class="button-secondary">Zu allen Kategorien</a>
                </div>
            </div>
        </section>

        <section class="new-arrivals">
            <div class="container">
                <h2>Neuheiten</h2>
                <div class="carousel-container">
                    <button class="carousel-btn carousel-btn-prev" aria-label="Vorheriges Produkt">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <div class="carousel-track-container">
                        <div class="carousel-track">
                            <div class="carousel-slide">
                                <div class="product-item">
                                    <a href="./php/produkt-detail.php?id=1014">
                                        <img src="./images/pictures/productids/1014/14.1.png" alt="Garmin D2 Mach 1 Aviator Smartwatch">
                                        <h3>Garmin D2 Mach 1 Aviator Smartwatch</h3>
                                        <p class="price">1.199,00 €</p>
                                    </a>
                                    <button class="add-to-cart-button">In den Warenkorb</button>
                                </div>
                            </div>
                            <div class="carousel-slide">
                                <div class="product-item">
                                    <a href="./php/produkt-detail.php?id=1035">
                                        <img src="./images/pictures/productids/1035/35.1.png" alt="Aero Cosmetics Wash Wax ALL (Konzentrat, 1L)">
                                        <h3>Aero Cosmetics Wash Wax ALL (Konzentrat, 1L)</h3>
                                        <p class="price">45,00 €</p>
                                    </a>
                                    <button class="add-to-cart-button">In den Warenkorb</button>
                                </div>
                            </div>
                            <div class="carousel-slide">
                                <div class="product-item">
                                    <a href="./php/produkt-detail.php?id=1040">
                                        <img src="./images/pictures/productids/1040/40.1.png" alt="H3R Aviation Halon 1211 Feuerlöscher (A344T)">
                                        <h3>H3R Aviation Halon 1211 Feuerlöscher (A344T)</h3>
                                        <p class="price">289,00 €</p>
                                    </a>
                                    <button class="add-to-cart-button">In den Warenkorb</button>
                                </div>
                            </div>
                            <div class="carousel-slide">
                                <div class="product-item">
                                    <a href="./php/produkt-detail.php?id=1007">
                                        <img src="./images/pictures/productids/1007/7.1.JPG" alt="Garmin aera 660 Portable Aviation GPS">
                                        <h3>Garmin aera 660 Portable Aviation GPS</h3>
                                        <p class="price">849,00 €</p>
                                    </a>
                                    <button class="add-to-cart-button">In den Warenkorb</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-next" aria-label="Nächstes Produkt">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
                <div class="carousel-dots"></div>
                <div class="view-all-link">
                    <a href="/kategorie/neuheiten" class="button-secondary">Zu den Neuheiten</a>
                </div>
            </div>
        </section>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.querySelector('.carousel-track');
            const slides = Array.from(document.querySelectorAll('.carousel-slide'));
            const prevBtn = document.querySelector('.carousel-btn-prev');
            const nextBtn = document.querySelector('.carousel-btn-next');
            const dotsContainer = document.querySelector('.carousel-dots');
            
            let currentIndex = 0;
            let slidesToShow = getSlidesToShow();
            
            function getSlidesToShow() {
                if (window.innerWidth <= 480) return 1;
                if (window.innerWidth <= 768) return 2;
                if (window.innerWidth <= 1024) return 3;
                return 4;
            }
            
            function updateCarousel() {
                slidesToShow = getSlidesToShow();
                const slideWidth = 100 / slidesToShow;
                slides.forEach(slide => {
                    slide.style.flex = `0 0 ${slideWidth}%`;
                });
                
                const maxIndex = Math.max(0, slides.length - slidesToShow);
                if (currentIndex > maxIndex) currentIndex = maxIndex;
                
                const offset = -currentIndex * (100 / slidesToShow);
                track.style.transform = `translateX(${offset}%)`;
                
                updateDots();
                updateButtons();
            }
            
            function updateDots() {
                const totalDots = Math.ceil(slides.length / slidesToShow);
                const activeDot = Math.floor(currentIndex / slidesToShow);
                
                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalDots; i++) {
                    const dot = document.createElement('button');
                    dot.classList.add('carousel-dot');
                    if (i === activeDot) dot.classList.add('active');
                    dot.addEventListener('click', () => {
                        currentIndex = i * slidesToShow;
                        updateCarousel();
                    });
                    dotsContainer.appendChild(dot);
                }
            }
            
            function updateButtons() {
                const maxIndex = Math.max(0, slides.length - slidesToShow);
                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                prevBtn.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
                nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
                nextBtn.style.pointerEvents = currentIndex >= maxIndex ? 'none' : 'auto';
            }
            
            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });
            
            nextBtn.addEventListener('click', () => {
                const maxIndex = Math.max(0, slides.length - slidesToShow);
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            });
            
            window.addEventListener('resize', updateCarousel);
            updateCarousel();
        });
        </script>

        <section class="bestsellers">
            <div class="container">
                <h2>Unsere Bestseller</h2>
                <div class="product-grid">
                    <div class="product-item">
                        <a href="./php/produkt-detail.php?id=1001">
                            <img src="./images/pictures/productids/1001/1.1.JPG" alt="Bose A30 Aviation Headset">
                            <h3>Bose A30 Aviation Headset</h3>
                            <p class="price">1.299,00 €</p>
                        </a>
                        <button class="add-to-cart-button">In den Warenkorb</button>
                    </div>
                    <div class="product-item">
                        <a href="./php/produkt-detail.php?id=1008">
                            <img src="./images/pictures/productids/1008/8.1.JPG" alt="ICAO Karte Deutschland (Set)">
                            <h3>ICAO Karte Deutschland (Set)</h3>
                            <p class="price">25,00 €</p>
                        </a>
                        <button class="add-to-cart-button">In den Warenkorb</button>
                    </div>
                    <div class="product-item">
                        <a href="./php/produkt-detail.php?id=1003">
                            <img src="./images/pictures/productids/1003/3.1.JPG" alt="David Clark H10-13.4 Aviation Headset">
                            <h3>David Clark H10-13.4 Aviation Headset</h3>
                            <p class="price">389,00 €</p>
                        </a>
                        <button class="add-to-cart-button">In den Warenkorb</button>
                    </div>
                    <div class="product-item">
                        <a href="./php/produkt-detail.php?id=1026">
                            <img src="./images/pictures/productids/1026/26.1.png" alt="ASA Standard Pilot Logbook">
                            <h3>ASA Standard Pilot Logbook (SP-30)</h3>
                            <p class="price">13,00 €</p>
                        </a>
                        <button class="add-to-cart-button">In den Warenkorb</button>
                    </div>
                </div>
                <div class="view-all-link">
                    <a href="/angebote" class="button-secondary">Zu den Angeboten</a>
                </div>
                
            </div>
        </section>

        <section class="brand-logos">
            <div class="container">
                <h2>Beliebte Marken</h2>
                <div class="logo-slider">
                    <img src="./images/pictures/marken/Bose.png" alt="Bose">
                    <img src="./images/pictures/marken/Garmin.png" alt="Garmin">
                    <img src="./images/pictures/marken/Jeppesen.jpg" alt="Jeppsen">
                    <img src="./images/pictures/marken/Sennheiser.png" alt="Sennheiser">
                </div>
            </div>
        </section>

        <section class="testimonials">
            <div class="container">
                <h2>Was unsere Kunden sagen</h2>
                <div class="testimonial-slider">
                    <div class="testimonial-item">
                        <div class="quote-icon"></div>
                        <div class="rating"></div>
                        <p>Tolles Produkt, schnelle Lieferung! Bin sehr zufrieden mit der Qualität und dem Service. Die Beratung war erstklassig.</p>
                        <div class="author">
                            <div class="author-avatar">MM</div>
                            <div class="author-info">
                                <span class="author-name">Max Müller</span>
                                <span class="author-location">Berlin</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <div class="quote-icon"></div>
                        <div class="rating"></div>
                        <p>Super Service und eine riesige Auswahl. Die Webseite ist sehr benutzerfreundlich. Kaufe gerne wieder hier ein!</p>
                        <div class="author">
                            <div class="author-avatar">AS</div>
                            <div class="author-info">
                                <span class="author-name">Anna Schmidt</span>
                                <span class="author-location">Hamburg</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <div class="quote-icon"></div>
                        <div class="rating"></div>
                        <p>Die besten Preise, die ich online finden konnte! Und die Ware kam sogar früher als erwartet. Absolute Empfehlung!</p>
                        <div class="author">
                            <div class="author-avatar">TK</div>
                            <div class="author-info">
                                <span class="author-name">Tom Koch</span>
                                <span class="author-location">München</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog-teaser">
            <div class="container">
                <h2>Aktuelles</h2>
                <div class="article-grid">
                    <div class="article-item">
                        <a href="https://www.aero-expo.de/">
                            <img src="/Webprojekt/images/pictures/indexpics/gadgets.jpeg" alt="Frühlingstrends 2025">
                            <h3>AERO Friedrichshafen 2025</h3>
                            <p>Starke Impulse für die Allgemeine Luftfahrt und Fokus auf Nachhaltigkeit</p>
                            <span>Weiterlesen &rarr;</span>
                        </a>
                    </div>
                    <div class="article-item">
                        <a href="https://www.dulv.de">
                            <img src="/Webprojekt/images/pictures/indexpics/ultraleicht.jpeg" alt="Nachhaltige Mode">
                            <h3>Ultraleicht Fliegen</h3>
                            <p>Der kostengünstige Einstieg ins Cockpit – Modelle, Lizenzen und laufende Kosten im Überblick</p>
                            <span>Weiterlesen &rarr;</span>
                        </a>
                    </div>
                    <div class="article-item">
                        <a href="https://www.siebert.aero">
                            <img src="/Webprojekt/images/pictures/indexpics/ausbildung.jpeg" alt="Geschenkideen">
                            <h3>Flugausbildung im Wandel</h3>
                            <p>Was angehende Piloten heute über moderne Trainingsmethoden wissen sollten</p>
                            <span>Weiterlesen &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="newsletter-signup">
            <div class="container">
                <h2>Bleibe auf dem Laufenden!</h2>
                <p>Abonniere unseren Newsletter und verpasse keine Neuigkeiten oder exklusiven Angebote. Erhalte 10% Rabatt auf deine nächste Bestellung!</p>
                <form action="/newsletter-anmeldung" method="post">
                    <input type="email" name="email" placeholder="Deine E-Mail-Adresse" required>
                    <button type="submit">Abonnieren</button>
                </form>
            </div>
        </section>

    </main>

    <?php include 'php/include/footimport.php'; ?>

</body>

</html>