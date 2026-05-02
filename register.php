<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Kayıt Ol - EfinanS';
$metaDescription = 'EfinanS finans platformuna kayıt olun ve piyasa izleme listenizi oluşturun.';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 6) {
        $error = 'Şifre en az 6 karakter olmalıdır.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = 'Bu e-posta adresi zaten kayıtlı.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)');
            $insert->execute([$fullName, $email, $hash, 'user']);
            $success = 'Kayıt başarılı. Giriş yapabilirsiniz.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <form class="auth-card" method="post" action="register.php">
        <p class="eyebrow">Yeni hesap</p>
        <h1>Kayıt Ol</h1>
        <?php if ($error): ?><div class="alert error"><?= e($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert success"><?= e($success); ?></div><?php endif; ?>
        <label for="full_name">Ad Soyad</label>
        <input id="full_name" name="full_name" type="text" required autocomplete="name">
        <label for="email">E-posta</label>
        <input id="email" name="email" type="email" required autocomplete="email">
        <label for="password">Şifre</label>
        <input id="password" name="password" type="password" required autocomplete="new-password">
        <button class="primary-button full" type="submit">Kayıt Ol</button>
        <p>Zaten hesabınız var mı? <a href="login.php">Giriş yapın</a></p>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
