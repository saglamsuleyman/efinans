<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Kripto Paralar - EfinanS';
$metaDescription = 'Bitcoin, Ethereum, Solana, BNB ve XRP dahil kripto para piyasalarını EfinanS koyu temalı tabloda takip edin.';

$stmt = $pdo->prepare("SELECT * FROM markets WHERE category = ? ORDER BY market_cap DESC, name");
$stmt->execute(['Kripto']);
$cryptos = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero split-hero">
    <div>
        <p class="eyebrow">Kripto piyasaları</p>
        <h1>Kripto varlıklarda anlık görünüm</h1>
        <p>Bitcoin, Ethereum ve yüksek hacimli altcoinleri piyasa değeri, hacim ve 24 saatlik değişimle izleyin.</p>
    </div>
    <a class="secondary-button" href="charts.php?category=Kripto">Kripto Grafiklerini Aç</a>
</section>

<section class="section">
    <div class="table-wrap trading-table">
        <table>
            <thead>
            <tr>
                <th>Kripto Para</th>
                <th>Sembol</th>
                <th>Fiyat</th>
                <th>24s Değişim</th>
                <th>Hacim</th>
                <th>Piyasa Değeri</th>
                <th>Durum</th>
                <th>Grafik</th>
                <th>İzleme</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cryptos as $crypto): ?>
                <tr>
                    <td><strong><?= e($crypto['name']); ?></strong></td>
                    <td><span class="symbol-pill"><?= e($crypto['symbol']); ?></span></td>
                    <td>$<?= number_format((float)$crypto['price'], 4, ',', '.'); ?></td>
                    <td class="<?= (float)$crypto['change_rate'] >= 0 ? 'positive' : 'negative'; ?>"><?= e($crypto['change_rate']); ?>%</td>
                    <td>$<?= number_format((float)$crypto['volume'], 0, ',', '.'); ?></td>
                    <td>$<?= number_format((float)$crypto['market_cap'], 0, ',', '.'); ?></td>
                    <td><?= e($crypto['status']); ?></td>
                    <td><a class="small-button" href="charts.php?category=Kripto">Grafiği Gör</a></td>
                    <td>
                        <form method="post" action="add_to_watchlist.php">
                            <input type="hidden" name="market_id" value="<?= (int)$crypto['id']; ?>">
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
