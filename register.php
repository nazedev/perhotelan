<?php
$title = "Daftar Akun";
require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['tamu_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = trim($_POST['nik'] ?? '');
    $nama_tamu = trim($_POST['nama_tamu'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $no_telp = trim($_POST['no_telp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if (empty($nama_tamu) || empty($username) || empty($password)) {
        $_SESSION['error'] = "Nama, Username, dan Password wajib diisi!";
    } else {
        // Cek username
        $stmt = $pdo->prepare("SELECT id FROM tamu WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Username sudah digunakan!";
        } else {
            // Cek NIK
            $stmt = $pdo->prepare("SELECT id FROM tamu WHERE nik = ? AND nik != '' AND nik IS NOT NULL");
            $stmt->execute([$nik]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = "NIK sudah terdaftar!";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO tamu (nik, nama_tamu, username, password, jenis_kelamin, no_telp, alamat) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([empty($nik) ? null : $nik, $nama_tamu, $username, $hash, $jenis_kelamin, $no_telp, $alamat])) {
                    $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
                    header("Location: login.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan sistem.";
                }
            }
        }
    }
}
require_once 'includes/guest_header.php';
?>

<div class="container">
    <div class="auth-container">
        <h2 class="auth-title">Daftar Akun Tamu</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama_tamu" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>NIK (KTP)</label>
                <input type="text" name="nik" class="form-control">
            </div>
            <div class="form-group">
                <label>Jenis Kelamin *</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_telp" class="form-control">
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">Daftar</button>
        </form>
        <div style="text-align: center; margin-top: 1.5rem;">
            <span style="color: var(--text-muted);">Sudah punya akun? </span>
            <a href="login.php" style="color: var(--primary); font-weight: 500;">Login di sini</a>
        </div>
    </div>
</div>

<?php require_once 'includes/guest_footer.php'; ?>
