<?php
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Döviz Kurları - EfinanS';
$metaDescription = 'USD/TRY, EUR/TRY, GBP/TRY ve diğer döviz kurlarını kart ve tablo görünümüyle takip edin.';

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');
$sort = trim($_GET['sort'] ?? 'price_asc');
$allowedStatuses = ['Yükselişte', 'Düşüşte', 'Stabil'];
$allowedSorts = [
    'price_asc' => 'price ASC',
    'price_desc' => 'price DESC',
];
if (!array_key_exists($sort, $allowedSorts)) {
    $sort = 'price_asc';
}

$conditions = ['category = ?'];
$params = ['Döviz'];

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
$forexItems = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">Döviz piyasası</p>
    <h1>Döviz kurları</h1>
    <p>Majör pariteleri ve Türk lirası karşılıklarını sade kartlar ve profesyonel tablo görünümüyle inceleyin.</p>
</section>

<section class="section">
    <form class="filter-card filter-grid" method="get" action="forex.php">
        <input class="form-control" type="search" name="search" placeholder="Döviz adı veya sembol ara" value="<?= e($search); ?>">
        <select class="form-select" name="status">
            <option value="">Tümü</option>
            <?php foreach ($allowedStatuses as $item): ?>
                <option value="<?= e($item); ?>" <?= $status === $item ? 'selected' : ''; ?>><?= e($item); ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-select" name="sort">
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : ''; ?>>Fiyata göre artan</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : ''; ?>>Fiyata göre azalan</option>
        </select>
        <button class="btn btn-efinans primary-button" type="submit">Filtrele</button>
        <a class="btn btn-outline-light secondary-button" href="forex.php">Filtreleri Temizle</a>
    </form>
    <?php if (!$forexItems): ?>
        <div class="alert alert-error">Aramanızla eşleşen sonuç bulunamadı.</div>
    <?php endif; ?>
    <div class="market-cards">
        <?php foreach ($forexItems as $item): ?>
            <article class="market-card rate-card card">
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
    <div class="table-responsive table-wrap">
        <table class="table table-dark table-hover align-middle">
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
