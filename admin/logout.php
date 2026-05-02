<?php
require_once __DIR__ . '/../includes/auth.php';

unset(
    $_SESSION['admin_id'],
    $_SESSION['admin_name'],
    $_SESSION['admin_email'],
    $_SESSION['admin_role'],
    $_SESSION['admin_last_activity'],
    $_SESSION['admin_flash']
);

header('Location: login.php');
exit;
