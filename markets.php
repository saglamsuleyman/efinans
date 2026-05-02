<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Piyasalar - EfinanS Finans Piyasa Verileri';
$metaDescription = 'EfinanS piyasalar sayfasında döviz, kripto, emtia ve borsa varlıklarını arayın, filtreleyin ve güncel fiyatları takip edin.';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$allowedCategories = ['Döviz', 'Kripto', 'Emtia', 'Borsa'];
$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = '(name LIKE ? OR symbol LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if (in_array($category, $allowedCategories, true)) {
    $conditions[] = 'category = ?';
    $params[] = $category;
}

$sql = 'SELECT * FROM markets';
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY category, name';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$markets = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">Piyasa terminali</p>
    <h1>Tüm finans piyasaları</h1>
    <p>Döviz, kripto, emtia ve borsa varlıklarını tek tabloda arayın, filtreleyin ve fiyat hareketlerini hızlıca okuyun.</p>
</section>

<section class="section">
    <form class="filter-bar" method="get" action="markets.php">
        <input type="search" name="search" placeholder="Varlık veya sembol ara" value="<?= e($search); ?>">
        <select name="category">
            <option value="">Tüm kategoriler</option>
            <?php foreach ($allowedCategories as $item): ?>
                <option value="<?= e($item); ?>" <?= $category === $item ? 'selected' : ''; ?>><?= e($item); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="primary-button" type="submit">Filtrele</button>
    </form>
    <div class="table-wrap terminal-table">
        <table>
            <thead>
            <tr>
                <th>Varlık</th>
                <th>Sembol</th>
                <th>Kategori</th>
                <th>Fiyat</th>
                <th>Değişim</th>
                <th>Hacim</th>
                <th>Durum</th>
                <th>Grafik</th>
                <th>İzleme</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($markets as $market): ?>
                <tr>
                    <td><strong><?= e($market['name']); ?></strong></td>
                    <td><?= e($market['symbol']); ?></td>
                    <td><?= e($market['category']); ?></td>
                    <td><?= number_format((float)$market['price'], 4, ',', '.'); ?></td>
                    <td class="<?= (float)$market['change_rate'] >= 0 ? 'positive' : 'negative'; ?>"><?= e($market['change_rate']); ?>%</td>
                    <td><?= number_format((float)$market['volume'], 0, ',', '.'); ?></td>
                    <td><?= e($market['status']); ?></td>
                    <td><a class="small-button" href="charts.php?category=<?= urlencode($market['category']); ?>">Aç</a></td>
                    <td>
                        <form method="post" action="add_to_watchlist.php">
                            <input type="hidden" name="market_id" value="<?= (int)$market['id']; ?>">
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
