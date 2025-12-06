import './bootstrap';

// Impor Alpine.js untuk interaktivitas UI (sidebar toggle)
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();


/**
 * Fungsi Validasi Frontend:
 * Hanya mengizinkan pengetikan angka dan tombol kontrol (backspace, tab, panah).
 */
function allowOnlyNumeric(event) {
    // Izinkan tombol kontrol
    if (event.key.length > 1 || event.ctrlKey || event.altKey || event.metaKey || 
        ['Backspace', 'Tab', 'Delete', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) {
        return;
    }
    
    // Jika tombol yang ditekan BUKAN angka, hentikan (batalkan) pengetikan
    if (!/\d/.test(event.key)) {
        event.preventDefault();
    }
}


// Jalankan kode setelah halaman selesai dimuat
document.addEventListener('DOMContentLoaded', () => {

    // 1. Menangani konfirmasi hapus data
    const deleteForms = document.querySelectorAll('form[data-confirm-delete]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', (event) => {
            const confirmation = confirm('Apakah Anda yakin ingin menghapus data ini?');
            if (!confirmation) {
                event.preventDefault(); // Batalkan submit jika pengguna klik 'Cancel'
            }
        });
    });

    // 2. Menghilangkan pesan sukses setelah 3 detik
    const successAlert = document.querySelector('.alert-success[data-dismiss-after]');
    if (successAlert) {
        const delay = successAlert.getAttribute('data-dismiss-after') || 3000;
        setTimeout(() => {
            successAlert.style.transition = 'opacity 0.5s ease';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }, delay);
    }

    // 3. Terapkan filter input angka
    const numericInputs = document.querySelectorAll('input[inputmode="numeric"], input[inputmode="tel"]');
    numericInputs.forEach(input => {
        // 'keydown' akan memfilter tombol SEBELUM ditampilkan di input
        input.addEventListener('keydown', allowOnlyNumeric);
    });

});