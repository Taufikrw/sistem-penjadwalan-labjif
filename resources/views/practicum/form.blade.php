<form id="form-practicum"
    action="{{ isset($practicum) ? route('practicum.update', $practicum->kode_praktikum) : route('practicum.store') }}"
    method="POST">
    @csrf
    @if (isset($practicum))
        @method('PUT')
    @endif

    <div>
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
            <label for="for_prodi" class="block text-gray-700 text-sm font-bold mb-2">Program Studi:</label>
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

        <div class="mb-4">
            <label for="semester" class="block text-gray-700 text-sm font-bold mb-2">Semester:</label>
            <input type="number" max="8" min="1" name="semester" id="semester"
                value="{{ old('semester', isset($practicum) ? $practicum->semester : '') }}"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('semester') border-red-500 @enderror"
                required>
            @error('semester')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
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
