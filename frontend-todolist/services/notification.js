// src/services/notification.js
import Swal from 'sweetalert2';

export function showSuccess(message) {
    Swal.fire('Sucesso', message, 'success');
}

export function showError(message) {
    Swal.fire('Erro', message, 'error');
}