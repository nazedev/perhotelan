<?php
$title = "Booking Kamar";
require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['tamu_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu untuk melakukan booking.";
    header("Location: login.php");
    exit();
}

$kamar_id = $_GET['kamar_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM kamar WHERE id = ? AND status = 'tersedia'");
$stmt->execute([$kamar_id]);
$kamar = $stmt->fetch();

if (!$kamar) {
    header("Location: index.php");
    exit();
}

// Fetch room photos
$stmtFoto = $pdo->prepare("SELECT * FROM kamar_foto WHERE kamar_id = ? ORDER BY urutan ASC, id ASC");
$stmtFoto->execute([$kamar_id]);
$fotos = $stmtFoto->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tgl_checkin = $_POST['tgl_checkin'] ?? '';
    $tgl_checkout = $_POST['tgl_checkout'] ?? '';

    if (empty($tgl_checkin) || empty($tgl_checkout)) {
        $_SESSION['error'] = "Tanggal check-in dan check-out wajib diisi!";
    } elseif (strtotime($tgl_checkin) >= strtotime($tgl_checkout)) {
        $_SESSION['error'] = "Tanggal check-out harus lebih besar dari tanggal check-in!";
    } elseif (strtotime($tgl_checkin) < strtotime(date('Y-m-d'))) {
        $_SESSION['error'] = "Tanggal check-in tidak boleh kurang dari hari ini!";
    } else {
        $diff = strtotime($tgl_checkout) - strtotime($tgl_checkin);
        $hari = floor($diff / (60 * 60 * 24));
        $total_bayar = $hari * $kamar['harga'];

        $stmt = $pdo->prepare("INSERT INTO reservasi (tamu_id, kamar_id, tgl_checkin, tgl_checkout, total_bayar, status) VALUES (?, ?, ?, ?, ?, 'booking')");
        if ($stmt->execute([$_SESSION['tamu_id'], $kamar['id'], $tgl_checkin, $tgl_checkout, $total_bayar])) {
            $_SESSION['success'] = "Booking berhasil! Silakan cek riwayat Anda.";
            header("Location: riwayat.php");
            exit();
        } else {
            $_SESSION['error'] = "Terjadi kesalahan sistem.";
        }
    }
}
require_once 'includes/guest_header.php';
?>

<div class="container section">
    <div class="auth-container" style="max-width: 600px;">
        <h2 class="auth-title">Booking Kamar</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Room Photo Carousel -->
        <?php if (count($fotos) > 0): ?>
        <div class="room-carousel" style="border-radius: 0.75rem; margin-bottom: 1.5rem; height: 220px;">
            <div class="room-carousel-track">
                <?php foreach ($fotos as $foto): ?>
                <img src="<?= htmlspecialchars($foto['cloudinary_url']) ?>" 
                     alt="Foto Kamar <?= htmlspecialchars($kamar['nomor_kamar']) ?>"
                     loading="lazy">
                <?php endforeach; ?>
            </div>
            <?php if (count($fotos) > 1): ?>
            <button class="room-carousel-nav prev"><i class="fas fa-chevron-left"></i></button>
            <button class="room-carousel-nav next"><i class="fas fa-chevron-right"></i></button>
            <div class="room-carousel-dots">
                <?php foreach ($fotos as $fi => $f): ?>
                <button class="room-carousel-dot <?= $fi === 0 ? 'active' : '' ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div style="background: var(--bg); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid var(--border);">
            <h3 style="margin-bottom: 0.5rem;">Kamar <?= htmlspecialchars($kamar['nomor_kamar']) ?> (<?= htmlspecialchars($kamar['tipe_kamar']) ?>)</h3>
            <p style="color: var(--primary); font-weight: 600; font-size: 1.25rem;">Rp <?= number_format($kamar['harga'], 0, ',', '.') ?> <span style="font-size: 0.875rem; color: var(--text-muted); font-weight: normal;">/ malam</span></p>
        </div>

        <form action="booking.php?kamar_id=<?= $kamar['id'] ?>" method="POST">
            <div class="form-group">
                <label>Tanggal Check-in</label>
                <input type="date" name="tgl_checkin" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Check-out</label>
                <input type="date" name="tgl_checkout" class="form-control" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem; font-size: 1.125rem;">Konfirmasi Booking</button>
        </form>
    </div>
</div>

<?php require_once 'includes/guest_footer.php'; ?>
