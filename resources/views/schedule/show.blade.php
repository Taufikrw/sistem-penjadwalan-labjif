<x-layouts.app>
    @if ($semester && $tahunAjaran)
        <x-slot name="title">{{ Str::title('Jadwal Praktikum ' . $semester . ' ' . $tahunAjaran) }}</x-slot>
    @else
        <x-slot name="title">Jadwal Praktikum</x-slot>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative flex items-center">
                <x-filter type="schedule" :filters="$filters ?? []" />
            </div>
            <div class="relative w-full md:w-80 bg-white h-11">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
                </span>
                <input type="text" placeholder="Cari..." id="search-schedule"
                    class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
            </div>
        </div>
        @if (Auth::user()->role === 'admin')
            <div class="flex items-center gap-4">
                <button
                    class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl bg-[#1E49F0] hover:bg-[#0031C5] text-white font-bold focus:outline-none cursor-pointer transition-colors duration-300"
                    type="button" id="btn-generate-schedule" data-url="{{ route('schedules.generateAssistants') }}">
                    <x-heroicon-s-cog-6-tooth class="w-5 h-5" />
                    Generate
                </button>
                <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl"
                    type="button" id="btn-create-schedule" onclick="showDynamicModal()">
                    <x-heroicon-s-plus class="w-5 h-5" />
                    Tambah
                </x-button-primary>
                <div id="deleted-info" class="items-center gap-6 hidden">
                    <div id="selected-info" class="text-key-primary font-bold">
                        0 Dipilih
                    </div>
                    <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl"
                        type="button" id="btn-bulk-delete">
                        <x-heroicon-s-trash class="w-5 h-5" />
                        Hapus
                    </x-button-primary>
                </div>
            </div>
        @endif
    </div>

    <div class="-mb-2">
        <div class="flex space-x-2">
            @foreach (['Semua', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                <a href="{{ request()->fullUrlWithQuery(['day' => $hari]) }}" class="mx-4">
                    <button type="button"
                        class="py-2 transition w-full
                            hover:text-key-primary hover:border-b-2 hover:border-key-primary
                            {{ $day === $hari ? 'text-key-primary border-b-2 font-bold border-key-primary' : 'text-gray-600 cursor-pointer' }}"
                        {{ $day === $hari ? 'disabled' : '' }}>
                        {{ $hari }}
                    </button>
                </a>
            @endforeach
        </div>
        <hr class="text-gray-300">
    </div>

    @if ($day === 'Semua')
        <x-data-table url="/api/get-schedule-table" :filters="['jenis_semester' => $semester, 'tahun_ajar' => $tahunAjar, 'day' => $day]" action-url="schedule/" :columns="[
            ['label' => 'Mata Kuliah', 'field' => 'practicum_name', 'sortable' => true],
            ['label' => 'Kelas', 'field' => 'class_name', 'sortable' => true],
            ['label' => 'Lab', 'field' => 'laboratorium_name', 'sortable' => false],
            ['label' => 'Dosen', 'field' => 'dosen', 'sortable' => false],
            ['label' => 'Hari', 'field' => 'day', 'sortable' => false],
            ['label' => 'Jam', 'field' => 'jam', 'sortable' => true],
            ['label' => 'Aslab', 'field' => 'assistant_names', 'sortable' => false],
        ]"
            has-actions="{{ Auth::user()->role === 'admin' ? true : false }}" table-id="schedule-table"
            search-input-id="search-schedule" btn-create-id="btn-create-schedule" :has-setAssistant="true" />
    @else
        <x-data-table url="/api/get-schedule-table" :filters="['jenis_semester' => $semester, 'tahun_ajar' => $tahunAjar, 'day' => $day]" action-url="schedule/" :columns="[
            ['label' => 'Mata Kuliah', 'field' => 'practicum_name', 'sortable' => true],
            ['label' => 'Kelas', 'field' => 'class_name', 'sortable' => true],
            ['label' => 'Lab', 'field' => 'laboratorium_name', 'sortable' => false],
            ['label' => 'Dosen', 'field' => 'dosen', 'sortable' => false],
            ['label' => 'Jam', 'field' => 'jam', 'sortable' => true],
            ['label' => 'Aslab', 'field' => 'assistant_names', 'sortable' => false],
        ]"
            has-actions="{{ Auth::user()->role === 'admin' ? true : false }}" table-id="schedule-table"
            search-input-id="search-schedule" btn-create-id="btn-create-schedule" :has-setAssistant="true" />
    @endif

    <x-form-modal modal-id="scheduleModal" ajax-url="{{ route('schedule.create') }}" :params="['tahun_ajar' => $tahunAjar, 'jenis_semester' => $semester, 'day' => $day]"
        action-url="schedule/" form-id="schedule-form" />

    <x-modal modal-id="setAssistantModal">
        <x-slot:title>
            <x-heroicon-c-users class="w-5 h-5 bg-key-primary text-white rounded p-0.5" />
            <span>Pilih Aslab</span>
        </x-slot:title>
    </x-modal>

    <script type="module">
        $(document).ready(function() {
            const generateBtn = $('#btn-generate-schedule');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            const urlParams = new URLSearchParams(window.location.search);
            const tahunAjar = urlParams.get('tahun_ajar') || '{{ $tahunAjar }}';
            const jenisSemester = urlParams.get('semester') || '{{ $semester }}';

            generateBtn.on('click', function() {
                const generateUrl = $(this).data('url');

                Swal.fire({
                    showCancelButton: false,
                    showConfirmButton: false,
                    width: '430px',
                    customClass: {
                        popup: 'my-swal-popup'
                    },
                    html: `
                        <div class="flex flex-col items-center justify-between h-full">
                            <div class="mt-8">
                                <div class="mb-4">
                                    <x-heroicon-s-question-mark-circle class="w-16 h-16 text-[#FFA41C] mx-auto" />
                                </div>
                                <div class="font-bold text-key-primary text-lg mb-2">Generate Data</div>
                                <div class="text-black font-semibold mb-4">
                                    Data ini tidak dapat dikembalikan setelah di-generate ulang.
                                </div>
                            </div>
                            <div class="flex gap-2 justify-between w-full mt-8">
                                <x-button-secondary id="swal-cancel-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                    Batal
                                </x-button-secondary>
                                <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                    Ya, Generate
                                </x-button-primary>
                            </div>
                        </div>
                        <style>
                            .my-swal-popup {
                                min-height: 300px;
                                max-height: 90vh;
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
                        Swal.fire({
                            customClass: {
                                popup: 'my-swal-popup'
                            },
                            html: `
                                <div class="flex flex-col items-center justify-center py-6">
                                    <x-icon-spinner class="h-16 w-16 animate-spin mb-6" />
                                    <div class="font-bold text-key-primary text-lg mb-4">Sedang Generate</div>
                                    <div>Sistem sedang membuat jadwal asisten secara otomatis...</div>
                                </div>
                                <style>.my-swal-popup { border-radius: 1.5rem !important; }</style>
                            `,
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });

                        $.ajax({
                            url: generateUrl,
                            type: 'POST',
                            contentType: 'application/json',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            data: JSON.stringify({
                                tahun_ajar: tahunAjar,
                                jenis_semester: jenisSemester
                            }),
                            success: function(data) {
                                Swal.close();
                                if (data.status === 'success') {
                                    Swal.fire({
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        width: '430px',
                                        customClass: {
                                            popup: 'my-swal-popup'
                                        },
                                        html: `
                                            <div class="flex flex-col items-center justify-between h-full">
                                                <div class="mt-8">
                                                    <div class="mb-4">
                                                        <x-heroicon-s-check-circle class="w-16 h-16 text-green-400 mx-auto" />
                                                    </div>
                                                    <div class="font-bold text-key-primary text-lg mb-2">Berhasil!</div>
                                                    <div class="text-black font-semibold mb-4">
                                                        ${data.message}
                                                    </div>
                                                </div>
                                                <div class="flex justify-center w-full mt-6">
                                                    <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                                        Oke
                                                    </x-button-primary>
                                                </div>
                                            </div>
                                            <style>
                                                .my-swal-popup {
                                                    min-height: 200px;
                                                    max-height: 90vh;
                                                    border-radius: 1.5rem !important;
                                                    overflow-y: auto;
                                                }
                                            </style>
                                        `,
                                        didOpen: () => {
                                            $('#swal-confirm-btn').on(
                                                'click',
                                                function() {
                                                    Swal.clickConfirm();
                                                });
                                        }
                                    }).then(() => {
                                        if (typeof window.reloadTable ===
                                            'function') {
                                            window.reloadTable(
                                                'schedule-table');
                                        } else {
                                            window.location.reload();
                                        }
                                    });
                                } else if (data.status === 'warning') {
                                    Swal.fire({
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        width: '430px',
                                        customClass: {
                                            popup: 'my-swal-popup'
                                        },
                                        html: `
                                            <div class="flex flex-col items-center justify-between h-full">
                                                <div class="mt-8">
                                                    <div class="mb-4">
                                                        <x-heroicon-s-exclamation-triangle class="w-16 h-16 text-[#FFCC00] mx-auto" />
                                                    </div>
                                                    <div class="font-bold text-key-primary text-lg mb-2">Berhasil Disimpan dengan Pengecualian!</div>
                                                    <div class="text-black font-semibold mb-4">
                                                        ${data.message}
                                                    </div>
                                                </div>
                                                <div class="flex justify-center w-full mt-6">
                                                    <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                                        Oke
                                                    </x-button-primary>
                                                </div>
                                            </div>
                                            <style>
                                                .my-swal-popup {
                                                    min-height: 200px;
                                                    max-height: 90vh;
                                                    border-radius: 1.5rem !important;
                                                    overflow-y: auto;
                                                }
                                            </style>
                                        `,
                                        didOpen: () => {
                                            $('#swal-confirm-btn').on(
                                                'click',
                                                function() {
                                                    Swal.clickConfirm();
                                                });
                                        }
                                    }).then(() => {
                                        if (typeof window.reloadTable ===
                                            'function') {
                                            window.reloadTable(
                                                'schedule-table');
                                        } else {
                                            window.location.reload();
                                        }
                                    });

                                } else {
                                    Swal.fire({
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        width: '430px',
                                        customClass: {
                                            popup: 'my-swal-popup'
                                        },
                                        html: `
                                            <div class="flex flex-col items-center justify-between h-full">
                                                <div class="mt-8">
                                                    <div class="mb-4">
                                                        <x-heroicon-s-x-circle class="w-16 h-16 text-[#BA1A1A] mx-auto" />
                                                    </div>
                                                    <div class="font-bold text-key-primary text-lg mb-2">Error!</div>
                                                    <div class="text-black font-semibold mb-4">
                                                        Terjadi kesalahan saat generate data. Silakan coba lagi.
                                                    </div>
                                                </div>
                                                <div class="flex justify-center w-full mt-6">
                                                    <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                                        Oke
                                                    </x-button-primary>
                                                </div>
                                            </div>
                                            <style>
                                                .my-swal-popup {
                                                    min-height: 200px;
                                                    max-height: 90vh;
                                                    border-radius: 1.5rem !important;
                                                    overflow-y: auto;
                                                }
                                            </style>
                                        `,
                                        didOpen: () => {
                                            $('#swal-confirm-btn').on(
                                                'click',
                                                function() {
                                                    Swal.clickConfirm();
                                                });
                                        }
                                    }).then(() => {
                                        if (typeof window.reloadTable ===
                                            'function') {
                                            window.reloadTable(
                                                'schedule-table');
                                        } else {
                                            window.location.reload();
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    width: '430px',
                                    customClass: {
                                        popup: 'my-swal-popup'
                                    },
                                    html: `
                                        <div class="flex flex-col items-center justify-between h-full">
                                            <div class="mt-8">
                                                <div class="mb-4">
                                                    <x-heroicon-s-x-circle class="w-16 h-16 text-[#BA1A1A] mx-auto" />
                                                </div>
                                                <div class="font-bold text-key-primary text-lg mb-2">Error!</div>
                                                <div class="text-black font-semibold mb-4">
                                                    Tidak dapat terhubung ke server. Silakan coba lagi.
                                                </div>
                                            </div>
                                            <div class="flex justify-center w-full mt-6">
                                                <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">
                                                    Oke
                                                </x-button-primary>
                                            </div>
                                        </div>
                                        <style>
                                            .my-swal-popup {
                                                min-height: 200px;
                                                max-height: 90vh;
                                                border-radius: 1.5rem !important;
                                                overflow-y: auto;
                                            }
                                        </style>
                                    `,
                                    didOpen: () => {
                                        $('#swal-confirm-btn').on(
                                            'click',
                                            function() {
                                                Swal.clickConfirm();
                                            });
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-layouts.app>
