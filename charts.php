<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Finans Grafikleri - EfinanS';
$metaDescription = 'EfinanS grafik sayfasında kripto, döviz ve emtia piyasalarını çizgi, bar ve değişim grafikleriyle görüntüleyin.';

$selectedCategory = trim($_GET['category'] ?? '');
$categories = ['Kripto', 'Döviz', 'Emtia'];
$chartData = [];

foreach ($categories as $category) {
    $stmt = $pdo->prepare('SELECT name, symbol, price, change_rate, volume FROM markets WHERE category = ? ORDER BY name');
    $stmt->execute([$category]);
    $chartData[$category] = $stmt->fetchAll();
}

function jsString(string $value): string
{
    return "'" . str_replace(["\\", "'", "</"], ["\\\\", "\\'", "<\/"], $value) . "'";
}

function jsArray(array $values, string $key, bool $string = false): string
{
    $items = [];
    foreach ($values as $value) {
        $items[] = $string ? jsString((string)$value[$key]) : (string)(float)$value[$key];
    }
    return '[' . implode(',', $items) . ']';
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">Grafik merkezi</p>
    <h1>Piyasa grafikleri</h1>
    <p>MySQL’den gelen finans verileriyle koyu temalı çizgi, bar ve değişim grafiklerini inceleyin.</p>
</section>

<section class="section chart-grid">
    <?php foreach ($categories as $category): ?>
        <article class="chart-card <?= $selectedCategory === $category ? 'highlight' : ''; ?>">
            <div class="section-heading compact">
                <h2><?= e($category); ?> Fiyat Grafiği</h2>
            </div>
            <canvas id="chart<?= e($category); ?>Line" height="150"></canvas>
        </article>
        <article class="chart-card">
            <div class="section-heading compact">
                <h2><?= e($category); ?> Hacim Grafiği</h2>
            </div>
            <canvas id="chart<?= e($category); ?>Bar" height="150"></canvas>
        </article>
        <article class="chart-card">
            <div class="section-heading compact">
                <h2><?= e($category); ?> Değişim Grafiği</h2>
            </div>
            <canvas id="chart<?= e($category); ?>Change" height="150"></canvas>
        </article>
    <?php endforeach; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.efinansCharts = {
<?php foreach ($categories as $category): ?>
    '<?= e($category); ?>': {
        labels: <?= jsArray($chartData[$category], 'symbol', true); ?>,
        prices: <?= jsArray($chartData[$category], 'price'); ?>,
        changes: <?= jsArray($chartData[$category], 'change_rate'); ?>,
        volumes: <?= jsArray($chartData[$category], 'volume'); ?>
    },
<?php endforeach; ?>
};
</script>
<script src="assets/js/charts.js"></script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
