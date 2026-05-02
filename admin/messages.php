<?php
require_once __DIR__ . '/includes/admin_auth.php';

$adminPageTitle = 'İletişim Mesajları - EfinanS Admin';
$adminHeading = 'İletişim Mesajları';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageId = (int)($_POST['message_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($messageId > 0 && $action === 'read') {
        $stmt = $pdo->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
        $stmt->execute([$messageId]);
        adminFlash('Mesaj okundu olarak işaretlendi.');
    }

    if ($messageId > 0 && $action === 'unread') {
        $stmt = $pdo->prepare('UPDATE contact_messages SET is_read = 0 WHERE id = ?');
        $stmt->execute([$messageId]);
        adminFlash('Mesaj okunmadı olarak işaretlendi.');
    }

    if ($messageId > 0 && $action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM contact_messages WHERE id = ?');
        $stmt->execute([$messageId]);
        adminFlash('Mesaj silindi.');
    }

    header('Location: messages.php');
    exit;
}

$messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-message-grid">
    <?php if (!$messages): ?>
        <div class="admin-panel"><p class="admin-muted">Henüz iletişim mesajı bulunmuyor.</p></div>
    <?php endif; ?>
    <?php foreach ($messages as $message): ?>
        <article class="admin-message-card">
            <header>
                <div>
                    <h2><?= e($message['subject']); ?></h2>
                    <span class="admin-muted"><?= e($message['name']); ?> · <?= e($message['email']); ?></span>
                </div>
                <span class="admin-badge <?= (int)$message['is_read'] === 1 ? 'read' : ''; ?>"><?= (int)$message['is_read'] === 1 ? 'Okundu' : 'Okunmadı'; ?></span>
            </header>
            <p><?= nl2br(e($message['message'])); ?></p>
            <footer>
                <time class="admin-muted"><?= date('d.m.Y H:i', strtotime($message['created_at'])); ?></time>
                <div class="admin-top-actions">
                    <form method="post" action="messages.php">
                        <input type="hidden" name="message_id" value="<?= (int)$message['id']; ?>">
                        <input type="hidden" name="action" value="<?= (int)$message['is_read'] === 1 ? 'unread' : 'read'; ?>">
                        <button class="admin-small" type="submit"><?= (int)$message['is_read'] === 1 ? 'Okunmadı Yap' : 'Okundu Yap'; ?></button>
                    </form>
                    <form method="post" action="messages.php">
                        <input type="hidden" name="message_id" value="<?= (int)$message['id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button class="admin-danger" type="submit" data-confirm="Bu mesaj silinsin mi?">Sil</button>
                    </form>
                </div>
            </footer>
        </article>
    <?php endforeach; ?>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
