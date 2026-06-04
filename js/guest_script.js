document.addEventListener('DOMContentLoaded', () => {
    // ============================================
    // THEME TOGGLE (Guest)
    // ============================================
    const themeToggle = document.getElementById('guest-theme-toggle');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = htmlElement.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            htmlElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeIcon(next);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeToggle) return;
        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    }

    // ============================================
    // BURGER MENU (Guest Mobile Nav)
    // ============================================
    const burgerBtn = document.getElementById('guest-burger');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileOverlay = document.getElementById('mobile-nav-overlay');
    const mobileClose = document.getElementById('mobile-nav-close');

    function openMobileNav() {
        if (mobileNav) mobileNav.classList.add('active');
        if (mobileOverlay) mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
        if (mobileNav) mobileNav.classList.remove('active');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (burgerBtn) burgerBtn.addEventListener('click', openMobileNav);
    if (mobileClose) mobileClose.addEventListener('click', closeMobileNav);
    if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobileNav);

    // Close on resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) closeMobileNav();
    });

    // Touch swipe right to close
    let navTouchStartX = 0;
    if (mobileNav) {
        mobileNav.addEventListener('touchstart', (e) => {
            navTouchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        mobileNav.addEventListener('touchend', (e) => {
            const diff = e.changedTouches[0].screenX - navTouchStartX;
            if (diff > 80) closeMobileNav();
        }, { passive: true });
    }

    // ============================================
    // HERO SLIDER (Dashboard Foto)
    // ============================================
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        const slides = heroSlider.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        let currentSlide = 0;
        let heroInterval;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            
            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            if (dots[currentSlide]) dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function startHeroSlider() {
            if (slides.length > 1) {
                heroInterval = setInterval(nextSlide, 5000);
            }
        }

        function stopHeroSlider() {
            clearInterval(heroInterval);
        }

        // Initialize
        if (slides.length > 0) {
            showSlide(0);
            startHeroSlider();

            // Pause on hover
            heroSlider.parentElement.addEventListener('mouseenter', stopHeroSlider);
            heroSlider.parentElement.addEventListener('mouseleave', startHeroSlider);

            // Dot click
            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    stopHeroSlider();
                    showSlide(i);
                    startHeroSlider();
                });
            });

            // Touch swipe for hero
            let heroStartX = 0;
            heroSlider.parentElement.addEventListener('touchstart', (e) => {
                heroStartX = e.changedTouches[0].screenX;
                stopHeroSlider();
            }, { passive: true });

            heroSlider.parentElement.addEventListener('touchend', (e) => {
                const diff = heroStartX - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 50) {
                    showSlide(diff > 0 ? currentSlide + 1 : currentSlide - 1);
                }
                startHeroSlider();
            }, { passive: true });
        }
    }

    // ============================================
    // ROOM CAROUSELS (Auto-slide)
    // ============================================
    const roomCarousels = document.querySelectorAll('.room-carousel');

    roomCarousels.forEach(carousel => {
        const track = carousel.querySelector('.room-carousel-track');
        const images = track ? track.querySelectorAll('img') : [];
        const dots = carousel.querySelectorAll('.room-carousel-dot');
        const prevBtn = carousel.querySelector('.room-carousel-nav.prev');
        const nextBtn = carousel.querySelector('.room-carousel-nav.next');

        if (images.length <= 1) return;

        let currentIndex = 0;
        let autoSlideInterval;

        function goToSlide(index) {
            currentIndex = (index + images.length) % images.length;
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            dots.forEach(d => d.classList.remove('active'));
            if (dots[currentIndex]) dots[currentIndex].classList.add('active');
        }

        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, 4000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        // Init
        goToSlide(0);
        startAutoSlide();

        // Hover pause
        carousel.addEventListener('mouseenter', stopAutoSlide);
        carousel.addEventListener('mouseleave', startAutoSlide);

        // Nav buttons
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                stopAutoSlide();
                goToSlide(currentIndex - 1);
                startAutoSlide();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                stopAutoSlide();
                goToSlide(currentIndex + 1);
                startAutoSlide();
            });
        }

        // Dots
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                stopAutoSlide();
                goToSlide(i);
                startAutoSlide();
            });
        });

        // Touch swipe
        let startX = 0;
        carousel.addEventListener('touchstart', (e) => {
            startX = e.changedTouches[0].screenX;
            stopAutoSlide();
        }, { passive: true });

        carousel.addEventListener('touchend', (e) => {
            const diff = startX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 40) {
                goToSlide(diff > 0 ? currentIndex + 1 : currentIndex - 1);
            }
            startAutoSlide();
        }, { passive: true });
    });
});
