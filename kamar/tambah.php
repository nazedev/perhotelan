<?php
$title = "Tambah Kamar";
require_once '../config/db.php';
require_once '../includes/header.php';
?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h3 class="mb-3">Tambah Kamar Baru</h3>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form action="proses.php?action=add" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nomor Kamar</label>
            <input type="text" name="nomor_kamar" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tipe Kamar</label>
            <input type="text" name="tipe_kamar" class="form-control" placeholder="Contoh: Standard, Deluxe, Suite" required>
        </div>
        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="tersedia">Tersedia</option>
                <option value="terisi">Terisi</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>

        <!-- Multi Photo Upload -->
        <div class="form-group">
            <label><i class="fas fa-camera"></i> Foto Kamar (bisa lebih dari 1)</label>
            <div class="foto-upload-area">
                <input type="file" name="foto_kamar[]" id="foto-kamar-input" multiple accept="image/jpeg,image/png,image/webp,image/gif">
                <div>
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p style="color: var(--text-muted); margin-top: 0.5rem;">Klik atau drag foto ke sini</p>
                    <p style="color: var(--text-muted); font-size: 0.8rem;">JPG, PNG, WebP, GIF — Maks. 5MB per file</p>
                </div>
            </div>
            <div class="foto-preview-grid" id="foto-preview-grid"></div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="index.php" class="btn" style="background: var(--border-color); color: var(--text-color);">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>
