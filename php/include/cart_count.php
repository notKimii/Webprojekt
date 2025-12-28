<?php
session_start();
header('Content-Type: application/json');

$cartCount = 0;
if (isset($_SESSION['temp_user'])) {
    include __DIR__ . '/connectcon.php';
    $userID = (int)$_SESSION['temp_user']['id'];
    $sql = "SELECT COUNT(wp.artikel_id) AS cnt
            FROM warenkorbkopf wk
            LEFT JOIN warenkorbposition wp ON wp.warenkorb_id = wk.id
            WHERE wk.kunde_id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->bind_result($cnt);
        if ($stmt->fetch()) {
            $cartCount = (int)($cnt ?? 0);
        }
        $stmt->close();
    }
}

echo json_encode(['count' => $cartCount]);
