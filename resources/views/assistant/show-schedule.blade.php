<x-layouts.app>
    @if (Auth::user()->role === 'assistant' && Auth::user()->username === $assistant->nim)
        <x-slot name="title">Dashboard</x-slot>
    @else
        <x-slot name="title">Detail Jadwal Praktikum</x-slot>
    @endif

    @if (Auth::user()->role === 'admin')
        <x-slot:back_button>
            <a href="{{ route('assistant.index') }}"
                class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition-colors w-fit">
                <x-heroicon-s-arrow-left class="w-4 h-4" />
                Kembali
            </a>
        </x-slot:back_button>
    @endif

    @if (Auth::user()->role === 'assistant' && Auth::user()->username !== $assistant->nim)
        <x-slot:back_button>
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition-colors w-fit">
                <x-heroicon-s-arrow-left class="w-4 h-4" />
                Kembali
            </a>
        </x-slot:back_button>
    @endif

    <div class="flex items-center rounded-xl bg-white px-10 py-6 gap-8 border border-[#D9D9D9]">
        <img src="{{ $assistant->foto ? asset('storage/assistants/' . $assistant->foto) : asset('assets/images/Avatar.svg') }}"
            alt="avatar" class="w-32 h-32 rounded-full">
        <div class="flex flex-col gap-6">
            <div class="text-2xl font-bold flex items-center">
                <h2>Biodata</h2>
                @if (Auth::user()->role === 'assistant' && Auth::user()->username === $assistant->nim)
                    <a href="{{ route('assistant.edit-biodata') }}"
                        class="hover:text-key-secondary text-key-tertiary transition duration-300">
                        <x-heroicon-s-pencil-square class="w-5 ml-4" />
                    </a>
                @endif
            </div>
            <div class="flex gap-16">
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-bold">NIM</span>
                    <span class="text-lg font-bold">{{ $assistant->nim }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-bold">Nama</span>
                    <span class="text-lg font-bold">{{ $assistant->name }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-bold">Program Studi</span>
                    <span class="text-lg font-bold">{{ $assistant->prodi }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-bold">Angkatan</span>
                    <span class="text-lg font-bold">{{ $assistant->angkatan }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-bold">Nomor Telepon</span>
                    <span class="text-lg font-bold">{{ $assistant->nomor_telp ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if (
        (Auth::user()->role === 'assistant' && Auth::user()->username === $assistant->nim) ||
            Auth::user()->role === 'admin')
        <div>
            <div class="flex space-x-2">
                <button id="tabSaatIni" type="button"
                    class="tab-button py-2 mx-4 transition cursor-pointer text-key-primary border-b-2 font-bold border-key-primary hover:text-key-primary hover:border-b-2 hover:border-key-primary hover:font-bold"
                    data-tab-target="contentSaatIni" data-active="true">
                    Jadwal Saat Ini
                </button>
                <button id="tabRiwayat" type="button"
                    class="tab-button py-2 mx-4 transition cursor-pointer hover:text-key-primary hover:border-b-2 hover:border-key-primary hover:font-bold"
                    data-tab-target="contentRiwayat" data-active="false">
                    Riwayat Mengajar
                </button>
            </div>
            <hr class="text-gray-300">
        </div>
    @endif

    <div id="contentSaatIni" class="tab-content" data-tab-name="contentSaatIni">
        <x-data-table url="{{ route('api.current-schedule.table') }}" :filters="['assistant_nim' => $assistant->nim]" action-url="schedule/"
            :columns="[
                ['label' => 'Mata Kuliah', 'field' => 'practicum_name', 'sortable' => false],
                ['label' => 'Kelas', 'field' => 'name', 'sortable' => false],
                ['label' => 'Lab', 'field' => 'laboratorium_name', 'sortable' => false],
                ['label' => 'Dosen', 'field' => 'dosen', 'sortable' => false],
                ['label' => 'Hari', 'field' => 'day', 'sortable' => false],
                ['label' => 'Jam', 'field' => 'jam', 'sortable' => false],
                ['label' => 'Partner', 'field' => 'partner_name', 'sortable' => false],
            ]" :has-actions="false"
            has-detail-link="{{ Auth::user()->username === $assistant->nim ? true : false }}" table-id="schedule-table"
            search-input-id="search-schedule" btn-create-id="btn-create-schedule" :has-setAssistant="true" />
    </div>

    @if (
        (Auth::user()->role === 'assistant' && Auth::user()->username === $assistant->nim) ||
            Auth::user()->role === 'admin')
        <div id="contentRiwayat" class="tab-content hidden" data-tab-name="contentRiwayat">
            <div class="rounded-lg w-full overflow-x-auto px-4 bg-white">
                <table class="w-full text-sm text-left rtl:text-right text-[#1E1E1E]">
                    <thead class="border-b border-[#E5E2E1] text-key-primary">
                        <tr>
                            <th scope="col" class="px-4 py-3 w-1/6">
                                <span class="flex items-center gap-1 font-extrabold" data-field="tahun_ajar"
                                    data-sort-direction="">
                                    Tahun Ajaran
                                </span>
                            </th>
                            <th scope="col" class="px-4 py-3 w-5/6">
                                <span class="flex items-center gap-1 font-extrabold" data-field="practicum_name"
                                    data-sort-direction="">
                                    Nama Praktikum
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="history-table-body">
                        <tr>
                            <td colspan="2" class="px-6 py-8 text-center text-neutral-50">
                                <div class="flex justify-center items-center">
                                    <x-icon-spinner class="h-16 w-16 animate-spin" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="paginationRiwayat" class="px-4"></div>
        </div>
    @endif

    <script type="module">
        $(document).ready(function() {
            const $tabButtons = $('.tab-button');
            const $tabContents = $('.tab-content');
            let nim = "{{ $assistant->nim }}";
            let currentPageRiwayat = 1;

            function renderPagination(pagination, containerId) {
                let html = '';
                const container = $(`#${containerId}`);
                container.empty(); // Kosongkan pagination sebelumnya

                if (pagination.last_page > 1 || pagination.total > 0) {
                    html += `<div class="flex justify-between items-center text-xs mt-4 mb-6">`;
                    if (pagination.total > 0) {
                        html += `<div class="text-[#5A5A5A] font-semibold px-2">
                            ${pagination.from ?? 0} hingga ${pagination.to ?? 0} data dari ${pagination.total}
                        </div>`;
                    } else {
                        html += `<div></div>`;
                    }
                    html += `<nav><ul class="inline-flex space-x-1 items-center">`;

                    const prevDisabled = pagination.current_page === 1;
                    html += `<li>
                        <button class="pagination-btn px-3 py-2 ml-0 leading-tight rounded-l-lg ${prevDisabled ? 'text-[#CDCDCD]' : 'text-[#5A5A5A] hover:text-[#434343] cursor-pointer'}"
                            data-page="${pagination.current_page - 1}" ${prevDisabled ? 'disabled' : ''}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                    </li>`;

                    let startPage = Math.max(1, pagination.current_page - 1);
                    let endPage = Math.min(pagination.last_page, startPage + 3);
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

                    const nextDisabled = pagination.current_page === pagination.last_page;
                    html += `<li>
                        <button class="pagination-btn px-3 py-2 leading-tight rounded-r-lg ${nextDisabled ? 'text-[#CDCDCD]' : 'text-[#5A5A5A] hover:text-[#434343] cursor-pointer'}"
                            data-page="${pagination.current_page + 1}" ${nextDisabled ? 'disabled' : ''}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </li>`;

                    html += `</ul></nav></div>`;
                }
                container.html(html);
            }

            function loadHistoryTable(page = 1) {
                currentPageRiwayat = page;
                const tableId = $('#history-table-body');
                let url = "/api/get-history-table/" + nim + "?page=" + page;

                $.ajax({
                    url: url,
                    method: 'GET',
                    beforeSend: function() {
                        tableId.empty();
                        tableId.append(`
                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-neutral-50">
                                    <div class="flex justify-center items-center">
                                        <x-icon-spinner class="h-16 w-16 animate-spin" />
                                    </div>
                                </td>
                            </tr>
                        `);
                    },
                    success: function(data) {
                        tableId.empty();
                        const paginationData = data.data;
                        if (data.data.data.length > 0) {
                            $.each(data.data.data, function(index, item) {
                                let row = `
                                    <tr class="border-b border-[#E5E2E1]">
                                        <td class="px-4 py-4">${item.tahun_ajar}</td>
                                        <td class="px-4 py-4">${item.name}</td>
                                    </tr>
                                `;
                                tableId.append(row);
                            });
                            renderPagination(paginationData, 'paginationRiwayat');
                        } else {
                            tableId.html(`
                                <tr>
                                    <td colspan="2" class="py-8 text-center font-medium">
                                        <x-icon-no-data class="w-60 mx-auto" />
                                    </td>
                                </tr>
                            `);
                            $('#paginationRiwayat').empty();
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching history data:', error);
                    }
                });
            }

            $tabButtons.on('click', function() {
                // Nonaktifkan semua tombol tab
                $tabButtons.removeClass(
                        'text-key-primary border-b-2 border-key-primary font-bold cursor-pointer')
                    .addClass(
                        'hover:text-key-primary hover:border-b-2 hover:border-key-primary hover:font-bold cursor-pointer'
                    )
                    .prop('disabled', false)
                    .attr('data-active', 'false');

                // Sembunyikan semua konten tab
                $tabContents.addClass('hidden');

                // Aktifkan tombol tab yang diklik
                $(this).addClass('text-key-primary border-b-2 border-key-primary font-bold')
                    .removeClass(
                        'hover:text-key-primary hover:border-b-2 hover:border-key-primary hover:font-bold cursor-pointer'
                    )
                    .prop('disabled', true)
                    .attr('data-active', 'true');

                // Tampilkan konten tab yang sesuai
                $('#' + $(this).data('tab-target')).removeClass('hidden');

                // Jika tab Riwayat diklik, panggil loadHistoryTable
                if ($(this).attr('id') === 'tabRiwayat') {
                    loadHistoryTable(currentPageRiwayat);
                }
            });

            $(document).on('click', '#paginationRiwayat .pagination-btn', function() {
                const page = $(this).data('page');
                if (page) {
                    loadHistoryTable(page);
                }
            });

            // Set tab "Saat Ini" aktif secara default saat halaman dimuat
            $('#tabSaatIni').click();
        });
    </script>
</x-layouts.app>
