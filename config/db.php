<?php
// ============================================
// Parse .env file
// ============================================
function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Strip surrounding quotes ("value" or 'value')
        if (strlen($value) >= 2 && (
            ($value[0] === '"' && $value[strlen($value)-1] === '"') ||
            ($value[0] === "'" && $value[strlen($value)-1] === "'")
        )) {
            $value = substr($value, 1, -1);
        }
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Load .env dari root project
loadEnv(__DIR__ . '/../.env');

// ============================================
// Database Connection (TiDB Cloud / Local)
// ============================================
$host   = $_ENV['DB_HOST']   ?? 'localhost';
$port   = $_ENV['DB_PORT']   ?? '3306';
$dbname = $_ENV['DB_NAME']   ?? 'hotel_db';
$user   = $_ENV['DB_USER']   ?? 'root';
$pass   = $_ENV['DB_PASS']   ?? '';
$useSSL = ($_ENV['DB_SSL']   ?? 'false') === 'true';
$sslCA  = $_ENV['DB_SSL_CA'] ?? '';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // TiDB Cloud SSL support
    if ($useSSL && !empty($sslCA) && file_exists($sslCA)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCA;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
    } elseif ($useSSL) {
        // TiDB Cloud tanpa CA file (gunakan system CA)
        $options[PDO::MYSQL_ATTR_SSL_CA] = '';
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
