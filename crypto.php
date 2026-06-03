<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Kripto Paralar - EfinanS';
$metaDescription = 'Bitcoin, Ethereum, Solana, BNB ve XRP dahil kripto para piyasalarını EfinanS koyu temalı tabloda takip edin.';

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');
$sort = trim($_GET['sort'] ?? 'price_desc');
$allowedStatuses = ['Yükselişte', 'Düşüşte', 'Stabil'];
$allowedSorts = [
    'price_asc' => 'price ASC',
    'price_desc' => 'price DESC',
];
if (!array_key_exists($sort, $allowedSorts)) {
    $sort = 'price_desc';
}

$conditions = ['category = ?'];
$params = ['Kripto'];

if ($search !== '') {
    $conditions[] = '(name LIKE ? OR symbol LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if (in_array($status, $allowedStatuses, true)) {
    $conditions[] = 'status = ?';
    $params[] = $status;
}

$sql = 'SELECT * FROM markets WHERE ' . implode(' AND ', $conditions) . ' ORDER BY ' . $allowedSorts[$sort] . ', name';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$cryptos = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero split-hero">
    <div>
        <p class="eyebrow">Kripto piyasaları</p>
        <h1>Kripto varlıklarda anlık görünüm</h1>
        <p>Bitcoin, Ethereum ve yüksek hacimli altcoinleri piyasa değeri, hacim ve 24 saatlik değişimle izleyin.</p>
    </div>
    <a class="btn btn-outline-light secondary-button" href="charts.php?category=Kripto">Kripto Grafiklerini Aç</a>
</section>

<section class="section">
    <form class="filter-card filter-grid" method="get" action="crypto.php">
        <input class="form-control" type="search" name="search" placeholder="Kripto adı veya sembol ara" value="<?= e($search); ?>">
        <select class="form-select" name="status">
            <option value="">Tümü</option>
            <?php foreach ($allowedStatuses as $item): ?>
                <option value="<?= e($item); ?>" <?= $status === $item ? 'selected' : ''; ?>><?= e($item); ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-select" name="sort">
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : ''; ?>>Fiyata göre azalan</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : ''; ?>>Fiyata göre artan</option>
        </select>
        <button class="btn btn-efinans primary-button" type="submit">Filtrele</button>
        <a class="btn btn-outline-light secondary-button" href="crypto.php">Filtreleri Temizle</a>
    </form>
    <?php if (!$cryptos): ?>
        <div class="alert alert-error">Aramanızla eşleşen sonuç bulunamadı.</div>
    <?php endif; ?>
    <div class="table-responsive table-wrap trading-table">
        <table class="table table-dark table-hover align-middle">
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
                    <td><a class="btn btn-sm btn-outline-light small-button" href="charts.php?category=Kripto">Grafiği Gör</a></td>
                    <td>
                        <form method="post" action="add_to_watchlist.php">
                            <input type="hidden" name="market_id" value="<?= (int)$crypto['id']; ?>">
                            <button class="btn btn-sm btn-efinans small-button" type="submit">İzleme Listesine Ekle</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
