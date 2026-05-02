<?php
require_once __DIR__ . '/includes/admin_auth.php';
require_once __DIR__ . '/../includes/settings.php';

$adminPageTitle = 'Site Ayarları - EfinanS Admin';
$adminHeading = 'Site Ayarları';

$settingLabels = [
    'site_name' => 'Site adı',
    'hero_title' => 'Ana sayfa hero başlığı',
    'hero_description' => 'Ana sayfa hero açıklaması',
    'footer_description' => 'Footer açıklaması',
    'contact_email' => 'İletişim e-posta adresi',
    'contact_phone' => 'İletişim telefon',
    'social_links' => 'Sosyal medya linkleri',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('UPDATE site_settings SET setting_value = ? WHERE setting_key = ?');
    foreach ($settingLabels as $key => $label) {
        $stmt->execute([trim($_POST[$key] ?? ''), $key]);
    }
    adminFlash('Site ayarları güncellendi.');
    header('Location: settings.php');
    exit;
}

$settings = getSiteSettings($pdo);
require_once __DIR__ . '/includes/admin_header.php';
?>
<section class="admin-form-card card">
    <h2>Genel Site Ayarları</h2>
    <form class="admin-form stacked" method="post" action="settings.php">
        <?php foreach ($settingLabels as $key => $label): ?>
            <label>
                <?= e($label); ?>
                <?php if (in_array($key, ['hero_description', 'footer_description', 'social_links'], true)): ?>
                    <textarea class="form-control" name="<?= e($key); ?>" rows="3"><?= e($settings[$key] ?? ''); ?></textarea>
                <?php else: ?>
                    <input class="form-control" name="<?= e($key); ?>" type="text" value="<?= e($settings[$key] ?? ''); ?>">
                <?php endif; ?>
            </label>
        <?php endforeach; ?>
        <button class="btn admin-button" type="submit">Ayarları Kaydet</button>
    </form>
</section>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
