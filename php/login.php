<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


// 1) DB-Verbindung mit Fehler-Reporting
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dbPilotenshop;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('DB-Verbindungsfehler: ' . $e->getMessage());
}

// 2) Eingaben prüfen
if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['2fa_code'])) {
    header("Location: ../login.html");
    exit;
}

    $email    = trim($_POST['email']);
    $password = hash('sha512', $_POST["password"]);
	$code = $_POST['2fa_code'] ?? '';

    require_once __DIR__ . '/../vendor/autoload.php';
	$gAuth = new PHPGangsta_GoogleAuthenticator();

    // 3) User laden – hier stimmt der Platzhalter
    $stmt = $pdo->prepare("SELECT * FROM user WHERE mail = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
    die('DEBUG: Benutzer nicht gefunden.');
    } else {
    echo '<pre>DEBUG: User geladen: ';
    print_r($user);
    echo '</pre>';
    }

// 4) Passwort prüfen
    if ($user && $password == $user['passwort']){
        $_SESSION['temp_user'] = [
            'id'            => $user['id'],
            'vorname'       => $user['vorname'],
            'nachname'      => $user['nachname'],
            'adresse'       => $user['adresse'],
            'plz'           => $user['plz'],
            'ort'           => $user['ort'],
            'email'         => $user['mail'],           // wenn die Spalte „mail“ heißt
            'google_secret' => $user['google_secret']
        ];

        $checkResult = $gAuth->verifyCode($user['google_secret'], $code, 2);
        if ($checkResult) {
            header("Location: basis.php");
            exit;
            } else {
            header("Location: ../login.html");
                exit;
            }
        } else {
        header("Location: ../login.html");
        exit;
    }
//Login mit befüllten feldern und fehlermeldung
?>
