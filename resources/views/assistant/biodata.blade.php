<x-layouts.app>
    <x-slot name="title">Edit Biodata</x-slot>

    <x-slot:back_button>
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition-colors w-fit">
            <x-heroicon-s-arrow-left class="w-4 h-4" />
            Kembali
        </a>
    </x-slot:back_button>

    <div class="flex gap-8 w-full">
        <form action="{{ route('assistant.update-biodata') }}" method="POST" enctype="multipart/form-data"
            id="biodata-form" class="w-3/5">
            @csrf
            @method('PUT')

            <div class="rounded-lg bg-white flex-1 py-10 px-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 mb-4">
                        <img src="{{ $assistant->foto ? asset('storage/assistants/' . $assistant->foto) : asset('assets/images/Avatar.svg') }}"
                            alt="avatar" class="mx-auto rounded-full w-48 h-48 object-cover">
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
                        <x-input-label for="nim" class="mb-1 text-sm" value="NIM" />
                        <x-text-input name="nim" id="nim" class="w-full" :value="old('nim', $assistant->nim)"
                            placeholder="Masukkan NIM" disabled />
                        @error('nim')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="prodi" class="mb-1 text-sm" value="Program Studi" />
                        <x-select-input id="prodi_select" name="prodi" :options="['Sistem Informasi' => 'Sistem Informasi', 'Informatika' => 'Informatika']" placeholder="Pilih prodi"
                            :selected="$assistant->prodi" />
                        @error('prodi')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="angkatan" class="mb-1 text-sm" value="Angkatan" />
                        <x-text-input name="angkatan" id="angkatan" class="w-full" type="number" :value="old('angkatan', $assistant->angkatan)"
                            placeholder="Masukkan angkatan" required />
                        @error('angkatan')
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

                <div class="mt-8 flex justify-between items-center w-full">
                    <a href="{{ route('dashboard') }}">
                        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-biodata">
                            Batal
                        </x-button-secondary>
                    </a>
                    <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-biodata">
                        Simpan
                    </x-button-primary>
                </div>
            </div>
        </form>
        <div class="rounded-lg px-4 bg-white w-2/5 h-fit flex flex-col" style="max-height: 60vh;">
            <table class="w-full text-sm text-left rtl:text-right text-key-primary">
                <thead class="border-b border-[#E5E2E1] sticky top-0 bg-white z-10">
                    <tr>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-2 font-extrabold" data-field="preference"
                                data-sort-direction="">
                                Preferensi
                                <x-heroicon-s-pencil-square
                                    class="w-4 h-4 text-key-tertiary cursor-pointer hover:text-key-secondary"
                                    onclick="showDynamicModal()" />
                            </span>
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-sm text-left rtl:text-right text-key-primary">
                    <tbody id="schedule-table-body">
                        <tr>
                            <td colspan="1" class="px-6 py-8 text-center text-neutral-50">
                                <div class="flex justify-center items-center">
                                    <x-icon-spinner class="h-16 w-16 animate-spin" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-form-modal modal-id="preferenceModal" ajax-url="{{ route('assistant.create-preference') }}"
        form-id="preference-form" />

    <script type="module">
        $(document).ready(function() {
            function loadPreferencesTable() {
                const tableId = $('#schedule-table-body');
                $.ajax({
                    url: "{{ route('api.preference.table') }}",
                    type: 'GET',
                    beforeSend: function() {
                        tableId.empty();
                        tableId.append(`
                            <tr>
                                <td colspan="1" class="px-6 py-8 text-center text-neutral-50">
                                    <div class="flex justify-center items-center">
                                        <x-icon-spinner class="h-16 w-16 animate-spin" />
                                    </div>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(response) {
                        tableId.empty();
                        if (response.data.length > 0) {
                            $.each(response.data, function(index, item) {
                                let row = `
                                    <tr class="border-b border-[#E5E2E1]">
                                        <td class="px-4 py-4 text-[#1E1E1E]">${item}</td>
                                    </tr>
                                `;
                                tableId.append(row);
                            });
                        } else {
                            tableId.html(`
                                <tr>
                                    <td colspan="2" class="py-8 text-center font-medium">
                                        <x-icon-no-data class="w-60 mx-auto" />
                                    </td>
                                </tr>
                            `);
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memuat data',
                            text: xhr.responseJSON.message ||
                                'Terjadi kesalahan saat memuat data preferensi.',
                        });
                    }
                });
            }

            loadPreferencesTable();

            document.addEventListener('reload-table', function() {
                loadPreferencesTable();
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
