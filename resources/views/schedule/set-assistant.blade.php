<div class="flex items-center justify-between mb-4">
    <div class="relative w-full md:w-80 bg-white h-11">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
        </span>
        <input type="text" placeholder="Cari..." id="search-select-assistant"
            class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
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
                            <span class="flex items-center gap-1 font-extrabold sortable-set-assistant cursor-pointer"
                                data-field="nama_asisten" data-sort-direction="">
                                Nama Asisten
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable-set-assistant cursor-pointer"
                                data-field="prodi_asisten" data-sort-direction="">
                                Prodi
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable-set-assistant cursor-pointer"
                                data-field="angkatan_asisten" data-sort-direction="">
                                Angkatan
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable-set-assistant cursor-pointer"
                                data-field="tahun_masuk_asisten" data-sort-direction="">
                                Tahun Masuk
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable-set-assistant cursor-pointer"
                                data-field="jumlah_kelas" data-sort-direction="">
                                Jml Kelas
                                <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                    <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                    <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                </span>
                            </span>
                        </th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable-set-assistant cursor-pointer"
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
                                        class="form-checkbox-set-assistant rounded text-key-secondary accent-key-secondary cursor-pointer"
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
        let currentSortBy = '';
        let currentSortOrder = '';

        $('#title').html(title);

        function updateSortIndicatorsSetAssistant() {
            $('.sortable-set-assistant').each(function() {
                const field = $(this).data('field');
                const sortOrder = $(this).data('sort-order');
                const ascIcon = $(this).find('.sort-asc-icon');
                const descIcon = $(this).find('.sort-desc-icon');

                ascIcon.css('visibility', 'visible');
                descIcon.css('visibility', 'visible');

                if (field === currentSortBy) {
                    if (currentSortOrder === 'asc') {
                        ascIcon.css('visibility', 'visible');
                        descIcon.css('visibility', 'hidden');
                    } else if (currentSortOrder === 'desc') {
                        descIcon.css('visibility', 'visible');
                        ascIcon.css('visibility', 'hidden');
                    }
                }
            });
        }

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

        $('.sortable-set-assistant').on('click', function() {
            const field = $(this).data('field');
            console.log('Sorting by:', field);

            // Toggle sort direction if same field, otherwise default to asc
            if (currentSortBy === field) {
                if (currentSortOrder === 'asc') {
                    currentSortOrder = 'desc';
                } else {
                    currentSortOrder = 'asc';
                }
            } else {
                currentSortBy = field;
                currentSortOrder = 'asc';
            }

            // Update data-sort-direction attribute for all headers
            $('.sortable-set-assistant').each(function() {
                if ($(this).data('field') === currentSortBy) {
                    $(this).data('sort-direction', currentSortOrder);
                } else {
                    $(this).data('sort-direction', '');
                }
            });

            updateSortIndicatorsSetAssistant();

            const rows = $('#set-assistant-body tr').toArray().sort((a, b) => {
                let aValue, bValue;

                if (field === 'preference_asisten') {
                    // Check if the icon is check-circle (preferred) or x-circle (not preferred)
                    const aHasCheck = $(a).find('td[data-field="preference_asisten"] svg')
                        .hasClass('text-green-500');
                    const bHasCheck = $(b).find('td[data-field="preference_asisten"] svg')
                        .hasClass('text-green-500');
                    aValue = aHasCheck ? 1 : 0;
                    bValue = bHasCheck ? 1 : 0;
                    return currentSortOrder === 'asc' ? aValue - bValue : bValue - aValue;
                } else {
                    aValue = $(a).find(`td[data-field="${field}"]`).text().toLowerCase();
                    bValue = $(b).find(`td[data-field="${field}"]`).text().toLowerCase();
                    return currentSortOrder === 'asc' ? aValue.localeCompare(bValue) : bValue
                        .localeCompare(aValue);
                }
            });

            $('#set-assistant-body').empty().append(rows);
        });

        $('#set-assistant-form').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                showCancelButton: false,
                showConfirmButton: false,
                width: '430px',
                customClass: {
                    popup: 'my-swal-popup'
                },
                html: `
                    <div class="flex flex-col items-center justify-between h-full">
                        <div class="mt-8">
                            <div class="mb-4">
                                <x-heroicon-s-exclamation-circle class="w-16 h-16 text-[#FF8D28] mx-auto" />
                            </div>
                            <div class="font-bold text-key-primary text-lg mb-2">Konfirmasi Data</div>
                            <div class="text-black font-semibold mb-4">
                                Apakah Anda yakin pilihan Aslab sudah sesuai?
                            </div>
                        </div>
                        <div class="flex gap-2 justify-between w-full mt-8">
                            <x-button-secondary id="swal-cancel-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                Periksa Kembali
                            </x-button-secondary>
                            <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                Ya, Simpan
                            </x-button-primary>
                        </div>
                    </div>
                    <style>
                        .my-swal-popup {
                            min-height: 300px;
                            max-height: 90vh;
                            border-radius: 1.5rem !important;
                            overflow-y: auto;
                        }
                    </style>
                `,
                didOpen: () => {
                    $('#swal-cancel-btn').on('click',
                        function() {
                            Swal.close();
                        });
                    $('#swal-confirm-btn').on('click',
                        function() {
                            Swal.clickConfirm();
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
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
                            const iconSVG = `
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="12" fill="#34D399"/>
                                    <path d="M8.25 12.375L10.875 15L15.75 9.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            `;
                            const
                                avatarDataUri =
                                `data:image/svg+xml;base64,${btoa(iconSVG)}`;
                            $('#setAssistantModal').find('#modalContent')
                                .removeClass('scale-100 opacity-100').addClass(
                                    'scale-95 opacity-0');
                            setTimeout(() => {
                                $('#setAssistantModal').addClass('hidden');
                            }, 300);
                            setTimeout(
                                () => {
                                    Toastify
                                        ({
                                            text: response.message,
                                            duration: 3000,
                                            gravity: "top",
                                            position: "right",
                                            avatar: avatarDataUri,
                                            style: {
                                                background: "rgba(52, 199, 89, 0.2)",
                                                color: "#208439",
                                                borderRadius: "8px",
                                                fontWeight: "500",
                                                boxShadow: "none",
                                                padding: "16px 24px",
                                                display: "flex",
                                                alignItems: "center",
                                                gap: "8px",
                                            },
                                        })
                                        .showToast();
                                }, 500);
                            document
                                .dispatchEvent(
                                    new CustomEvent(
                                        'reload-table'
                                    ));
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
