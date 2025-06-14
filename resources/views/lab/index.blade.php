@extends('layouts.app')

@section('title', 'Room List')

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

            <div class="p-10 flex-1">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Daftar Laboratorium</h1>
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                        <a href="{{ route('lab.create') }}"
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
                                    Nama
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Lokasi
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Kapasitas
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-black tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-table-header border-b-2 border-table-header">
                            @forelse ($labs as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->location }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->capacity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                        <a href="{{ route('lab.edit', $item->id) }}">
                                            <x-heroicon-o-pencil-square
                                                class="w-5 h-5 text-extended-1 hover:text-extended-light" />
                                        </a>
                                        <form action="{{ route('lab.delete', $item->id) }}" method="POST"
                                            class="flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus laboratorium ini?')"><x-heroicon-o-trash
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
    @endsection
