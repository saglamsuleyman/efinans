<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Giriş Gerekli - EfinanS İzleme Listesi';
$metaDescription = 'EfinanS izleme listesine varlık eklemek için giriş yapmanız gerekir.';
$warning = $_SESSION['watchlist_warning'] ?? 'İzleme listesine eklemek için önce giriş yapmalısınız.';
unset($_SESSION['watchlist_warning']);

require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <div class="auth-card card notice-card">
        <p class="eyebrow">Giriş gerekli</p>
        <h1>İzleme listesi hesabınıza özeldir.</h1>
        <div class="alert alert-danger error"><?= e($warning); ?></div>
        <p class="muted">Takip etmek istediğiniz finans varlıklarını kaydetmek için EfinanS hesabınıza giriş yapın veya yeni hesap oluşturun.</p>
        <div class="hero-actions">
            <a class="btn btn-efinans primary-button" href="login.php">Giriş Yap</a>
            <a class="btn btn-outline-light secondary-button" href="register.php">Kayıt Ol</a>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
