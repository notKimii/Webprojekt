<?php
session_start();

// Überprüfen ob User eingeloggt ist
if (!isset($_SESSION['temp_user'])) {
    header('Location: /Webprojekt/php/login/loginformular.php');
    exit();
}

$userID = $_SESSION['temp_user']['id'];
$userName = $_SESSION['temp_user']['name'] ?? 'User';

// Datenbankverbindung
$con = new mysqli('localhost', 'root', '', 'dbpilotenshop');

if ($con->connect_error) {
    die("Verbindung fehlgeschlagen: " . $con->connect_error);
}

// Aktuellen Punktestand abrufen
$stmt = $con->prepare("SELECT punktestand FROM punkte WHERE user_id = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$punkteRow = $result->fetch_assoc();
$aktuellerPunktestand = $punkteRow['punktestand'] ?? 0;
$stmt->close();

// Punkte-Historie abrufen (alle Transaktionen)
$historie = [];
$query = "SELECT 
            transaktions_id,
            punkte_aenderung,
            art,
            bemerkung,
            neuer_punktestand,
            datum
          FROM punktelog 
          WHERE user_id = ? 
          ORDER BY datum DESC";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $historie[] = $row;
}
$stmt->close();

// Statistiken berechnen
$gesamtErhalten = 0;
$gesamtAusgegeben = 0;
foreach ($historie as $eintrag) {
    if ($eintrag['punkte_aenderung'] > 0) {
        $gesamtErhalten += $eintrag['punkte_aenderung'];
    } else {
        $gesamtAusgegeben += abs($eintrag['punkte_aenderung']);
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Treuepunkte - CockpitCorner</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Section */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header h1 svg {
            width: 40px;
            height: 40px;
            fill: #ffd700;
        }

        .breadcrumb {
            color: #666;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card.current {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
        }

        .stat-card.earned {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .stat-card.earned .stat-label,
        .stat-card.earned .stat-value {
            color: white;
        }

        .stat-card.spent {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .stat-card.spent .stat-label,
        .stat-card.spent .stat-value {
            color: white;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .stat-card.current .stat-label {
            color: #333;
        }

        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-icon {
            width: 30px;
            height: 30px;
        }

        /* Historie Section */
        .historie-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .historie-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .historie-header h2 {
            color: #333;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: #666;
        }

        .filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .filter-btn.active {
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        /* Historie Table */
        .historie-table {
            width: 100%;
            border-collapse: collapse;
        }

        .historie-table thead {
            background: #f8f9fa;
        }

        .historie-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .historie-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #666;
            font-size: 14px;
        }

        .historie-table tbody tr {
            transition: background 0.2s ease;
        }

        .historie-table tbody tr:hover {
            background: #f8f9fa;
        }

        .historie-table tbody tr:last-child td {
            border-bottom: none;
        }

        .punkte-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        .punkte-badge.erhalten {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }

        .punkte-badge.ausgegeben {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .typ-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .typ-badge.kauf {
            background: #e3f2fd;
            color: #1976d2;
        }

        .typ-badge.bonus {
            background: #fff3e0;
            color: #f57c00;
        }

        .typ-badge.einloesung {
            background: #fce4ec;
            color: #c2185b;
        }

        .typ-badge.gutschrift {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .datum {
            color: #999;
            font-size: 13px;
        }

        .beschreibung {
            color: #333;
            font-weight: 500;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 20px;
            color: #666;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #667eea;
            color: white;
            transform: translateX(-5px);
        }

        .back-btn svg {
            width: 20px;
            height: 20px;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }

            .page-header,
            .historie-container {
                padding: 20px;
                border-radius: 15px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .stat-value {
                font-size: 28px;
            }

            .historie-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .historie-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-buttons {
                width: 100%;
            }

            .filter-btn {
                flex: 1;
                text-align: center;
            }
        }

        @media screen and (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/php/kundenkonto.php" class="back-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Zurück zum Kundenkonto
        </a>

        <div class="page-header">
            <h1>
                <svg viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Meine Treuepunkte
            </h1>
            <div class="breadcrumb">
                <a href="/Webprojekt/index.php">Startseite</a> / 
                <a href="/Webprojekt/php/Kundenkonto.php">Kundenkonto</a> / 
                Treuepunkte
            </div>
        </div>

        <!-- Statistik Karten -->
        <div class="stats-grid">
            <div class="stat-card current">
                <div class="stat-label">Aktueller Punktestand</div>
                <div class="stat-value">
                    <svg class="stat-icon" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <?php echo number_format($aktuellerPunktestand, 0, ',', '.'); ?>
                </div>
            </div>

            <div class="stat-card earned">
                <div class="stat-label">Gesamt Erhalten</div>
                <div class="stat-value">
                    <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12l7 7 7-7"/>
                    </svg>
                    +<?php echo number_format($gesamtErhalten, 0, ',', '.'); ?>
                </div>
            </div>

            <div class="stat-card spent">
                <div class="stat-label">Gesamt Ausgegeben</div>
                <div class="stat-value">
                    <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 19V5M5 12l7-7 7 7"/>
                    </svg>
                    -<?php echo number_format($gesamtAusgegeben, 0, ',', '.'); ?>
                </div>
            </div>
        </div>

        <!-- Punkte Historie -->
        <div class="historie-container">
            <div class="historie-header">
                <h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Punkte-Historie
                </h2>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="alle">Alle</button>
                    <button class="filter-btn" data-filter="erhalten">Erhalten</button>
                    <button class="filter-btn" data-filter="ausgegeben">Ausgegeben</button>
                </div>
            </div>

            <?php if (empty($historie)): ?>
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <h3>Noch keine Aktivitäten</h3>
                    <p>Sammle Punkte durch Einkäufe und andere Aktionen.</p>
                </div>
            <?php else: ?>
                <table class="historie-table">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Beschreibung</th>
                            <th>Typ</th>
                            <th>Punkte</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historie as $eintrag): ?>
                            <?php 
                                $isErhalten = $eintrag['punkte_aenderung'] > 0;
                                $filterClass = $isErhalten ? 'erhalten' : 'ausgegeben';
                                
                                // Art der Transaktion bestimmen
                                $art = $eintrag['art'] ?? 'Sonstiges';
                                if (empty($art) || $art == 'Automatisch') {
                                    // Versuche aus Bemerkung zu klassifizieren
                                    $bemerkung = strtolower($eintrag['bemerkung'] ?? '');
                                    if (strpos($bemerkung, 'bestellung') !== false || strpos($bemerkung, 'kauf') !== false) {
                                        $art = 'Kauf';
                                    } elseif (strpos($bemerkung, 'bonus') !== false || strpos($bemerkung, 'willkommen') !== false) {
                                        $art = 'Bonus';
                                    } elseif (strpos($bemerkung, 'eingelöst') !== false || strpos($bemerkung, 'gutschein') !== false) {
                                        $art = 'Einloesung';
                                    } elseif (strpos($bemerkung, 'gutschrift') !== false) {
                                        $art = 'Gutschrift';
                                    } elseif (strpos($bemerkung, 'storno') !== false) {
                                        $art = 'Storno';
                                    } else {
                                        $art = 'Aktion';
                                    }
                                }
                            ?>
                            <tr class="historie-row" data-filter="<?php echo $filterClass; ?>">
                                <td class="datum">
                                    <?php 
                                        $datum = new DateTime($eintrag['datum']);
                                        echo $datum->format('d.m.Y - H:i'); 
                                    ?> Uhr
                                </td>
                                <td class="beschreibung">
                                    <?php echo htmlspecialchars($eintrag['bemerkung'] ?? 'Keine Beschreibung'); ?>
                                    <br><small style="color: #999;">Neuer Stand: <?php echo number_format($eintrag['neuer_punktestand'], 0, ',', '.'); ?> Punkte</small>
                                </td>
                                <td>
                                    <span class="typ-badge <?php echo strtolower($art); ?>">
                                        <?php echo htmlspecialchars($art); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="punkte-badge <?php echo $filterClass; ?>">
                                        <?php echo $isErhalten ? '+' : ''; ?>
                                        <?php echo number_format($eintrag['punkte_aenderung'], 0, ',', '.'); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Filter Funktionalität
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const historieRows = document.querySelectorAll('.historie-row');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Aktive Klasse setzen
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Zeilen filtern
                historieRows.forEach(row => {
                    if (filter === 'alle') {
                        row.style.display = '';
                    } else {
                        if (row.getAttribute('data-filter') === filter) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
    });
    </script>
</body>
</html>