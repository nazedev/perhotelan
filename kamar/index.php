<?php
$title = "Data Kamar";
require_once '../config/db.php';
require_once '../includes/header.php';

$stmt = $pdo->query("SELECT k.*, (SELECT COUNT(*) FROM kamar_foto WHERE kamar_id = k.id) as foto_count, 
                      (SELECT cloudinary_url FROM kamar_foto WHERE kamar_id = k.id AND is_primary = 1 LIMIT 1) as foto_url
                      FROM kamar k ORDER BY nomor_kamar ASC");
?>
<div class="card">
    <div class="d-flex justify-between align-center mb-3">
        <h3>Daftar Kamar</h3>
        <a href="tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kamar</a>
    </div>
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>No. Kamar</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td>
                        <?php if ($row['foto_url']): ?>
                        <img src="<?= htmlspecialchars($row['foto_url']) ?>" 
                             alt="Kamar <?= htmlspecialchars($row['nomor_kamar']) ?>" 
                             style="width: 60px; height: 40px; object-fit: cover; border-radius: 6px;">
                        <?php else: ?>
                        <div style="width: 60px; height: 40px; background: var(--bg-color); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.8rem;">
                            <i class="fas fa-image"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['nomor_kamar']) ?></td>
                    <td><?= htmlspecialchars($row['tipe_kamar']) ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="proses.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kamar ini? Semua foto akan ikut terhapus.')" title="Hapus"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($stmt->rowCount() == 0): ?>
                <tr><td colspan="6" style="text-align: center;">Data kosong</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
