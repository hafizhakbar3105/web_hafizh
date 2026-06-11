// --- 1. Logika Menu Mobile ---
const mobileBtn = document.getElementById('mobile-menu-btn');
const closeBtn = document.getElementById('close-menu');
const menu = document.getElementById('mobile-menu');
const mobileLinks = document.querySelectorAll('.mobile-link');

// Buka Menu
mobileBtn.addEventListener('click', () => {
    menu.classList.remove('translate-x-full');
});

// Tutup Menu
const closeMenu = () => {
    menu.classList.add('translate-x-full');
};

closeBtn.addEventListener('click', closeMenu);

// Tutup saat link diklik
mobileLinks.forEach(link => {
    link.addEventListener('click', closeMenu);
});


// --- 2. Scroll Reveal Animation ---
function reveal() {
    const reveals = document.querySelectorAll(".reveal");
    for (let i = 0; i < reveals.length; i++) {
        const windowHeight = window.innerHeight;
        const elementTop = reveals[i].getBoundingClientRect().top;
        const elementVisible = 150; // Jarak sebelum elemen muncul

        if (elementTop < windowHeight - elementVisible) {
            reveals[i].classList.add("active");
        }
    }
}


// --- 3. Navbar Styling on Scroll ---
function handleNavbar() {
    const nav = document.getElementById('main-nav');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
}

window.addEventListener("scroll", () => {
    reveal();
    handleNavbar();
});

// Jalankan saat pertama kali dibuka
window.onload = () => {
    reveal();
};