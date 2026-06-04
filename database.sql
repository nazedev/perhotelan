CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'resepsionis') DEFAULT 'admin'
);

CREATE TABLE IF NOT EXISTS kamar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_kamar VARCHAR(10) NOT NULL UNIQUE,
    tipe_kamar VARCHAR(50) NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    status ENUM('tersedia', 'terisi', 'maintenance') DEFAULT 'tersedia'
);

CREATE TABLE IF NOT EXISTS tamu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(20) UNIQUE,
    nama_tamu VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    no_telp VARCHAR(15),
    alamat TEXT
);

CREATE TABLE IF NOT EXISTS reservasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tamu_id INT NOT NULL,
    kamar_id INT NOT NULL,
    tgl_checkin DATE NOT NULL,
    tgl_checkout DATE NOT NULL,
    total_bayar DECIMAL(10, 2) NOT NULL,
    status ENUM('booking', 'checkin', 'checkout', 'batal') DEFAULT 'booking',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tamu_id) REFERENCES tamu(id) ON DELETE CASCADE,
    FOREIGN KEY (kamar_id) REFERENCES kamar(id) ON DELETE CASCADE
);

-- Foto Kamar (multi-foto per kamar, disimpan di Cloudinary)
CREATE TABLE IF NOT EXISTS kamar_foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kamar_id INT NOT NULL,
    cloudinary_public_id VARCHAR(255) NOT NULL,
    cloudinary_url VARCHAR(500) NOT NULL,
    urutan INT DEFAULT 0,
    is_primary TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kamar_id) REFERENCES kamar(id) ON DELETE CASCADE
);

-- Foto Dashboard/Slider (untuk hero section halaman utama)
CREATE TABLE IF NOT EXISTS dashboard_foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cloudinary_public_id VARCHAR(255) NOT NULL,
    cloudinary_url VARCHAR(500) NOT NULL,
    judul VARCHAR(200),
    deskripsi TEXT,
    urutan INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
-- bcrypt hash for 'admin123'
INSERT IGNORE INTO users (username, password, nama_lengkap, role) VALUES 
('admin', '$2y$10$Fw4gH05Wf9P3b/VzPzH0F.N3Y3n.c6x4k9U6N9/r5gM9E/p/4Y7Gq', 'Administrator', 'admin');