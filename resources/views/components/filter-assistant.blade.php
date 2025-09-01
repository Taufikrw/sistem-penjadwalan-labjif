<div>
    <x-button-secondary
        class="flex justify-between items-center px-4 h-full text-md gap-3 rounded-xl border border-[#D9D9D9]"
        type="button" id="btn-filter-toggle">
        <div class="flex items-center gap-2">
            <x-heroicon-s-funnel class="w-4 h-4" />
            Filter
        </div>
        <span id="filter-badge"
            class="hidden bg-key-secondary text-white text-xs font-bold rounded-full px-2 py-0.5"></span>
    </x-button-secondary>

    <div id="filter-container"
        class="hidden absolute left-0 mt-2 w-120 rounded-lg border-[#D9D9D9] border bg-white shadow-lg z-10 overflow-hidden">
        <div class="flex items-center justify-between border-b border-[#D9D9D9] p-4 bg-[#FCFCFC]">
            <h3 class="font-bold text-key-primary">Filter</h3>
            <button id="btn-filter-close" class="text-gray-400 hover:text-gray-600 cursor-pointer" type="button">
                <x-heroicon-s-x-mark class="h-6 w-6" />
            </button>
        </div>

        <div class="space-y-6 p-4">
            <div>
                <div class="flex justify-between">
                    <label class="text-sm font-semibold text-[#B3B3B3] mb-2">Prodi</label>
                    <span id="clear-filter-prodi"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <div class="flex items-center gap-4 mt-1">
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="prodi"
                            value="Informatika" class="form-radio"> Informatika</label>
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="prodi"
                            value="Sistem Informasi" class="form-radio"> Sistem Informasi</label>
                </div>
            </div>
            <div>
                <div class="flex justify-between">
                    <label for="filter-angkatan" class="text-sm font-semibold text-[#B3B3B3] mb-2">Angkatan</label>
                    <span id="clear-filter-angkatan"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <x-select-input id="filter-angkatan" name="angkatan"
                    :options="collect($filters['angkatan'] ?? [])->mapWithKeys(fn($item) => [$item => $item])->toArray()"
                    placeholder="Pilih Angkatan" :selected="old('angkatan')" />
            </div>
            <div>
                <div class="flex justify-between">
                    <label for="filter-tahun-masuk" class="text-sm font-semibold text-[#B3B3B3] mb-2">Tahun Masuk</label>
                    <span id="clear-filter-tahun-masuk"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <x-select-input id="filter-tahun-masuk" name="tahun_masuk" :options="collect($filters['tahun_masuk'] ?? [])->mapWithKeys(fn($item) => [$item => $item])->toArray()"
                    placeholder="Pilih Tahun Masuk" :selected="old('tahun_masuk')" />
            </div>
            <div>
                <div class="flex justify-between">
                    <label class="text-sm font-semibold text-[#B3B3B3] mb-2">Status Aslab</label>
                    <span id="clear-filter-status"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <div class="flex items-center gap-4 mt-1">
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="status"
                            value="aktif" class="form-radio"> Aktif</label>
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="status"
                            value="non-aktif" class="form-radio"> Non-Aktif</label>
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="status"
                            value="selesai" class="form-radio"> Selesai</label>
                </div>
            </div>
            <div>
                <div class="flex justify-between">
                    <label class="text-sm font-semibold text-[#B3B3B3] mb-2">Jadwal Kuliah Aslab</label>
                    <span id="clear-filter-course"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <div class="flex items-center gap-4 mt-1">
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="course"
                            value="false" class="form-radio"> Belum Final</label>
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="course"
                            value="true" class="form-radio"> Sudah Final</label>
                </div>
            </div>
        </div>

        <div class="flex justify-between border-t border-[#D9D9D9] p-4">
            <x-button-secondary id="btn-filter-reset" type="button" class="rounded-xl px-6">Reset</x-button-secondary>
            <x-button-primary id="btn-filter-apply" type="button" class="rounded-xl px-6">Terapkan</x-button-primary>
        </div>
    </div>
</div>

<script type="module">
    $(document).ready(function() {
        function checkFilterFilled() {
            const prodi = $('input[name="prodi"]:checked').val();
            const angkatan = $('#hidden-filter-angkatan').val();
            const tahunMasuk = $('#hidden-filter-tahun-masuk').val();
            const status = $('input[name="status"]:checked').val();
            const course = $('input[name="course"]:checked').val();

            if (!prodi && !angkatan && !tahunMasuk && !status && !course) {
                $('#btn-filter-apply').prop('disabled', true).addClass('opacity-50 cursor-not-allowed')
                    .removeClass('cursor-pointer');
            } else {
                $('#btn-filter-apply').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed')
                    .addClass('cursor-pointer');
            }
        }

        checkFilterFilled();

        $('#hidden-filter-angkatan, #hidden-filter-tahun-masuk').on('change input', checkFilterFilled);
        $('input[name="prodi"], input[name="status"], input[name="course"]').on('change', checkFilterFilled);

        $('#btn-filter-reset').on('click', function() {
            setTimeout(function() {
                // Reset all filters
                $('input[name="prodi"]').prop('checked', false).trigger('change');
                $('#filter-angkatan').val('').trigger('change');
                $('#filter-tahun-masuk').val('').trigger('change');
                $('input[name="status"]').prop('checked', false).trigger('change');
                $('input[name="course"]').prop('checked', false).trigger('change');
                checkFilterFilled();
            }, 10);
        });

        // Clear per filter
        $('#clear-filter-prodi').on('click', function() {
            $('input[name="prodi"]').prop('checked', false).trigger('change');
        });
        $('#clear-filter-angkatan').on('click', function() {
            $('#hidden-filter-angkatan').val('').trigger('change');
            // Reset tampilan teks ke placeholder
            $('#selected-text-filter-angkatan')
                .text('Pilih Nama Angkatan')
                .removeClass('text-gray-900')
                .addClass('text-gray-500');
            // Tutup dropdown jika terbuka
            $('#options-filter-angkatan').addClass('hidden');
            $('#filter-angkatan').removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
        });
        $('#clear-filter-tahun-masuk').on('click', function() {
            $('#hidden-filter-tahun-masuk').val('').trigger('change');
            // Reset tampilan teks ke placeholder
            $('#selected-text-filter-tahun-masuk')
                .text('Pilih Nama Tahun Masuk')
                .removeClass('text-gray-900')
                .addClass('text-gray-500');
            // Tutup dropdown jika terbuka
            $('#options-filter-tahun-masuk').addClass('hidden');
            $('#filter-tahun-masuk').removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
        });
        $('#clear-filter-status').on('click', function() {
            $('input[name="status"]').prop('checked', false).trigger('change');
        });
        $('#clear-filter-course').on('click', function() {
            $('input[name="course"]').prop('checked', false).trigger('change');
        });
    });
</script>
