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

$users = $pdo->query('SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-panel">
    <h2>Kullanıcılar</h2>
    <div class="admin-table-wrap">
        <table class="admin-table">
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
                            <select name="role">
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>user</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
                            </select>
                            <button class="admin-small" type="submit">Kaydet</button>
                        </form>
                        <form class="inline-form" method="post" action="users.php">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= (int)$user['id']; ?>">
                            <button class="admin-danger" type="submit" data-confirm="Bu kullanıcı silinsin mi?" <?= (int)$user['id'] === $currentUserId ? 'disabled' : ''; ?>>Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
