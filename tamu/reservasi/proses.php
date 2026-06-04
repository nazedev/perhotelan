<?php
session_start();
require_once '../../config/db.php';
require_once '../../includes/auth_cek.php';

$action = $_GET['action'] ?? '';

if ($action == 'add' || $action == 'edit') {
    $tamu_id = $_POST['tamu_id'];
    $kamar_id = $_POST['kamar_id'];
    $tgl_checkin = $_POST['tgl_checkin'];
    $tgl_checkout = $_POST['tgl_checkout'];
    $status = $_POST['status'];

    // Calculate total bayar
    $checkin = new DateTime($tgl_checkin);
    $checkout = new DateTime($tgl_checkout);
    $interval = $checkin->diff($checkout);
    $days = $interval->days > 0 ? $interval->days : 1; // at least 1 day

    $stmtKamar = $pdo->prepare("SELECT harga FROM kamar WHERE id = ?");
    $stmtKamar->execute([$kamar_id]);
    $kamar = $stmtKamar->fetch();
    $total_bayar = $kamar['harga'] * $days;

    if ($action == 'add') {
        $stmt = $pdo->prepare("INSERT INTO reservasi (tamu_id, kamar_id, tgl_checkin, tgl_checkout, total_bayar, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$tamu_id, $kamar_id, $tgl_checkin, $tgl_checkout, $total_bayar, $status]);
        
        // update kamar status if checkin
        if ($status == 'checkin') {
            $pdo->prepare("UPDATE kamar SET status = 'terisi' WHERE id = ?")->execute([$kamar_id]);
        }
        
        $_SESSION['msg'] = "Data reservasi berhasil ditambahkan!";
    } else {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE reservasi SET tamu_id = ?, kamar_id = ?, tgl_checkin = ?, tgl_checkout = ?, total_bayar = ?, status = ? WHERE id = ?");
        $stmt->execute([$tamu_id, $kamar_id, $tgl_checkin, $tgl_checkout, $total_bayar, $status, $id]);
        
        // update kamar status
        if ($status == 'checkin') {
            $pdo->prepare("UPDATE kamar SET status = 'terisi' WHERE id = ?")->execute([$kamar_id]);
        } elseif ($status == 'checkout' || $status == 'batal') {
            $pdo->prepare("UPDATE kamar SET status = 'tersedia' WHERE id = ?")->execute([$kamar_id]);
        }
        
        $_SESSION['msg'] = "Data reservasi berhasil diupdate!";
    }
    
    header("Location: index.php");
    exit;
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM reservasi WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = "Data reservasi berhasil dihapus!";
    header("Location: index.php");
    exit;
}
?>
