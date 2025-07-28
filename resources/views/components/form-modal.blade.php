<div id="{{ $modalId }}"
    class="fixed inset-0 z-50 hidden overflow-y-auto transition-opacity duration-300 ease-in-out">
    <div id="modalOverlay"
        class="flex items-center justify-center min-h-screen bg-gray-900/75 transition-opacity duration-300 ease-in-out">
        <div class="relative bg-white rounded-3xl shadow-xl max-w-2xl w-full p-8 transform transition-all duration-300 sm:my-8 sm:align-middle sm:w-full scale-95 opacity-0"
            id="modalContent">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-key-primary font-bold text-xl" id="title"></h3>
                <button id="closeModalBtn"
                    class="text-[#929090] hover:text-[#535252] focus:outline-none cursor-pointer">
                    <x-heroicon-s-x-mark class="w-6 h-6" />
                </button>
            </div>

            <hr class="border-[#F4F0EF] mb-4">

            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            const modalId = '{{ $modalId }}';
            const ajaxUrl = '{{ $ajaxUrl }}';
            const formId = '{{ $formId }}';
            const modalElement = $(`#${modalId}`);
            const modalBody = modalElement.find('.modal-body');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            window.openModal = function() {
                modalElement.removeClass('hidden');
                setTimeout(() => {
                    $('#modalContent').removeClass('scale-95 opacity-0').addClass(
                        'scale-100 opacity-100');
                }, 10);
            }

            window.closeModal = function() {
                $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(() => {
                    modalElement.addClass('hidden');
                }, 300);
            }

            window.showDynamicModal = function(url = ajaxUrl) {
                window.openModal();

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    beforeSend: function() {
                        modalBody.html(`
                            <div class="flex justify-center items-center gap-4">
                                <div class="border-gray-300 h-8 w-8 animate-spin rounded-full border-5 border-t-secondary"></div>
                                Loading...
                            </div>
                        `);
                    },
                    success: function(response) {
                        modalBody.html(response);
                        window.initInputComponent();
                        if (formId) {
                            const form = modalBody.find(`#${formId}`);
                            if (form.length) {
                                form.on('submit', function(e) {
                                    e.preventDefault();
                                    const formData = $(this).serialize();
                                    $('.text-red-500').remove();
                                    $('input, textarea').removeClass('border-red-500');
                                    console.log(form.attr('method'));
                                    

                                    $.ajax({
                                        url: form.attr('action'),
                                        type: form.attr('method'),
                                        data: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        beforeSend: function() {
                                            form.find('button[type="submit"]')
                                                .html(`
                                                    <div class="flex justify-center items-center gap-2">
                                                        <x-icon-spinner class="h-5 w-5 animate-spin" />
                                                        Menyimpan...
                                                    </div>
                                                `).prop('disabled', true);
                                        },
                                        success: function(data) {
                                            if (data.status === 'success') {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: data.message,
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                }).then(() => {
                                                    window.location
                                                        .reload();
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Terjadi Kesalahan!',
                                                    text: data
                                                        .message ||
                                                        'Maaf, terjadi kesalahan pada server. Silakan coba lagi.',
                                                    showConfirmButton: true
                                                });
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            form.find('button[type="submit"]')
                                                .text('Kirim').prop('disabled',
                                                    false).addClass('px-8');

                                            if (xhr.status === 422) {
                                                let errors = xhr.responseJSON
                                                    .errors;
                                                $.each(errors, function(key,
                                                    value) {
                                                    let inputElement =
                                                        $(
                                                            `[name="${key}"]`
                                                        );
                                                    inputElement
                                                        .addClass(
                                                            'border-red-500'
                                                        );
                                                    inputElement.after(
                                                        `<p class="text-red-500 text-sm mt-1">${value[0]}</p>`
                                                    );
                                                });
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal Validasi!',
                                                    text: xhr
                                                        .responseJSON
                                                        .message ||
                                                        'Mohon periksa kembali input Anda.',
                                                    showConfirmButton: true
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Terjadi Kesalahan!',
                                                    text: xhr
                                                        .responseJSON
                                                        .message ||
                                                        'Maaf, terjadi kesalahan pada server. Silakan coba lagi.',
                                                    showConfirmButton: true
                                                });
                                            }
                                        }
                                    });
                                });
                            } else {
                                console.error(`Form with ID ${formId} not found.`);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading modal content:", error);
                        modalBody.html(
                            '<p class="text-red-500 text-center">Gagal memuat formulir. Silakan coba lagi.</p>'
                        );
                    }
                });
            };

            $('#closeModalBtn').on('click', function() {
                window.closeModal();
            });

            $('#modalOverlay').on('click', function(e) {
                if (e.target === this) {
                    window.closeModal();
                }
            });
        });
    </script>
@endpush
