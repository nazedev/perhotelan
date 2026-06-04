<?php
session_start();
require_once '../config/db.php';
require_once '../config/cloudinary.php';
include_once '../includes/auth_cek.php';

$action = $_GET['action'] ?? '';

// ============================================
// ADD DASHBOARD FOTO
// ============================================
if ($action == 'add') {
    if (!isset($_FILES['foto_dashboard']) || $_FILES['foto_dashboard']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Pilih file foto terlebih dahulu!";
        header("Location: dashboard_foto.php");
        exit;
    }

    $file = $_FILES['foto_dashboard'];
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    // Validate
    $validation = validate_image($file);
    if ($validation !== true) {
        $_SESSION['error'] = $validation;
        header("Location: dashboard_foto.php");
        exit;
    }

    // Upload to Cloudinary
    $result = cloudinary_upload($file['tmp_name'], 'hotel_grandiera/dashboard');
    if (!$result) {
        $_SESSION['error'] = "Gagal upload foto ke Cloudinary. Periksa koneksi internet dan credentials.";
        header("Location: dashboard_foto.php");
        exit;
    }

    // Get next order number
    $stmtMax = $pdo->query("SELECT COALESCE(MAX(urutan), -1) + 1 FROM dashboard_foto");
    $nextUrutan = $stmtMax->fetchColumn();

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO dashboard_foto (cloudinary_public_id, cloudinary_url, judul, deskripsi, urutan, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([$result['public_id'], $result['secure_url'], $judul, $deskripsi, $nextUrutan]);

    $_SESSION['msg'] = "Foto dashboard berhasil diupload!";
    header("Location: dashboard_foto.php");
    exit;

// ============================================
// DELETE DASHBOARD FOTO
// ============================================
} elseif ($action == 'delete') {
    $id = $_GET['id'] ?? 0;
    
    $stmt = $pdo->prepare("SELECT * FROM dashboard_foto WHERE id = ?");
    $stmt->execute([$id]);
    $foto = $stmt->fetch();

    if ($foto) {
        // Delete from Cloudinary
        cloudinary_delete($foto['cloudinary_public_id']);

        // Delete from database
        $pdo->prepare("DELETE FROM dashboard_foto WHERE id = ?")->execute([$id]);
        $_SESSION['msg'] = "Foto berhasil dihapus!";
    }

    header("Location: dashboard_foto.php");
    exit;

// ============================================
// TOGGLE ACTIVE STATUS
// ============================================
} elseif ($action == 'toggle') {
    $id = $_GET['id'] ?? 0;
    $pdo->prepare("UPDATE dashboard_foto SET is_active = NOT is_active WHERE id = ?")->execute([$id]);
    $_SESSION['msg'] = "Status foto diperbarui!";
    header("Location: dashboard_foto.php");
    exit;

// ============================================
// UPDATE URUTAN
// ============================================
} elseif ($action == 'update_urutan') {
    $id = $_POST['id'] ?? 0;
    $urutan = (int)($_POST['urutan'] ?? 0);
    $pdo->prepare("UPDATE dashboard_foto SET urutan = ? WHERE id = ?")->execute([$urutan, $id]);
    $_SESSION['msg'] = "Urutan foto diperbarui!";
    header("Location: dashboard_foto.php");
    exit;
}
?>
