@extends('layouts.app')

@section('title', 'Practicum')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg mb-4">Tambah Praktikum</h1>
            @if ($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">{{ $errors->first('error') }}</span>
                </div>
            @endif

            <form
                action="{{ isset($practicum) ? route('practicum.update', $practicum->kode_praktikum) : route('practicum.store') }}"
                method="POST">
                @csrf
                @if (isset($practicum))
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label for="kode_praktikum" class="block text-gray-700 text-sm font-bold mb-2">Kode Praktikum:</label>
                    <input type="text" name="kode_praktikum" id="kode_praktikum"
                        value="{{ old('kode_praktikum', isset($practicum) ? $practicum->kode_praktikum : '') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kode_praktikum') border-red-500 @enderror"
                        required>
                    @error('kode_praktikum')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Praktikum:</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', isset($practicum) ? $practicum->name : '') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="for_prodi" class="block text-gray-700 text-sm font-bold mb-2">Prodi:</label>
                    <select name="for_prodi" id="for_prodi"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('for_prodi') border-red-500 @enderror"
                        required>
                        <option value="">Select Option</option>
                        <option value="Informatika"
                            {{ old('for_prodi', isset($practicum) ? $practicum->for_prodi : '') == 'Informatika' ? 'selected' : '' }}>
                            Informatika</option>
                        <option value="Sistem Informasi"
                            {{ old('for_prodi', isset($practicum) ? $practicum->for_prodi : '') == 'Sistem Informasi' ? 'selected' : '' }}>
                            Sistem Informasi</option>
                    </select>
                    @error('for_prodi')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="is_odd" class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai:</label>
                    <select name="is_odd" id="is_odd"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('is_odd') border-red-500 @enderror"
                        required>
                        <option value="">Select Option</option>
                        <option value="1"
                            {{ old('is_odd', isset($practicum) ? $practicum->is_odd : '') == '1' ? 'selected' : '' }}>
                            Genap</option>
                        <option value="0"
                            {{ old('is_odd', isset($practicum) ? $practicum->is_odd : '') == '0' ? 'selected' : '' }}>
                            Ganjil</option>
                    </select>
                    @error('is_odd')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ isset($practicum) ? 'Update' : 'Create' }}
                </button>
            </form>
        </div>
    </div>
@endsection
