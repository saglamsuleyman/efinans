<?php
require_once __DIR__ . '/includes/admin_auth.php';

$adminPageTitle = 'Haber Yönetimi - EfinanS Admin';
$adminHeading = 'Haber Yönetimi';
$editing = null;

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM news WHERE id = ?');
    $stmt->execute([(int)$_GET['delete']]);
    adminFlash('Haber silindi.');
    header('Location: news.php');
    exit;
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editing = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $createdAt = str_replace('T', ' ', trim($_POST['created_at'] ?? date('Y-m-d H:i:s')));
    if (strlen($createdAt) === 16) {
        $createdAt .= ':00';
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE news SET title = ?, summary = ?, content = ?, image = ?, created_at = ? WHERE id = ?');
        $stmt->execute([$title, $summary, $content, $image, $createdAt, $id]);
        adminFlash('Haber güncellendi.');
    } else {
        $stmt = $pdo->prepare('INSERT INTO news (title, summary, content, image, created_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$title, $summary, $content, $image, $createdAt]);
        adminFlash('Haber eklendi.');
    }

    header('Location: news.php');
    exit;
}

$newsItems = $pdo->query('SELECT * FROM news ORDER BY created_at DESC')->fetchAll();
require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-form-card">
    <h2><?= $editing ? 'Haber Düzenle' : 'Manuel Haber Ekle'; ?></h2>
    <p class="admin-muted">Haberler sadece manuel eklenir. Online haber çekme, API, RSS veya scraping kullanılmaz.</p>
    <form class="admin-form stacked" method="post" action="news.php">
        <input type="hidden" name="id" value="<?= (int)($editing['id'] ?? 0); ?>">
        <label>Başlık<input name="title" type="text" value="<?= e($editing['title'] ?? ''); ?>" required></label>
        <label>Özet<textarea name="summary" rows="3" required><?= e($editing['summary'] ?? ''); ?></textarea></label>
        <label>İçerik<textarea name="content" rows="7" required><?= e($editing['content'] ?? ''); ?></textarea></label>
        <label>Görsel Yolu<input name="image" type="text" value="<?= e($editing['image'] ?? ''); ?>" placeholder="assets/images/haber.jpg"></label>
        <label>Oluşturulma Tarihi<input name="created_at" type="datetime-local" value="<?= isset($editing['created_at']) ? e(date('Y-m-d\TH:i', strtotime($editing['created_at']))) : e(date('Y-m-d\TH:i')); ?>"></label>
        <button class="admin-button" type="submit"><?= $editing ? 'Güncelle' : 'Ekle'; ?></button>
    </form>
</section>

<section class="admin-panel">
    <h2>Haberler</h2>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Başlık</th><th>Özet</th><th>Tarih</th><th>İşlem</th></tr></thead>
            <tbody>
            <?php foreach ($newsItems as $item): ?>
                <tr>
                    <td><?= e($item['title']); ?></td>
                    <td><?= e($item['summary'] ?? ''); ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($item['created_at'])); ?></td>
                    <td><a class="admin-small" href="news.php?edit=<?= (int)$item['id']; ?>">Düzenle</a> <a class="admin-danger" href="news.php?delete=<?= (int)$item['id']; ?>" data-confirm="Bu haber silinsin mi?">Sil</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
