@extends('layouts.app')

@section('title', 'Create Schedule')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <x-topbar />

            @if ($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-error px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->first('error') }}</span>
                </div>
            @endif

            <div class="p-10 flex-1">
                <h1 class="text-3xl font-bold mb-6">
                    @isset($schedule)
                        Edit Jadwal Praktikum
                    @else
                        Tambah Jadwal Praktikum
                    @endisset
                </h1>

                <form action="{{ isset($schedule) ? route('schedule.update', $schedule->id) : route('schedule.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($schedule))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <label for="kode_praktikum" class="block text-gray-700 text-sm font-bold mb-2">Nama
                                Praktikum</label>
                            <select name="kode_praktikum" id="kode_praktikum"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Pilih Nama Praktikum</option>
                                @foreach ($practicums as $practicum)
                                    <option value="{{ $practicum->kode_praktikum }}"
                                        {{ old('kode_praktikum', isset($schedule) ? $schedule->kode_praktikum : '') == $practicum->kode_praktikum ? 'selected' : '' }}>
                                        {{ $practicum->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_praktikum')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-3">
                            <label for="laboratorium_id"
                                class="block text-gray-700 text-sm font-bold mb-2">Laboratorium</label>
                            <select name="laboratorium_id" id="laboratorium_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Pilih Laboratorim</option>
                                @foreach ($labs as $lab)
                                    <option value="{{ $lab->id }}"
                                        {{ old('laboratorium_id', isset($schedule) ? $schedule->laboratorium_id : '') == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('laboratorium_id')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-3">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Kelas:</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', isset($schedule) ? $schedule->name : '') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama kelas (contoh : IF-A)"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-3">
                            <label for="dosen" class="block text-gray-700 text-sm font-bold mb-2">Nama Dosen:</label>
                            <input type="text" name="dosen" id="dosen"
                                value="{{ old('dosen', isset($schedule) ? $schedule->dosen : '') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('dosen') border-red-500 @enderror"
                                placeholder="Masukkan nama dosen"
                                required>
                            @error('dosen')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-3">
                            <label for="tahun_ajar" class="block text-gray-700 text-sm font-bold mb-2">Tahun Ajaran:</label>
                            <input type="number" min="2021" name="tahun_ajar" id="tahun_ajar"
                                value="{{ old('tahun_ajar', isset($schedule) ? $schedule->tahun_ajar : now()->year) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tahun_ajar') border-red-500 @enderror"
                                placeholder="Masukkan tahun ajar"
                                required>
                            @error('tahun_ajar')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="day" class="block text-gray-700 text-sm font-bold mb-2">Hari</label>
                            <select name="day" id="day"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Pilih Hari</option>
                                @foreach (App\Enums\Day::cases() as $day)
                                    <option value="{{ $day->value }}"
                                        {{ old('day', isset($schedule) ? $schedule->day->value : '') == $day->value ? 'selected' : '' }}>
                                        {{ $day->value }}</option>
                                @endforeach
                            </select>
                            @error('day')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Started Time</label>
                            <input type="time" name="start_time" id="start_time"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                value="{{ old('start_time', isset($schedule) ? $schedule->start_time->format('H:i') : '') }}"
                                required>
                            @error('start_time')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">End Time</label>
                            <input type="time" name="end_time" id="end_time"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                value="{{ old('end_time', isset($schedule) ? $schedule->end_time->format('H:i') : '') }}"
                                required>
                            @error('end_time')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit"
                            class="bg-secondary hover:bg-secondary-40 text-tertiary border-1 border-tertiary font-bold py-2 px-8 rounded">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
