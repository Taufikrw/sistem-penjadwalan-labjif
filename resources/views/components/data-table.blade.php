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
            const columns = @json($columns);
            const hasActions = {{ $hasActions ? 'true' : 'false' }};

            function loadTableData() {
                $.ajax({
                    url: url,
                    method: 'GET',
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
                                    rowsHtml += `<td class="pl-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                    <button data-id="${item.kode_praktikum}" class="btn-edit"><x-heroicon-o-pencil-square class="w-5 h-5 text-extended-1 hover:text-extended-light cursor-pointer"/></button>
                                    <button data-id="${item.kode_praktikum}" class="btn-delete"><x-heroicon-o-trash class="w-5 h-5 text-error hover:text-error-darked cursor-pointer"/></button>
                                </td>`;
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

            loadTableData(); // Initial load
        });
    </script>
@endpush
