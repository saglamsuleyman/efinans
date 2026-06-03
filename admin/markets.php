<?php
require_once __DIR__ . '/includes/admin_auth.php';

$adminPageTitle = 'Piyasa Yönetimi - EfinanS Admin';
$adminHeading = 'Piyasa Yönetimi';
$editing = null;

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM markets WHERE id = ?');
    $stmt->execute([(int)$_GET['delete']]);
    adminFlash('Piyasa verisi silindi.');
    header('Location: markets.php');
    exit;
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM markets WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        trim($_POST['name'] ?? ''),
        trim($_POST['symbol'] ?? ''),
        $_POST['category'] ?? 'Döviz',
        (float)($_POST['price'] ?? 0),
        (float)($_POST['change_rate'] ?? 0),
        $_POST['status'] ?? 'Stabil',
        (float)($_POST['volume'] ?? 0),
        (float)($_POST['market_cap'] ?? 0),
    ];

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE markets SET name = ?, symbol = ?, category = ?, price = ?, change_rate = ?, status = ?, volume = ?, market_cap = ? WHERE id = ?');
        $stmt->execute([...$data, $id]);
        adminFlash('Piyasa verisi güncellendi.');
    } else {
        $stmt = $pdo->prepare('INSERT INTO markets (name, symbol, category, price, change_rate, status, volume, market_cap) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute($data);
        adminFlash('Piyasa verisi eklendi.');
    }

    header('Location: markets.php');
    exit;
}

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$allowedCategories = ['Döviz', 'Kripto', 'Emtia', 'Borsa'];
$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = 'name LIKE ?';
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
require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-form-card card">
    <h2><?= $editing ? 'Piyasa Verisi Düzenle' : 'Yeni Piyasa Verisi'; ?></h2>
    <form class="admin-form" method="post" action="markets.php">
        <input type="hidden" name="id" value="<?= (int)($editing['id'] ?? 0); ?>">
        <label>Ad<input class="form-control" name="name" type="text" value="<?= e($editing['name'] ?? ''); ?>" required></label>
        <label>Sembol<input class="form-control" name="symbol" type="text" value="<?= e($editing['symbol'] ?? ''); ?>"></label>
        <label>Kategori
            <select class="form-select" name="category">
                <?php foreach (['Döviz', 'Kripto', 'Emtia', 'Borsa'] as $cat): ?>
                    <option value="<?= e($cat); ?>" <?= ($editing['category'] ?? '') === $cat ? 'selected' : ''; ?>><?= e($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Fiyat<input class="form-control" name="price" type="number" step="0.0001" value="<?= e((string)($editing['price'] ?? '')); ?>" required></label>
        <label>Değişim (%)<input class="form-control" name="change_rate" type="number" step="0.01" value="<?= e((string)($editing['change_rate'] ?? '')); ?>" required></label>
        <label>Durum
            <select class="form-select" name="status">
                <?php foreach (['Yükselişte', 'Düşüşte', 'Stabil'] as $status): ?>
                    <option value="<?= e($status); ?>" <?= ($editing['status'] ?? '') === $status ? 'selected' : ''; ?>><?= e($status); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Hacim<input class="form-control" name="volume" type="number" step="0.01" value="<?= e((string)($editing['volume'] ?? '')); ?>"></label>
        <label>Piyasa Değeri<input class="form-control" name="market_cap" type="number" step="0.01" value="<?= e((string)($editing['market_cap'] ?? '')); ?>"></label>
        <button class="btn admin-button span-4" type="submit"><?= $editing ? 'Güncelle' : 'Ekle'; ?></button>
    </form>
</section>

<section class="admin-panel card">
    <h2>Piyasa Verileri</h2>
    <form class="admin-filter-card" method="get" action="markets.php">
        <input class="form-control" type="search" name="search" placeholder="Piyasa adına göre ara" value="<?= e($search); ?>">
        <select class="form-select" name="category">
            <option value="">Tüm kategoriler</option>
            <?php foreach ($allowedCategories as $cat): ?>
                <option value="<?= e($cat); ?>" <?= $category === $cat ? 'selected' : ''; ?>><?= e($cat); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn admin-button" type="submit">Filtrele</button>
        <a class="btn admin-ghost" href="markets.php">Filtreleri Temizle</a>
    </form>
    <?php if (!$markets): ?>
        <p class="admin-muted">Aramanızla eşleşen sonuç bulunamadı.</p>
    <?php endif; ?>
    <div class="table-responsive admin-table-wrap">
        <table class="table table-dark table-hover align-middle admin-table">
            <thead><tr><th>Ad</th><th>Sembol</th><th>Kategori</th><th>Fiyat</th><th>Değişim</th><th>Durum</th><th>Hacim</th><th>Piyasa Değeri</th><th>İşlem</th></tr></thead>
            <tbody>
            <?php foreach ($markets as $market): ?>
                <tr>
                    <td><?= e($market['name']); ?></td>
                    <td><?= e($market['symbol']); ?></td>
                    <td><?= e($market['category']); ?></td>
                    <td><?= number_format((float)$market['price'], 4, ',', '.'); ?></td>
                    <td><?= e($market['change_rate']); ?>%</td>
                    <td><?= e($market['status']); ?></td>
                    <td><?= number_format((float)$market['volume'], 0, ',', '.'); ?></td>
                    <td><?= number_format((float)$market['market_cap'], 0, ',', '.'); ?></td>
                    <td><a class="admin-small" href="markets.php?edit=<?= (int)$market['id']; ?>">Düzenle</a> <a class="admin-danger" href="markets.php?delete=<?= (int)$market['id']; ?>" data-confirm="Bu piyasa verisi silinsin mi?">Sil</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
