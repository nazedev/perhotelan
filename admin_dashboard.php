<?php
$title = "Dashboard";
require_once 'config/db.php';
require_once 'includes/header.php';

// Fetch statistics
$stmtKamar = $pdo->query("SELECT COUNT(*) FROM kamar");
$totalKamar = $stmtKamar->fetchColumn();

$stmtTamu = $pdo->query("SELECT COUNT(*) FROM tamu");
$totalTamu = $stmtTamu->fetchColumn();

$stmtReservasi = $pdo->query("SELECT COUNT(*) FROM reservasi WHERE status IN ('booking', 'checkin')");
$aktifReservasi = $stmtReservasi->fetchColumn();

// Count photos
$stmtFoto = $pdo->query("SELECT COUNT(*) FROM kamar_foto");
$totalFoto = $stmtFoto->fetchColumn();

$stmtDashFoto = $pdo->query("SELECT COUNT(*) FROM dashboard_foto WHERE is_active = 1");
$totalDashFoto = $stmtDashFoto->fetchColumn();
?>

<div class="grid-cards">
    <div class="card stat-card">
        <h3><i class="fas fa-bed"></i> Total Kamar</h3>
        <div class="value"><?= $totalKamar ?></div>
    </div>
    <div class="card stat-card">
        <h3><i class="fas fa-users"></i> Total Tamu</h3>
        <div class="value"><?= $totalTamu ?></div>
    </div>
    <div class="card stat-card">
        <h3><i class="fas fa-calendar-check"></i> Reservasi Aktif</h3>
        <div class="value"><?= $aktifReservasi ?></div>
    </div>
    <div class="card stat-card">
        <h3><i class="fas fa-camera"></i> Foto Kamar</h3>
        <div class="value"><?= $totalFoto ?></div>
    </div>
</div>

<!-- Quick Links -->
<div class="grid-cards" style="margin-bottom: 2rem;">
    <a href="admin/dashboard_foto.php" class="card" style="text-align: center; text-decoration: none; color: var(--text-color); transition: transform 0.2s;">
        <i class="fas fa-images" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4>Kelola Foto Dashboard</h4>
        <p style="color: var(--text-muted); font-size: 0.85rem;"><?= $totalDashFoto ?> foto aktif</p>
    </a>
    <a href="kamar/index.php" class="card" style="text-align: center; text-decoration: none; color: var(--text-color); transition: transform 0.2s;">
        <i class="fas fa-bed" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4>Kelola Kamar & Foto</h4>
        <p style="color: var(--text-muted); font-size: 0.85rem;"><?= $totalKamar ?> kamar terdaftar</p>
    </a>
    <a href="tamu/reservasi/index.php" class="card" style="text-align: center; text-decoration: none; color: var(--text-color); transition: transform 0.2s;">
        <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4>Kelola Reservasi</h4>
        <p style="color: var(--text-muted); font-size: 0.85rem;"><?= $aktifReservasi ?> reservasi aktif</p>
    </a>
</div>

<div class="card">
    <div class="d-flex justify-between align-center mb-3">
        <h3>Reservasi Terbaru</h3>
        <a href="tamu/reservasi/index.php" class="btn btn-primary btn-sm">Lihat Semua</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Check In</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmtRec = $pdo->query("SELECT r.*, t.nama_tamu, k.nomor_kamar FROM reservasi r 
                                        JOIN tamu t ON r.tamu_id = t.id 
                                        JOIN kamar k ON r.kamar_id = k.id 
                                        ORDER BY r.created_at DESC LIMIT 5");
                while ($row = $stmtRec->fetch()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_tamu']) ?></td>
                    <td><?= htmlspecialchars($row['nomor_kamar']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tgl_checkin'])) ?></td>
                    <td>
                        <span class="badge badge-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($stmtRec->rowCount() == 0): ?>
                <tr><td colspan="4" style="text-align: center;">Belum ada reservasi</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
