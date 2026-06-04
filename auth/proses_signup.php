<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = 'admin'; // Default role for this form

    if (empty($nama_lengkap) || empty($username) || empty($password)) {
        $_SESSION['error'] = "Semua kolom wajib diisi!";
        header("Location: signup.php");
        exit();
    }

    // Check if username exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Username sudah digunakan, silakan pilih yang lain.";
        header("Location: signup.php");
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $hash, $nama_lengkap, $role])) {
        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login dengan akun baru Anda.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data.";
        header("Location: signup.php");
        exit();
    }
}
?>
