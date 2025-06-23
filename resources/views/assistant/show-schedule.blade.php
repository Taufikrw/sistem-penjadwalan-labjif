@extends('layouts.app')

@section('title', 'Assistant')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen">
            <x-topbar />

            @if (session('success'))
                <div class="bg-green-500 text-white px-4 py-3 relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="h-40 flex flex-col items-center justify-center gap-2">
                <h1 class="text-2xl font-bold">{{ $assistant->name }}</h1>
                <p class="text-xl">{{ $assistant->nim }} | {{ $assistant->prodi }} | {{ $assistant->angkatan }}</p>
            </div>

            <div class="rounded-lg shadow overflow-hidden flex-1">
                <div class="px-16 flex gap-4">
                    <button id="tabSaatIni"
                        class="tab-button px-6 py-1 text-md font-semibold border-x-3 border-t-3 border-secondary rounded-t-xl bg-[#FFDDB0] text-neutral-30 hover:bg-secondary focus:outline-none"
                        data-tab-target="contentSaatIni" data-active="true">
                        Saat Ini
                    </button>
                    <button id="tabRiwayat"
                        class="tab-button px-6 py-1 text-md font-semibold border-x-3 border-t-3 border-secondary rounded-t-xl bg-[#FFDDB0] text-neutral-30 hover:bg-secondary focus:outline-none"
                        data-tab-target="contentRiwayat" data-active="false">
                        Riwayat
                    </button>
                </div>

                <div class="px-10 tab-content-wrapper">
                    <div id="contentSaatIni" class="tab-content" data-tab-name="contentSaatIni">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-table-header">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Nama Mata Praktikum
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Kelas
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Partner
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Lab
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Jadwal Kuliah
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Dosen
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-2 divide-table-header border-b-2 border-table-header">
                                    @forelse ($schedules as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->practicum->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $partners = $item->assistantSchedules->filter(function (
                                                        $assistantSchedule,
                                                    ) use ($assistant) {
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
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->laboratorium->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->day }}
                                                {{ $item->start_time->format('h:i A') }} -
                                                {{ $item->end_time->format('h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->dosen }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                                Tidak ada data jadwal kuliah.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="contentRiwayat" class="tab-content hidden" data-tab-name="contentRiwayat">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-table-header">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Nama Praktikum
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                            Tahun Ajaran
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-2 divide-table-header border-b-2 border-table-header">
                                    @forelse ($histories as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->tahun_ajar }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                                Tidak ada data riwayat.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Nonaktifkan semua tombol tab
                    tabButtons.forEach(btn => {
                        btn.classList.remove('bg-secondary', 'border-secondary');
                        btn.classList.add('border-secondary');
                        btn.dataset.active = 'false'; // Update data-active attribute
                    });

                    // Sembunyikan semua konten tab
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Aktifkan tombol tab yang diklik
                    this.classList.remove('border-secondary');
                    this.classList.add('bg-secondary', 'border-secondary');
                    this.dataset.active = 'true'; // Update data-active attribute

                    // Tampilkan konten tab yang sesuai
                    const targetId = this.dataset.tabTarget;
                    document.getElementById(targetId).classList.remove('hidden');
                });
            });

            // Set tab "Saat Ini" aktif secara default saat halaman dimuat
            document.getElementById('tabSaatIni').click();
        });
    </script>
@endpush
