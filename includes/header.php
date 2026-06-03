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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $basePath; ?>assets/css/style.css">
</head>
<body>
<header class="site-header">
    <nav class="navbar navbar-expand-xl navbar-dark" aria-label="Ana menü">
        <div class="container-fluid site-nav-container">
        <a class="navbar-brand logo" href="<?= $basePath; ?>index.php" aria-label="EfinanS ana sayfa">
            <span><?= e(substr($displaySiteName, 0, 1)); ?></span><?= e(substr($displaySiteName, 1)); ?>
        </a>
        <button class="navbar-toggler menu-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-label="Menüyü aç/kapat" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
        <div class="navbar-nav ms-auto nav-links align-items-xl-center">
            <a class="nav-link <?= activeClass('index.php'); ?>" href="<?= $basePath; ?>index.php">Ana Sayfa</a>
            <div class="nav-item dropdown nav-dropdown">
                <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Piyasalar</button>
                <div class="dropdown-menu dropdown-menu-dark">
                    <a class="dropdown-item <?= activeClass('markets.php'); ?>" href="<?= $basePath; ?>markets.php">Tüm Piyasalar</a>
                    <a class="dropdown-item <?= activeClass('crypto.php'); ?>" href="<?= $basePath; ?>crypto.php">Kripto Paralar</a>
                    <a class="dropdown-item <?= activeClass('forex.php'); ?>" href="<?= $basePath; ?>forex.php">Döviz</a>
                    <a class="dropdown-item <?= activeClass('charts.php'); ?>" href="<?= $basePath; ?>charts.php">Grafikler</a>
                </div>
            </div>
            <a class="nav-link <?= activeClass('calculator.php'); ?>" href="<?= $basePath; ?>calculator.php">Finans Hesap Makinesi</a>
            <a class="nav-link <?= activeClass('news.php'); ?>" href="<?= $basePath; ?>news.php">Haberler</a>
            <a class="nav-link <?= activeClass('about.php'); ?>" href="<?= $basePath; ?>about.php">Hakkımızda</a>
            <a class="nav-link <?= activeClass('contact.php'); ?>" href="<?= $basePath; ?>contact.php">İletişim</a>
            <form class="nav-search" method="get" action="<?= $basePath; ?>search.php" role="search">
                <input class="form-control form-control-sm" type="search" name="q" placeholder="Piyasa, kripto, döviz veya haber ara..." value="<?= activeClass('search.php') ? e(trim($_GET['q'] ?? '')) : ''; ?>" aria-label="Site içinde ara">
                <button class="btn btn-sm btn-efinans" type="submit">Ara</button>
            </form>
            <?php if (isLoggedIn()): ?>
                <a class="nav-link <?= activeClass('dashboard.php'); ?>" href="<?= $basePath; ?>dashboard.php">Kullanıcı Paneli</a>
                <?php if (isAdmin()): ?>
                    <a class="nav-link" href="<?= $basePath; ?>admin/index.php">Admin Paneli</a>
                <?php endif; ?>
                <a class="btn btn-efinans nav-button" href="<?= $basePath; ?>logout.php">Çıkış</a>
            <?php else: ?>
                <a class="nav-link <?= activeClass('login.php'); ?>" href="<?= $basePath; ?>login.php">Giriş</a>
                <a class="btn btn-efinans nav-button" href="<?= $basePath; ?>register.php">Kayıt Ol</a>
            <?php endif; ?>
        </div>
        </div>
        </div>
    </nav>
</header>
<main>
