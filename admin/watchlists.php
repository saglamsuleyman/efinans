<?php
require_once __DIR__ . '/includes/admin_auth.php';

$adminPageTitle = 'İzleme Listeleri - EfinanS Admin';
$adminHeading = 'İzleme Listeleri';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $watchId = (int)($_POST['watch_id'] ?? 0);
    if ($watchId > 0) {
        $stmt = $pdo->prepare('DELETE FROM watchlist WHERE id = ?');
        $stmt->execute([$watchId]);
        adminFlash('İzleme listesi kaydı silindi.');
    }
    header('Location: watchlists.php');
    exit;
}

$items = $pdo->query('
    SELECT w.id, w.created_at, u.full_name, u.email, m.name AS market_name, m.symbol, m.category
    FROM watchlist w
    INNER JOIN users u ON u.id = w.user_id
    INNER JOIN markets m ON m.id = w.market_id
    ORDER BY w.created_at DESC
')->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-panel">
    <h2>Kullanıcı İzleme Listeleri</h2>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Kullanıcı</th><th>E-posta</th><th>Piyasa</th><th>Sembol</th><th>Kategori</th><th>Eklenme</th><th>İşlem</th></tr></thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= e($item['full_name']); ?></td>
                    <td><?= e($item['email']); ?></td>
                    <td><?= e($item['market_name']); ?></td>
                    <td><?= e($item['symbol']); ?></td>
                    <td><?= e($item['category']); ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($item['created_at'])); ?></td>
                    <td>
                        <form method="post" action="watchlists.php">
                            <input type="hidden" name="watch_id" value="<?= (int)$item['id']; ?>">
                            <button class="admin-danger" type="submit" data-confirm="Bu izleme listesi kaydı silinsin mi?">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
