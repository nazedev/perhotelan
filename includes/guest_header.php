<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$parts = explode('/', $_SERVER['SCRIPT_NAME'] ?? '');
$base_url = (isset($parts[1]) && $parts[1] === 'pw') ? '/pw' : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Hotel Grandiera</title>
    <meta name="description" content="Hotel Grandiera - Pengalaman menginap mewah dan tak terlupakan untuk Anda dan keluarga.">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>/includes/assets/guest_style.css">
</head>
<body>
    <header class="guest-header">
        <div class="container header-container">
            <a href="<?= $base_url ?>/index.php" class="logo">
                <i class="fas fa-hotel"></i> Grandiera
            </a>
            <nav class="guest-nav">
                <ul class="nav-links">
                    <li><a href="<?= $base_url ?>/index.php">Beranda</a></li>
                    <?php if (isset($_SESSION['tamu_id'])): ?>
                        <li><a href="<?= $base_url ?>/riwayat.php">Riwayat Booking</a></li>
                        <li>
                            <div class="user-menu">
                                <span><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['tamu_nama']) ?></span>
                                <a href="<?= $base_url ?>/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="<?= $base_url ?>/login.php" class="btn btn-outline-light btn-sm">Login</a></li>
                        <li><a href="<?= $base_url ?>/register.php" class="btn btn-light btn-sm">Daftar</a></li>
                    <?php endif; ?>
                </ul>
                <button class="guest-theme-toggle" id="guest-theme-toggle" aria-label="Ganti tema">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="guest-burger" id="guest-burger" aria-label="Buka menu">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>
    <nav class="mobile-nav" id="mobile-nav">
        <div class="mobile-nav-header">
            <a href="<?= $base_url ?>/index.php" class="logo">
                <i class="fas fa-hotel"></i> Grandiera
            </a>
            <button class="mobile-nav-close" id="mobile-nav-close" aria-label="Tutup menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="mobile-nav-links">
            <li><a href="<?= $base_url ?>/index.php"><i class="fas fa-home" style="width: 20px; margin-right: 8px;"></i> Beranda</a></li>
            <?php if (isset($_SESSION['tamu_id'])): ?>
                <li><a href="<?= $base_url ?>/riwayat.php"><i class="fas fa-history" style="width: 20px; margin-right: 8px;"></i> Riwayat Booking</a></li>
                <hr class="mobile-nav-divider">
                <li>
                    <span style="display: block; padding: 0.5rem 1rem; color: #94a3b8; font-size: 0.85rem;">
                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['tamu_nama']) ?>
                    </span>
                </li>
                <li><a href="<?= $base_url ?>/logout.php"><i class="fas fa-sign-out-alt" style="width: 20px; margin-right: 8px;"></i> Logout</a></li>
            <?php else: ?>
                <hr class="mobile-nav-divider">
                <li><a href="<?= $base_url ?>/login.php"><i class="fas fa-sign-in-alt" style="width: 20px; margin-right: 8px;"></i> Login</a></li>
                <li><a href="<?= $base_url ?>/register.php"><i class="fas fa-user-plus" style="width: 20px; margin-right: 8px;"></i> Daftar</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="guest-main">
