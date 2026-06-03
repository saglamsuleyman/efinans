<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Arama - EfinanS';
$metaDescription = 'EfinanS içinde piyasa verileri, kripto paralar, döviz kurları ve finans haberleri arayın.';

$query = trim($_GET['q'] ?? '');
$marketResults = [];
$newsResults = [];

if ($query !== '') {
    $like = '%' . $query . '%';

    $marketStmt = $pdo->prepare(
        'SELECT * FROM markets
         WHERE name LIKE ? OR symbol LIKE ? OR category LIKE ?
         ORDER BY category, name'
    );
    $marketStmt->execute([$like, $like, $like]);
    $marketResults = $marketStmt->fetchAll();

    $newsStmt = $pdo->prepare(
        'SELECT * FROM news
         WHERE title LIKE ? OR summary LIKE ? OR content LIKE ?
         ORDER BY created_at DESC'
    );
    $newsStmt->execute([$like, $like, $like]);
    $newsResults = $newsStmt->fetchAll();
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">Site araması</p>
    <h1>Arama sonuçları</h1>
    <p><?= $query !== '' ? '"' . e($query) . '" için bulunan piyasa ve haber sonuçları.' : 'Piyasa, kripto, döviz veya haber aramak için bir kelime yazın.'; ?></p>
</section>

<section class="section">
    <form class="filter-card filter-grid" method="get" action="search.php">
        <input class="form-control" type="search" name="q" placeholder="Piyasa, kripto, döviz veya haber ara..." value="<?= e($query); ?>">
        <button class="btn btn-efinans primary-button" type="submit">Ara</button>
        <a class="btn btn-outline-light secondary-button" href="search.php">Filtreleri Temizle</a>
    </form>

    <?php if ($query !== '' && !$marketResults && !$newsResults): ?>
        <div class="alert alert-error">Aramanızla eşleşen sonuç bulunamadı.</div>
    <?php endif; ?>

    <div class="section-heading compact"><h2>Piyasa Sonuçları</h2></div>
    <?php if ($marketResults): ?>
        <div class="table-responsive table-wrap terminal-table">
            <table class="table table-dark table-hover align-middle">
                <thead><tr><th>Varlık</th><th>Sembol</th><th>Kategori</th><th>Fiyat</th><th>Değişim</th><th>Durum</th><th>Grafik</th></tr></thead>
                <tbody>
                <?php foreach ($marketResults as $market): ?>
                    <tr>
                        <td><strong><?= e($market['name']); ?></strong></td>
                        <td><?= e($market['symbol']); ?></td>
                        <td><?= e($market['category']); ?></td>
                        <td><?= number_format((float)$market['price'], 4, ',', '.'); ?></td>
                        <td class="<?= (float)$market['change_rate'] >= 0 ? 'positive' : 'negative'; ?>"><?= e($market['change_rate']); ?>%</td>
                        <td><?= e($market['status']); ?></td>
                        <td><a class="btn btn-sm btn-outline-light small-button" href="charts.php?category=<?= urlencode($market['category']); ?>">Aç</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="muted">Piyasa sonucu bulunamadı.</p>
    <?php endif; ?>
</section>

<section class="section">
    <div class="section-heading compact"><h2>Haber Sonuçları</h2></div>
    <?php if ($newsResults): ?>
        <div class="news-grid expanded">
            <?php foreach ($newsResults as $item): ?>
                <article class="news-card card">
                    <?php if (!empty($item['image'])): ?>
                        <img class="news-thumb" src="<?= e($item['image']); ?>" alt="<?= e($item['title']); ?>">
                    <?php else: ?>
                        <div class="news-image"></div>
                    <?php endif; ?>
                    <div>
                        <time datetime="<?= e($item['created_at']); ?>"><?= date('d.m.Y', strtotime($item['created_at'])); ?></time>
                        <h2><?= e($item['title']); ?></h2>
                        <p><?= e($item['summary'] ?: substr($item['content'], 0, 155)); ?></p>
                        <a class="btn btn-sm btn-outline-light small-button" href="news-detail.php?id=<?= (int)$item['id']; ?>">Detay</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="muted">Haber sonucu bulunamadı.</p>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
