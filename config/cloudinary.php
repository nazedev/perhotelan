<?php
// ============================================
// Cloudinary Upload Helper (Pure cURL, no SDK)
// ============================================

/**
 * Upload gambar ke Cloudinary
 * @param string $filePath - Path ke temporary file ($_FILES['xxx']['tmp_name'])
 * @param string $folder   - Folder di Cloudinary (e.g. 'hotel_grandiera/kamar')
 * @return array|false     - ['public_id' => '...', 'secure_url' => '...'] atau false jika gagal
 */
function cloudinary_upload($filePath, $folder = 'hotel_grandiera') {
    $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
    $apiKey    = $_ENV['CLOUDINARY_API_KEY'] ?? '';
    $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';

    if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
        error_log('Cloudinary: Credentials not configured in .env');
        return false;
    }

    $timestamp = time();
    
    // Generate signature
    $params = [
        'folder'    => $folder,
        'timestamp' => $timestamp,
    ];
    ksort($params);
    $signStr = '';
    foreach ($params as $k => $v) {
        $signStr .= ($signStr ? '&' : '') . "$k=$v";
    }
    $signStr .= $apiSecret;
    $signature = sha1($signStr);

    // Build POST data
    $postData = [
        'file'      => new CURLFile($filePath),
        'folder'    => $folder,
        'timestamp' => $timestamp,
        'api_key'   => $apiKey,
        'signature' => $signature,
    ];

    $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        error_log("Cloudinary cURL error: $curlError");
        return false;
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200 || !isset($result['secure_url'])) {
        error_log("Cloudinary upload failed: " . ($result['error']['message'] ?? $response));
        return false;
    }

    return [
        'public_id'  => $result['public_id'],
        'secure_url' => $result['secure_url'],
        'width'      => $result['width'] ?? 0,
        'height'     => $result['height'] ?? 0,
    ];
}

/**
 * Hapus gambar dari Cloudinary
 * @param string $publicId - Public ID gambar di Cloudinary
 * @return bool
 */
function cloudinary_delete($publicId) {
    $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
    $apiKey    = $_ENV['CLOUDINARY_API_KEY'] ?? '';
    $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';

    if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
        return false;
    }

    $timestamp = time();
    $signStr = "public_id={$publicId}&timestamp={$timestamp}{$apiSecret}";
    $signature = sha1($signStr);

    $postData = [
        'public_id' => $publicId,
        'timestamp' => $timestamp,
        'api_key'   => $apiKey,
        'signature' => $signature,
    ];

    $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return isset($result['result']) && $result['result'] === 'ok';
}

/**
 * Generate Cloudinary optimized URL
 * @param string $url        - Original Cloudinary URL
 * @param int    $width      - Desired width
 * @param int    $height     - Desired height (0 = auto)
 * @param string $crop       - Crop mode (fill, fit, thumb, etc.)
 * @return string
 */
function cloudinary_url($url, $width = 800, $height = 0, $crop = 'fill') {
    if (empty($url)) return '';
    
    // Insert transformation before /upload/ segment
    $transform = "f_auto,q_auto,w_{$width}";
    if ($height > 0) {
        $transform .= ",h_{$height}";
    }
    $transform .= ",c_{$crop}";
    
    return preg_replace('/\/upload\//', "/upload/{$transform}/", $url, 1);
}

/**
 * Validasi file gambar sebelum upload
 * @param array $file - Element dari $_FILES array
 * @return string|true - True jika valid, string error message jika tidak
 */
function validate_image($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Upload gagal dengan error code: " . $file['error'];
    }

    if ($file['size'] > $maxSize) {
        return "Ukuran file terlalu besar. Maksimal 5MB.";
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        return "Format file tidak didukung. Gunakan JPG, PNG, WebP, atau GIF.";
    }

    return true;
}
?>
