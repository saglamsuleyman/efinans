<aside class="admin-sidebar-panel">
    <a class="admin-brand" href="index.php"><span>E</span>finanS</a>
    <p class="admin-sidebar-user"><?= e($_SESSION['admin_name'] ?? 'Yönetici'); ?><br><span><?= e($_SESSION['admin_email'] ?? ''); ?></span></p>
    <nav class="admin-nav" aria-label="Admin menü">
        <a class="<?= adminActive('index.php'); ?>" href="index.php">Dashboard</a>
        <a class="<?= adminActive('settings.php'); ?>" href="settings.php">Site Ayarları</a>
        <a class="<?= adminActive('markets.php'); ?>" href="markets.php">Piyasa Yönetimi</a>
        <a class="<?= adminActive('news.php'); ?>" href="news.php">Haber Yönetimi</a>
        <a class="<?= adminActive('users.php'); ?>" href="users.php">Kullanıcı Yönetimi</a>
        <a class="<?= adminActive('messages.php'); ?>" href="messages.php">İletişim Mesajları</a>
        <a class="<?= adminActive('watchlists.php'); ?>" href="watchlists.php">İzleme Listeleri</a>
        <a href="logout.php">Çıkış</a>
    </nav>
</aside>
