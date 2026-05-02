<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$marketId = (int)($_POST['market_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $marketId > 0) {
    $stmt = $pdo->prepare('DELETE FROM watchlist WHERE user_id = ? AND market_id = ?');
    $stmt->execute([(int)$_SESSION['user_id'], $marketId]);
    $_SESSION['watchlist_message'] = 'Varlık izleme listenizden çıkarıldı.';
}

header('Location: dashboard.php');
exit;
