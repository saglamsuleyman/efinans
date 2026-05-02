<?php
declare(strict_types=1);

// XAMPP varsayılanlarına göre ayarlanmıştır. Gerekirse kullanıcı adı/şifreyi burada değiştirin.
$host = 'localhost';
$dbName = 'efinans';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $columns = $pdo->query("SHOW COLUMNS FROM markets")->fetchAll(PDO::FETCH_COLUMN);
    $schemaUpdates = [
        'symbol' => "ALTER TABLE markets ADD COLUMN symbol VARCHAR(24) DEFAULT NULL AFTER name",
        'volume' => "ALTER TABLE markets ADD COLUMN volume DECIMAL(20, 2) DEFAULT 0 AFTER change_rate",
        'market_cap' => "ALTER TABLE markets ADD COLUMN market_cap DECIMAL(20, 2) DEFAULT 0 AFTER volume",
    ];

    foreach ($schemaUpdates as $column => $sql) {
        if (!in_array($column, $columns, true)) {
            $pdo->exec($sql);
        }
    }

    // Eski kurulumlarda news tablosunu manuel haber modeline uyumlu hale getirir.
    $newsColumns = $pdo->query("SHOW COLUMNS FROM news")->fetchAll(PDO::FETCH_COLUMN);
    $newsSchemaUpdates = [
        'summary' => "ALTER TABLE news ADD COLUMN summary VARCHAR(255) DEFAULT NULL AFTER title",
        'content' => "ALTER TABLE news ADD COLUMN content TEXT NULL AFTER summary",
        'image' => "ALTER TABLE news ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER content",
    ];

    foreach ($newsSchemaUpdates as $column => $sql) {
        if (!in_array($column, $newsColumns, true)) {
            $pdo->exec($sql);
        }
    }

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS site_settings (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(120) NOT NULL UNIQUE,
            setting_value TEXT NULL
        ) ENGINE=InnoDB
    ");

    $defaultSettings = [
        'site_name' => 'EfinanS',
        'hero_title' => 'EfinanS ile piyasaları güçlü, hızlı ve sade takip edin.',
        'hero_description' => 'Döviz, kripto, emtia, borsa verileri, manuel eklenen finans haberleri ve hesap araçlarıyla karar süreçlerinizi güçlendirin.',
        'footer_description' => 'Piyasa verileri, finans haberleri ve izleme listeleri için modern finans platformu.',
        'contact_email' => 'destek@efinans.com',
        'contact_phone' => '+90 212 000 00 00',
        'social_links' => 'LinkedIn, X, Instagram',
    ];

    $settingInsert = $pdo->prepare('INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES (?, ?)');
    foreach ($defaultSettings as $key => $value) {
        $settingInsert->execute([$key, $value]);
    }

    $contactColumns = $pdo->query("SHOW COLUMNS FROM contact_messages")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('is_read', $contactColumns, true)) {
        $pdo->exec('ALTER TABLE contact_messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0 AFTER message');
    }
} catch (PDOException $e) {
    exit('Veritabanı bağlantısı kurulamadı. Lütfen includes/db.php ayarlarını kontrol edin.');
}
