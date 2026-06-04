<?php
$title = "Login Tamu";
require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['tamu_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM tamu WHERE username = ?");
    $stmt->execute([$username]);
    $tamu = $stmt->fetch();

    if ($tamu && password_verify($password, $tamu['password'])) {
        $_SESSION['tamu_id'] = $tamu['id'];
        $_SESSION['tamu_nama'] = $tamu['nama_tamu'];
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Username atau password salah!";
    }
}
require_once 'includes/guest_header.php';
?>

<div class="container">
    <div class="auth-container">
        <h2 class="auth-title">Login Tamu</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 1.5rem;">Login</button>
        </form>
        <div style="text-align: center; margin-top: 1.5rem;">
            <span style="color: var(--text-muted);">Belum punya akun? </span>
            <a href="register.php" style="color: var(--primary); font-weight: 500;">Daftar di sini</a>
        </div>
    </div>
</div>

<?php require_once 'includes/guest_footer.php'; ?>
