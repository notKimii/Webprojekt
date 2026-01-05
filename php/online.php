<?php
session_start();

if (isset($_SESSION['user'])) {
    include 'include/connect.php'; // Deine DB-Verbindung ($conPDO)
    
    $stmt = $conPDO->prepare("SELECT COUNT(*) FROM user WHERE online=1");
    $stmt->execute();
    $anzahl = $stmt->fetchColumn();

    echo $anzahl;
    exit;
} else {
    echo 0;
    exit;
}
?>
