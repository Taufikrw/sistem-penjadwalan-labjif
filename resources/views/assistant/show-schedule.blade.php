<x-layouts.app>
    <x-slot name="title">Dashboard</x-slot>

    <div class="flex items-center rounded-lg bg-white px-10 py-6 gap-8">
        <img src="{{ asset('assets/images/Avatar.svg') }}" alt="avatar" class="w-32 h-32 rounded-full">
        <div class="flex flex-col gap-6">
            <div class="text-2xl font-bold flex items-center">
                <h2>Biodata</h2>
                @if (Auth::user()->role === 'assistant')
                    <a href="" class="hover:text-key-secondary transition duration-300">
                        <x-heroicon-s-pencil-square class="w-5 ml-4" />
                    </a>
                @endif
            </div>
            <div class="flex gap-8">
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-light">NIM</span>
                    <span class="text-lg">{{ $assistant->nim }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-light">Nama</span>
                    <span class="text-lg">{{ $assistant->name }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-light">Program Studi</span>
                    <span class="text-lg">{{ $assistant->prodi }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-light">Angkatan</span>
                    <span class="text-lg">{{ $assistant->angkatan }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[#5A5A5A] font-light">Nomor Telepon</span>
                    <span class="text-lg">{{ $assistant->nomor_telp ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="">
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

    <div id="contentSaatIni" class="tab-content" data-tab-name="contentSaatIni">
        <div class="rounded-lg w-full overflow-x-auto px-4 bg-white">
            <table class="w-full text-sm text-left rtl:text-right text-key-primary">
                <thead class="border-b border-[#E5E2E1]">
                    <tr>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="practicum_name"
                                data-sort-direction="">
                                Mata Kuliah
                            </span>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="tahun_ajar"
                                data-sort-direction="">
                                Kelas
                            </span>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="tahun_ajar"
                                data-sort-direction="">
                                Partner
                            </span>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="tahun_ajar"
                                data-sort-direction="">
                                Lab
                            </span>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="tahun_ajar"
                                data-sort-direction="">
                                Jadwal Kuliah
                            </span>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="tahun_ajar"
                                data-sort-direction="">
                                Dosen
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody id="schedule-table-body">
                    @forelse ($schedules as $item)
                        <tr class="border-b border-[#E5E2E1]">
                            <td class="px-4 py-4">{{ $item->practicum->name }}</td>
                            <td class="px-4 py-4">{{ $item->name }}</td>
                            <td class="px-4 py-4">
                                @php
                                    $partners = $item->assistantSchedules->filter(function ($assistantSchedule) use (
                                        $assistant,
                                    ) {
                                        return $assistantSchedule->assistant->nim !== $assistant->nim;
                                    });
                                @endphp

                                @if ($partners->isEmpty())
                                    Tidak ada
                                @else
                                    @foreach ($partners as $partner)
                                        {{ $partner->assistant->name }}
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-4 py-4">{{ $item->laboratorium->name }}</td>
                            <td class="px-4 py-4">{{ $item->day }}
                                {{ $item->start_time->format('h:i A') }} - {{ $item->end_time->format('h:i A') }}
                            </td>
                            <td class="px-4 py-4">{{ $item->dosen }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center font-medium">
                                <x-icon-no-data class="w-70 mx-auto" />
                                <span class="font-bold">Tidak ada data.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="contentRiwayat" class="tab-content hidden" data-tab-name="contentRiwayat">
        <div class="rounded-lg w-full overflow-x-auto px-4 bg-white">
            <table class="w-full text-sm text-left rtl:text-right text-key-primary">
                <thead class="border-b border-[#E5E2E1]">
                    <tr>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="practicum_name"
                                data-sort-direction="">
                                Nama Praktikum
                            </span>
                        </th>
                        <th scope="col" class="px-4 py-3">
                            <span class="flex items-center gap-1 font-extrabold" data-field="tahun_ajar"
                                data-sort-direction="">
                                Tahun Ajaran
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
    </div>

    <script type="module">
        $(document).ready(function() {
            const $tabButtons = $('.tab-button');
            const $tabContents = $('.tab-content');
            let nim = "{{ $assistant->nim }}";

            function loadHistoryTable() {
                const tableId = $('#history-table-body');
                let url = "/api/get-history-table/" + nim;

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
                        if (data.data.length > 0) {
                            $.each(data.data, function(index, item) {
                                let row = `
                                    <tr class="border-b border-[#E5E2E1]">
                                        <td class="px-4 py-4">${item.name}</td>
                                        <td class="px-4 py-4">${item.tahun_ajar}</td>
                                    </tr>
                                `;
                                tableId.append(row);
                            });
                        } else {
                            tableId.html(`
                                <tr>
                                    <td colspan="2" class="py-8 text-center font-medium">
                                        <x-icon-no-data class="w-70 mx-auto" />
                                        <span class="font-bold">Tidak ada data.</span>
                                    </td>
                                </tr>
                            `);
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
                    loadHistoryTable();
                }
            });

            // Set tab "Saat Ini" aktif secara default saat halaman dimuat
            $('#tabSaatIni').click();
        });
    </script>
</x-layouts.app>