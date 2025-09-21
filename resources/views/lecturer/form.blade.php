<form id="lecturer-form" action="{{ isset($lecturer) ? route('lecturer.update', $lecturer->id) : route('lecturer.store') }}"
    method="POST">
    @csrf
    @if (isset($lecturer))
        @method('PUT')
    @endif

    <div id="title-hidden" class="hidden">
        <div class="flex gap-3 items-center">
            @isset($lecturer)
                <x-heroicon-c-pencil-square class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Edit Dosen</span>
            @else
                <x-heroicon-c-plus class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Tambah Dosen</span>
            @endisset
        </div>
    </div>

    <div class="grid gap-4">
        <div>
            <x-input-label for="nip" class="mb-1 text-sm" value="NIP Dosen" />
            <x-text-input name="nip" id="nip" class="w-full" :value="old('nip', isset($lecturer) ? $lecturer->nip : '')"
                placeholder="Masukkan NIP dosen" required />
        </div>

        <div>
            <x-input-label for="name" class="mb-1 text-sm" value="Nama Dosen" />
            <x-text-input name="name" id="name" class="w-full" :value="old('name', isset($lecturer) ? $lecturer->name : '')"
                placeholder="Masukkan nama dosen" required />
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
