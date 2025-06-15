@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <x-topbar />

            <div class="p-10 flex-1">
                <h1 class="text-3xl font-bold mb-6">Jadwal Praktikum Hari Ini</h1>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-table-header">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Nama Praktikum
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Dosen Pengampu
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Ruangan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Asisten
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Jadwal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-table-header border-b-2 border-table-header">
                            @forelse ($schedules as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->practicum->name }} {{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->dosen }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->laboratorium->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            @forelse ($item->assistantSchedules as $assistant)
                                                <span
                                                    class="block text-gray-700">{{ Str::limit($assistant->assistant->name, 15) }}</span>
                                            @empty
                                                <span class="block text-gray-700">Tidak ada</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->day }}
                                        {{ $item->start_time->format('H:i A') }} -
                                        {{ $item->end_time->format('H:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                        Tidak ada data jadwal praktikum hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
