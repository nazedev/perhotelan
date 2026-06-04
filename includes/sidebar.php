<?php 
$parts = explode('/', $_SERVER['SCRIPT_NAME'] ?? '');
$base_url = (isset($parts[1]) && $parts[1] === 'pw') ? '/pw' : '';
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-hotel"></i> HotelSystem
        <button class="sidebar-close" id="sidebar-close" aria-label="Tutup menu">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="<?= $base_url ?>/admin_dashboard.php"><i class="fas fa-home" style="width: 25px;"></i> Dashboard</a></li>
        <li><a href="<?= $base_url ?>/kamar/index.php"><i class="fas fa-bed" style="width: 25px;"></i> Data Kamar</a></li>
        <li><a href="<?= $base_url ?>/tamu/index.php"><i class="fas fa-users" style="width: 25px;"></i> Data Tamu</a></li>
        <li><a href="<?= $base_url ?>/tamu/reservasi/index.php"><i class="fas fa-calendar-check" style="width: 25px;"></i> Reservasi</a></li>
        <li><a href="<?= $base_url ?>/admin/dashboard_foto.php"><i class="fas fa-images" style="width: 25px;"></i> Foto Dashboard</a></li>
    </ul>
    <div style="margin-top: auto;">
        <a href="<?= $base_url ?>/auth/logout.php" class="btn btn-danger" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 8px;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
