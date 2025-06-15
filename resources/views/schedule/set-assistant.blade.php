@extends('layouts.app')

@section('title', 'Set Assistant')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex-col">
            <x-topbar />

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-error px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="p-10">
                <h1 class="text-3xl font-bold mb-4">Detail Jadwal</h1>
                <div class="border border-primary rounded-lg p-6">
                    <div class="grid grid-cols-6 gap-4">
                        <div class="text-lg font-semibold text-gray-700">Nama Praktikum</div>
                        <div class="text-md text-gray-600 col-span-5">{{ $schedule->practicum->name }} {{ $schedule->name }}</div>

                        <div class="text-lg font-semibold text-gray-700">Nama Dosen</div>
                        <div class="text-md text-gray-600 col-span-5">{{ $schedule->dosen }}</div>

                        <div class="text-lg font-semibold text-gray-700">Laboratorium</div>
                        <div class="text-md text-gray-600 col-span-5">{{ $schedule->laboratorium->name }}</div>

                        <div class="text-lg font-semibold text-gray-700">Jadwal</div>
                        <div class="text-md text-gray-600 col-span-5">{{ $schedule->day }} {{ $schedule->start_time->format('H:i') }} -
                            {{ $schedule->end_time->format('H:i') }}</div>
                    </div>
                </div>


                <h1 class="text-3xl font-bold mb-4 mt-6">Asisten</h1>
                <form action="{{ !empty($selectedAssistants) ? route('schedule.update-assistant', $schedule->id) : route('schedule.store-assistant', $schedule->id) }}" method="{{ !empty($selectedAssistants) ? 'POST' : 'POST' }}">
                    @csrf
                    @if (!empty($selectedAssistants))
                        @method('PUT')
                    @endif
                    <div class="mb-4">
                        <label class="block text-md font-medium text-gray-700 mb-4">Pilih asisten yang mengajar (Max: 2)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($assistants as $assistant)
                                <label class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-3 shadow-sm" for="assistant-{{ $assistant->nim }}">
                                    <input type="checkbox" name="assistants[]" value="{{ $assistant->nim }}"
                                        id="assistant-{{ $assistant->nim }}"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        onchange="limitSelection(this)"
                                        @if (in_array($assistant->nim, array_column($selectedAssistants, 'nim'))) checked @endif>
                                    <span class="ml-3 text-sm text-gray-700">
                                        {{ $assistant->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('assistants')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-8">
                        <button type="submit"
                            class="bg-secondary hover:bg-secondary-40 text-tertiary border-1 border-tertiary font-bold py-2 px-8 rounded">
                            @if (!empty($selectedAssistants))
                                Update
                            @else
                                Kirim
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function limitSelection(checkbox) {
            const checkboxes = document.querySelectorAll('input[name="assistants[]"]:checked');
            if (checkboxes.length > 2) {
                checkbox.checked = false;
                alert('You can only select up to 2 assistants.');
            }
        }
    </script>
@endpush
