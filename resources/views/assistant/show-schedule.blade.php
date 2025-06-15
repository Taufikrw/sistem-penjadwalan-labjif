@extends('layouts.app')

@section('title', 'Assistant')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
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

            <div class="px-10 pb-10 flex-1">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Daftar Praktikum</h1>
                </div>

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
                                            $partners = $item->assistantSchedules->filter(function ($assistantSchedule) use ($assistant) {
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
                                        {{ $item->start_time->format('h:i A') }} - {{ $item->end_time->format('h:i A') }}
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
        </div>
    </div>
@endsection
