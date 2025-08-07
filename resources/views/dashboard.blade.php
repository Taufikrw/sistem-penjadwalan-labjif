<x-layouts.app>
    <x-slot:title>
        Dashboard
    </x-slot:title>

    <div class="p-6 bg-white rounded-lg border border-[#D9D9D9]">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">Ringkasan Data Aslab</h2>
            <x-select-input id="data-filter" width="w-48" :options="['status' => 'Status', 'prodi' => 'Prodi', 'angkatan' => 'Angkatan']" :selected="$filterType ?? 'status'" name="filterType" />
        </div>

        <div id="summary-bar" class="relative w-full h-5 bg-gray-200 rounded-full overflow-hidden"></div>
        <div id="summary-list" class="flex flex-wrap items-center mt-6 text-sm gap-12"></div>
    </div>

    <h3 class="font-bold text-2xl">Jadwal Praktikum Hari Ini</h3>

    <x-data-table url="{{ route('api.today-schedules') }}" action-url="schedule/" :columns="[
        ['label' => 'Mata Kuliah', 'field' => 'practicum_name', 'sortable' => false],
        ['label' => 'Kelas', 'field' => 'name', 'sortable' => false],
        ['label' => 'Lab', 'field' => 'laboratorium_name', 'sortable' => false],
        ['label' => 'Dosen', 'field' => 'dosen', 'sortable' => false],
        ['label' => 'Jam', 'field' => 'jam', 'sortable' => false],
        ['label' => 'Asisten', 'field' => 'assistant_names', 'sortable' => false],
    ]" :has-actions="false"
        table-id="today-schedule-table" has-assistant="true" btn-create-id="btn-create-today-schedule" />

    <script type="module">
        $(document).ready(function() {
            function showLoader() {
                $('#summary-bar').html(
                    '<div class="w-full h-5 flex items-center justify-center bg-gray-200 animate-pulse rounded-full"></div>'
                );
                $('#summary-list').html(
                    '<div class="flex items-center gap-2 text-gray-500">' +
                    '<span class="loader-dot animate-bounce w-3 h-3 bg-gray-300 rounded"></span>' +
                    '<span>Memuat...</span>' +
                    '</div>'
                );
            }

            function loadSummary(filterType = 'status') {
                showLoader();
                $.getJSON("{{ route('api.assistant.overview') }}", {
                    filterType
                }, function(res) {
                    const data = res.data;
                    const total = Math.max(1, res.total);

                    // Render bar
                    let left = 0;
                    let barHtml = '';
                    data.forEach(item => {
                        const width = (item.count / total) * 100;
                        barHtml +=
                            `<div class="h-full absolute top-0" style="left:${left}%;width:${width}%;background-color:${item.color};"></div>`;
                        left += width;
                    });
                    $('#summary-bar').html(
                        '<div class="relative w-full h-5 bg-gray-200 rounded-full overflow-hidden" style="position:relative;">' +
                        barHtml + '</div>');

                    // Render list
                    let listHtml = '';
                    data.forEach(item => {
                        listHtml += `<div class="flex items-center">
                <div class="w-4 h-4 rounded mr-2" style="background-color:${item.color};"></div>
                <span class="font-semibold">${item.label} : ${item.count}</span>
            </div>`;
                    });
                    listHtml += `<div class="font-semibold">Total : ${total} orang</div>`;
                    $('#summary-list').html(listHtml);
                });
            }

            // Initial load
            let filterType = $('#hidden-data-filter').val() || 'status';
            loadSummary(filterType);

            // On filter change
            $('#hidden-data-filter').on('change', function() {
                loadSummary($(this).val());
            });
        });
    </script>
</x-layouts.app>
