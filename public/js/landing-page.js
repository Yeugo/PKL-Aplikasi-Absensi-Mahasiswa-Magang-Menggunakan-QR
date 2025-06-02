// js/landing-page.js

document.addEventListener('DOMContentLoaded', function () {
    // Smooth scrolling untuk link navigasi
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Optional: Highlight active nav link on scroll (scrollspy manual)
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop - document.querySelector('.navbar').offsetHeight;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop && pageYOffset < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    });

    // Optional: Add a class to navbar on scroll for styling (e.g., make it opaque)
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) { // Setelah scroll 50px
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Tambahkan CSS untuk class 'scrolled' di landing-page.css jika menggunakan ini
    /*
    .navbar.scrolled {
        background-color: rgba(255, 255, 255, 0.95) !important; // Sedikit transparan
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        backdrop-filter: blur(5px); // Efek blur (modern)
    }
    */
});
