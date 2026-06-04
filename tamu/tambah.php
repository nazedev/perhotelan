<?php
$title = "Tambah Tamu";
require_once '../config/db.php';
require_once '../includes/header.php';
?>
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 class="mb-3">Tambah Tamu Baru</h3>
    <form action="proses.php?action=add" method="POST">
        <div class="form-group">
            <label>NIK (Nomor Induk Kependudukan)</label>
            <input type="text" name="nik" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_tamu" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nomor Telepon</label>
            <input type="text" name="no_telp" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="3"></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="index.php" class="btn" style="background: var(--border-color); color: var(--text-color);">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>
