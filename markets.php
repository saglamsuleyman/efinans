<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Piyasalar - EfinanS Finans Piyasa Verileri';
$metaDescription = 'EfinanS piyasalar sayfasında döviz, kripto, emtia ve borsa varlıklarını arayın, filtreleyin ve güncel fiyatları takip edin.';

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$status = trim($_GET['status'] ?? '');
$sort = trim($_GET['sort'] ?? 'name_asc');
$allowedCategories = ['Döviz', 'Kripto', 'Emtia', 'Borsa'];
$allowedStatuses = ['Yükselişte', 'Düşüşte', 'Stabil'];
$allowedSorts = [
    'name_asc' => 'name ASC',
    'price_asc' => 'price ASC',
    'price_desc' => 'price DESC',
    'change_asc' => 'change_rate ASC',
    'change_desc' => 'change_rate DESC',
];
if (!array_key_exists($sort, $allowedSorts)) {
    $sort = 'name_asc';
}
$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = '(name LIKE ? OR symbol LIKE ? OR category LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if (in_array($category, $allowedCategories, true)) {
    $conditions[] = 'category = ?';
    $params[] = $category;
}

if (in_array($status, $allowedStatuses, true)) {
    $conditions[] = 'status = ?';
    $params[] = $status;
}

$sql = 'SELECT * FROM markets';
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY ' . $allowedSorts[$sort];

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
    <form class="filter-card filter-grid markets-filter" method="get" action="markets.php">
        <input class="form-control" type="search" name="search" placeholder="Varlık veya sembol ara" value="<?= e($search); ?>">
        <select class="form-select" name="category">
            <option value="">Tümü</option>
            <?php foreach ($allowedCategories as $item): ?>
                <option value="<?= e($item); ?>" <?= $category === $item ? 'selected' : ''; ?>><?= e($item); ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-select" name="status">
            <option value="">Tümü</option>
            <?php foreach ($allowedStatuses as $item): ?>
                <option value="<?= e($item); ?>" <?= $status === $item ? 'selected' : ''; ?>><?= e($item); ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-select" name="sort">
            <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : ''; ?>>Ada göre A-Z</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : ''; ?>>Fiyata göre artan</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : ''; ?>>Fiyata göre azalan</option>
            <option value="change_asc" <?= $sort === 'change_asc' ? 'selected' : ''; ?>>Değişim oranına göre artan</option>
            <option value="change_desc" <?= $sort === 'change_desc' ? 'selected' : ''; ?>>Değişim oranına göre azalan</option>
        </select>
        <button class="btn btn-efinans primary-button" type="submit">Filtrele</button>
        <a class="btn btn-outline-light secondary-button" href="markets.php">Filtreleri Temizle</a>
    </form>
    <?php if (!$markets): ?>
        <div class="alert alert-error">Aramanızla eşleşen sonuç bulunamadı.</div>
    <?php endif; ?>
    <div class="table-responsive table-wrap terminal-table">
        <table class="table table-dark table-hover align-middle">
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
                    <td><a class="btn btn-sm btn-outline-light small-button" href="charts.php?category=<?= urlencode($market['category']); ?>">Aç</a></td>
                    <td>
                        <form method="post" action="add_to_watchlist.php">
                            <input type="hidden" name="market_id" value="<?= (int)$market['id']; ?>">
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
