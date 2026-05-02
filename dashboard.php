<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$pageTitle = 'Kullanıcı Paneli - EfinanS';
$metaDescription = 'EfinanS kullanıcı panelinde piyasa tablolarını görüntüleyin ve izleme listenizi yönetin.';
$message = $_SESSION['watchlist_message'] ?? '';
unset($_SESSION['watchlist_message']);
$userId = (int)$_SESSION['user_id'];

$markets = $pdo->query('SELECT * FROM markets ORDER BY category, name')->fetchAll();

$watchStmt = $pdo->prepare('
    SELECT m.* FROM watchlist w
    INNER JOIN markets m ON m.id = w.market_id
    WHERE w.user_id = ?
    ORDER BY w.created_at DESC
');
$watchStmt->execute([$userId]);
$watchlist = $watchStmt->fetchAll();

$watchIds = array_map(static fn($item) => (int)$item['id'], $watchlist);
$byCategory = [];
foreach ($markets as $market) {
    $byCategory[$market['category']][] = $market;
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="panel-hero">
    <div>
        <p class="eyebrow">Kullanıcı paneli</p>
        <h1>Hoş geldiniz, <?= e($_SESSION['full_name'] ?? 'Yatırımcı'); ?></h1>
        <p>Piyasa tablolarını inceleyin ve takip etmek istediğiniz finans varlıklarını izleme listenize ekleyin.</p>
    </div>
</section>

<?php if ($message): ?><div class="section alert success"><?= e($message); ?></div><?php endif; ?>

<section class="section">
    <div class="section-heading compact">
        <h2>İzleme Listem</h2>
    </div>
    <?php if (!$watchlist): ?>
        <p class="muted">Henüz izleme listenize varlık eklemediniz.</p>
    <?php else: ?>
        <div class="market-cards">
            <?php foreach ($watchlist as $market): ?>
                <article class="market-card">
                    <span><?= e($market['category']); ?></span>
                    <h3><?= e($market['name']); ?></h3>
                    <p><?= number_format((float)$market['price'], 4, ',', '.'); ?></p>
                    <form method="post" action="remove_from_watchlist.php">
                        <input type="hidden" name="market_id" value="<?= (int)$market['id']; ?>">
                        <button class="small-button" type="submit">Listeden Çıkar</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php foreach (['Döviz', 'Kripto', 'Emtia', 'Borsa'] as $category): ?>
    <section class="section data-section">
        <div class="section-heading compact">
            <h2><?= e($category); ?></h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Varlık</th>
                    <th>Fiyat</th>
                    <th>Değişim</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($byCategory[$category] ?? [] as $market): ?>
                    <tr>
                        <td><?= e($market['name']); ?></td>
                        <td><?= number_format((float)$market['price'], 4, ',', '.'); ?></td>
                        <td class="<?= (float)$market['change_rate'] >= 0 ? 'positive' : 'negative'; ?>"><?= e($market['change_rate']); ?>%</td>
                        <td><?= e($market['status']); ?></td>
                        <td>
                            <?php if (in_array((int)$market['id'], $watchIds, true)): ?>
                                <span class="badge">Listede</span>
                            <?php else: ?>
                                <form method="post" action="add_to_watchlist.php">
                                    <input type="hidden" name="market_id" value="<?= (int)$market['id']; ?>">
                                    <button class="small-button" type="submit">İzleme Listesine Ekle</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endforeach; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
