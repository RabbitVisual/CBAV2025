document.addEventListener('DOMContentLoaded', function() {

    // --- Menu Mobile ---
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuPanel = document.getElementById('mobile-menu-panel');

    if (mobileMenuButton && mobileMenuPanel) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenuPanel.classList.toggle('hidden');
        });
    }

    // --- Header com Scroll ---
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('bg-blue-800/90', 'backdrop-blur-sm');
            } else {
                header.classList.remove('bg-blue-800/90', 'backdrop-blur-sm');
            }
        });
    }

    // --- Botão Voltar ao Topo ---
    const backToTopButton = document.getElementById('backToTop');
    if (backToTopButton) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.remove('scale-0');
            } else {
                backToTopButton.classList.add('scale-0');
            }
        });
    }

    // --- Gestão de Cookies ---
    const cookieBanner = document.getElementById('cookieBanner');
    const acceptCookiesButton = document.getElementById('acceptCookies');
    const rejectCookiesButton = document.getElementById('rejectCookies');

    const cookieConsent = localStorage.getItem('cookieConsent');

    if (!cookieConsent && cookieBanner) {
        setTimeout(() => {
            cookieBanner.classList.remove('translate-y-full');
        }, 1000);
    }

    if (acceptCookiesButton) {
        acceptCookiesButton.addEventListener('click', () => {
            localStorage.setItem('cookieConsent', 'true');
            cookieBanner.classList.add('translate-y-full');
        });
    }

    if (rejectCookiesButton) {
        rejectCookiesButton.addEventListener('click', () => {
            localStorage.setItem('cookieConsent', 'false');
            cookieBanner.classList.add('translate-y-full');
        });
    }

    // --- Smooth Scroll para Âncoras ---
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});