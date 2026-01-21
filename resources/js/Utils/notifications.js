import Swal from 'sweetalert2';

// Konfigurasi default Toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

export const showSuccess = (title, message = '') => {
    return Toast.fire({
        icon: 'success',
        title: title,
        text: message
    });
};

export const showError = (title, message = '') => {
    return Toast.fire({
        icon: 'error',
        title: title,
        text: message
    });
};

export const showConfirm = (title, text, confirmButtonText = 'Ya, Lanjutkan') => {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal'
    });
};
