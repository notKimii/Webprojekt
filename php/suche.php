<?php
// Session sicher starten (falls headimport es nicht tut)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datenbankverbindung
include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php'; 

// Suchbegriff aus der URL holen (das 'q' kommt aus dem name="q" im Header)
$suchbegriff = isset($_GET['q']) ? trim($_GET['q']) : '';

$ergebnisse = [];

if (!empty($suchbegriff)) {
    try {
        // Suche in Name, Beschreibung oder Kategorie
        $sql = "SELECT * FROM artikel 
                WHERE name LIKE :search 
                OR beschreibung LIKE :search 
                OR kategorie LIKE :search";
        
        $stmt = $conPDO->prepare($sql);
        $stmt->execute(['search' => '%' . $suchbegriff . '%']);
        $ergebnisse = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        // Fehlerbehandlung (optional loggen)
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suche: <?php echo htmlspecialchars($suchbegriff); ?> - Cockpit Corner</title>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/headimport.php'; ?>

    <style>
        /* Spezielles CSS nur für die Suchseite */
        .search-container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
            min-height: 60vh; /* Damit der Footer nicht hochrutscht */
        }
        
        .search-title {
            margin-bottom: 30px;
            font-size: 24px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 15px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .product-desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
            flex: 1;
        }

        .product-price {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            margin-top: auto;
        }
        
        .no-results {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<main class="search-container">
    <h1 class="search-title">Suchergebnisse für "<?php echo htmlspecialchars($suchbegriff); ?>"</h1>

    <?php if (empty($suchbegriff)): ?>
        <div class="no-results">
            <p>Bitte geben Sie einen Suchbegriff oben in die Leiste ein.</p>
        </div>
    <?php elseif (count($ergebnisse) > 0): ?>
        
        <div class="product-grid">
            <?php foreach ($ergebnisse as $artikel): ?>
                <a href="/Webprojekt/php/produkt-detail.php?id=<?php echo $artikel['id']; ?>" class="product-card">
                    <div class="card-content">
                        <div class="product-name"><?php echo htmlspecialchars($artikel['name']); ?></div>
                        <div class="product-desc">
                            <?php 
                            // Beschreibung auf 100 Zeichen kürzen
                            $desc = $artikel['beschreibung'];
                            if (strlen($desc) > 100) {
                                echo htmlspecialchars(substr($desc, 0, 100)) . '...';
                            } else {
                                echo htmlspecialchars($desc);
                            }
                            ?>
                        </div>
                        <div class="product-price">
                            <?php echo number_format($artikel['preis'], 2, ',', '.'); ?> €
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="no-results">
            <h2>Keine Treffer ✈️</h2>
            <p>Leider haben wir zu "<?php echo htmlspecialchars($suchbegriff); ?>" nichts gefunden.</p>
            <a href="/Webprojekt/index.php" class="btn-back">Zur Startseite</a>
        </div>
    <?php endif; ?>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/footimport.php'; ?>

</body>
</html>