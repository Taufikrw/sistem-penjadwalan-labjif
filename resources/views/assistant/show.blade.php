@extends('layouts.app')

@section('title', 'Assistant')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg mb-4">{{ $assistant->name }}</h1>

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('course.create', $assistant->nim) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Jadwal Kuliah
            </a>

            @if($assistant->courseSchedules->isEmpty())
                <p class="text-gray-500 mt-4">Belum ada jadwal kuliah.</p>
            @else
                <table class="table-auto w-full mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Mata Kuliah</th>
                            <th class="px-4 py-2">Hari</th>
                            <th class="px-4 py-2">Jam</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td class="border px-4 py-2">{{ $course->course }} {{ $course->name }}</td>
                                <td class="border px-4 py-2">{{ $course->day }}</td>
                                <td class="border px-4 py-2">{{ $course->start_time->format('h:i A') }} - {{ $course->end_time->format('h:i A') }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('course.edit', [$assistant->nim, $course->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('course.delete', [$assistant->nim, $course->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="border px-4 py-2 text-center text-gray-500">Belum ada jadwal kuliah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection