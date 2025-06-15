@extends('layouts.app')

@section('title', 'Schedule')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex-col">
            <x-topbar />

            @if (session('success'))
                <div class="bg-green-500 text-white px-4 py-3 relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="p-10 flex-1">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Daftar Jadwal Praktikum</h1>
                    @if (Auth::user()->role === 'admin')
                        <div
                            class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                            <a href="#"
                                class="bg-secondary font-bold py-2 px-4 rounded border-1 border-tertiary hover:bg-secondary-70">
                                <p class="text-tertiary text-sm">Generate</p>
                            </a>
                            <a href="{{ route('schedule.create') }}"
                                class="bg-secondary font-bold py-2 px-4 rounded border-1 border-tertiary hover:bg-secondary-70">
                                <p class="text-tertiary text-sm">Tambah</p>
                            </a>
                        </div>
                    @endif
                </div>

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
                                @if (Auth::user()->role === 'admin')
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                        Aksi
                                    </th>
                                @endif
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
                                        @if (Auth::user()->role === 'admin')
                                            @if ($item->assistantSchedules && $item->assistantSchedules->isEmpty())
                                                <a href="{{ route('schedule.set-assistant', $item->id) }}"
                                                    class="text-error hover:text-error-darked flex items-center gap-1">
                                                    <x-heroicon-o-plus class="w-4 h-4" /> Tambah Asisten
                                                </a>
                                            @elseif ($item->assistantSchedules->count() === 1)
                                                <div class="space-y-1">
                                                    @foreach ($item->assistantSchedules as $assistant)
                                                        <span
                                                            class="block text-gray-700">{{ Str::limit($assistant->assistant->name, 15) }}</span>
                                                    @endforeach
                                                    <a href="{{ route('schedule.edit-assistant', $item->id) }}"
                                                        class="text-error hover:text-error-darked flex items-center gap-1">
                                                        <x-heroicon-o-plus class="w-4 h-4" /> Tambah Asisten
                                                    </a>
                                                </div>
                                            @else
                                                <div class="space-y-1">
                                                    @foreach ($item->assistantSchedules as $assistant)
                                                        <span
                                                            class="block text-gray-700">{{ Str::limit($assistant->assistant->name, 15) }}</span>
                                                    @endforeach
                                                    <a href="{{ route('schedule.edit-assistant', $item->id) }}"
                                                        class="text-secondary hover:underline">Edit Asisten</a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="space-y-1">
                                                @forelse ($item->assistantSchedules as $assistant)
                                                    <span
                                                        class="block text-gray-700">{{ Str::limit($assistant->assistant->name, 15) }}</span>
                                                @empty
                                                    <span class="block text-gray-700">Tidak ada asisten</span>
                                                @endforelse
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->day }}
                                        {{ $item->start_time->format('H:i A') }} -
                                        {{ $item->end_time->format('H:i A') }}</td>
                                    @if (Auth::user()->role === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('schedule.edit', $item->id) }}">
                                                    <x-heroicon-o-pencil-square
                                                        class="w-5 h-5 text-extended-1 hover:text-extended-light" />
                                                </a>
                                                <form action="{{ route('schedule.delete', $item->id) }}" method="POST"
                                                    class="flex">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Yakin ingin menghapus asisten ini?')"><x-heroicon-o-trash
                                                            class="w-5 h-5 text-error hover:text-error-darked" /></button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                        Tidak ada data jadwal praktikum.
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
