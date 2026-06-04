<?php
$title = "Riwayat Booking";
require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['tamu_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT r.*, k.nomor_kamar, k.tipe_kamar FROM reservasi r JOIN kamar k ON r.kamar_id = k.id WHERE r.tamu_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$_SESSION['tamu_id']]);
$reservasi = $stmt->fetchAll();

require_once 'includes/guest_header.php';
?>

<div class="container section">
    <h2 class="section-title">Riwayat Booking Anda</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="max-width: 800px; margin: 0 auto 2rem;">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal Pesan</th>
                        <th>Kamar</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservasi as $r): ?>
                    <tr>
                        <td><?= date('d M Y H:i', strtotime($r['created_at'])) ?></td>
                        <td><?= htmlspecialchars($r['nomor_kamar']) ?> - <?= htmlspecialchars($r['tipe_kamar']) ?></td>
                        <td><?= date('d M Y', strtotime($r['tgl_checkin'])) ?></td>
                        <td><?= date('d M Y', strtotime($r['tgl_checkout'])) ?></td>
                        <td>Rp <?= number_format($r['total_bayar'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge badge-<?= $r['status'] ?>">
                                <?= ucfirst($r['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (count($reservasi) == 0): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Belum ada riwayat booking.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/guest_footer.php'; ?>
