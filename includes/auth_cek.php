<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    $parts = explode('/', $_SERVER['SCRIPT_NAME'] ?? '');
    $base_url = (isset($parts[1]) && $parts[1] === 'pw') ? '/pw' : '';
    header("Location: $base_url/auth/login.php");
    exit();
}
?>
