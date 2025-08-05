<div class="flex items-center justify-between mb-4">
    <div class="relative w-full md:w-80 bg-white">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-[#C9C6C5]" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"></path>
            </svg>
        </span>
        <input type="text" placeholder="Cari..." id="search-select-assistant"
            class="block w-full pl-10 pr-3 py-3 border border-[#C9C6C5] rounded-lg leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
    </div>
</div>

<form id="set-assistant-form"
    action="{{ !empty($selectedAssistants) ? route('schedule.update-assistant', $schedule->id) : route('schedule.store-assistant', $schedule->id) }}"
    method="{{ !empty($selectedAssistants) ? 'POST' : 'POST' }}" class="flex flex-col justify-between h-full flex-1">
    @csrf
    @if (!empty($selectedAssistants))
        @method('PUT')
    @endif

    <div class="w-full overflow-x-auto">
        <div class="max-h-88 overflow-y-auto">
            <table class="w-full text-sm text-left rtl:text-right text-key-primary border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th scope="col" class="sticky top-0 px-2 py-3 w-8 border-b border-[#E5E2E1] bg-white"></th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable cursor-pointer"
                                data-field="nama_asisten" data-sort-direction="">
                                Nama Asisten
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable cursor-pointer"
                                data-field="prodi_asisten" data-sort-direction="">
                                Prodi
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable cursor-pointer"
                                data-field="angkatan_asisten" data-sort-direction="">
                                Angkatan
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable cursor-pointer"
                                data-field="tahun_masuk_asisten" data-sort-direction="">
                                Tahun Masuk
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable cursor-pointer"
                                data-field="jumlah_kelas" data-sort-direction="">
                                Jml Kelas
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable cursor-pointer"
                                data-field="preference_asisten" data-sort-direction="">
                                Pref.
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody id="set-assistant-body">
                    @foreach ($assistants as $assistant)
                        <tr>
                            <td class="px-2 py-5 border-b border-[#E5E2E1]">
                                <div class="flex items-center justify-center h-full">
                                    <input type="checkbox"
                                        class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer"
                                        name="assistants[]" value="{{ $assistant->nim }}"
                                        id="assistant-{{ $assistant->nim }}" onchange="limitSelection(this)"
                                        @if (in_array($assistant->nim, array_column($selectedAssistants, 'nim'))) checked @endif />
                                </div>
                            </td>
                            <td class="px-4 py-4 border-b border-[#E5E2E1]" data-field="nama_asisten">
                                {{ $assistant->name }}
                            </td>
                            <td class="px-4 py-4 border-b border-[#E5E2E1]" data-field="prodi_asisten">
                                {{ $assistant->prodi }}
                            </td>
                            <td class="px-4 py-4 border-b border-[#E5E2E1]" data-field="angkatan_asisten">
                                {{ $assistant->angkatan }}
                            </td>
                            <td class="px-4 py-4 border-b border-[#E5E2E1]" data-field="tahun_masuk_asisten">
                                {{ $assistant->tahun_masuk }}
                            </td>
                            <td class="px-4 py-4 border-b border-[#E5E2E1]" data-field="jumlah_kelas">
                                {{ $assistant->jumlah_kelas }}
                            </td>
                            <td class="px-4 py-4 border-b border-[#E5E2E1]" data-field="preference_asisten">
                                @if ($assistant->preference)
                                    <x-heroicon-m-check-circle class="w-5 h-5 text-green-500" />
                                @else
                                    <x-heroicon-m-x-circle class="w-5 h-5 text-red-500" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-between items-center w-full">
        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-set-assistant"
            onclick="closeModal()">
            Batal
        </x-button-secondary>
        <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-set-assistant">
            Simpan
        </x-button-primary>
    </div>
</form>

<script type="module">
    $(document).ready(function() {
        const title = $('#title-hidden').html();
        const searchInput = $('#search-select-assistant');

        $('#title').html(title);

        if (searchInput.length) {
            searchInput.on('input', function() {
                const searchValue = $(this).val().toLowerCase();
                $('#set-assistant-body tr').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.includes(searchValue));
                });
            });
        }

        $('#btn-cancel-set-assistant').on('click', function() {
            $('#setAssistantModal').find('#modalContent').removeClass('scale-100 opacity-100').addClass(
                'scale-95 opacity-0');
            setTimeout(() => {
                $('#setAssistantModal').addClass('hidden');
            }, 300);
        });

        $('.sortable').on('click', function() {
            const field = $(this).data('field');
            console.log('Sorting by:', field);
            let sortDirection = $(this).data('sort-direction') || 'asc';
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            $(this).data('sort-direction', sortDirection);

            const rows = $('#set-assistant-body tr').toArray().sort((a, b) => {
                const aText = $(a).find(`td[data-field="${field}"]`).text().toLowerCase();
                const bText = $(b).find(`td[data-field="${field}"]`).text().toLowerCase();
                return sortDirection === 'asc' ? aText.localeCompare(bText) : bText
                    .localeCompare(aText);
            });

            $('#set-assistant-body').empty().append(rows);
        });

        $('#set-assistant-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            const method = form.attr('method');
            const data = form.serialize();

            $.ajax({
                url: url,
                method: method,
                data: data,
                beforeSend: function() {
                    $('#btn-submit-set-assistant').html(`
                        <div class="flex justify-center items-center gap-2">
                            <x-icon-spinner class="h-5 w-5 animate-spin" />
                            Menyimpan...
                        </div>
                    `).prop('disabled', true);
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        closeModal();
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON.message ||
                            'Terjadi kesalahan saat menyimpan asisten.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>

<script>
    function limitSelection(checkbox) {
        const checkboxes = document.querySelectorAll('input[name="assistants[]"]:checked');
        if (checkboxes.length > 2) {
            checkbox.checked = false;
            Swal.fire({
                icon: 'warning',
                title: 'Maksimal 2 Asisten',
                text: 'Anda hanya dapat memilih max 2 asisten.',
                confirmButtonText: 'OK'
            });
        }
    }
</script>
