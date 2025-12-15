<?php
session_start();

if (isset($_SESSION['temp_user'])) {
    include 'include/connect.php';

    $stmt = $conPDO->prepare("SELECT COUNT(*) FROM user WHERE online=1");
    $stmt->execute();
    $anzahl = $stmt->fetchColumn();

    echo $anzahl;
    exit;
} else {
    // Optional: falls nicht eingeloggt → 0 zurückgeben
    echo 0;
    exit;
}
?>
