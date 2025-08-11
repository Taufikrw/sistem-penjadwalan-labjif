<div class="rounded-lg w-full bg-white px-4">

    <div class="overflow-x-auto overflow-y-auto max-h-[75vh]">
        <table class="w-full text-sm text-left rtl:text-right text-[#1E1E1E] border-separate border-spacing-0">
            <thead class="text-key-primary">
                <tr>
                    @if ($hasActions)
                        <th scope="col" class="px-2 py-3 w-8 sticky top-0 bg-white border-b border-[#E5E2E1]">
                            <div class="flex items-center justify-center h-full">
                                <input type="checkbox" id="selectAllCheckboxes"
                                    class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer" />
                            </div>
                        </th>
                    @endif
                    @foreach ($columns as $column)
                        <th scope="col" class="px-4 py-3 sticky top-0 bg-white border-b border-[#E5E2E1]">
                            <span
                                class="flex items-center gap-1 font-extrabold {{ $column['sortable'] ? 'sortable cursor-pointer' : '' }}"
                                data-field="{{ $column['field'] }}" data-sort-direction="">
                                {{ $column['label'] }}
                                @if ($column['sortable'])
                                    <span class="sort-indicator flex flex-col ml-1 gap-[3px]">
                                        <x-icon-sort-up class="text-[#4B57AC] sort-asc-icon" />
                                        <x-icon-sort-down class="text-[#4B57AC] sort-desc-icon" />
                                    </span>
                                @endif
                            </span>
                        </th>
                    @endforeach
                    @if ($hasActions)
                        <th scope="col" class="px-4 py-3 sticky top-0 bg-white border-b border-[#E5E2E1]">
                            <span class="flex items-center gap-1 font-extrabold">
                                Aksi
                            </span>
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody id="{{ $tableId }}-body">
                <tr>
                    <td colspan="{{ count($columns) + 1 + ($hasActions ? 1 : 0) }}"
                        class="px-6 py-8 text-center text-neutral-50">
                        <div class="flex justify-center items-center">
                            <x-icon-spinner class="h-16 w-16 animate-spin" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="pagination" class="px-4"></div>

</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            const tableId = '{{ $tableId }}';
            let url = '{{ $url }}';
            const actionUrl = '{{ $actionUrl }}';
            const columns = @json($columns);
            const hasActions = {{ $hasActions ? 'true' : 'false' }};
            const primary = '{{ $primary }}';
            const searchInputId = '{{ $searchInputId ?? '' }}';
            const btnCreateId = '{{ $btnCreateId ?? '' }}';
            const checked = $('.form-checkbox[name="selected[]"]:checked').length > 0;
            const bulkDeleteUrl = actionUrl + 'bulk-delete';
            const hasSetAssistant = {{ $hasSetAssistant ? 'true' : 'false' }};
            const filters = @json($filters);

            let currentSortBy = '';
            let currentSortOrder = '';
            let currentSearch = '';
            let currentPage = 1;
            let currentFilters = {
                ...filters
            };

            function loadTableData(page = 1) {
                currentPage = page;
                let requestUrl = url;
                const params = new URLSearchParams();

                if (currentSortBy && currentSortOrder) {
                    params.append('sort_by', currentSortBy);
                    params.append('sort_order', currentSortOrder);
                }
                if (currentSearch) {
                    params.append('search', currentSearch);
                }
                Object.keys(currentFilters).forEach(function(key) {
                    if (currentFilters[key]) {
                        params.append(key, currentFilters[key]);
                    }
                });
                params.append('page', currentPage);

                if (url.includes('?')) {
                    requestUrl = url.replace(/\?$/, '');
                    requestUrl += `&${params.toString()}`;
                } else if ([...params].length > 0) {
                    requestUrl = `${url}?${params.toString()}`;
                }

                $.ajax({
                    url: requestUrl,
                    method: 'GET',
                    success: function(response) {
                        let rowsHtml = '';
                        if (response.data.data.length === 0) {
                            rowsHtml =
                                `
                                <tr>
                                    <td colspan="${columns.length + 1 + (hasActions ? 1 : 0)}" class="py-8 text-center font-medium">
                                        <x-icon-no-data class="w-70 mx-auto" />
                                        <span class="font-bold">Tidak ada data.</span>
                                    </td>
                                </tr>
                            `;
                        } else {
                            response.data.data.forEach(function(item) {
                                rowsHtml += '<tr>';
                                if (hasActions) {
                                    if (primary === 'nim') {
                                        rowsHtml +=
                                            `<td class="px-2 py-5 border-b border-[#E5E2E1]">
                                                <div class="flex items-center justify-center h-full">
                                                    <input type="checkbox"
                                                        class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer"
                                                        name="selected[]" value="${item.nim}" />
                                                </div>
                                            </td>`;
                                    } else if (primary === 'kode_praktikum') {
                                        rowsHtml +=
                                            `<td class="px-2 py-5 border-b border-[#E5E2E1]">
                                                <div class="flex items-center justify-center h-full">
                                                    <input type="checkbox"
                                                        class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer"
                                                        name="selected[]" value="${item.kode_praktikum}" />
                                                </div>
                                            </td>`;
                                    } else {
                                        rowsHtml +=
                                            `<td class="px-2 py-5 border-b border-[#E5E2E1]">
                                                <div class="flex items-center justify-center h-full">
                                                    <input type="checkbox"
                                                        class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer"
                                                        name="selected[]" value="${item.id}" />
                                                </div>
                                            </td>`;
                                    }
                                }
                                columns.forEach(function(column) {
                                    if (column.field === 'status') {
                                        let statusClass = '';
                                        let statusText = '';
                                        switch (item.status) {
                                            case 'aktif':
                                                statusClass =
                                                    'bg-green-100 text-green-700';
                                                statusText = 'Aktif';
                                                break;
                                            case 'non-aktif':
                                                statusClass = 'bg-red-100 text-red-700';
                                                statusText = 'Non-Aktif';
                                                break;
                                            default:
                                                statusClass =
                                                    'text-blue-700 bg-blue-100 capitalize';
                                                statusText = item.status ?? '';
                                                break;
                                        }
                                        rowsHtml +=
                                            `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                                <span class="inline-block px-3 py-1 text-xs font-semibold ${statusClass} rounded-full">
                                                    ${statusText}
                                                </span>
                                            </td>`;
                                    } else if (column.field === 'jadwal_praktikum') {
                                        rowsHtml +=
                                            `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                                <a href="assistants/${item.nim}/detail-jadwal-praktikum"
                                                    class="flex items-center gap-2 hover:text-key-secondary w-fit">
                                                    <x-heroicon-o-eye class="w-4 h-4" />
                                                    Detail
                                                </a>
                                            </td>`;
                                    } else if (column.field === 'jadwal_kuliah') {
                                        rowsHtml +=
                                            `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                                <a href="assistants/${item.nim}/detail-jadwal-kuliah"
                                                    class="flex items-center gap-2 hover:text-key-secondary w-fit">
                                                    <x-heroicon-o-eye class="w-4 h-4" />
                                                    Detail
                                                </a>
                                            </td>`;
                                    } else if (column.field === 'assistant_names') {
                                        rowsHtml +=
                                            `<td class="px-4 py-2 border-b border-[#E5E2E1]">`;
                                        if (item.assistant_schedules && item
                                            .assistant_schedules.length > 0) {
                                            item.assistant_schedules.forEach(function(
                                                asst, index) {
                                                rowsHtml +=
                                                    `<span class="block">${index + 1}. ${asst.assistant.name}</span>`;
                                            });
                                        } else {
                                            rowsHtml +=
                                                `<span class="block">Tidak ada asisten</span>`;
                                        }
                                        rowsHtml += `</td>`;
                                    } else {
                                        rowsHtml +=
                                            `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                                ${item[column.field]}
                                            </td>`;
                                    }
                                });
                                if (hasActions) {
                                    if (primary === 'nim') {
                                        rowsHtml += `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                            <div class="flex items-center gap-2">
                                                <button data-id="${item.nim}" class="btn-edit"><x-heroicon-s-pencil-square class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                                <button data-id="${item.nim}" class="btn-delete"><x-heroicon-s-trash class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                            </div>
                                        </td>`;
                                    } else if (primary === 'kode_praktikum') {
                                        rowsHtml += `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                            <div class="flex items-center gap-2">
                                                <button data-id="${item.kode_praktikum}" class="btn-edit"><x-heroicon-s-pencil-square class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                                <button data-id="${item.kode_praktikum}" class="btn-delete"><x-heroicon-s-trash class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                            </div>
                                        </td>`;
                                    } else {
                                        if (hasSetAssistant) {
                                            rowsHtml += `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                                <div class="flex items-center gap-2">
                                                    <button data-id="${item.id}" class="btn-set-assistant"><x-heroicon-s-users class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                                    <button data-id="${item.id}" class="btn-edit"><x-heroicon-s-pencil-square class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                                    <button data-id="${item.id}" class="btn-delete"><x-heroicon-s-trash class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                                </div>
                                            </td>`;
                                        } else {
                                            rowsHtml += `<td class="px-4 py-4 border-b border-[#E5E2E1]">
                                                <div class="flex items-center gap-2">
                                                    <button data-id="${item.id}" class="btn-edit"><x-heroicon-s-pencil-square class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                                    <button data-id="${item.id}" class="btn-delete"><x-heroicon-s-trash class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                                </div>
                                            </td>`;
                                        }
                                    }
                                }
                                rowsHtml += '</tr>';
                            });
                        }

                        $('#selectAllCheckboxes').prop('checked', false).prop('indeterminate', false);
                        $(`#${tableId}-body`).html(rowsHtml);
                        updateSortIndicators()
                        toggleButtons();
                        renderPagination(response.data, tableId);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading table data:", error);
                        $(`#${tableId}-body`).html(
                            `
                                <tr>
                                    <td colspan="${columns.length + (hasActions ? 1 : 0)}" class="py-12 text-center font-medium">
                                        Gagal memuat data. Silakan coba lagi.
                                    </td>
                                </tr>
                            `
                        );
                    }
                });
            }

            function updateSortIndicators() {
                $('.sortable').each(function() {
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

            function toggleButtons() {
                const checkedCount = $('.form-checkbox[name="selected[]"]:checked').length;
                if (checkedCount > 0) {
                    $(`#${btnCreateId}`).addClass('hidden');
                    $('#deleted-info').removeClass('hidden');
                    $('#deleted-info').addClass('flex');
                    $('#selected-info').removeClass('hidden').text(`${checkedCount} Dipilih`);
                } else {
                    $(`#${btnCreateId}`).removeClass('hidden');
                    $('#deleted-info').addClass('hidden');
                    $('#deleted-info').removeClass('flex');
                    $('#selected-info').addClass('hidden').text('0 Dipilih');
                }
            }

            function renderPagination(pagination, tableId) {
                let html = '';
                if (pagination.last_page > 1 || pagination.total > 0) {
                    html += `<div class="flex justify-between items-center text-xs mt-4 mb-6">`;

                    // Left: Showing x to y of z Data
                    if (pagination.total > 0) {
                        const from = pagination.from ?? 0;
                        const to = pagination.to ?? 0;
                        const total = pagination.total ?? 0;
                        html += `<div class="text-[#5A5A5A] px-2">
                            ${from} hingga ${to} data dari ${total}
                        </div>`;
                    } else {
                        html += `<div></div>`;
                    }

                    // Right: Pagination
                    html += `<nav><ul class="inline-flex space-x-1 items-center">`;

                    // Previous
                    const prevDisabled = pagination.current_page === 1;
                    html += `<li>
                        <button class="pagination-btn px-3 py-2 ml-0 leading-tight rounded-l-lg ${prevDisabled ? 'text-[#CDCDCD]' : 'text-[#5A5A5A] hover:text-[#434343] cursor-pointer'}"
                            data-page="${pagination.current_page - 1}" ${prevDisabled ? 'disabled' : ''}>
                            <x-heroicon-s-chevron-left class="w-5 h-5" />
                        </button>
                    </li>`;

                    // Calculate start and end page for max 4 pages
                    let startPage = Math.max(1, pagination.current_page - 1);
                    let endPage = Math.min(pagination.last_page, startPage + 3);

                    // Adjust if at the end
                    if (endPage - startPage < 3) {
                        startPage = Math.max(1, endPage - 3);
                    }

                    for (let i = startPage; i <= endPage; i++) {
                        const isCurrent = i === pagination.current_page;
                        html += `<li>
                            <button class="pagination-btn px-3 py-2 leading-tight rounded-lg ${isCurrent ? 'bg-key-secondary text-white' : 'text-[#1E1E1E] hover:bg-[#29293A]/23 cursor-pointer'}"
                                data-page="${i}" ${isCurrent ? 'disabled' : ''}>
                                ${i}
                            </button>
                        </li>`;
                    }

                    // Next
                    const nextDisabled = pagination.current_page === pagination.last_page;
                    html += `<li>
                        <button class="pagination-btn px-3 py-2 leading-tight rounded-r-lg ${nextDisabled ? 'text-[#CDCDCD]' : 'text-[#5A5A5A] hover:text-[#434343] cursor-pointer'}"
                            data-page="${pagination.current_page + 1}" ${nextDisabled ? 'disabled' : ''}>
                            <x-heroicon-s-chevron-right class="w-5 h-5" />
                        </button>
                    </li>`;

                    html += `</ul></nav>`;
                    html += `</div>`;
                }
                $(`#pagination`).html(html);
            }

            loadTableData();

            $(document).on('change', '#selectAllCheckboxes', function() {
                $('.form-checkbox').not('#selectAllCheckboxes').prop('checked', this.checked);
                this.indeterminate = false;
                toggleButtons();
            });

            $(document).on('change', '.form-checkbox:not(#selectAllCheckboxes)', function() {
                const total = $('.form-checkbox').not('#selectAllCheckboxes').length;
                const checked = $('.form-checkbox').not('#selectAllCheckboxes').filter(':checked').length;
                const selectAll = $('#selectAllCheckboxes').get(0);

                if (checked === 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                } else if (checked === total) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                } else {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                }
            });

            toggleButtons();

            $(document).on('change', '.form-checkbox[name="selected[]"]', function() {
                toggleButtons();
            });

            $(document).on('click', '.pagination-btn', function() {
                const page = parseInt($(this).data('page'));
                if (!isNaN(page)) {
                    loadTableData(page);
                }
            });

            $(document).on('click', '.sortable', function() {
                const clickedField = $(this).data('field');

                if (clickedField === currentSortBy) {
                    if (currentSortOrder === '') {
                        currentSortOrder = 'asc';
                    } else if (currentSortOrder === 'asc') {
                        currentSortOrder = 'desc';
                    } else {
                        currentSortOrder = '';
                        currentSortBy = '';
                    }
                } else {
                    currentSortBy = clickedField;
                    currentSortOrder = 'asc';
                }

                loadTableData();
            });

            if (searchInputId) {
                $(document).on('input', `#${searchInputId}`, function() {
                    currentSearch = $(this).val();
                    loadTableData();
                });
            }

            $(document).on('click', '.btn-edit', function() {
                const url = actionUrl + $(this).data('id');
                showDynamicModal(url);
            });

            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                const url = actionUrl + $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Apakah Anda yakin ingin menghapus praktikum ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    loadTableData();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON.message ||
                                        'Terjadi kesalahan saat menghapus praktikum.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#btn-bulk-delete', function() {
                const selectedIds = $('.form-checkbox[name="selected[]"]:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Data Terpilih',
                        text: 'Silakan pilih data yang ingin Anda hapus.',
                        showConfirmButton: true
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus ${selectedIds.length} data yang dipilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: bulkDeleteUrl,
                            type: 'POST',
                            data: {
                                ids: selectedIds
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    loadTableData(
                                        currentPage
                                    ); // Muat ulang data di halaman saat ini
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON.message ||
                                        'Terjadi kesalahan saat menghapus data.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });

            $('#setAssistantModal').find('#closeModalBtn').on('click', function() {
                $('#setAssistantModal').find('#modalContent').removeClass('scale-100 opacity-100').addClass(
                    'scale-95 opacity-0');
                setTimeout(() => {
                    $('#setAssistantModal').addClass('hidden');
                }, 300);
            });

            $('#setAssistantModal').find('#modalOverlay').on('click', function(e) {
                if (e.target === this) {
                    $('#setAssistantModal').find('#modalContent').removeClass('scale-100 opacity-100')
                        .addClass(
                            'scale-95 opacity-0');
                    setTimeout(() => {
                        $('#setAssistantModal').addClass('hidden');
                    }, 300);
                }
            });

            $(document).on('click', '.btn-set-assistant', function() {
                const scheduleId = $(this).data('id');
                const url = actionUrl + 'set-assistant/' + scheduleId;
                console.log('Set Assistant URL:', url);

                $('#setAssistantModal').removeClass('hidden');
                setTimeout(() => {
                    $('#setAssistantModal').find('#modalContent').removeClass('scale-95 opacity-0')
                        .addClass('scale-100 opacity-100');
                }, 10);

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#setAssistantModal').find('.modal-body').html(`
                            <div class="flex justify-center items-center">
                                <x-icon-spinner class="h-16 w-16 animate-spin" />
                            </div>
                        `);
                    },
                    success: function(response) {
                        console.log(response);
                        $('#setAssistantModal').find('.modal-body').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading modal content:", error);
                        $('#setAssistantModal').find('.modal-body').html(
                            '<p class="text-red-500 text-center">Gagal memuat formulir. Silakan coba lagi.</p>'
                        );
                    }
                });
            });
        });
    </script>
@endpush
