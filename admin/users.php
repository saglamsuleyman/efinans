<?php
require_once __DIR__ . '/includes/admin_auth.php';

$adminPageTitle = 'Kullanıcı Yönetimi - EfinanS Admin';
$adminHeading = 'Kullanıcı Yönetimi';
$currentUserId = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = (int)($_POST['user_id'] ?? 0);

    if ($action === 'role' && $userId > 0) {
        $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
        $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
        $stmt->execute([$role, $userId]);
        adminFlash('Kullanıcı rolü güncellendi.');
    }

    if ($action === 'delete' && $userId > 0) {
        if ($userId === $currentUserId) {
            adminFlash('Kendi admin hesabınızı silemezsiniz.', 'error');
        } else {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            adminFlash('Kullanıcı silindi.');
        }
    }

    header('Location: users.php');
    exit;
}

$search = trim($_GET['search'] ?? '');
$role = trim($_GET['role'] ?? '');
$allowedRoles = ['user', 'admin'];
$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = '(full_name LIKE ? OR email LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

if (in_array($role, $allowedRoles, true)) {
    $conditions[] = 'role = ?';
    $params[] = $role;
}

$sql = 'SELECT id, full_name, email, role, created_at FROM users';
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();
require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-panel card">
    <h2>Kullanıcılar</h2>
    <form class="admin-filter-card" method="get" action="users.php">
        <input class="form-control" type="search" name="search" placeholder="Kullanıcı adı veya e-posta ara" value="<?= e($search); ?>">
        <select class="form-select" name="role">
            <option value="">Tüm roller</option>
            <?php foreach ($allowedRoles as $item): ?>
                <option value="<?= e($item); ?>" <?= $role === $item ? 'selected' : ''; ?>><?= e($item); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn admin-button" type="submit">Filtrele</button>
        <a class="btn admin-ghost" href="users.php">Filtreleri Temizle</a>
    </form>
    <?php if (!$users): ?>
        <p class="admin-muted">Aramanızla eşleşen sonuç bulunamadı.</p>
    <?php endif; ?>
    <div class="table-responsive admin-table-wrap">
        <table class="table table-dark table-hover align-middle admin-table">
            <thead><tr><th>ID</th><th>Ad Soyad</th><th>E-posta</th><th>Rol</th><th>Kayıt Tarihi</th><th>İşlem</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= (int)$user['id']; ?></td>
                    <td><?= e($user['full_name']); ?></td>
                    <td><?= e($user['email']); ?></td>
                    <td><span class="admin-badge"><?= e($user['role']); ?></span></td>
                    <td><?= date('d.m.Y H:i', strtotime($user['created_at'])); ?></td>
                    <td>
                        <form class="inline-form" method="post" action="users.php">
                            <input type="hidden" name="action" value="role">
                            <input type="hidden" name="user_id" value="<?= (int)$user['id']; ?>">
                            <select class="form-select form-select-sm" name="role">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>user</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
                            </select>
                            <button class="btn btn-sm admin-small" type="submit">Kaydet</button>
                        </form>
                        <form class="inline-form" method="post" action="users.php">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= (int)$user['id']; ?>">
                            <button class="btn btn-sm admin-danger" type="submit" data-confirm="Bu kullanıcı silinsin mi?" <?= (int)$user['id'] === $currentUserId ? 'disabled' : ''; ?>>Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
