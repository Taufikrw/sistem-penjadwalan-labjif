@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <x-topbar />

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-error px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="p-10 flex-1">
                <h1 class="text-3xl font-bold mb-6">
                    @isset($courseItem)
                        Edit Jadwal Kuliah
                    @else
                        Tambah Jadwal Kuliah
                    @endisset
                </h1>

                <form
                    action="{{ isset($courseItem) ? route('course.update', [$assistant->nim, $courseItem->id]) : route('course.store', $assistant->nim) }}"
                    method="POST">
                    @csrf
                    @if (isset($courseItem))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-3">
                            <label for="course" class="block text-gray-700 text-sm font-bold mb-2">Course</label>
                            <select name="course" id="course"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Select Course</option>
                                @foreach (App\Enums\Course::cases() as $course)
                                    <option value="{{ $course->value }}" data-prodi="{{ $course->prodi() }}"
                                        {{ old('course', isset($courseItem) ? $courseItem->course->value : '') == $course->value ? 'selected' : '' }}>
                                        {{ $course->value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-3">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <div class="flex items-center">
                                @if (!isset($courseItem))
                                    <span id="prodi-prefix"
                                        class="shadow appearance-none border border-r-0 rounded-l w-16 py-2 px-3 text-gray-700 bg-gray-200 leading-tight text-center">
                                        IF-
                                    </span>
                                @endif

                                <input type="text" name="name" id="name"
                                    maxlength="{{ isset($courseItem) ? '4' : '1' }}"
                                    class="shadow appearance-none border rounded-r w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase"
                                    value="{{ old('name', isset($courseItem) ? $courseItem->name : '') }}" required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="day" class="block text-gray-700 text-sm font-bold mb-2">Day</label>
                            <select name="day" id="day"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Select Day</option>
                                @foreach (App\Enums\Day::cases() as $day)
                                    <option value="{{ $day->value }}"
                                        {{ old('day', isset($courseItem) ? $courseItem->day->value : '') == $day->value ? 'selected' : '' }}>
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
                                value="{{ old('start_time', isset($courseItem) ? $courseItem->start_time->format('H:i') : '') }}"
                                required>
                            @error('start_time')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">End Time</label>
                            <input type="time" name="end_time" id="end_time"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                value="{{ old('end_time', isset($courseItem) ? $courseItem->end_time->format('H:i') : '') }}"
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

@push('scripts')
    <script>
        document.getElementById('course').addEventListener('change', function() {
            const selectedCourse = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const prodi = selectedOption.getAttribute('data-prodi');
            const prefix = prodi === 'Teknik Informatika' ? 'IF-' : 'SI-';
            document.getElementById('prodi-prefix').textContent = prefix;
        });
    </script>
@endpush
