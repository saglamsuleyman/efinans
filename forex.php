<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Döviz Kurları - EfinanS';
$metaDescription = 'USD/TRY, EUR/TRY, GBP/TRY ve diğer döviz kurlarını kart ve tablo görünümüyle takip edin.';

$stmt = $pdo->prepare("SELECT * FROM markets WHERE category = ? ORDER BY name");
$stmt->execute(['Döviz']);
$forexItems = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">Döviz piyasası</p>
    <h1>Döviz kurları</h1>
    <p>Majör pariteleri ve Türk lirası karşılıklarını sade kartlar ve profesyonel tablo görünümüyle inceleyin.</p>
</section>

<section class="section">
    <div class="market-cards">
        <?php foreach ($forexItems as $item): ?>
            <article class="market-card rate-card">
                <span><?= e($item['symbol']); ?></span>
                <h2><?= e($item['name']); ?></h2>
                <p><?= number_format((float)$item['price'], 4, ',', '.'); ?></p>
                <strong class="<?= (float)$item['change_rate'] >= 0 ? 'positive' : 'negative'; ?>"><?= e($item['change_rate']); ?>%</strong>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section data-section">
    <div class="section-heading compact"><h2>Döviz Tablosu</h2></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Kur</th><th>Sembol</th><th>Fiyat</th><th>Değişim</th><th>Hacim</th><th>Durum</th><th>İzleme</th></tr></thead>
            <tbody>
            <?php foreach ($forexItems as $item): ?>
                <tr>
                    <td><?= e($item['name']); ?></td>
                    <td><?= e($item['symbol']); ?></td>
                    <td><?= number_format((float)$item['price'], 4, ',', '.'); ?></td>
                    <td class="<?= (float)$item['change_rate'] >= 0 ? 'positive' : 'negative'; ?>"><?= e($item['change_rate']); ?>%</td>
                    <td><?= number_format((float)$item['volume'], 0, ',', '.'); ?></td>
                    <td><?= e($item['status']); ?></td>
                    <td>
                        <form method="post" action="add_to_watchlist.php">
                            <input type="hidden" name="market_id" value="<?= (int)$item['id']; ?>">
                            <button class="small-button" type="submit">İzleme Listesine Ekle</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
