<?php
session_start();
$parts = explode('/', $_SERVER['SCRIPT_NAME'] ?? '');
$base_url = (isset($parts[1]) && $parts[1] === 'pw') ? '/pw' : '';
if (isset($_SESSION['user_id'])) {
    header("Location: $base_url/admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Login - Hotel Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>/includes/assets/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2 class="auth-title"><i class="fas fa-hotel"></i> HotelSystem</h2>
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
            <form action="proses_login.php" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <div style="text-align: center; margin-top: 1.5rem;">
                <span style="color: var(--text-muted);">Belum punya akun? </span>
                <a href="signup.php" style="font-weight: 500;">Daftar di sini</a>
            </div>
            <div style="text-align: center; margin-top: 1rem;">
                <button id="theme-toggle" class="theme-toggle" aria-label="Ganti tema">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </div>
    <script src="<?= $base_url ?>/js/script.js"></script>
</body>
</html>
