<?php
$title = "Data Tamu";
require_once '../config/db.php';
require_once '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM tamu ORDER BY nama_tamu ASC");
?>
<div class="card">
    <div class="d-flex justify-between align-center mb-3">
        <h3>Daftar Tamu</h3>
        <a href="tambah.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Tamu</a>
    </div>
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama Tamu</th>
                    <th>L/P</th>
                    <th>No. Telp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nik']) ?></td>
                    <td><?= htmlspecialchars($row['nama_tamu']) ?></td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                    <td><?= htmlspecialchars($row['no_telp']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="proses.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data tamu ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($stmt->rowCount() == 0): ?>
                <tr><td colspan="5" style="text-align: center;">Data kosong</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
