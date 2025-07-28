import './bootstrap';
import jQuery from 'jquery';
window.$  = jQuery;
import Swal from 'sweetalert2';
window.Swal = Swal;

window.initInputComponent = function() {
    $('.input-component').each(function() {
        if ($(this).val().length > 0) {
            $(this).removeClass('border-[#C9C6C5]').addClass('border-key-secondary');
        } else {
            $(this).removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
        }
    });
    $('.input-component').off('input change').on('input change', function() {
        if ($(this).val().length > 0) {
            $(this).removeClass('border-[#C9C6C5]').addClass('border-key-secondary');
        } else {
            $(this).removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
        }
    });
};