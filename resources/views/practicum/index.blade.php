@extends('layouts.app')

@section('title', 'Practicum')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg mb-4">Practicum</h1>

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('practicum.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Praktikum
            </a>

            @if($practicums->isEmpty())
                <p class="text-gray-500 mt-4">Belum ada jadwal kuliah.</p>
            @else
                <table class="table-auto w-full mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Kode Praktikum</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Prodi</th>
                            <th class="px-4 py-2">Semester</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($practicums as $practicum)
                            <tr>
                                <td class="border px-4 py-2">{{ $practicum->kode_praktikum }}</td>
                                <td class="border px-4 py-2">{{ $practicum->name }}</td>
                                <td class="border px-4 py-2">{{ $practicum->for_prodi }}</td>
                                <td class="border px-4 py-2">
                                    {{ $practicum->is_odd ? 'Genap' : 'Ganjil' }}
                                </td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('practicum.edit', $practicum->kode_praktikum) }}" class="text-blue-500 hover:underline">Edit</a>
                                    <form action="{{ route('practicum.delete', $practicum->kode_praktikum) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
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