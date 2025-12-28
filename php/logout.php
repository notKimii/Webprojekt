<?php
session_start();

// KORREKTUR: Der Pfad ist jetzt "include/..." (ohne .. davor),
// weil logout.php und der include-Ordner im gleichen Verzeichnis liegen.
include "include/connectcon.php";

// 1. User in der Datenbank auf "offline" (0) setzen
if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];

    if ($stmt = $con->prepare("UPDATE user SET online = 0 WHERE id = ?")) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->close();
    }
}

// 2. Session löschen
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout - Cockpit Corner</title>
  
  <link rel="stylesheet" href="/Webprojekt/produkt.css">
  
  <style>
    .logout-content {
        text-align: center;
        padding: 50px 20px;
        min-height: 40vh;
    }
    .logout-content img {
        max-width: 250px;
        margin-bottom: 30px;
    }
    .add-to-cart-button {
        /* Falls die Klasse nicht im produkt.css ist, hier ein Fallback-Style */
        display: inline-block;
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }
    .add-to-cart-button:hover {
        background-color: #218838;
    }
  </style>
</head>
<body>

  <?php include "include/headimport.php"; ?>

<main>
  <div class="container">
    
    <div class="logout-content">
        

        <h1>Logout erfolgreich</h1>
        <p>Du wurdest erfolgreich abgemeldet.</p>
        <p>Wir freuen uns, dich bald wiederzusehen!</p>

        <a href="login/loginformular.php" class="add-to-cart-button">Erneut anmelden</a>
        
        <br><br>
        <a href="../index.php" style="color: inherit; text-decoration: underline;">Zurück zur Startseite</a>
    </div>

  </div>
</main>

  <?php include "include/footimport.php"; ?>

</body>
</html>