<?php
$title = "Data Reservasi";
require_once '../../config/db.php';
require_once '../../includes/header.php';

$stmt = $pdo->query("SELECT r.*, t.nama_tamu, k.nomor_kamar FROM reservasi r JOIN tamu t ON r.tamu_id = t.id JOIN kamar k ON r.kamar_id = k.id ORDER BY r.created_at DESC");
?>
<div class="card">
    <div class="d-flex justify-between align-center mb-3">
        <h3>Daftar Reservasi</h3>
        <a href="tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Reservasi</a>
    </div>
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tamu</th>
                    <th>Kamar</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_tamu']) ?></td>
                    <td><?= htmlspecialchars($row['nomor_kamar']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tgl_checkin'])) ?></td>
                    <td><?= date('d M Y', strtotime($row['tgl_checkout'])) ?></td>
                    <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="proses.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data reservasi ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($stmt->rowCount() == 0): ?>
                <tr><td colspan="7" style="text-align: center;">Data kosong</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../../includes/footer.php'; ?>
