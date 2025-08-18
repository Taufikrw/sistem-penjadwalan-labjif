<form id="lab-form" action="{{ isset($laboratorium) ? route('lab.update', $laboratorium->id) : route('lab.store') }}"
    method="POST">
    @csrf
    @if (isset($laboratorium))
        @method('PUT')
    @endif

    <div id="title-hidden" class="hidden">
        <div class="flex gap-3 items-center">
            @isset($laboratorium)
                <x-heroicon-c-pencil-square class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Edit Laboratorium</span>
            @else
                <x-heroicon-c-plus class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Tambah Laboratorium</span>
            @endisset
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="name" class="mb-1 text-sm" value="Nama Laboratorium" />
            <x-text-input name="name" id="name" class="w-full" :value="old('name', isset($laboratorium) ? $laboratorium->name : '')"
                placeholder="Masukkan nama laboratorium" required />
        </div>

        <div>
            <x-input-label for="capacity" class="mb-1 text-sm" value="Kapasitas" />
            <x-text-input name="capacity" id="capacity" class="w-full" :value="old('capacity', isset($laboratorium) ? $laboratorium->capacity : '')"
                type="number" min="1" placeholder="Masukkan kapasitas laboratorium" required />
        </div>

        <div>
            <x-input-label for="location" class="mb-1 text-sm" value="Lokasi" />
            <x-select-input id="location_select" name="location" :options="['Pattimura I' => 'Pattimura I', 'Pattimura II' => 'Pattimura II', 'Pattimura III' => 'Pattimura III']" placeholder="Pilih lokasi laboratorium"
                :selected="isset($laboratorium) ? $laboratorium->location : null" />
        </div>
    </div>

    <div class="mt-8 flex justify-between items-center w-full">
        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-lab"
            onclick="closeModal()">
            Batal
        </x-button-secondary>
        <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-lab">
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
