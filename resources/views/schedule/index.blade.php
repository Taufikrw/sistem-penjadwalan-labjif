@extends('layouts.app')

@section('title', 'Schedule')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg mb-4">Schedule</h1>

            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('schedule.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Jadwal
            </a>

            @if ($schedules->isEmpty())
                <p class="text-gray-500 mt-4">Belum ada jadwal kuliah.</p>
            @else
                <table class="table-auto w-full mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Ruangan</th>
                            <th class="px-4 py-2">Asisten</th>
                            <th class="px-4 py-2">Waktu</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            <tr>
                                <td class="border px-4 py-2">{{ $schedule->practicum->name }} {{ $schedule->name }}</td>
                                <td class="border px-4 py-2">{{ $schedule->room->name }}</td>
                                <td class="border px-4 py-2">
                                    @if ($schedule->assistantSchedules && $schedule->assistantSchedules->isEmpty())
                                        <a href="{{ route('schedule.set-assistant', $schedule->id) }}"
                                            class="text-red-500 hover:underline">Set Assistant</a>
                                    @else
                                        <ul class="list-disc list-inside">
                                            @foreach ($schedule->assistantSchedules as $assistant)
                                                <li class="text-gray-700">{{ $assistant->nim }}</li>
                                            @endforeach
                                        </ul>
                                        <a href="{{ route('schedule.edit-assistant', $schedule->id) }}"
                                            class="text-blue-500 hover:underline">Edit Assistant</a>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">{{ $schedule->day }}
                                    {{ $schedule->start_time->format('H:i A') }} -
                                    {{ $schedule->end_time->format('H:i A') }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('schedule.edit', $schedule->id) }}"
                                        class="text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('schedule.delete', $schedule->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline"
                                            onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
