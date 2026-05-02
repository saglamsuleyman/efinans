CREATE DATABASE IF NOT EXISTS efinans CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE efinans;

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS watchlist;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS site_settings;
DROP TABLE IF EXISTS markets;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE markets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    symbol VARCHAR(24) DEFAULT NULL,
    category ENUM('Döviz', 'Kripto', 'Emtia', 'Borsa') NOT NULL,
    price DECIMAL(18, 4) NOT NULL,
    change_rate DECIMAL(8, 2) NOT NULL DEFAULT 0,
    volume DECIMAL(20, 2) DEFAULT 0,
    market_cap DECIMAL(20, 2) DEFAULT 0,
    status ENUM('Yükselişte', 'Düşüşte', 'Stabil') NOT NULL DEFAULT 'Stabil',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    summary VARCHAR(255) DEFAULT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(120) NOT NULL UNIQUE,
    setting_value TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE watchlist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    market_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_watch (user_id, market_id),
    CONSTRAINT fk_watch_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_watch_market FOREIGN KEY (market_id) REFERENCES markets(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (full_name, email, password, role) VALUES
('EfinanS Admin', 'admin@efinans.com', '$2y$10$65B5OH10Dk5HTqf2Pkjx6O6RWzRj1qzXhafeFciIH7tRPGuxtOuCO', 'admin'),
('Demo Kullanıcı', 'user@efinans.com', '$2y$10$RffsSdeRToLcGM3aiQZBQOPEykeKSbF/MusYYU0kFuy0Dw207H5l2', 'user');

INSERT INTO markets (name, symbol, category, price, change_rate, volume, market_cap, status) VALUES
('Bitcoin', 'BTC', 'Kripto', 64250.0000, 2.35, 31500000000.00, 1260000000000.00, 'Yükselişte'),
('Ethereum', 'ETH', 'Kripto', 3150.0000, 1.18, 14800000000.00, 378000000000.00, 'Yükselişte'),
('Solana', 'SOL', 'Kripto', 145.7400, -1.04, 2900000000.00, 65400000000.00, 'Düşüşte'),
('BNB', 'BNB', 'Kripto', 587.4200, 0.82, 1800000000.00, 90300000000.00, 'Yükselişte'),
('XRP', 'XRP', 'Kripto', 0.5450, -0.74, 1350000000.00, 30100000000.00, 'Düşüşte'),
('USD/TRY', 'USDTRY', 'Döviz', 32.4850, 0.42, 985000000.00, 0.00, 'Yükselişte'),
('EUR/TRY', 'EURTRY', 'Döviz', 35.0910, -0.18, 720000000.00, 0.00, 'Düşüşte'),
('GBP/TRY', 'GBPTRY', 'Döviz', 40.8520, 0.11, 410000000.00, 0.00, 'Yükselişte'),
('Altın', 'XAU', 'Emtia', 2447.6800, 0.63, 16200000000.00, 0.00, 'Yükselişte'),
('Gümüş', 'XAG', 'Emtia', 28.4600, -0.32, 5100000000.00, 0.00, 'Düşüşte'),
('Petrol', 'BRENT', 'Emtia', 83.2100, 0.08, 8900000000.00, 0.00, 'Stabil'),
('BIST 100', 'XU100', 'Borsa', 9842.1500, 0.91, 112000000000.00, 0.00, 'Yükselişte'),
('NASDAQ', 'IXIC', 'Borsa', 16340.8700, -0.27, 0.00, 0.00, 'Düşüşte'),
('S&P 500', 'SPX', 'Borsa', 5128.7400, 0.22, 0.00, 0.00, 'Yükselişte');

INSERT INTO news (title, summary, content, image) VALUES
('Küresel piyasalarda merkez bankası mesajları izleniyor', 'Faiz, enflasyon ve büyüme beklentileri piyasa fiyatlamalarında ana gündem olmayı sürdürüyor.', 'Yatırımcılar faiz politikaları ve enflasyon görünümüne ilişkin yeni sinyalleri yakından takip ediyor. EfinanS editörleri, döviz, kripto, emtia ve hisse piyasalarındaki ana eğilimleri sade bir piyasa okumasıyla özetliyor.', 'assets/images/market-news.jpg'),
('Kripto piyasasında işlem hacmi yeniden yükseldi', 'Bitcoin ve Ethereum öncülüğünde dijital varlıklarda risk iştahı yeniden güçleniyor.', 'Kripto para piyasasında majör varlıkların işlem hacimleri artarken kısa vadeli yön arayışı devam ediyor. Kullanıcılar EfinanS kripto sayfasından fiyat, hacim ve değişim verilerini takip edebilir.', 'assets/images/crypto-news.jpg'),
('Emtia tarafında petrol ve altın hareketliliği', 'Altın, gümüş ve petrol fiyatlarında küresel haber akışının etkisi öne çıkıyor.', 'Jeopolitik gündem, arz beklentileri ve para politikası sinyalleri emtia fiyatlarında dalgalanmayı artırıyor. EfinanS piyasa tabloları, emtia varlıklarındaki günlük görünümü tek ekranda sunar.', 'assets/images/commodity-news.jpg');

INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'EfinanS'),
('hero_title', 'EfinanS ile piyasaları güçlü, hızlı ve sade takip edin.'),
('hero_description', 'Döviz, kripto, emtia, borsa verileri, manuel eklenen finans haberleri ve hesap araçlarıyla karar süreçlerinizi güçlendirin.'),
('footer_description', 'Piyasa verileri, finans haberleri ve izleme listeleri için modern finans platformu.'),
('contact_email', 'destek@efinans.com'),
('contact_phone', '+90 212 000 00 00'),
('social_links', 'LinkedIn, X, Instagram');

-- Mevcut kurulumlarda eksik kolonlar includes/db.php içindeki kontrollü migration ile eklenir.
