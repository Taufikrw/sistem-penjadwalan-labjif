<x-layouts.guest>
    <div class="flex">
        <div class="w-140 bg-white flex items-center justify-center px-18 rounded-l-3xl flex-col relative">
            <img src="{{ asset('assets/images/Logo.svg') }}" alt="logo lab jif" class="absolute top-8 right-8">
            <h2 id="login-title" class="text-5xl text-key-secondary font-extrabold text-left w-full mb-12">
                Masuk</h2>

            <span id="validation-error"
                class="bg-[#FFEDEA] border text-sm border-[#FF897D] py-3 px-4 w-full flex items-center justify-between rounded-xl mb-4 hidden">
                <div class="flex items-center gap-2">
                    <x-heroicon-m-exclamation-circle class="w-5 h-5 text-[#FF5449]" />
                    <span id="validation-error-message"></span>
                </div>
                <x-heroicon-s-x-mark class="w-5 h-5" />
            </span>

            <form method="POST" action="{{ route('login.store') }}" class="w-full" id="login-form">
                @csrf

                <div class="mb-2">
                    <x-input-label for="username" class="mb-1 text-sm" value="Nama Pengguna" />
                    <x-text-input name="username" id="username" class="w-full" :value="old('username')"
                        placeholder="Masukkan nama pengguna" />
                    <span id="username-error" class="text-[#BA1A1A] text-sm mt-1 font-semibold invisible">.</span>
                </div>

                <div class="mb-10">
                    <x-input-label for="password" class="mb-1 text-sm" value="Kata Sandi" />
                    <x-text-input name="password" id="password" type="password" class="w-full"
                        placeholder="Kata Sandi" />
                    <span id="password-error" class="text-[#BA1A1A] text-sm mt-1 font-semibold invisible">.</span>
                </div>

                <div class="flex items-center justify-between">
                    <x-button-primary class="w-full text-lg rounded-2xl" type="submit">
                        Masuk
                    </x-button-primary>
                </div>
            </form>
        </div>

        <div
            class="w-fit text-white flex flex-col items-center justify-center py-16 rounded-r-3xl relative overflow-hidden bg-black/20">
            <div class="mb-4 w-full text-center">
                <h2 class="text-4xl font-extrabold mb-2">Sistem Penjadwalan</h2>
                <h2 class="text-4xl font-extrabold">Lab Jurusan Informatika</h2>
            </div>
            {{-- <img src="{{ asset('assets/images/pair-aslab-transparant.svg') }}" alt=""> --}}
            <x-icon-pair-aslab-transparant />
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#login-form').find('button[type="submit"]')
                            .html(`
                                <div class="flex justify-center items-center gap-2">
                                    <x-icon-spinner class="h-5 w-5 animate-spin" />
                                    Mengirim...
                                </div>
                            `).prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = response.redirect ||
                                    '{{ route('dashboard') }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: response
                                    .message ||
                                    'Maaf, terjadi kesalahan pada server. Silakan coba lagi.',
                                showConfirmButton: true
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#login-form').find('button[type="submit"]')
                            .text('Kirim').prop('disabled', false).addClass('px-8');

                        $('#validation-error').addClass('hidden');
                        $('#login-title').addClass('mb-12').removeClass('mb-4');

                        // Reset semua error
                        $('#username-error, #password-error')
                            .text('.').removeClass('visible').addClass('invisible');

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            if (errors.username) {
                                $('#username-error').text(errors.username[0]).removeClass(
                                    'invisible').addClass('visible');
                            }
                            if (errors.password) {
                                $('#password-error').text(errors.password[0]).removeClass(
                                    'invisible').addClass('visible');
                            }
                            if (errors.validation) {
                                $('#login-title').removeClass('mb-12').addClass('mb-4');
                                $('#validation-error').removeClass('hidden');
                                $('#validation-error-message').text(errors.validation[0])
                            .show();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: xhr.responseJSON?.message ||
                                    'Maaf, terjadi kesalahan pada server. Silakan coba lagi.',
                                showConfirmButton: true
                            });
                        }
                    }
                });
            });
        });
    </script>
</x-layouts.guest>
