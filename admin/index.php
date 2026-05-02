<?php
require_once __DIR__ . '/includes/admin_auth.php';

$adminPageTitle = 'Dashboard - EfinanS Admin';
$adminHeading = 'Dashboard';

$stats = [
    'users' => $pdo->query('SELECT COUNT(*) AS total FROM users')->fetch()['total'],
    'markets' => $pdo->query('SELECT COUNT(*) AS total FROM markets')->fetch()['total'],
    'news' => $pdo->query('SELECT COUNT(*) AS total FROM news')->fetch()['total'],
    'messages' => $pdo->query('SELECT COUNT(*) AS total FROM contact_messages')->fetch()['total'],
];

$latestUsers = $pdo->query('SELECT full_name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5')->fetchAll();
$latestNews = $pdo->query('SELECT title, created_at FROM news ORDER BY created_at DESC LIMIT 5')->fetchAll();
$latestMessages = $pdo->query('SELECT name, email, subject, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 5')->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-grid">
    <article class="admin-card card"><span>Toplam Kullanıcı</span><strong><?= (int)$stats['users']; ?></strong></article>
    <article class="admin-card card"><span>Piyasa Verisi</span><strong><?= (int)$stats['markets']; ?></strong></article>
    <article class="admin-card card"><span>Haber</span><strong><?= (int)$stats['news']; ?></strong></article>
    <article class="admin-card card"><span>İletişim Mesajı</span><strong><?= (int)$stats['messages']; ?></strong></article>
</section>

<section class="admin-sections">
    <article class="admin-panel card quick-actions">
        <h2>Hızlı İşlemler</h2>
        <div class="quick-action-grid">
            <a class="admin-ghost" href="settings.php">Site Ayarlarını Düzenle</a>
            <a class="admin-ghost" href="markets.php">Piyasa Verisi Ekle</a>
            <a class="admin-ghost" href="news.php">Haber Ekle</a>
            <a class="admin-ghost" href="messages.php">Mesajları Gör</a>
        </div>
    </article>
    <article class="admin-panel card">
        <h2>Son Kayıt Olan Kullanıcılar</h2>
        <div class="admin-list">
            <?php foreach ($latestUsers as $user): ?>
                <div class="admin-list-item">
                    <div><strong><?= e($user['full_name']); ?></strong><br><span class="admin-muted"><?= e($user['email']); ?></span></div>
                    <span class="admin-badge"><?= e($user['role']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </article>
    <article class="admin-panel card">
        <h2>Son Eklenen Haberler</h2>
        <div class="admin-list">
            <?php foreach ($latestNews as $item): ?>
                <div class="admin-list-item">
                    <strong><?= e($item['title']); ?></strong>
                    <span class="admin-muted"><?= date('d.m.Y', strtotime($item['created_at'])); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </article>
    <article class="admin-panel card">
        <h2>Son Gelen Mesajlar</h2>
        <div class="admin-list">
            <?php foreach ($latestMessages as $message): ?>
                <div class="admin-list-item">
                    <div><strong><?= e($message['subject']); ?></strong><br><span class="admin-muted"><?= e($message['name']); ?> · <?= e($message['email']); ?></span></div>
                    <span class="admin-muted"><?= date('d.m.Y', strtotime($message['created_at'])); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </article>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
