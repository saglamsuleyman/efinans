<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

const ADMIN_SESSION_TIMEOUT = 1800;

if (
    !isset($_SESSION['admin_id'], $_SESSION['admin_role']) ||
    $_SESSION['admin_role'] !== 'admin'
) {
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['admin_last_activity']) && time() - (int)$_SESSION['admin_last_activity'] > ADMIN_SESSION_TIMEOUT) {
    unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email'], $_SESSION['admin_role'], $_SESSION['admin_last_activity']);
    $_SESSION['admin_login_error'] = 'Oturum süreniz doldu. Lütfen tekrar giriş yapın.';
    header('Location: login.php');
    exit;
}

$_SESSION['admin_last_activity'] = time();

function adminActive(string $page): string
{
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}

function adminFlash(?string $message = null, string $type = 'success'): ?array
{
    if ($message !== null) {
        $_SESSION['admin_flash'] = ['message' => $message, 'type' => $type];
        return null;
    }

    $flash = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);
    return $flash;
}
