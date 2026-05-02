<?php
require_once __DIR__ . '/includes/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    http_response_code(404);
    $pageTitle = 'Haber Bulunamadı - EfinanS';
    $metaDescription = 'Aradığınız EfinanS haberi bulunamadı.';
} else {
    $pageTitle = $article['title'] . ' - EfinanS';
    $metaDescription = $article['summary'] ?: substr($article['content'], 0, 155);
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero article-hero">
    <?php if (!$article): ?>
        <h1>Haber bulunamadı</h1>
        <p>Aradığınız haber kaldırılmış veya hiç oluşturulmamış olabilir.</p>
    <?php else: ?>
        <p class="eyebrow"><?= date('d.m.Y H:i', strtotime($article['created_at'])); ?></p>
        <h1><?= e($article['title']); ?></h1>
        <p><?= e($metaDescription); ?></p>
    <?php endif; ?>
</section>
<?php if ($article): ?>
    <article class="section article-body" itemscope itemtype="https://schema.org/Article">
        <?php if (!empty($article['image'])): ?>
            <img class="article-thumb" src="<?= e($article['image']); ?>" alt="<?= e($article['title']); ?>" itemprop="image">
        <?php else: ?>
            <div class="news-image article-image"></div>
        <?php endif; ?>
        <p><?= nl2br(e($article['content'])); ?></p>
        <a class="btn btn-outline-light secondary-button" href="news.php">Tüm Haberlere Dön</a>
    </article>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
