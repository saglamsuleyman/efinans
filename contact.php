<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/settings.php';

$pageTitle = 'İletişim - EfinanS Finans Platformu';
$metaDescription = 'EfinanS iletişim sayfasından finans platformu, piyasa verileri ve üyelik süreçleri hakkında mesaj gönderin.';
$success = '';
$error = '';
$siteSettings = getSiteSettings($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        $error = 'Lütfen tüm alanları doldurun.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $subject, $message]);
        $success = 'Mesajınız başarıyla kaydedildi.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">İletişim</p>
    <h1>Finans gündemi kadar hızlı dönüş hedefliyoruz.</h1>
    <p>Platform, üyelik veya piyasa verileriyle ilgili sorularınızı bize iletin. Mesajlar admin paneline güvenli şekilde kaydedilir.</p>
</section>

<section class="contact-layout section">
    <aside class="contact-info card">
        <p class="eyebrow">İletişim</p>
        <h2>EfinanS destek hattı</h2>
        <p>Piyasa verileri, hesap erişimi veya içerik yönetimiyle ilgili taleplerinizi bu formdan iletebilirsiniz.</p>
        <div class="info-row"><strong>E-posta</strong><span><?= e(settingValue($siteSettings, 'contact_email', 'destek@efinans.com')); ?></span></div>
        <div class="info-row"><strong>Telefon</strong><span><?= e(settingValue($siteSettings, 'contact_phone', '+90 212 000 00 00')); ?></span></div>
        <div class="info-row"><strong>Adres</strong><span>İstanbul Finans Merkezi</span></div>
        <div class="info-row"><strong>Yanıt Süresi</strong><span>Hafta içi 24 saat içinde</span></div>
    </aside>
    <form class="auth-card card" method="post" action="contact.php">
        <?php if ($error): ?><div class="alert alert-danger error"><?= e($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success success"><?= e($success); ?></div><?php endif; ?>
        <label class="form-label" for="name">Ad Soyad</label>
        <input class="form-control" id="name" name="name" type="text" required>
        <label class="form-label" for="email">E-posta</label>
        <input class="form-control" id="email" name="email" type="email" required>
        <label class="form-label" for="subject">Konu</label>
        <input class="form-control" id="subject" name="subject" type="text" required>
        <label class="form-label" for="message">Mesaj</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        <button class="btn btn-efinans w-100 mt-3 primary-button full" type="submit">Gönder</button>
    </form>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
