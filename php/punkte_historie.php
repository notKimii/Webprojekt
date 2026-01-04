<?php
session_start();

// Überprüfen ob User eingeloggt ist
if (!isset($_SESSION['user'])) {
    header('Location: /Webprojekt/php/login/loginformular.php');
    exit();
}

$userID = $_SESSION['user']['id'];
$userName = $_SESSION['user']['name'] ?? 'User';

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
    <title>Meine Treuepunkte - Cockpit Corner</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <style>
        /* Punkte Historie - angepasst an Hauptdesign */
        .punkte-page {
            min-height: 100vh;
            background: var(--light-color);
            padding: calc(var(--spacing-unit) * 2) calc(var(--spacing-unit) * 1.5);
        }

        .punkte-container {
            max-width: var(--container-max-width);
            margin: 0 auto;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: white;
            color: var(--text-color);
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: calc(var(--spacing-unit) * 2);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .back-btn:hover {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .back-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 50%, #334155 100%);
            border-radius: var(--border-radius-xl);
            padding: calc(var(--spacing-unit) * 2.5);
            margin-bottom: calc(var(--spacing-unit) * 2);
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
        }

        .page-header h1 {
            color: white;
            font-size: clamp(1.5rem, 4vw, 2rem);
            margin-bottom: calc(var(--spacing-unit) * 0.5);
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .page-header h1 svg {
            width: 32px;
            height: 32px;
            fill: #fbbf24;
        }

        .breadcrumb {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            position: relative;
            z-index: 1;
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            color: white;
            text-decoration: underline;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: calc(var(--spacing-unit) * 1.5);
            margin-bottom: calc(var(--spacing-unit) * 2);
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: calc(var(--spacing-unit) * 2);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card.current {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            border: none;
        }

        .stat-card.current .stat-label,
        .stat-card.current .stat-value {
            color: white;
        }

        .stat-card.earned {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
        }

        .stat-card.earned .stat-label,
        .stat-card.earned .stat-value {
            color: white;
        }

        .stat-card.spent {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
        }

        .stat-card.spent .stat-label,
        .stat-card.spent .stat-value {
            color: white;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .stat-value {
            font-size: clamp(1.75rem, 4vw, 2.25rem);
            font-weight: 800;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-icon {
            width: 28px;
            height: 28px;
            opacity: 0.9;
        }

        /* Historie Container */
        .historie-container {
            background: white;
            border-radius: var(--border-radius-xl);
            padding: calc(var(--spacing-unit) * 2);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .historie-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: calc(var(--spacing-unit) * 2);
            flex-wrap: wrap;
            gap: calc(var(--spacing-unit));
        }

        .historie-header h2 {
            color: var(--primary-color);
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 18px;
            border: 2px solid var(--border-color);
            background: white;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-color);
            transition: var(--transition);
        }

        .filter-btn:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
            color: white;
            border-color: transparent;
        }

        /* Historie Table */
        .historie-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .historie-table thead th {
            background: var(--light-color);
            padding: calc(var(--spacing-unit)) calc(var(--spacing-unit) * 1.25);
            text-align: left;
            font-weight: 600;
            color: var(--text-light);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
        }

        .historie-table thead th:first-child {
            border-radius: var(--border-radius) 0 0 0;
        }

        .historie-table thead th:last-child {
            border-radius: 0 var(--border-radius) 0 0;
        }

        .historie-table tbody tr {
            transition: var(--transition);
        }

        .historie-table tbody tr:hover {
            background: var(--light-color);
        }

        .historie-table tbody td {
            padding: calc(var(--spacing-unit) * 1.25);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 0.95rem;
        }

        .historie-table .datum {
            color: var(--text-light);
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .historie-table .beschreibung {
            max-width: 350px;
        }

        .historie-table .beschreibung small {
            color: var(--text-light);
            font-size: 0.8rem;
        }

        /* Typ Badge */
        .typ-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .typ-badge.kauf {
            background: #dbeafe;
            color: var(--accent-color);
        }

        .typ-badge.bonus {
            background: #fef3c7;
            color: #d97706;
        }

        .typ-badge.einloesung {
            background: #fce7f3;
            color: #db2777;
        }

        .typ-badge.gutschrift {
            background: #d1fae5;
            color: #059669;
        }

        .typ-badge.storno {
            background: #fee2e2;
            color: #dc2626;
        }

        .typ-badge.aktion {
            background: #e0e7ff;
            color: #4f46e5;
        }

        /* Punkte Badge */
        .punkte-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: var(--border-radius);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .punkte-badge.erhalten {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .punkte-badge.ausgegeben {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: calc(var(--spacing-unit) * 5) calc(var(--spacing-unit) * 2);
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: calc(var(--spacing-unit) * 1.5);
            stroke: var(--text-light);
            opacity: 0.4;
        }

        .empty-state h3 {
            color: var(--primary-color);
            margin-bottom: calc(var(--spacing-unit) * 0.5);
            font-size: 1.25rem;
        }

        .empty-state p {
            color: var(--text-light);
            max-width: 400px;
            margin: 0 auto;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .punkte-page {
                padding: calc(var(--spacing-unit)) calc(var(--spacing-unit));
            }

            .page-header,
            .historie-container {
                padding: calc(var(--spacing-unit) * 1.5);
                border-radius: var(--border-radius-lg);
            }

            .page-header h1 {
                font-size: 1.35rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .historie-table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
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
                padding: 10px 12px;
                font-size: 0.8rem;
            }
        }

        @media screen and (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: calc(var(--spacing-unit) * 1.5);
            }
        }
    </style>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/headimport.php'; ?>
    <div class="punkte-page">
        <div class="punkte-container">
            <a href="/Webprojekt/php/Kundenkonto.php" class="back-btn">
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
                                        <br><small>Neuer Stand: <?php echo number_format($eintrag['neuer_punktestand'], 0, ',', '.'); ?> Punkte</small>
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