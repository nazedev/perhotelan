<?php
$title = "Foto Dashboard";
require_once '../config/db.php';
require_once '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM dashboard_foto ORDER BY urutan ASC, id ASC");
$fotos = $stmt->fetchAll();
?>
<div class="card">
    <div class="d-flex justify-between align-center mb-3" style="flex-wrap: wrap; gap: 0.75rem;">
        <h3><i class="fas fa-images"></i> Kelola Foto Dashboard / Slider</h3>
    </div>
    
    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
        Foto-foto ini akan ditampilkan sebagai slider otomatis di halaman utama (hero section). Upload foto dengan resolusi tinggi untuk hasil terbaik.
    </p>

    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Upload Form -->
    <div class="card" style="background: var(--bg-color); border: 1px dashed var(--border-color);">
        <h4 style="margin-bottom: 1rem;"><i class="fas fa-upload"></i> Upload Foto Baru</h4>
        <form action="proses_dashboard_foto.php?action=add" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;" class="upload-form-grid">
                <div class="form-group">
                    <label>Judul (opsional)</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Lobby Mewah">
                </div>
                <div class="form-group">
                    <label>Deskripsi (opsional)</label>
                    <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi singkat">
                </div>
            </div>
            <div class="form-group">
                <div class="foto-upload-area">
                    <input type="file" name="foto_dashboard" accept="image/jpeg,image/png,image/webp,image/gif" required>
                    <div>
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p style="color: var(--text-muted); margin-top: 0.5rem;">Klik untuk pilih foto</p>
                        <p style="color: var(--text-muted); font-size: 0.8rem;">JPG, PNG, WebP — Maks. 5MB — Disarankan 1920×1080</p>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
        </form>
    </div>
</div>

<!-- Existing Photos -->
<?php if (count($fotos) > 0): ?>
<div class="card">
    <h3 class="mb-3"><i class="fas fa-th-large"></i> Foto Terpasang (<?= count($fotos) ?>)</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Preview</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fotos as $foto): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($foto['cloudinary_url']) ?>" 
                             alt="<?= htmlspecialchars($foto['judul'] ?? 'Dashboard foto') ?>" 
                             style="width: 70px; height: 45px; object-fit: cover; border-radius: 6px;">
                    </td>
                    <td><?= htmlspecialchars($foto['judul'] ?? '-') ?></td>
                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?= htmlspecialchars($foto['deskripsi'] ?? '-') ?>
                    </td>
                    <td>
                        <form action="proses_dashboard_foto.php?action=update_urutan" method="POST" style="display: inline-flex; align-items: center; gap: 4px;">
                            <input type="hidden" name="id" value="<?= $foto['id'] ?>">
                            <input type="number" name="urutan" value="<?= $foto['urutan'] ?>" class="form-control" style="width: 60px; padding: 0.3rem 0.5rem; text-align: center;">
                            <button type="submit" class="btn btn-sm btn-primary" title="Update urutan"><i class="fas fa-check"></i></button>
                        </form>
                    </td>
                    <td>
                        <?php if ($foto['is_active']): ?>
                            <span class="badge badge-tersedia">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-maintenance">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="proses_dashboard_foto.php?action=toggle&id=<?= $foto['id'] ?>" class="btn btn-sm <?= $foto['is_active'] ? 'btn-danger' : 'btn-success' ?>" title="<?= $foto['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                <i class="fas <?= $foto['is_active'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                            </a>
                            <a href="proses_dashboard_foto.php?action=delete&id=<?= $foto['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus foto ini dari Cloudinary?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="card" style="text-align: center; padding: 3rem;">
    <i class="fas fa-image" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
    <p style="color: var(--text-muted);">Belum ada foto dashboard. Upload foto pertama Anda di atas.</p>
</div>
<?php endif; ?>

<style>
@media (max-width: 768px) {
    .upload-form-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>
