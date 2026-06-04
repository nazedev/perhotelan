<?php
$title = "Edit Tamu";
require_once '../config/db.php';
require_once '../includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM tamu WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if(!$row) {
    echo "Data tamu tidak ditemukan.";
    require_once '../includes/footer.php';
    exit;
}
?>
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 class="mb-3">Edit Data Tamu</h3>
    <form action="proses.php?action=edit" method="POST">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <div class="form-group">
            <label>NIK</label>
            <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($row['nik']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_tamu" class="form-control" value="<?= htmlspecialchars($row['nama_tamu']) ?>" required>
        </div>
        <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="L" <?= $row['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= $row['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nomor Telepon</label>
            <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($row['no_telp']) ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="index.php" class="btn" style="background: var(--border-color); color: var(--text-color);">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>
