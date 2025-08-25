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
        <div class="rounded-lg w-full overflow-x-auto px-4 bg-white">
            <table class="w-full text-sm text-left rtl:text-right text-[#1E1E1E]">
                <thead class="border-b border-[#E5E2E1] text-key-primary">
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
                                        <div class="flex items-center gap-2">
                                            {{ $partner->assistant->name }}
                                            @if (Auth::user()->username === $assistant->nim)
                                                <a href="{{ route('assistant.show', $partner->assistant->nim) }}"
                                                    title="Lihat detail asisten">
                                                    <x-heroicon-s-eye class="inline w-4 h-4 text-gray-400" />
                                                </a>
                                            @endif
                                        </div>
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
        </div>
    @endif

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
                                        <td class="px-4 py-4">${item.tahun_ajar}</td>
                                        <td class="px-4 py-4">${item.name}</td>
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
