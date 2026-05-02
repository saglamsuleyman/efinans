<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/settings.php';

$pageTitle = 'EfinanS - Piyasalar, Döviz, Kripto, Emtia ve Finans Haberleri';
$metaDescription = 'EfinanS finans platformunda piyasa özetleri, döviz kurları, kripto varlıklar, emtia fiyatları ve güncel finans haberlerini takip edin.';

$markets = $pdo->query('SELECT * FROM markets ORDER BY category, name')->fetchAll();
$newsItems = $pdo->query('SELECT * FROM news ORDER BY created_at DESC LIMIT 3')->fetchAll();
$siteSettings = getSiteSettings($pdo);

$byCategory = [];
foreach ($markets as $market) {
    $byCategory[$market['category']][] = $market;
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">Canlı piyasa odağı</p>
        <h1><?= e(settingValue($siteSettings, 'hero_title', 'EfinanS ile piyasaları güçlü, hızlı ve sade takip edin.')); ?></h1>
        <p><?= e(settingValue($siteSettings, 'hero_description', 'Döviz, kripto, emtia, borsa verileri, manuel eklenen finans haberleri ve hesap araçlarıyla karar süreçlerinizi güçlendirin.')); ?></p>
        <div class="hero-actions">
            <a class="primary-button" href="login.php">İşlem yapmak için giriş yapın</a>
            <a class="secondary-button" href="#piyasalar">Piyasaları İncele</a>
            <a class="secondary-button" href="charts.php">Grafikleri Aç</a>
        </div>
    </div>
    <aside class="hero-panel" aria-label="Piyasa özeti">
        <h2>Piyasa Nabzı</h2>
        <?php foreach (array_slice($markets, 0, 4) as $market): ?>
            <div class="ticker-row">
                <strong><?= e($market['name']); ?></strong>
                <span><?= number_format((float)$market['price'], 2, ',', '.'); ?></span>
                <em class="<?= (float)$market['change_rate'] >= 0 ? 'positive' : 'negative'; ?>">
                    <?= e($market['change_rate']); ?>%
                </em>
            </div>
        <?php endforeach; ?>
    </aside>
</section>

<section class="section cta-band">
    <div>
        <p class="eyebrow">Profesyonel finans platformu</p>
        <h2>Piyasa ekranları, grafikler, haberler ve hesap makineleri tek yerde.</h2>
    </div>
    <div class="hero-actions">
        <a class="primary-button" href="register.php">Ücretsiz Kayıt Ol</a>
        <a class="secondary-button" href="calculator.php">Hesap Makinesini Aç</a>
    </div>
</section>

<section id="piyasalar" class="section">
    <div class="section-heading">
        <p class="eyebrow">Özet kartlar</p>
        <h2>Finans piyasalarına hızlı bakış</h2>
    </div>
    <div class="market-cards">
        <?php foreach (array_slice($markets, 0, 8) as $market): ?>
            <article class="market-card">
                <span><?= e($market['category']); ?></span>
                <h3><?= e($market['name']); ?></h3>
                <p><?= number_format((float)$market['price'], 4, ',', '.'); ?></p>
                <strong class="<?= (float)$market['change_rate'] >= 0 ? 'positive' : 'negative'; ?>">
                    <?= e($market['change_rate']); ?>% · <?= e($market['status']); ?>
                </strong>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php foreach (['Döviz' => 'doviz', 'Kripto' => 'kripto', 'Emtia' => 'emtia', 'Borsa' => 'borsa'] as $category => $anchor): ?>
    <section id="<?= $anchor; ?>" class="section data-section">
        <div class="section-heading compact">
            <h2><?= e($category); ?> Tablosu</h2>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Varlık</th>
                    <th>Fiyat</th>
                    <th>Değişim</th>
                    <th>Durum</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($byCategory[$category] ?? [] as $market): ?>
                    <tr>
                        <td><?= e($market['name']); ?></td>
                        <td><?= number_format((float)$market['price'], 4, ',', '.'); ?></td>
                        <td class="<?= (float)$market['change_rate'] >= 0 ? 'positive' : 'negative'; ?>"><?= e($market['change_rate']); ?>%</td>
                        <td><?= e($market['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endforeach; ?>

<section class="section">
    <div class="section-heading">
        <p class="eyebrow">Güncel haberler</p>
        <h2>Son finans haberleri</h2>
    </div>
    <div class="news-grid">
        <?php foreach ($newsItems as $item): ?>
            <article class="news-card">
                <div class="news-image"></div>
                <div>
                    <time datetime="<?= e($item['created_at']); ?>"><?= date('d.m.Y', strtotime($item['created_at'])); ?></time>
                    <h3><?= e($item['title']); ?></h3>
                    <p><?= e($item['summary'] ?: substr($item['content'], 0, 140)); ?></p>
                    <a class="small-button" href="news-detail.php?id=<?= (int)$item['id']; ?>">Detay</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
