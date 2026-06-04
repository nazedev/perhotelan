<?php
session_start();
require_once '../config/db.php';
require_once '../includes/auth_cek.php';

$action = $_GET['action'] ?? '';

if ($action == 'add') {
    $nik = trim($_POST['nik']);
    $nama_tamu = trim($_POST['nama_tamu']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = trim($_POST['no_telp']);
    $alamat = trim($_POST['alamat']);

    $stmt = $pdo->prepare("INSERT INTO tamu (nik, nama_tamu, jenis_kelamin, no_telp, alamat) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nik, $nama_tamu, $jenis_kelamin, $no_telp, $alamat]);
    $_SESSION['msg'] = "Data tamu berhasil ditambahkan!";
    header("Location: index.php");
    exit;
} elseif ($action == 'edit') {
    $id = $_POST['id'];
    $nik = trim($_POST['nik']);
    $nama_tamu = trim($_POST['nama_tamu']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = trim($_POST['no_telp']);
    $alamat = trim($_POST['alamat']);

    $stmt = $pdo->prepare("UPDATE tamu SET nik = ?, nama_tamu = ?, jenis_kelamin = ?, no_telp = ?, alamat = ? WHERE id = ?");
    $stmt->execute([$nik, $nama_tamu, $jenis_kelamin, $no_telp, $alamat, $id]);
    $_SESSION['msg'] = "Data tamu berhasil diupdate!";
    header("Location: index.php");
    exit;
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM tamu WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = "Data tamu berhasil dihapus!";
    header("Location: index.php");
    exit;
}
?>
