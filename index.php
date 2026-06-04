<?php
$title = "Beranda";
require_once 'config/db.php';
require_once 'includes/guest_header.php';

// Fetch dashboard slider photos
$stmtSlider = $pdo->query("SELECT * FROM dashboard_foto WHERE is_active = 1 ORDER BY urutan ASC, id ASC");
$sliderFotos = $stmtSlider->fetchAll();

// Fetch available rooms with their photos
$stmt = $pdo->query("SELECT * FROM kamar WHERE status = 'tersedia' ORDER BY harga ASC");
$kamarList = $stmt->fetchAll();

// Fetch photos for all rooms
$kamarFotos = [];
if (count($kamarList) > 0) {
    $kamarIds = array_column($kamarList, 'id');
    $placeholders = implode(',', array_fill(0, count($kamarIds), '?'));
    $stmtFotos = $pdo->prepare("SELECT * FROM kamar_foto WHERE kamar_id IN ($placeholders) ORDER BY urutan ASC, id ASC");
    $stmtFotos->execute($kamarIds);
    foreach ($stmtFotos->fetchAll() as $foto) {
        $kamarFotos[$foto['kamar_id']][] = $foto;
    }
}
?>

<!-- Hero Section with Dynamic Slider -->
<section class="hero <?= count($sliderFotos) === 0 ? 'hero-static' : '' ?>">
    <?php if (count($sliderFotos) > 0): ?>
    <div class="hero-slider">
        <?php foreach ($sliderFotos as $i => $slide): ?>
        <div class="hero-slide <?= $i === 0 ? 'active' : '' ?>" 
             style="background-image: url('<?= htmlspecialchars($slide['cloudinary_url']) ?>');">
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="container hero-content">
        <h1>Selamat Datang di Hotel Grandiera</h1>
        <p>Temukan kenyamanan dan kemewahan tak terlupakan. Booking kamar Anda sekarang untuk pengalaman menginap terbaik.</p>
        <?php if (!isset($_SESSION['tamu_id'])): ?>
            <a href="register.php" class="btn btn-primary" style="font-size: 1.125rem; padding: 1rem 2rem;">Daftar Sekarang</a>
        <?php endif; ?>
    </div>

    <?php if (count($sliderFotos) > 1): ?>
    <div class="hero-dots">
        <?php foreach ($sliderFotos as $i => $slide): ?>
        <button class="hero-dot <?= $i === 0 ? 'active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<section class="section container">
    <h2 class="section-title">Kamar Tersedia</h2>
    
    <div class="room-grid">
        <?php foreach ($kamarList as $kamar): ?>
        <div class="room-card">
            <?php 
            $fotos = $kamarFotos[$kamar['id']] ?? [];
            if (count($fotos) > 0): 
            ?>
            <!-- Room Photo Carousel -->
            <div class="room-carousel">
                <div class="room-carousel-track">
                    <?php foreach ($fotos as $foto): ?>
                    <img src="<?= htmlspecialchars($foto['cloudinary_url']) ?>" 
                         alt="Foto Kamar <?= htmlspecialchars($kamar['nomor_kamar']) ?>"
                         loading="lazy">
                    <?php endforeach; ?>
                </div>
                <?php if (count($fotos) > 1): ?>
                <button class="room-carousel-nav prev" aria-label="Foto sebelumnya"><i class="fas fa-chevron-left"></i></button>
                <button class="room-carousel-nav next" aria-label="Foto selanjutnya"><i class="fas fa-chevron-right"></i></button>
                <div class="room-carousel-dots">
                    <?php foreach ($fotos as $fi => $f): ?>
                    <button class="room-carousel-dot <?= $fi === 0 ? 'active' : '' ?>" aria-label="Foto <?= $fi + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <!-- Fallback: No Photo -->
            <div class="room-img">
                <i class="fas fa-bed"></i>
            </div>
            <?php endif; ?>

            <div class="room-details">
                <h3 class="room-title">Kamar <?= htmlspecialchars($kamar['nomor_kamar']) ?> - <?= htmlspecialchars($kamar['tipe_kamar']) ?></h3>
                <div class="room-price">Rp <?= number_format($kamar['harga'], 0, ',', '.') ?> <span style="font-size: 1rem; color: var(--text-muted); font-weight: normal;">/malam</span></div>
                <div class="room-meta">
                    <span><i class="fas fa-wifi"></i> Free Wifi</span>
                    <span><i class="fas fa-tv"></i> Smart TV</span>
                </div>
                <?php if (isset($_SESSION['tamu_id'])): ?>
                    <a href="booking.php?kamar_id=<?= $kamar['id'] ?>" class="btn btn-primary btn-block">Booking Sekarang</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-block">Login untuk Booking</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (count($kamarList) == 0): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--surface); border-radius: 1rem; border: 1px solid var(--border);">
                <i class="fas fa-frown-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p>Maaf, saat ini tidak ada kamar yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/guest_footer.php'; ?>
