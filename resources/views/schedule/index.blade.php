<x-layouts.app>
    <x-slot:title>
        Jadwal Praktikum
    </x-slot>

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white h-11">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
            </span>
            <input type="text" placeholder="Cari..." id="search-schedule-list"
                class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl" type="button"
            id="btn-create-schedule-list">
            <x-heroicon-s-plus class="w-5 h-5" />
            Tambah
        </x-button-primary>
    </div>

    <div id="card-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
        <div class="cols-span-1 md:col-span-2 lg:col-span-5 py-12">
            <x-icon-spinner class="h-16 w-16 animate-spin mx-auto" />
        </div>
    </div>

    <x-modal modal-id="scheduleListModal">
        <x-slot:title>
            <x-heroicon-s-plus class="w-4 h-4" />
            <span>Tambah Daftar Jadwal Praktikum</span>
        </x-slot:title>

        <div class="w-full px-16 py-4 mb-4">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div id="stepper-1-circle"
                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold transition-all duration-300">
                        <span id="stepper-1-num">1</span>
                        <svg id="stepper-1-check" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span id="stepper-1-text" class="text-xs mt-2 font-semibold">Tahun Ajar</span>
                </div>

                <div id="stepper-line" class="flex-auto border-t-2 transition-all duration-300 mx-4"></div>

                <div class="flex flex-col items-center">
                    <div id="stepper-2-circle"
                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold transition-all duration-300">
                        <span>2</span>
                    </div>
                    <span id="stepper-2-text" class="text-xs mt-2">Jadwal Praktikum</span>
                </div>
            </div>
        </div>

        <form id="schedule-wizard-form" action="{{ route('schedule-list.store') }}" method="POST"
            class="flex flex-col justify-between h-full flex-1">
            @csrf

            <div id="step-1" class="h-full">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="semester" class="mb-1 text-sm" value="Semester" />
                        <x-select-input id="jenis_semester" name="jenis_semester" :options="[
                            'ganjil' => 'Gasal',
                            'genap' => 'Genap',
                        ]"
                            placeholder="Pilih Semester" :selected="old('jenis_semester')" />
                        <span id="error-jenis_semester" class="text-[#BA1A1A] text-sm mt-1 font-semibold"></span>
                    </div>

                    <div>
                        <x-input-label for="tahun_ajar1" class="mb-1 text-sm" value="Tahun Ajar" />
                        <div class="flex">
                            <div>
                                <x-text-input name="tahun_ajar1" id="tahun_ajar1" class="w-full" :value="old('tahun_ajar1')"
                                    type="number" min="2023" placeholder="Masukkan tahun ajaran" />
                            </div>
                            <x-heroicon-s-slash class="w-6 h-6 my-auto" />
                            <div>
                                <x-text-input name="tahun_ajar2" id="tahun_ajar2" class="w-full" :value="old('tahun_ajar2')"
                                    type="number" min="2023" placeholder="Masukkan tahun ajaran" />
                            </div>
                        </div>
                        <span id="error-tahun_ajar" class="text-[#BA1A1A] text-sm mt-1 font-semibold"></span>
                    </div>
                </div>
            </div>

            <div id="step-2" class="hidden">
                <div class="grid grid-cols-4 gap-4">
                    <div class="col-span-4">
                        <x-input-label for="kode_praktikum" class="mb-1 text-sm" value="Nama Praktikum" />
                        <x-select-input id="kode_praktikum" name="kode_praktikum" :options="$practicums->pluck('name', 'kode_praktikum')->toArray()"
                            placeholder="Pilih Nama Praktikum" :selected="old('kode_praktikum', isset($schedule) ? $schedule->kode_praktikum : null)" />
                        @error('kode_praktikum')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="dosen" class="mb-1 text-sm" value="Nama Dosen" />
                        <x-text-input name="dosen" id="dosen" class="w-full" :value="old('dosen', isset($schedule) ? $schedule->dosen : '')"
                            placeholder="Masukkan nama dosen" />
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="laboratorium_id" class="mb-1 text-sm" value="Nama Laboratorium" />
                        <x-select-input id="laboratorium_id" name="laboratorium_id" :options="$labs->pluck('name', 'id')->toArray()"
                            placeholder="Pilih Nama Laboratorium" :selected="old('laboratorium_id', isset($schedule) ? $schedule->laboratorium_id : null)" />
                        @error('kode_praktikum')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="name" class="mb-1 text-sm" value="Nama Kelas" />
                        <x-text-input name="name" id="name" class="w-full" :value="old('name', isset($schedule) ? $schedule->name : '')" type="text"
                            placeholder="Masukkan nama kelas (IF/SI-X)" />
                    </div>

                    <div>
                        <x-input-label for="day" class="mb-1 text-sm" value="Hari" />
                        <x-select-input id="day" name="day" :options="collect(App\Enums\Day::cases())
                            ->mapWithKeys(fn($day) => [$day->value => $day->value])
                            ->toArray()" placeholder="Pilih hari"
                            :selected="old('day', isset($schedule) ? $schedule->day->value : null)" />
                    </div>

                    <div>
                        <x-input-label for="start_time" class="mb-1 text-sm" value="Jam Mulai" />
                        <x-text-input type="time" name="start_time" id="start_time" class="w-full"
                            :value="old('start_time', isset($schedule) ? $schedule->start_time->format('H:i') : '')" />
                    </div>

                    <div>
                        <x-input-label for="end_time" class="mb-1 text-sm" value="Jam Selesai" />
                        <x-text-input type="time" name="end_time" id="end_time" class="w-full"
                            :value="old('end_time', isset($schedule) ? $schedule->end_time->format('H:i') : '')" />
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center w-full">
                <div>
                    <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-wizard">
                        Batal
                    </x-button-secondary>
                    <x-button-secondary class="rounded-xl py-3 px-6 hidden" type="button" id="btn-prev-step">
                        Kembali
                    </x-button-secondary>
                </div>

                <div>
                    <x-button-primary class="rounded-xl py-3 px-6" type="button" id="btn-next-step">
                        Lanjut
                    </x-button-primary>
                    <x-button-primary class="rounded-xl py-3 px-6 hidden" type="submit" id="btn-submit-wizard">
                        Simpan
                    </x-button-primary>
                </div>
            </div>
        </form>
    </x-modal>
</x-layouts.app>

<script type="module">
    $(document).ready(function() {
        const cardContainer = $('#card-container');
        const searchInput = $('#search-schedule-list');
        const wizardModal = $('#scheduleListModal');
        const modalContent = wizardModal.find('.modal-body');
        const wizardForm = $('#schedule-wizard-form');

        // Steps
        const step1 = $('#step-1');
        const step2 = $('#step-2');

        // Buttons
        const btnNext = $('#btn-next-step');
        const btnPrev = $('#btn-prev-step');
        const btnSubmit = $('#btn-submit-wizard');
        const btnCancel = $('#btn-cancel-wizard');
        const btnOpenModal = $('#btn-create-schedule-list');

        const stepper1 = {
            circle: $('#stepper-1-circle'),
            num: $('#stepper-1-num'),
            check: $('#stepper-1-check'),
            text: $('#stepper-1-text'),
        };
        const stepper2 = {
            circle: $('#stepper-2-circle'),
            text: $('#stepper-2-text'),
        };
        const stepperLine = $('#stepper-line');

        const activeCircleClass = 'bg-key-primary text-white';
        const inactiveCircleClass = 'bg-gray-200 text-gray-500';
        const completedCircleClass = 'bg-key-primary text-white'; // Sama dengan active, tapi ikon berubah
        const activeTextClass = 'text-key-primary font-semibold';
        const inactiveTextClass = 'text-gray-400';
        const activeLineClass = 'border-key-primary';
        const inactiveLineClass = 'border-gray-200';

        let currentStep = 1;

        function openModal() {
            resetWizard();
            $('#scheduleListModal').removeClass('hidden');
            setTimeout(() => {
                $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 10);
        }

        function updateStepperUI(step) {
            if (step === 1) {
                // Atur Step 1 sebagai aktif
                stepper1.circle.removeClass(inactiveCircleClass).addClass(activeCircleClass);
                stepper1.num.removeClass('hidden');
                stepper1.check.addClass('hidden');
                stepper1.text.removeClass(inactiveTextClass).addClass(activeTextClass);

                // Atur garis sebagai tidak aktif
                stepperLine.removeClass(activeLineClass).addClass(inactiveLineClass);

                // Atur Step 2 sebagai tidak aktif
                stepper2.circle.removeClass(activeCircleClass).addClass(inactiveCircleClass);
                stepper2.text.removeClass(activeTextClass).addClass(inactiveTextClass);
            } else if (step === 2) {
                // Atur Step 1 sebagai selesai
                stepper1.circle.removeClass(inactiveCircleClass).addClass(completedCircleClass);
                stepper1.num.addClass('hidden');
                stepper1.check.removeClass('hidden');
                stepper1.text.removeClass(inactiveTextClass).addClass(activeTextClass);

                // Atur garis sebagai aktif
                stepperLine.removeClass(inactiveLineClass).addClass(activeLineClass);

                // Atur Step 2 sebagai aktif
                stepper2.circle.removeClass(inactiveCircleClass).addClass(activeCircleClass);
                stepper2.text.removeClass(inactiveTextClass).addClass(activeTextClass);
            }
        }

        function resetWizard() {
            currentStep = 1;
            wizardForm[0].reset();

            wizardForm.find('[id^="error-"]').text('');
            $('.error-message').remove();

            $('input, textarea, select, button').removeClass('border-red-500 border-[#BA1A1A]');

            // Tampilkan step 1
            wizardForm.find('div[data-placeholder]').each(function() {
                const component = $(this);
                const placeholderText = component.data('placeholder');
                const selectedTextSpan = component.find('[id^="selected-text-"]');

                // Kembalikan teks ke placeholder awal
                selectedTextSpan.text(placeholderText);

                // Kembalikan warna teks ke warna placeholder
                selectedTextSpan.removeClass('text-gray-900').addClass('text-gray-500');
            });

            // 4. Atur ulang tampilan wizard ke langkah 1
            step1.removeClass('hidden');
            step2.addClass('hidden');

            btnNext.removeClass('hidden');
            btnPrev.addClass('hidden');
            btnSubmit.addClass('hidden');
            btnCancel.removeClass('hidden');

            updateStepperUI(1);
        }

        function goToStep(step) {
            currentStep = step;
            updateStepperUI(step);
            if (step === 1) {
                step1.removeClass('hidden');
                step2.addClass('hidden');

                btnNext.removeClass('hidden');
                btnSubmit.addClass('hidden');
                btnPrev.addClass('hidden');
            } else if (step === 2) {
                step1.addClass('hidden');
                step2.removeClass('hidden');

                btnNext.addClass('hidden');
                btnSubmit.removeClass('hidden');
                btnPrev.removeClass('hidden');
            }
        }

        function validateStep1() {
            let isValid = true;
            const tahun1 = $('#tahun_ajar1').val();
            const tahun2 = $('#tahun_ajar2').val();
            let semester = $('#hidden-jenis_semester').val();

            $('#error-jenis_semester').text('');
            $('#error-tahun_ajar').text('');
            $('#jenis_semester, #tahun_ajar1, #tahun_ajar2').removeClass('border-red-500');

            if (!tahun1) {
                $('#error-tahun_ajar').text('Tahun ajar tidak boleh kosong.');
                $('#tahun_ajar1').addClass('border-red-500');
                isValid = false;
            }
            if (!tahun2) {
                $('#error-tahun_ajar').text('Tahun ajar tidak boleh kosong.');
                $('#tahun_ajar2').addClass('border-red-500');
                isValid = false;
            }
            if (tahun1 && tahun2) {
                const numTahun1 = parseInt(tahun1);
                const numTahun2 = parseInt(tahun2);

                if (isNaN(numTahun1) || isNaN(numTahun2)) {
                    $('#error-tahun_ajar').text('Tahun ajar harus berupa angka.');
                    $('#tahun_ajar1, #tahun_ajar2').addClass('border-red-500');
                    isValid = false;
                } else if (numTahun2 !== numTahun1 + 1) {
                    $('#error-tahun_ajar').text('Tahun ajar kedua harus satu tahun setelah tahun pertama.');
                    $('#tahun_ajar1, #tahun_ajar2').addClass('border-red-500');
                    isValid = false;
                }
            }
            if (!semester) {
                $('#error-jenis_semester').text('Semester wajib dipilih.');
                $('#jenis_semester').addClass('border-red-500'); // Asumsi select input bisa diberi border
                isValid = false;
            }
            return isValid;
        }

        function loadCard() {
            $.ajax({
                url: '/api/get-year-schedule-list',
                method: 'GET',
                success: function(data) {
                    cardContainer.empty();
                    if (!data.data || data.data.length === 0) {
                        cardContainer.append(`
                                <div
                                    class="rounded-xl bg-white cols-span-1 md:col-span-2 lg:col-span-5 p-4 flex flex-col py-12 justify-center items-center">
                                    <x-icon-no-data class="w-60 mx-auto" />
                                    <span class="font-bold text-sm text-key-primary">Tidak ada data</span>
                                </div>
                            `);
                    } else {
                        data.data.forEach(item => {
                            const card = `
                                    <div class="rounded-xl bg-white p-4 h-40 flex flex-col justify-between border border-[#C9C6C5]">
                                        <div class="year-list text-key-primary">
                                            <h3 class="font-bold text-lg capitalize">Praktikum ${item.semester}</h3>
                                            <p class="text-gray-600">${item.tahun_ajaran}</p>
                                        </div>
                                        <a href="/schedule-detail?semester=${encodeURIComponent(item.semester)}&tahun_ajar=${encodeURIComponent(item.tahun_ajar)}" class="w-full">
                                            <x-button-secondary class="flex items-center px-5 py-3 text-md gap-2 rounded-xl w-full"
                                                type="button" id="btn-create-course">
                                                <x-heroicon-s-eye class="w-5 h-5" />
                                                Detail
                                            </x-button-secondary>
                                        </a>
                                    </div>
                                `;
                            cardContainer.append(card);
                        });
                    }
                }
            });
        }

        loadCard();

        if (searchInput) {
            searchInput.on('input', function() {
                const searchValue = $(this).val().toLowerCase();
                cardContainer.children().each(function() {
                    const yearListText = $(this).find('.year-list').text().toLowerCase();
                    if (yearListText.includes(searchValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        }

        btnOpenModal.on('click', function() {
            openModal();
        });

        btnNext.on('click', function() {
            if (validateStep1()) {
                goToStep(2);
            }
        });

        btnPrev.on('click', function() {
            goToStep(1);
        });

        wizardForm.on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                showCancelButton: false,
                showConfirmButton: false,
                width: '420px',
                customClass: {
                    popup: 'my-swal-popup'
                },
                html: `
                    <div class="flex flex-col items-center justify-between h-full">
                        <div class="my-8">
                            <div class="mb-4">
                                <x-heroicon-s-exclamation-circle class="w-16 h-16 text-[#FF8D28] mx-auto" />
                            </div>
                            <div class="font-bold text-key-primary text-lg mb-2">Konfirmasi</div>
                            <div class="text-black font-semibold mb-4">Anda akan menyimpan jadwal praktikum untuk semester baru. Tindakan ini akan mereset semua jadwal Aslab yang ada.</div>
                            <div class="text-black font-semibold mb-4">Aksi ini tidak dapat dibatalkan, Yakin ingin melanjutkan?</div>
                        </div>
                        <div class="flex gap-2 justify-between w-full">
                            <x-button-secondary id="swal-cancel-btn" type="button" class="rounded-xl py-3 px-6 text-sm">Periksa kembali</x-button-secondary>
                            <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">Ya, Simpan</x-button-primary>
                        </div>
                    </div>
                    <style>
                        .my-swal-popup {
                            min-height: 300px;
                            border-radius: 1.5rem !important;
                            overflow-y: auto;
                        }
                    </style>
                `,
                didOpen: () => {
                    $('#swal-cancel-btn').on('click',
                        function() {
                            Swal.close();
                        });
                    $('#swal-confirm-btn').on('click',
                        function() {
                            Swal.clickConfirm();
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = $(this).serialize();

                    $.ajax({
                        url: wizardForm.attr('action'),
                        method: 'POST',
                        data: formData,
                        beforeSend: function() {
                            btnSubmit.html(`
                                    <div class="flex justify-center items-center gap-2">
                                        <x-icon-spinner class="h-5 w-5 animate-spin" />
                                        Menyimpan...
                                    </div>
                                `).prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    const tahun1 = $('#tahun_ajar1').val();
                                    const tahun2 = $('#tahun_ajar2').val();
                                    const semester = $(
                                        '#hidden-jenis_semester').val();

                                    const tahunAjarRedirect = (semester ===
                                            'ganjil') ?
                                        tahun1 : tahun2;
                                    console.log(semester,
                                    tahunAjarRedirect);
                                    window.location.href =
                                        `/schedule-detail?semester=${encodeURIComponent(semester)}&tahun_ajar=${encodeURIComponent(tahunAjarRedirect)}`;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan!',
                                    text: response.message ||
                                        'Maaf, terjadi kesalahan pada server. Silakan coba lagi.',
                                    showConfirmButton: true
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) { // Error validasi dari Laravel
                                let errors = xhr.responseJSON
                                    .errors;
                                wizardForm.find('.text-red-500').text('');
                                $.each(errors, function(key,
                                    value) {
                                    let inputElement =
                                        $(
                                            `[name="${key}"]`
                                        );
                                    inputElement
                                        .addClass(
                                            'border-[#BA1A1A]'
                                        );
                                    inputElement.closest('.relative').after(
                                        `<p class="error-message text-[#BA1A1A] text-sm mt-1 font-semibold">${value[0]}</p>`
                                    );
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Validasi!',
                                    text: xhr
                                        .responseJSON
                                        .message ||
                                        'Mohon periksa kembali input Anda.',
                                    showConfirmButton: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan!',
                                    text: xhr
                                        .responseJSON
                                        .message ||
                                        'Maaf, terjadi kesalahan pada server. Silakan coba lagi.',
                                    showConfirmButton: true
                                });
                            }
                        },
                        complete: function() {
                            // Kembalikan tombol ke keadaan normal
                            btnSubmit.prop('disabled', false).html('Simpan');
                        }
                    });
                }
            });
        });
    });
</script>
