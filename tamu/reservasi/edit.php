<?php
$title = "Edit Reservasi";
require_once '../../config/db.php';
require_once '../../includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM reservasi WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if(!$row) {
    echo "Data reservasi tidak ditemukan.";
    require_once '../../includes/footer.php';
    exit;
}

$tamu = $pdo->query("SELECT id, nama_tamu FROM tamu ORDER BY nama_tamu ASC")->fetchAll();
// fetch all rooms because the room might not be available anymore but still selected
$kamar = $pdo->query("SELECT id, nomor_kamar, tipe_kamar, harga FROM kamar ORDER BY nomor_kamar ASC")->fetchAll();
?>
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 class="mb-3">Edit Data Reservasi</h3>
    <form action="proses.php?action=edit" method="POST">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <div class="form-group">
            <label>Tamu</label>
            <select name="tamu_id" class="form-control" required>
                <?php foreach ($tamu as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= $row['tamu_id'] == $t['id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['nama_tamu']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Kamar</label>
            <select name="kamar_id" class="form-control" required>
                <?php foreach ($kamar as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= $row['kamar_id'] == $k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nomor_kamar']) ?> - <?= htmlspecialchars($k['tipe_kamar']) ?> (Rp <?= number_format($k['harga'], 0, ',', '.') ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Tanggal Check-in</label>
            <input type="date" name="tgl_checkin" class="form-control" value="<?= $row['tgl_checkin'] ?>" required>
        </div>
        <div class="form-group">
            <label>Tanggal Check-out</label>
            <input type="date" name="tgl_checkout" class="form-control" value="<?= $row['tgl_checkout'] ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="booking" <?= $row['status'] == 'booking' ? 'selected' : '' ?>>Booking</option>
                <option value="checkin" <?= $row['status'] == 'checkin' ? 'selected' : '' ?>>Check-in</option>
                <option value="checkout" <?= $row['status'] == 'checkout' ? 'selected' : '' ?>>Check-out</option>
                <option value="batal" <?= $row['status'] == 'batal' ? 'selected' : '' ?>>Batal</option>
            </select>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="index.php" class="btn" style="background: var(--border-color); color: var(--text-color);">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../../includes/footer.php'; ?>
