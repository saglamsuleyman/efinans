<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

if (isset($_SESSION['admin_id'], $_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin') {
    header('Location: index.php');
    exit;
}

$error = $_SESSION['admin_login_error'] ?? '';
unset($_SESSION['admin_login_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, full_name, email, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$user['id'];
        $_SESSION['admin_name'] = $user['full_name'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_role'] = 'admin';
        $_SESSION['admin_last_activity'] = time();
        header('Location: index.php');
        exit;
    }

    $error = 'Bu panele yalnızca yöneticiler erişebilir.';
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>EfinanS Admin Girişi</title>
    <meta name="description" content="EfinanS yönetici giriş ekranı">
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-login-body">
<main class="admin-login-page">
    <section class="admin-login-brand">
        <a class="admin-brand" href="login.php"><span>E</span>finanS</a>
        <p class="admin-eyebrow">Özel yönetim alanı</p>
        <h1>EfinanS Admin Panel</h1>
        <p>Site ayarları, piyasa verileri, haberler, kullanıcılar ve iletişim mesajları için ayrı yönetim merkezi.</p>
    </section>
    <form class="admin-login-card" method="post" action="login.php">
        <h2>Yönetici Girişi</h2>
        <?php if ($error): ?>
            <div class="admin-alert error"><?= e($error); ?></div>
        <?php endif; ?>
        <label>
            Email
            <input name="email" type="email" required autocomplete="email">
        </label>
        <label>
            Şifre
            <input name="password" type="password" required autocomplete="current-password">
        </label>
        <button class="admin-button" type="submit">Yönetici Girişi</button>
        <a class="admin-ghost login-site-link" href="../index.php">Siteyi Görüntüle</a>
    </form>
</main>
</body>
</html>
