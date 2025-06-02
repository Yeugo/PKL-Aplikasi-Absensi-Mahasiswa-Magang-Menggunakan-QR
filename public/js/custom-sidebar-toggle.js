document.addEventListener('DOMContentLoaded', function () {
    // Dapatkan elemen-elemen yang dibutuhkan
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const body = document.querySelector('body');
    const contentWrapper = document.getElementById('content-wrapper'); // Pastikan Anda memiliki id ini di wrapper konten utama Anda

    if (sidebarToggle && sidebar && body) {
        sidebarToggle.addEventListener('click', function () {
            // Toggle class 'toggled' pada sidebar
            sidebar.classList.toggle('toggled');

            // Toggle class 'sidebar-toggled' pada body
            body.classList.toggle('sidebar-toggled');

            // Jika sidebar terbuka, tutup semua submenu collapse (opsional, tapi umum di tema)
            if (sidebar.classList.contains('toggled')) {
                const collapses = sidebar.querySelectorAll('.collapse');
                collapses.forEach(collapse => {
                    // Ini akan memerlukan Bootstrap JS untuk fungsi collapse
                    // Jika Anda menggunakan Bootstrap JS, ini akan bekerja.
                    // Atau, Anda bisa menambahkan logika untuk menyembunyikan secara manual.
                    if (collapse.classList.contains('show')) {
                        new bootstrap.Collapse(collapse, {
                            toggle: false
                        }).hide();
                    }
                });
            }
        });
    }
});
