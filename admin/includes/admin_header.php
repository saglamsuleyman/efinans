<?php
$adminPageTitle = $adminPageTitle ?? 'EfinanS Admin Paneli';
$adminPageDescription = $adminPageDescription ?? 'EfinanS yönetim paneli';
$flash = adminFlash();
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($adminPageTitle); ?></title>
    <meta name="description" content="<?= e($adminPageDescription); ?>">
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-body">
<div class="admin-shell">
    <?php require __DIR__ . '/admin_sidebar.php'; ?>
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="admin-menu-toggle" type="button" aria-label="Admin menüyü aç/kapat">
                <span></span><span></span><span></span>
            </button>
            <div>
                <p class="admin-eyebrow">Yönetim Paneli</p>
                <h1><?= e($adminHeading ?? 'Dashboard'); ?></h1>
            </div>
            <div class="admin-top-actions">
                <a class="admin-ghost" href="../index.php">Siteyi Görüntüle</a>
                <a class="admin-danger-link" href="logout.php">Çıkış</a>
            </div>
        </header>
        <?php if ($flash): ?>
            <div class="admin-alert <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
        <?php endif; ?>
        <main class="admin-content">
