<div align="center">
  <img src="https://img.icons8.com/color/120/000000/5-star-hotel.png" alt="Hotel Logo" width="120">

  # 🏨 Sistem Informasi Reservasi Hotel (SIRH) Enterprise

  Sebuah platform reservasi hotel modern, cepat, dan aman yang dibangun dengan pendekatan **Monolith Modular** berbasis PHP Native. 

  [![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![Database](https://img.shields.io/badge/TiDB-Cloud-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://tidbcloud.com/)
  [![Storage](https://img.shields.io/badge/Cloudinary-Media-3448C5?style=for-the-badge&logo=cloudinary&logoColor=white)](https://cloudinary.com/)
  [![License](https://img.shields.io/badge/License-MIT-success?style=for-the-badge)](LICENSE)
  
  ---
</div>

## 📖 Deskripsi Proyek

**Sistem Informasi Reservasi Hotel (SIRH)** adalah solusi perangkat lunak manajemen properti (PMS) *open-source* yang dirancang untuk mendigitalkan operasional perhotelan. Mulai dari pemesanan kamar oleh tamu (front-end) hingga manajemen ketersediaan dan moderasi pembayaran oleh staf (back-office). 

Sistem ini dirancang tanpa *framework* (*Native*) guna mengutamakan performa pemrosesan tinggi, ukuran file yang ringan, serta kemudahan modifikasi. Dioptimalkan untuk berjalan pada infrastruktur *cloud-native* maupun *shared hosting* tradisional.

## ✨ Fitur Unggulan

### 🧑‍💼 Modul Tamu (Guest Portal)
- **Katalog Kamar Dinamis:** Pencarian dan filter kamar berdasarkan tipe, harga, dan ketersediaan secara *real-time*.
- **Pemesanan Multi-Step:** Alur pemesanan interaktif dengan kalkulasi harga otomatis berdasarkan durasi inap (Check-in & Check-out).
- **Google SSO (Single Sign-On):** Integrasi *one-click login* dengan Google Identity Service (Mendatang).
- **Riwayat Reservasi:** Pelacakan status pesanan (*Booking, Check-in, Check-out, Cancel*) dalam satu dashboard tamu.

### 🛡️ Modul Manajemen (Admin & Resepsionis)
- **Role-Based Access Control (RBAC):** Pemisahan tegas antara wewenang Administrator (manajemen sistem/user) dan Resepsionis (manajemen tamu/reservasi).
- **Manajemen Media Terpusat:** Integrasi Cloudinary API untuk *upload* dan optimisasi foto kamar/slider secara dinamis, menghemat penyimpanan lokal.
- **Dashboard Analitik:** Ringkasan okupansi kamar, pendapatan bulanan, dan aktivitas tamu terkini.
- **Manajemen Ketersediaan:** Pemblokiran kamar untuk *maintenance* atau *force majeure*.

---

## 🏗️ Arsitektur & Teknologi

SIRH menggunakan arsitektur **MVC-like** (meski secara native) dengan memisahkan *logic* database, otentikasi, dan presentasi UI.

| Lapisan | Teknologi & Tools |
| :--- | :--- |
| **Frontend UI/UX** | HTML5 Semantic, CSS3 (Custom Variables), Vanilla JavaScript |
| **Backend Logic** | PHP 8.x (Native, Object-Oriented & Procedural Hybrid) |
| **Database** | MySQL / TiDB Cloud Serverless (Relational RDBMS) |
| **Media Storage** | Cloudinary SDK / REST API (CDN-backed) |
| **Security Layer** | PDO Prepared Statements, Bcrypt Password Hashing, CSRF Tokens |

---

## 🗄️ Skema Database Utama (ERD Ringkas)

Sistem menggunakan 5 tabel utama yang saling berelasi dengan tipe data yang ketat:

1. `users`: Menyimpan kredensial staf (`admin`, `resepsionis`).
2. `tamu`: Menyimpan profil pelanggan/tamu hotel (Mendukung integrasi Google ID).
3. `kamar`: Master data inventori kamar beserta harga dasar.
4. `reservasi`: Tabel transaksional (Relasi ke `tamu` dan `kamar`) yang merekam *check-in*, *check-out*, dan status inap.
5. `kamar_foto` & `dashboard_foto`: Menyimpan referensi URL gambar Cloudinary (`public_id`).

---

## 🚀 Panduan Instalasi (Deployment)

### 1. Prasyarat Sistem
- Web Server: Apache 2.4+ / Nginx
- PHP: Versi 8.0 ke atas (Ekstensi wajib: `pdo`, `pdo_mysql`, `curl`, `json`)
- Database: MySQL 5.7+ / MariaDB 10.3+ / TiDB Cloud

### 2. Instalasi Lokal (Development)

```bash
# 1. Clone repository
git clone https://github.com/nazedev/hotel-reservation.git
cd hotel-reservation

# 2. Setup Environment Variables
cp .env.example .env
```

Buka file `.env` dan konfigurasikan koneksi database Anda:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=hotel_db
DB_USER=root
DB_PASS=secret
DB_SSL=false

# Konfigurasi Cloudinary (Wajib untuk fitur foto)
CLOUDINARY_URL="cloudinary://API_KEY:API_SECRET@CLOUD_NAME"
```

```bash
# 3. Jalankan migrasi database
# Eksekusi file database.sql melalui PHPMyAdmin, DBeaver, atau MySQL CLI:
mysql -u root -p hotel_db < database.sql

# 4. Jalankan server lokal
php -S localhost:8000
```
Buka browser dan akses: `http://localhost:8000`. <br>
**Akses Admin Default:** Username: `admin` | Password: `admin123`

---

## 🔐 Standar Keamanan Sistem

Proyek ini sangat memperhatikan mitigasi kerentanan *OWASP Top 10*:
*   **SQL Injection Prevention:** Seluruh *query* database wajib menggunakan PDO Prepared Statements (`$stmt->prepare()` dan `$stmt->execute()`).
*   **XSS Protection:** Output dari database (seperti nama tamu atau catatan) selalu di-*sanitize* dengan `htmlspecialchars()`.
*   **Secure Authentication:** Tidak menyimpan *plain-text password*. Menggunakan algoritma hash terstandar PHP `password_hash($pass, PASSWORD_BCRYPT)`.
*   **Environment Isolation:** Kredensial penting dijauhkan dari *source code* dan disimpan dengan aman menggunakan *parser* `.env`.

---

## 🤝 Kontribusi (Contributing)

Kami sangat menyambut kontribusi (*Pull Requests* & *Issues*) dari komunitas!

1. *Fork* repositori ini.
2. Buat *branch* fitur Anda (`git checkout -b feature/FiturBaru`).
3. *Commit* perubahan Anda (`git commit -m 'Menambahkan fitur keren'`).
4. *Push* ke *branch* Anda (`git push origin feature/FiturBaru`).
5. Buka *Pull Request* baru.

---

<div align="center">
  <p>Dibuat dengan ❤️ untuk ekosistem perhotelan Indonesia.</p>
  <p>&copy; 2026 NazeDev / Hak Cipta Dilindungi Undang-Undang.</p>
</div>
