<?php
include_once __DIR__ . '/auth_cek.php';
$parts = explode('/', $_SERVER['SCRIPT_NAME'] ?? '');
$base_url = (isset($parts[1]) && $parts[1] === 'pw') ? '/pw' : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Hotel Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>/includes/assets/style.css">
</head>
<body>
<div class="app-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="main-content">
        <div class="topbar">
            <div class="d-flex align-center gap-2">
                <button class="burger-btn" id="burger-btn" aria-label="Buka menu">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="topbar-title"><?= isset($title) ? $title : 'Dashboard' ?></h1>
            </div>
            <div class="d-flex align-center gap-2">
                <span class="badge badge-booking" style="padding: 0.5rem 1rem;"><i class="fas fa-user" style="margin-right: 5px;"></i> <?= $_SESSION['nama_lengkap'] ?? 'Admin' ?></span>
                <button id="theme-toggle" class="theme-toggle" aria-label="Ganti tema">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
