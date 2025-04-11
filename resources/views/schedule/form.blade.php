@extends('layouts.app')

@section('title', 'Create Schedule')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg mb-4">Tambah Jadwal</h1>
            @if ($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">{{ $errors->first('error') }}</span>
                </div>
            @endif

            <form action="{{ isset($schedule) ? route('schedule.update', $schedule->id) : route('schedule.store') }}" method="POST">
                @csrf
                @if (isset($schedule))
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label for="kode_praktikum" class="block text-gray-700 text-sm font-bold mb-2">Nama Praktikum</label>
                    <select name="kode_praktikum" id="kode_praktikum"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="">Select Practicum</option>
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
                
                <div class="mb-4">
                    <label for="room_id" class="block text-gray-700 text-sm font-bold mb-2">Laboratorium</label>
                    <select name="room_id" id="room_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="">Select Room</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}"
                                {{ old('room_id', isset($schedule) ? $schedule->room_id : '') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Kelas:</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', isset($schedule) ? $schedule->name : '') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="day" class="block text-gray-700 text-sm font-bold mb-2">Day</label>
                    <select name="day" id="day"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="">Select Day</option>
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

                <div class="mb-4">
                    <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Started Time</label>
                    <input type="time" name="start_time" id="start_time"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ old('start_time', isset($schedule) ? $schedule->start_time->format('H:i') : '') }}"
                        required>
                    @error('start_time')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">End Time</label>
                    <input type="time" name="end_time" id="end_time"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ old('end_time', isset($schedule) ? $schedule->end_time->format('H:i') : '') }}"
                        required>
                    @error('end_time')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ isset($schedule) ? 'Update' : 'Create' }}
                </button>
            </form>
        </div>
    </div>
@endsection
