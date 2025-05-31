<?php
    session_start();


    // DB-Verbindung mit Fehler-Reporting
    include 'include/connect.php';

        $email    = trim($_POST['email']);
        $code = $_POST['2fa_code'] ?? '';
        $password = $_POST["password"];
        
    // Eingaben prüfen
    if (strlen($email) < 5 || strpos($email, '@') === false || empty($password)) {
        header("Location: ../login.html");
        exit;
    }

    if (strlen($password) < 9 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) 
    {
        header("Location: ../login.html");
        exit;
    }

    // Wenn alle Prüfungen bestanden: Passwort hashen
    $password = hash('sha512', $_POST["password"]);

    include 'include/vendorconnect.php';
	$gAuth = new PHPGangsta_GoogleAuthenticator();

    // User laden 
    $stmt = $con->prepare("SELECT * FROM user WHERE mail = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Passwort prüfen
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
        } 
        else {
            header("Location: ../login.html");
             exit;
    }
//Login mit befüllten feldern und fehlermeldung
?>
