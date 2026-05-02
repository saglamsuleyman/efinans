</main>
<footer class="site-footer">
    <div class="footer-grid">
        <div>
            <a class="logo footer-logo" href="<?= $basePath ?? ''; ?>index.php"><span><?= e(substr($siteSettings['site_name'] ?? 'EfinanS', 0, 1)); ?></span><?= e(substr($siteSettings['site_name'] ?? 'EfinanS', 1)); ?></a>
            <p><?= e($siteSettings['footer_description'] ?? 'Piyasa verileri, finans haberleri ve izleme listeleri için modern finans platformu.'); ?></p>
        </div>
        <div>
            <h2>Piyasalar</h2>
            <a href="<?= $basePath ?? ''; ?>markets.php">Tüm Piyasalar</a>
            <a href="<?= $basePath ?? ''; ?>crypto.php">Kripto Paralar</a>
            <a href="<?= $basePath ?? ''; ?>forex.php">Döviz Kurları</a>
            <a href="<?= $basePath ?? ''; ?>charts.php">Grafikler</a>
        </div>
        <div>
            <h2>Platform</h2>
            <a href="<?= $basePath ?? ''; ?>calculator.php">Hesap Makinesi</a>
            <a href="<?= $basePath ?? ''; ?>news.php">Haberler</a>
            <a href="<?= $basePath ?? ''; ?>about.php">Hakkında</a>
            <a href="<?= $basePath ?? ''; ?>login.php">Giriş Yap</a>
        </div>
    </div>
    <p class="copyright">© <?= date('Y'); ?> EfinanS. Tüm hakları saklıdır.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $basePath ?? ''; ?>assets/js/main.js"></script>
</body>
</html>
