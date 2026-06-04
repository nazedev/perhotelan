<?php
$title = "Edit Kamar";
require_once '../config/db.php';
require_once '../includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM kamar WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if(!$row) {
    echo "Kamar tidak ditemukan.";
    require_once '../includes/footer.php';
    exit;
}

// Fetch existing photos
$stmtFoto = $pdo->prepare("SELECT * FROM kamar_foto WHERE kamar_id = ? ORDER BY urutan ASC, id ASC");
$stmtFoto->execute([$id]);
$fotos = $stmtFoto->fetchAll();
?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h3 class="mb-3">Edit Kamar</h3>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    <form action="proses.php?action=edit" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <div class="form-group">
            <label>Nomor Kamar</label>
            <input type="text" name="nomor_kamar" class="form-control" value="<?= htmlspecialchars($row['nomor_kamar']) ?>" required>
        </div>
        <div class="form-group">
            <label>Tipe Kamar</label>
            <input type="text" name="tipe_kamar" class="form-control" value="<?= htmlspecialchars($row['tipe_kamar']) ?>" required>
        </div>
        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" value="<?= $row['harga'] ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="tersedia" <?= $row['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="terisi" <?= $row['status'] == 'terisi' ? 'selected' : '' ?>>Terisi</option>
                <option value="maintenance" <?= $row['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
            </select>
        </div>

        <!-- Existing Photos -->
        <?php if (count($fotos) > 0): ?>
        <div class="form-group">
            <label><i class="fas fa-images"></i> Foto Saat Ini</label>
            <div class="foto-preview-grid">
                <?php foreach ($fotos as $i => $foto): ?>
                <div class="foto-preview-item">
                    <img src="<?= htmlspecialchars($foto['cloudinary_url']) ?>" alt="Foto kamar">
                    <?php if ($foto['is_primary']): ?>
                        <span class="foto-primary">Utama</span>
                    <?php endif; ?>
                    <a href="proses.php?action=delete_foto&foto_id=<?= $foto['id'] ?>&kamar_id=<?= $row['id'] ?>" 
                       class="foto-remove" 
                       onclick="return confirm('Hapus foto ini?')"
                       title="Hapus foto">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Upload New Photos -->
        <div class="form-group">
            <label><i class="fas fa-plus-circle"></i> Tambah Foto Baru</label>
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
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="index.php" class="btn" style="background: var(--border-color); color: var(--text-color);">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>
