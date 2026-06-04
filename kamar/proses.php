<?php
session_start();
require_once '../config/db.php';
require_once '../config/cloudinary.php';

$action = $_GET['action'] ?? '';

// ============================================
// Helper: Upload multiple photos to Cloudinary
// ============================================
function uploadKamarFotos($pdo, $kamarId, $files) {
    if (empty($files['name'][0])) return;

    // Check if kamar already has photos
    $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM kamar_foto WHERE kamar_id = ?");
    $stmtCount->execute([$kamarId]);
    $existingCount = $stmtCount->fetchColumn();

    $fileCount = count($files['name']);
    $uploadedCount = 0;

    for ($i = 0; $i < $fileCount; $i++) {
        $file = [
            'name'     => $files['name'][$i],
            'type'     => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error'    => $files['error'][$i],
            'size'     => $files['size'][$i],
        ];

        // Validate
        $validation = validate_image($file);
        if ($validation !== true) {
            continue; // Skip invalid files
        }

        // Upload to Cloudinary
        $result = cloudinary_upload($file['tmp_name'], 'hotel_grandiera/kamar');
        if (!$result) {
            continue; // Skip failed uploads
        }

        // Save to database
        $isPrimary = ($existingCount === 0 && $uploadedCount === 0) ? 1 : 0;
        $urutan = $existingCount + $uploadedCount;

        $stmt = $pdo->prepare("INSERT INTO kamar_foto (kamar_id, cloudinary_public_id, cloudinary_url, urutan, is_primary) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$kamarId, $result['public_id'], $result['secure_url'], $urutan, $isPrimary]);
        $uploadedCount++;
    }

    return $uploadedCount;
}

// ============================================
// ADD KAMAR
// ============================================
if ($action == 'add') {
    $nomor_kamar = trim($_POST['nomor_kamar']);
    $tipe_kamar = trim($_POST['tipe_kamar']);
    $harga = trim($_POST['harga']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO kamar (nomor_kamar, tipe_kamar, harga, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nomor_kamar, $tipe_kamar, $harga, $status]);
    $kamarId = $pdo->lastInsertId();

    // Upload photos if provided
    if (isset($_FILES['foto_kamar']) && !empty($_FILES['foto_kamar']['name'][0])) {
        $uploaded = uploadKamarFotos($pdo, $kamarId, $_FILES['foto_kamar']);
        $_SESSION['msg'] = "Data kamar berhasil ditambahkan! ($uploaded foto diupload)";
    } else {
        $_SESSION['msg'] = "Data kamar berhasil ditambahkan!";
    }

    header("Location: index.php");
    exit;

// ============================================
// EDIT KAMAR
// ============================================
} elseif ($action == 'edit') {
    $id = $_POST['id'];
    $nomor_kamar = trim($_POST['nomor_kamar']);
    $tipe_kamar = trim($_POST['tipe_kamar']);
    $harga = trim($_POST['harga']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE kamar SET nomor_kamar = ?, tipe_kamar = ?, harga = ?, status = ? WHERE id = ?");
    $stmt->execute([$nomor_kamar, $tipe_kamar, $harga, $status, $id]);

    // Upload new photos if provided
    if (isset($_FILES['foto_kamar']) && !empty($_FILES['foto_kamar']['name'][0])) {
        $uploaded = uploadKamarFotos($pdo, $id, $_FILES['foto_kamar']);
        $_SESSION['msg'] = "Data kamar berhasil diupdate! ($uploaded foto baru diupload)";
    } else {
        $_SESSION['msg'] = "Data kamar berhasil diupdate!";
    }

    header("Location: index.php");
    exit;

// ============================================
// DELETE KAMAR
// ============================================
} elseif ($action == 'delete') {
    $id = $_GET['id'];
    
    // Delete all photos from Cloudinary first
    $stmtFotos = $pdo->prepare("SELECT cloudinary_public_id FROM kamar_foto WHERE kamar_id = ?");
    $stmtFotos->execute([$id]);
    while ($foto = $stmtFotos->fetch()) {
        cloudinary_delete($foto['cloudinary_public_id']);
    }

    $stmt = $pdo->prepare("DELETE FROM kamar WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = "Data kamar berhasil dihapus!";
    header("Location: index.php");
    exit;

// ============================================
// DELETE SINGLE PHOTO
// ============================================
} elseif ($action == 'delete_foto') {
    $fotoId = $_GET['foto_id'] ?? 0;
    $kamarId = $_GET['kamar_id'] ?? 0;

    // Get photo info
    $stmt = $pdo->prepare("SELECT * FROM kamar_foto WHERE id = ?");
    $stmt->execute([$fotoId]);
    $foto = $stmt->fetch();

    if ($foto) {
        // Delete from Cloudinary
        cloudinary_delete($foto['cloudinary_public_id']);

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM kamar_foto WHERE id = ?");
        $stmt->execute([$fotoId]);

        // If deleted photo was primary, set next photo as primary
        if ($foto['is_primary']) {
            $stmtNext = $pdo->prepare("SELECT id FROM kamar_foto WHERE kamar_id = ? ORDER BY urutan ASC LIMIT 1");
            $stmtNext->execute([$kamarId]);
            $nextFoto = $stmtNext->fetch();
            if ($nextFoto) {
                $pdo->prepare("UPDATE kamar_foto SET is_primary = 1 WHERE id = ?")->execute([$nextFoto['id']]);
            }
        }

        $_SESSION['msg'] = "Foto berhasil dihapus!";
    }

    header("Location: edit.php?id=$kamarId");
    exit;
}
?>
