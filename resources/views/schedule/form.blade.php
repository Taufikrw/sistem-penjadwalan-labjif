<form id="schedule-form" action="{{ isset($schedule) ? route('schedule.update', $schedule->id) : route('schedule.store') }}"
    method="POST">
    @csrf
    @if (isset($schedule))
        @method('PUT')
    @endif

    <div id="title-hidden" class="hidden">
        <div class="flex gap-3 items-center">
            @isset($schedule)
                <x-heroicon-s-pencil-square class="w-4 h-4" />
                <span>Edit Jadwal Praktikum</span>
            @else
                <x-heroicon-s-plus class="w-4 h-4" />
                <span>Tambah Jadwal Praktikum</span>
            @endisset
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
            <x-input-label for="kode_praktikum" class="mb-1 text-sm" value="Nama Praktikum" />
            <x-select-input
                id="kode_praktikum"
                name="kode_praktikum"
                :options="$practicums->pluck('name', 'kode_praktikum')->toArray()"
                placeholder="Pilih Nama Praktikum"
                :selected="old('kode_praktikum', isset($schedule) ? $schedule->kode_praktikum : null)"
                required
            />
            @error('kode_praktikum')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-2">
            <x-input-label for="laboratorium_id" class="mb-1 text-sm" value="Nama Laboratorium" />
            <x-select-input
                id="laboratorium_id"
                name="laboratorium_id"
                :options="$labs->pluck('name', 'id')->toArray()"
                placeholder="Pilih Nama Laboratorium"
                :selected="old('laboratorium_id', isset($schedule) ? $schedule->laboratorium_id : null)"
                required
            />
            @error('kode_praktikum')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-2">
            <x-input-label for="dosen" class="mb-1 text-sm" value="Nama Dosen" />
            <x-text-input name="dosen" id="dosen" class="w-full" :value="old('dosen', isset($schedule) ? $schedule->dosen : '')"
                placeholder="Masukkan nama dosen" required />
        </div>

        <div>
            <x-input-label for="name" class="mb-1 text-sm" value="Nama Kelas" />
            <x-text-input name="name" id="name" class="w-full" :value="old('name', isset($schedule) ? $schedule->name : '')"
                type="text" placeholder="Masukkan nama kelas (IF/SI-X)" required />
        </div>

        <div>
            <x-input-label for="day" class="mb-1 text-sm" value="Hari" />
            <x-select-input id="day" name="day" :options="collect(App\Enums\Day::cases())
                ->mapWithKeys(fn($day) => [$day->value => $day->value])
                ->toArray()" placeholder="Pilih hari" :selected="old('day', isset($schedule) ? $schedule->day->value : $dayForm)"
                required />
        </div>

        <div>
            <x-input-label for="start_time" class="mb-1 text-sm" value="Jam Mulai" />
            <x-text-input type="time" name="start_time" id="start_time" class="w-full" :value="old('start_time', isset($schedule) ? $schedule->start_time->format('H:i') : '')" required />
        </div>

        <div>
            <x-input-label for="end_time" class="mb-1 text-sm" value="Jam Selesai" />
            <x-text-input type="time" name="end_time" id="end_time" class="w-full" :value="old('end_time', isset($schedule) ? $schedule->end_time->format('H:i') : '')" required />
        </div>

        <input type="hidden" name="tahun_ajar" id="tahun_ajar" value="{{ old('tahun_ajar', isset($schedule) ? $schedule->tahun_ajar : $tahunAjar) }}">
        <input type="hidden" name="jenis_semester" id="jenis_semester" value="{{ old('jenis_semester', isset($schedule) ? $schedule->jenis_semester : $semester) }}">
    </div>

    <div class="mt-8 flex justify-between items-center w-full">
        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-schedule"
            onclick="closeModal()">
            Batal
        </x-button-secondary>
        <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-schedule">
            Simpan
        </x-button-primary>
    </div>
</form>

<script type="module">
    $(document).ready(function() {
        const title = $('#title-hidden').html();
        $('#title').html(title);

    });
</script>


{{-- @extends('layouts.app')

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
@endsection --}}
