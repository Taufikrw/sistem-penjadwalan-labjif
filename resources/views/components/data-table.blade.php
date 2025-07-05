<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-table-header">
            <tr>
                @foreach ($columns as $column)
                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                        {{ $column['label'] }}</th>
                @endforeach
                @if ($hasActions)
                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                        Aksi
                    </th>
                @endif
            </tr>
        </thead>
        <tbody id="{{ $tableId }}-body" class="divide-y-2 divide-table-header border-b-2 border-table-header">
            <tr>
                <td colspan="{{ count($columns) + ($hasActions ? 1 : 0) }}"
                    class="px-6 py-4 text-center text-neutral-50">
                    <div class="flex justify-center items-center gap-4">
                        <div class="border-gray-300 h-8 w-8 animate-spin rounded-full border-5 border-t-secondary">
                        </div>
                        Loading...
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            const tableId = '{{ $tableId }}';
            const url = '{{ $url }}';
            const actionUrl = '{{ $actionUrl }}';
            const columns = @json($columns);
            const hasActions = {{ $hasActions ? 'true' : 'false' }};
            const primary = '{{ $primary }}';

            function loadTableData() {
                $.ajax({
                    url: url,
                    method: 'GET',
                    beforeSend: function() {
                        $(`#${tableId}-body`).html(
                            `<tr>
                                <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                    <div class="flex justify-center items-center gap-4">
                                        <div class="border-gray-300 h-8 w-8 animate-spin rounded-full border-5 border-t-secondary"></div>
                                        Loading...
                                    </div>
                                </td>
                            </tr>`
                        );
                    },
                    success: function(response) {
                        let rowsHtml = '';
                        if (response.data.data.length === 0) {
                            rowsHtml =
                                `
                                <tr>
                                    <td colspan="${columns.length + (hasActions ? 1 : 0)}" class="px-6 py-4 text-center text-neutral-50">
                                        Tidak ada data.
                                    </td>
                                </tr>
                            `;
                        } else {
                            response.data.data.forEach(function(item) {
                                rowsHtml +=
                                    '<tr>';
                                columns.forEach(function(column) {
                                    rowsHtml +=
                                        `<td class="pl-6 py-4 whitespace-nowrap">${item[column.field]}</td>`;
                                });
                                if (hasActions) {
                                    if (primary === 'nim') {
                                        rowsHtml += `<td class="pl-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                            <button data-id="${item.nim}" class="btn-edit"><x-heroicon-o-pencil-square class="w-5 h-5 text-extended-1 hover:text-extended-light cursor-pointer"/></button>
                                            <button data-id="${item.nim}" class="btn-delete"><x-heroicon-o-trash class="w-5 h-5 text-error hover:text-error-darked cursor-pointer"/></button>
                                        </td>`;
                                    } else if (primary === 'kode_praktikum') {
                                        rowsHtml += `<td class="pl-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                            <button data-id="${item.kode_praktikum}" class="btn-edit"><x-heroicon-o-pencil-square class="w-5 h-5 text-extended-1 hover:text-extended-light cursor-pointer"/></button>
                                            <button data-id="${item.kode_praktikum}" class="btn-delete"><x-heroicon-o-trash class="w-5 h-5 text-error hover:text-error-darked cursor-pointer"/></button>
                                        </td>`;
                                    } else {
                                        rowsHtml += `<td class="pl-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                            <button data-id="${item.id}" class="btn-edit"><x-heroicon-o-pencil-square class="w-5 h-5 text-extended-1 hover:text-extended-light cursor-pointer"/></button>
                                            <button data-id="${item.id}" class="btn-delete"><x-heroicon-o-trash class="w-5 h-5 text-error hover:text-error-darked cursor-pointer"/></button>
                                        </td>`;
                                    }
                                }
                                rowsHtml += '</tr>';
                            });
                        }
                        $(`#${tableId}-body`).html(rowsHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading table data:", error);
                        $(`#${tableId}-body`).html(
                            `
                                <tr>
                                    <td colspan="${columns.length + (hasActions ? 1 : 0)}" class="px-6 py-4 text-center text-red-500">
                                        Gagal memuat data. Silakan coba lagi.
                                    </td>
                                </tr>
                            `
                        );
                    }
                });
            }

            loadTableData();

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
        });
    </script>
@endpush
