<?php
require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'EfinanS - Finans Piyasaları, Döviz, Kripto ve Haberler';
$metaDescription = $metaDescription ?? 'EfinanS ile döviz, kripto, emtia, borsa verileri ve güncel finans haberlerini modern bir finans panelinde takip edin.';
$isAdminArea = str_contains($_SERVER['SCRIPT_NAME'], '/admin/');
$basePath = $isAdminArea ? '../' : '';
$displaySiteName = $siteSettings['site_name'] ?? 'EfinanS';
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle); ?></title>
    <meta name="description" content="<?= e($metaDescription); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e('http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $_SERVER['REQUEST_URI']); ?>">
    <link rel="stylesheet" href="<?= $basePath; ?>assets/css/style.css">
</head>
<body>
<header class="site-header">
    <nav class="navbar" aria-label="Ana menü">
        <a class="logo" href="<?= $basePath; ?>index.php" aria-label="EfinanS ana sayfa">
            <span><?= e(substr($displaySiteName, 0, 1)); ?></span><?= e(substr($displaySiteName, 1)); ?>
        </a>
        <button class="menu-toggle" type="button" aria-label="Menüyü aç/kapat" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links">
            <a class="<?= activeClass('index.php'); ?>" href="<?= $basePath; ?>index.php">Ana Sayfa</a>
            <div class="nav-dropdown">
                <button type="button">Piyasalar</button>
                <div class="dropdown-menu">
                    <a class="<?= activeClass('markets.php'); ?>" href="<?= $basePath; ?>markets.php">Tüm Piyasalar</a>
                    <a class="<?= activeClass('crypto.php'); ?>" href="<?= $basePath; ?>crypto.php">Kripto Paralar</a>
                    <a class="<?= activeClass('forex.php'); ?>" href="<?= $basePath; ?>forex.php">Döviz</a>
                    <a class="<?= activeClass('charts.php'); ?>" href="<?= $basePath; ?>charts.php">Grafikler</a>
                </div>
            </div>
            <a class="<?= activeClass('calculator.php'); ?>" href="<?= $basePath; ?>calculator.php">Finans Hesap Makinesi</a>
            <a class="<?= activeClass('news.php'); ?>" href="<?= $basePath; ?>news.php">Haberler</a>
            <a class="<?= activeClass('about.php'); ?>" href="<?= $basePath; ?>about.php">Hakkımızda</a>
            <a class="<?= activeClass('contact.php'); ?>" href="<?= $basePath; ?>contact.php">İletişim</a>
            <?php if (isLoggedIn()): ?>
                <a class="<?= activeClass('dashboard.php'); ?>" href="<?= $basePath; ?>dashboard.php">Kullanıcı Paneli</a>
                <?php if (isAdmin()): ?>
                    <a href="<?= $basePath; ?>admin/index.php">Admin Paneli</a>
                <?php endif; ?>
                <a class="nav-button" href="<?= $basePath; ?>logout.php">Çıkış</a>
            <?php else: ?>
                <a class="<?= activeClass('login.php'); ?>" href="<?= $basePath; ?>login.php">Giriş</a>
                <a class="nav-button" href="<?= $basePath; ?>register.php">Kayıt Ol</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<main>
