<?php
session_start();


// 1) DB-Verbindung mit Fehler-Reporting
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dbpferdeshop;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('DB-Verbindungsfehler: ' . $e->getMessage());
}

// 2) Eingaben prüfen
if (empty($_POST['email']) || empty($_POST['password'])) {
    header("Location: ../login.html");
    exit;
}

$email    = trim($_POST['email']);
$password = $_POST['password'];

// 3) User laden – hier stimmt der Platzhalter
$stmt = $pdo->prepare("SELECT * FROM user WHERE mail = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 4) Passwort prüfen
if ($user && password_verify($password, $user['passwort'])) {
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
    header("Location: verify_2fa.php");
    exit;
} else {
    header("Location: ../login.html?error=1");
    exit;
}
