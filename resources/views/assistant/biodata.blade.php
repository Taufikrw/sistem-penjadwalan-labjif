<x-layouts.app>
    <x-slot name="title">Edit Biodata</x-slot>

    <x-slot:back_button>
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition-colors w-fit">
            <x-heroicon-s-arrow-left class="w-4 h-4" />
            Kembali
        </a>
    </x-slot:back_button>

    <x-slot:header_actions>
        <x-button-primary form="biodata-form" class="rounded-xl px-6" type="submit" id="btn-submit-biodata">
            Simpan
        </x-button-primary>
    </x-slot:header_actions>

    <form action="{{ route('assistant.update-biodata') }}" method="POST" enctype="multipart/form-data" id="biodata-form">
        @csrf
        @method('PUT')
        <div class="flex gap-8 w-full">
            <div class="rounded-lg bg-white w-3/5 py-16 px-8 border border-[#D9D9D9]">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 mb-4">
                        <img src="{{ $assistant->foto ? asset('storage/assistants/' . $assistant->foto) : asset('assets/images/Avatar.svg') }}"
                            alt="avatar" class="mx-auto rounded-full w-40 h-40 object-cover">
                    </div>
                    <div>
                        <x-input-label for="nim" class="mb-1 text-sm" value="NIM" />
                        <x-text-input name="nim" id="nim" class="w-full" :value="old('nim', $assistant->nim)"
                            placeholder="Masukkan NIM" disabled />
                        @error('nim')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="prodi" class="mb-1 text-sm" value="Program Studi" />
                        <x-text-input name="prodi" id="prodi" class="w-full" :value="old('prodi', $assistant->prodi)"
                            placeholder="Masukkan prodi" disabled />
                        @error('prodi')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="angkatan" class="mb-1 text-sm" value="Angkatan" />
                        <x-text-input name="angkatan" id="angkatan" class="w-full" type="number" :value="old('angkatan', $assistant->angkatan)"
                            placeholder="Masukkan angkatan" disabled />
                        @error('angkatan')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="name" class="mb-1 text-sm" value="Nama" />
                        <x-text-input name="name" id="name" class="w-full" :value="old('name', $assistant->name)"
                            placeholder="Masukkan nama" required />
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="nomor_telp" class="mb-1 text-sm" value="Nomor Telepon" />
                        <x-text-input name="nomor_telp" id="nomor_telp" class="w-full" :value="old('nomor_telp', $assistant->nomor_telp)"
                            placeholder="Masukkan nomor Telepon/Whatsapp" inputmode="numeric" pattern="[0-9]*" />
                        @error('nomor_telp')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="foto" class="mb-1 text-sm" value="Foto Profile" />
                        <input type="file" name="foto" id="foto"
                            class="block w-full text-black border rounded-xl pr-10 border-[#C9C6C5] hover:border-[#929090] focus:outline-none focus:border-key-secondary input-component cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700" />
                        @error('foto')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="rounded-lg px-4 bg-white w-2/5 h-fit flex flex-col border border-[#D9D9D9]"
                style="max-height: 60vh;">
                <table class="w-full text-sm text-left rtl:text-right text-key-primary">
                    <thead class="border-b border-[#E5E2E1] sticky top-0 bg-white z-10">
                        <tr>
                            <th scope="col" class="px-4 py-3">
                                <div class="flex justify-between gap-2 mt-2" data-field="preference"
                                    data-sort-direction="">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-bold">
                                            Preferensi Mata Kuliah
                                        </span>
                                        <span class="text-[#757575] font-medium">
                                            Pilih mata kuliah yang ingin kamu ajar
                                        </span>
                                    </div>
                                    <x-heroicon-s-squares-plus
                                        class="w-6 h-6 text-key-tertiary cursor-pointer hover:text-key-secondary"
                                        id="open-preference-modal-btn" />
                                </div>
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-sm text-left rtl:text-right text-key-primary">
                        <tbody id="preference-display-list">
                            @forelse ($selectedPracticums as $preference)
                                <tr data-kode="{{ $preference->kode_praktikum }}" class="border-b border-[#E5E2E1]">
                                    <td class="px-4 py-4 text-[#1E1E1E]">{{ $preference->name }}</td>
                                </tr>
                            @empty
                                <tr id="no-preference-row">
                                    <td class="px-4 py-8 text-center text-gray-500">Belum ada preferensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="hidden-practicums-container">
                    @foreach ($selectedPracticums as $preference)
                        <input type="hidden" name="practicums[]" value="{{ $preference->kode_praktikum }}">
                    @endforeach
                </div>
            </div>
        </div>
    </form>

    <div id="preference-modal-container"
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

                <div id="preference-modal-content">
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {
            function openModal() {
                $('#preference-modal-container').removeClass('hidden');
                setTimeout(() => {
                    $('#modalContent').removeClass('scale-95 opacity-0').addClass(
                        'scale-100 opacity-100');
                }, 10);
            }
            window.closeModalPref = function() {
                $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(() => {
                    $('#preference-modal-container').addClass('hidden');
                }, 300);
            }

            $('#closeModalBtn').on('click', function() {
                closeModalPref();
            });

            $('#modalOverlay').on('click', function(e) {
                if (e.target === this) {
                    closeModalPref();
                }
            });

            // 1. Logika untuk MEMBUKA modal
            $('#open-preference-modal-btn').on('click', function() {
                const currentlySelected = $('#biodata-form input[name="practicums[]"]').map(function() {
                    return $(this).val();
                }).get();

                $.ajax({
                    url: "{{ route('assistant.create-preference') }}",
                    type: 'GET',
                    data: {
                        selected: currentlySelected
                    },
                    beforeSend: function() {
                        $('#preference-modal-content').html(
                            '<div class="p-8 text-center">Memuat...</div>');
                        openModal();
                    },
                    success: function(response) {
                        $('#preference-modal-content').html(response);
                    },
                    error: function() {
                        alert('Gagal memuat data. Silakan coba lagi.');
                        closeModal();
                    }
                });
            });

            $(document).on('click', '#btn-apply-preference', function() {
                $('#preference-display-list, #hidden-practicums-container').empty();
                let hasSelection = false;

                $('#modal-preference-form input[type="checkbox"]:checked').each(function() {
                    hasSelection = true;
                    const code = $(this).val();
                    const name = $(this).data('name');

                    $('#preference-display-list').append(`
                        <tr class="border-b border-[#E5E2E1]" data-kode="${code}"><td class="px-4 py-4">${name}</td></tr>
                    `);
                    $('#hidden-practicums-container').append(`
                        <input type="hidden" name="practicums[]" value="${code}">
                    `);
                });

                if (!hasSelection) {
                    $('#preference-display-list').html(`
                        <tr id="no-preference-row"><td class="px-4 py-8 text-center text-gray-500">Belum ada preferensi.</td></tr>
                    `);
                }
                closeModalPref();
            });

            $('#biodata-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const method = form.attr('method');

                $.ajax({
                    url: url,
                    type: method,
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(key,
                                value) {
                                let inputElement =
                                    $(
                                        `[name="${key}"]`
                                    );
                                inputElement
                                    .addClass(
                                        'border-[#BA1A1A]'
                                    );
                                inputElement
                                    .closest(
                                        '.relative')
                                    .after(
                                        `<p class="error-message text-[#BA1A1A] text-sm mt-1 font-semibold">${value[0]}</p>`
                                    );
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON.message ||
                                    'Terjadi kesalahan saat menyimpan asisten.',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
</x-layouts.app>
