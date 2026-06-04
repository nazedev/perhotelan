<?php
$title = "Tambah Reservasi";
require_once '../../config/db.php';
require_once '../../includes/header.php';

$tamu = $pdo->query("SELECT id, nama_tamu FROM tamu ORDER BY nama_tamu ASC")->fetchAll();
$kamar = $pdo->query("SELECT id, nomor_kamar, tipe_kamar, harga FROM kamar WHERE status = 'tersedia' ORDER BY nomor_kamar ASC")->fetchAll();
?>
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 class="mb-3">Tambah Reservasi Baru</h3>
    <form action="proses.php?action=add" method="POST">
        <div class="form-group">
            <label>Tamu</label>
            <select name="tamu_id" class="form-control" required>
                <option value="">-- Pilih Tamu --</option>
                <?php foreach ($tamu as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nama_tamu']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Kamar (Harga/malam)</label>
            <select name="kamar_id" class="form-control" required>
                <option value="">-- Pilih Kamar --</option>
                <?php foreach ($kamar as $k): ?>
                    <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nomor_kamar']) ?> - <?= htmlspecialchars($k['tipe_kamar']) ?> (Rp <?= number_format($k['harga'], 0, ',', '.') ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Tanggal Check-in</label>
            <input type="date" name="tgl_checkin" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tanggal Check-out</label>
            <input type="date" name="tgl_checkout" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="booking">Booking</option>
                <option value="checkin">Check-in</option>
            </select>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="index.php" class="btn" style="background: var(--border-color); color: var(--text-color);">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../../includes/footer.php'; ?>
