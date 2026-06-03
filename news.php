<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Finans Haberleri - EfinanS';
$metaDescription = 'EfinanS haberler sayfasında admin panelinden manuel eklenen finans, piyasa, kripto, döviz ve emtia haberlerini okuyun.';

$search = trim($_GET['search'] ?? '');
$params = [];
$sql = 'SELECT * FROM news';

if ($search !== '') {
    $sql .= ' WHERE title LIKE ? OR summary LIKE ?';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$newsItems = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">Finans gündemi</p>
    <h1>Finans haberleri</h1>
    <p>Admin panelinden manuel eklenen finans haberlerini, özetleri ve detay sayfalarıyla takip edin.</p>
</section>
<section class="section">
    <form class="filter-card filter-grid" method="get" action="news.php">
        <input class="form-control" type="search" name="search" placeholder="Haber başlığı veya özet ara" value="<?= e($search); ?>">
        <button class="btn btn-efinans primary-button" type="submit">Ara</button>
        <a class="btn btn-outline-light secondary-button" href="news.php">Filtreleri Temizle</a>
    </form>
    <?php if (!$newsItems): ?>
        <div class="alert alert-error">Aramanızla eşleşen sonuç bulunamadı.</div>
    <?php endif; ?>
    <div class="news-grid expanded">
        <?php foreach ($newsItems as $item): ?>
            <article class="news-card card" itemscope itemtype="https://schema.org/Article">
                <?php if (!empty($item['image'])): ?>
                    <img class="news-thumb" src="<?= e($item['image']); ?>" alt="<?= e($item['title']); ?>" itemprop="image">
                <?php else: ?>
                    <div class="news-image"></div>
                <?php endif; ?>
                <div>
                    <time datetime="<?= e($item['created_at']); ?>"><?= date('d.m.Y', strtotime($item['created_at'])); ?></time>
                    <h2 itemprop="headline"><?= e($item['title']); ?></h2>
                    <p itemprop="description"><?= e($item['summary'] ?: substr($item['content'], 0, 155)); ?></p>
                    <a class="btn btn-sm btn-outline-light small-button" href="news-detail.php?id=<?= (int)$item['id']; ?>">Detay</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
