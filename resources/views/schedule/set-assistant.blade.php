@extends('layouts.app')

@section('title', 'Set Assistant')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Schedule Details</h1>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Name</h2>
                    <p class="text-sm text-gray-600">{{ $schedule->practicum->name }} {{ $schedule->name }}</p>
                </div>
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Lab</h2>
                    <p class="text-sm text-gray-600">{{ $schedule->room->name }}</p>
                </div>
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Tanggal</h2>
                    <p class="text-sm text-gray-600">{{ $schedule->day }} {{ $schedule->start_time->format('H:i') }} -
                        {{ $schedule->end_time->format('H:i') }}</p>
                </div>
            </div>


            <h1 class="text-2xl font-bold mb-4 mt-6">Set Assistant</h1>
            <form action="{{ isset($assistantSchedules) ? route('schedule.update-assistant', $schedule->id) : route('schedule.store-assistant', $schedule->id) }}" method="POST">
                @csrf
                @if (isset($assistantSchedules))
                    @method('PUT')
                @endif
                <div class="mb-4">
                    <label for="assistant1" class="block text-sm font-medium text-gray-700 mb-2">Select Assistant 1</label>
                    <select name="assistants[0][nim]" id="assistant1"
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Select Assistant</option>
                        @foreach ($assistants as $assistant)
                            <option value="{{ $assistant->nim }}"
                                {{ old('assistants.0.nim', $assistantSchedules[0]->nim ?? '') == $assistant->nim ? 'selected' : '' }}>
                                {{ $assistant->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="exists[0][id]" value="{{ $assistantSchedules[0]->id ?? '' }}">
                    @error('assistants.0.nim')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="assistant2" class="block text-sm font-medium text-gray-700 mb-2">Select Assistant 2</label>
                    <select name="assistants[1][nim]" id="assistant2"
                        class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Select Assistant</option>
                        @foreach ($assistants as $assistant)
                            <option value="{{ $assistant->nim }}"
                                {{ old('assistants.1.nim', $assistantSchedules[1]->nim ?? '') == $assistant->nim ? 'selected' : '' }}>
                                {{ $assistant->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="exists[1][id]" value="{{ $assistantSchedules[1]->id ?? '' }}">

                    @error('assistants.1.nim')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    {{ isset($assistantSchedules) ? 'Update' : 'Set' }} Assistant</button>
            </form>
        </div>
    </div>
@endsection
