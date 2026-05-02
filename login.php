<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Giriş Yap - EfinanS';
$metaDescription = 'EfinanS hesabınıza güvenli şekilde giriş yapın.';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, full_name, email, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    }

    $error = 'E-posta veya şifre hatalı.';
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <form class="auth-card card" method="post" action="login.php">
        <p class="eyebrow">Hesap erişimi</p>
        <h1>Giriş Yap</h1>
        <?php if ($error): ?><div class="alert alert-danger error"><?= e($error); ?></div><?php endif; ?>
        <label class="form-label" for="email">E-posta</label>
        <input class="form-control" id="email" name="email" type="email" required autocomplete="email">
        <label class="form-label" for="password">Şifre</label>
        <input class="form-control" id="password" name="password" type="password" required autocomplete="current-password">
        <button class="btn btn-efinans w-100 mt-3 primary-button full" type="submit">Giriş Yap</button>
        <p>Hesabınız yok mu? <a href="register.php">Kayıt olun</a></p>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
