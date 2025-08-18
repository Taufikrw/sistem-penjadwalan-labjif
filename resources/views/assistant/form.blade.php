<form id="assistant-form"
    action="{{ isset($assistant) ? route('assistant.update', $assistant->nim) : route('assistant.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($assistant))
        @method('PUT')
    @endif

    <div id="title-hidden" class="hidden">
        <div class="flex gap-3 items-center">
            @isset($assistant)
                <x-heroicon-c-pencil-square class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Edit Aslab</span>
            @else
                <x-heroicon-c-plus class="w-5 h-5 bg-key-primary text-white rounded" />
                <span>Tambah Aslab</span>
            @endisset
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
            <x-input-label for="name" class="mb-1 text-sm" value="Nama" />
            <x-text-input name="name" id="name" class="w-full" :value="old('name', isset($assistant) ? $assistant->name : '')"
                placeholder="Masukkan nama asisten" required />
        </div>
        <div>
            <x-input-label for="nim" class="mb-1 text-sm" value="NIM" />
            <x-text-input name="nim" id="nim" class="w-full" :value="old('nim', isset($assistant) ? $assistant->nim : '')"
                placeholder="Masukkan NIM asisten" required />
        </div>
        <div>
            <x-input-label for="prodi" class="mb-1 text-sm" value="Program Studi" />
            <x-select-input id="prodi_select" name="prodi" :options="['Sistem Informasi' => 'Sistem Informasi', 'Informatika' => 'Informatika']" placeholder="Pilih prodi asisten"
                :selected="isset($assistant) ? $assistant->prodi : null" />
        </div>
        <div>
            <x-input-label for="angkatan" class="mb-1 text-sm" value="Angkatan" />
            <x-text-input name="angkatan" id="angkatan" class="w-full" type="number" :value="old('angkatan', isset($assistant) ? $assistant->angkatan : '')"
                placeholder="Masukkan angkatan asisten" required />
        </div>
        <div>
            <x-input-label for="tahun_masuk" class="mb-1 text-sm" value="Tahun Masuk" />
            <x-text-input name="tahun_masuk" id="tahun_masuk" class="w-full" type="number" min="2021"
                :value="old('tahun_masuk', isset($assistant) ? $assistant->tahun_masuk : date('Y'))" placeholder="Masukkan tahun masuk asisten" required />
        </div>
        <div class="{{ isset($assistant) ? '' : 'hidden' }}">
            <x-input-label for="status" class="mb-1 text-sm" value="Status" />
            <x-select-input id="status_select" name="status" :options="['aktif' => 'Aktif', 'non-aktif' => 'Non-Aktif', 'selesai' => 'Selesai']" placeholder="Pilih status asisten"
                :selected="isset($assistant) ? $assistant->status : 'aktif'" />
        </div>
        @isset($assistant)
            <div>
                <x-input-label for="ubah-password" class="mb-1 text-sm" value="Ubah Kata Sandi" />
                <x-text-input name="ubah-password" id="ubah-password" class="w-full" type="password" :value="old('password')"
                    placeholder="Masukkan kata sandi baru" />
            </div>
        @endisset
        <div class="{{ isset($assistant) ? 'hidden' : '' }}">
            <x-input-label for="password" class="mb-1 text-sm" value="Kata Sandi" />
            <x-text-input name="password" id="password" class="w-full" type="password" :value="old('password')"
                placeholder="Masukkan kata sandi" />
        </div>
        <div class="{{ isset($assistant) ? 'hidden' : '' }}">
            <x-input-label for="password_confirmation" class="mb-1 text-sm" value="Konfirmasi Kata Sandi" />
            <x-text-input name="password_confirmation" id="password_confirmation" class="w-full" type="password"
                placeholder="Konfirmasi kata sandi" />
        </div>
    </div>
    <div class="mt-8 flex justify-between items-center w-full">
        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-assistant"
            onclick="closeModal()">
            Batal
        </x-button-secondary>
        <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-assistant">
            Simpan
        </x-button-primary>
    </div>
</form>

<script type="module">
    $(document).ready(function() {
        const title = $('#title-hidden').html();
        $('#title').html(title);

        $(document).on('input', '#nim', function() {
            const nimValue = $(this).val();
            $('#password').val(nimValue);
            $('#password_confirmation').val(nimValue);

            if (nimValue.length >= 5) {
                const angkatanDigits = nimValue.substring(3, 5);
                const angkatanYear = '20' + angkatanDigits;
                $('#angkatan').val(angkatanYear);
            } else {
                $('#angkatan').val('');
            }

            if (nimValue.length >= 3) {
                const kodeProdi = nimValue.substring(0, 3);
                let prodi = '';
                if (kodeProdi === '123') {
                    prodi = 'Informatika';
                } else if (kodeProdi === '124') {
                    prodi = 'Sistem Informasi';
                }
                if (prodi) {
                    $('#hidden-prodi_select').val(prodi).trigger('change');
                    $('#selected-text-prodi_select').text(prodi).removeClass('text-gray-500').addClass(
                        'text-gray-900');
                }
            }
        });
    });
</script>
