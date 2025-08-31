<div>
    <x-button-secondary
        class="flex justify-between items-center px-4 py-2 text-md gap-3 rounded-xl border border-transparent"
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
                    <label for="filter-practicum" class="text-sm font-semibold text-[#B3B3B3] mb-2">Mata
                        Kuliah</label>
                    <span id="clear-filter-practicum"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <x-select-input id="filter-practicum" name="practicum_name" :options="$filters['practicums']->pluck('name', 'name')->toArray()"
                    placeholder="Pilih Nama Praktikum" :selected="old('practicum_name', isset($schedule) ? $schedule->practicum_name : null)" />
            </div>
            <div>
                <div class="flex justify-between">
                    <label for="filter-lab" class="text-sm font-semibold text-[#B3B3B3] mb-2">Laboratorium</label>
                    <span id="clear-filter-lab"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <x-select-input id="filter-lab" name="laboratorium_name" :options="$filters['labs']->pluck('name', 'name')->toArray()"
                    placeholder="Pilih Nama Laboratorium" :selected="old('laboratorium_name', isset($schedule) ? $schedule->laboratorium_name : null)" />
            </div>
            <div>
                <div class="flex justify-between">
                    <label class="text-sm font-semibold text-[#B3B3B3] mb-2">Jam Praktikum</label>
                    <span id="clear-filter-time"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-1/2">
                        <label for="filter-start-time" class="text-sm font-semibold text-[#1E1E1E] block mb-2">Jam
                            Mulai</label>
                        <x-text-input type="time" name="start_time" id="filter-start-time" class="w-full"
                            :value="old('start_time', isset($schedule) ? $schedule->start_time->format('H:i') : '')" required />
                    </div>

                    <div class="w-1/2">
                        <label for="filter-end-time" class="text-sm font-semibold text-[#1E1E1E] block mb-2">Jam
                            Selesai</label>
                        <x-text-input type="time" name="end_time" id="filter-end-time" class="w-full"
                            :value="old('end_time', isset($schedule) ? $schedule->end_time->format('H:i') : '')" required />
                    </div>
                </div>
            </div>
            <div>
                <div class="flex justify-between">
                    <label class="text-sm font-semibold text-[#B3B3B3] mb-2">Aslab</label>
                    <span id="clear-filter-aslab"
                        class="text-sm text-key-secondary font-semibold cursor-pointer">Bersihkan</span>
                </div>
                <div class="flex items-center gap-4 mt-1">
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="assistant_count"
                            value="0" class="form-radio"> Belum ada</label>
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="assistant_count"
                            value="1" class="form-radio"> 1 orang</label>
                    <label class="flex items-center gap-2 text-[#1E1E1E]"><input type="radio" name="assistant_count"
                            value="2" class="form-radio"> 2 orang</label>
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
            const practicum = $('#hidden-filter-practicum').val();
            const lab = $('#hidden-filter-lab').val();
            const startTime = $('#filter-start-time').val();
            const endTime = $('#filter-end-time').val();
            const assistantCount = $('input[name="assistant_count"]:checked').val();

            if (!practicum && !lab && !startTime && !endTime && !assistantCount) {
                $('#btn-filter-apply').prop('disabled', true).addClass('opacity-50 cursor-not-allowed')
                    .removeClass('cursor-pointer');
            } else {
                $('#btn-filter-apply').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed')
                    .addClass('cursor-pointer');
            }
        }

        checkFilterFilled();

        $('#hidden-filter-practicum, #hidden-filter-lab, #filter-start-time, #filter-end-time').on(
            'change input',
            checkFilterFilled
        );
        $('input[name="assistant_count"]').on('change', checkFilterFilled);

        $('#btn-filter-reset').on('click', function() {
            setTimeout(checkFilterFilled, 10);
        });

        // Clear per filter
        $('#clear-filter-practicum').on('click', function() {
            // Reset value input hidden
            $('#hidden-filter-practicum').val('').trigger('change');
            // Reset tampilan teks ke placeholder
            $('#selected-text-filter-practicum')
                .text('Pilih Nama Praktikum')
                .removeClass('text-gray-900')
                .addClass('text-gray-500');
            // Tutup dropdown jika terbuka
            $('#options-filter-practicum').addClass('hidden');
            $('#filter-practicum').removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
        });

        // --- Perbaikan fitur bersihkan Lab ---
        $('#clear-filter-lab').on('click', function() {
            $('#hidden-filter-lab').val('').trigger('change');
            $('#selected-text-filter-lab')
                .text('Pilih Nama Laboratorium')
                .removeClass('text-gray-900')
                .addClass('text-gray-500');
            $('#options-filter-lab').addClass('hidden');
            $('#filter-lab').removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
        });
        $('#clear-filter-time').on('click', function() {
            $('#filter-start-time').val('').trigger('input');
            $('#filter-end-time').val('').trigger('input');
        });
        $('#clear-filter-aslab').on('click', function() {
            $('input[name="assistant_count"]').prop('checked', false).trigger('change');
        });
    });
</script>
