<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin(): void
{
    if (!isLoggedIn() || !isAdmin()) {
        header('Location: ../login.php');
        exit;
    }
}

function activeClass(string $page): string
{
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}
