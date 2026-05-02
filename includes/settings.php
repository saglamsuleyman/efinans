<?php
declare(strict_types=1);

function getSiteSettings(PDO $pdo): array
{
    $settings = [];
    $stmt = $pdo->query('SELECT setting_key, setting_value FROM site_settings');

    foreach ($stmt->fetchAll() as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    return $settings;
}

function settingValue(array $settings, string $key, string $fallback = ''): string
{
    return trim((string)($settings[$key] ?? '')) !== '' ? (string)$settings[$key] : $fallback;
}
