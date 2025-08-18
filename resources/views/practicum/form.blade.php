<form id="practicum-form"
    action="{{ isset($practicum) ? route('practicum.update', $practicum->kode_praktikum) : route('practicum.store') }}"
    method="POST">
    @csrf
    @if (isset($practicum))
        @method('PUT')
    @endif

    <div id="title-hidden" class="hidden">
        <div class="flex gap-3 items-center">
            @isset($practicum)
                <x-heroicon-c-pencil-square class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Edit Praktikum</span>
            @else
                <x-heroicon-s-plus class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Tambah Praktikum</span>
            @endisset
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
            <x-input-label for="kode_praktikum" class="mb-1 text-sm" value="Kode Praktikum" />
            <x-text-input name="kode_praktikum" id="kode_praktikum"
                class="w-full" :value="old('kode_praktikum', isset($practicum) ? $practicum->kode_praktikum : '')"
                placeholder="Masukkan kode praktikum" required />
        </div>

        <div class="col-span-2">
            <x-input-label for="name" class="mb-1 text-sm" value="Mata Kuliah" />
            <x-text-input name="name" id="name"
                class="w-full" :value="old('name', isset($practicum) ? $practicum->name : '')"
                placeholder="Masukkan nama praktikum" required />
        </div>

        <div>
            <x-input-label for="for_prodi" class="mb-1 text-sm" value="Prodi" />
            <x-select-input id="for_prodi" name="for_prodi" :options="['Informatika' => 'Informatika', 'Sistem Informasi' => 'Sistem Informasi']" placeholder="Pilih program studi"
                :selected="isset($practicum) ? $practicum->for_prodi : null" />
        </div>

        <div>
            <x-input-label for="semester" class="mb-1 text-sm" value="Semester" />
            <x-text-input name="semester" id="semester" class="w-full" :value="old('semester', isset($practicum) ? $practicum->semester : '')"
                type="number" max="8" min="1" placeholder="Masukkan semester" required />
        </div>
    </div>

    <div class="mt-8 flex justify-between items-center w-full">
        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-practicum"
            onclick="closeModal()">
            Batal
        </x-button-secondary>
        <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-practicum">
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