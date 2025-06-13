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
                    <h1 class="text-3xl font-bold">Daftar Perkuliahan</h1>
                    <div
                        class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                        <a href="{{ route('course.create', $assistant->nim) }}"
                            class="bg-secondary font-bold py-2 px-4 rounded border-1 border-tertiary hover:bg-secondary-70">
                            <p class="text-tertiary text-sm">Tambah</p>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-table-header">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Nama Mata Kuliah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Kelas
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Jadwal Kuliah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-table-header border-b-2 border-table-header">
                            @forelse ($courses as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->course }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->day }}
                                        {{ $item->start_time->format('h:i A') }} - {{ $item->end_time->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                        <a href="{{ route('course.edit', [$assistant->nim, $item->id]) }}">
                                            <x-heroicon-o-pencil-square
                                                class="w-5 h-5 text-extended-1 hover:text-extended-light" />
                                        </a>
                                        <form action="{{ route('course.delete', [$assistant->nim, $item->id]) }}"
                                            method="POST" class="flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus asisten ini?')"><x-heroicon-o-trash
                                                    class="w-5 h-5 text-error hover:text-error-darked" /></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                        Tidak ada data asisten.
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
