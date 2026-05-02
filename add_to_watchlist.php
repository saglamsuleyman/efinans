<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$marketId = (int)($_POST['market_id'] ?? $_GET['market_id'] ?? 0);

if (!isLoggedIn()) {
    $_SESSION['watchlist_warning'] = 'İzleme listesine eklemek için önce giriş yapmalısınız.';
    header('Location: watchlist-warning.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $marketId <= 0) {
    header('Location: markets.php');
    exit;
}

$marketCheck = $pdo->prepare('SELECT id FROM markets WHERE id = ? LIMIT 1');
$marketCheck->execute([$marketId]);

if (!$marketCheck->fetch()) {
    $_SESSION['watchlist_message'] = 'Eklemek istediğiniz piyasa varlığı bulunamadı.';
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare('INSERT IGNORE INTO watchlist (user_id, market_id) VALUES (?, ?)');
$stmt->execute([(int)$_SESSION['user_id'], $marketId]);

$_SESSION['watchlist_message'] = 'Varlık izleme listenize eklendi.';
header('Location: dashboard.php');
exit;
