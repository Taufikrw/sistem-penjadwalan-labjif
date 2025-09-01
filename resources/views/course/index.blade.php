<x-layouts.app>
    <x-slot:title>
        @if (Auth::user()->role === 'admin')
            Jadwal Kuliah {{ $assistant->name }} - {{ $assistant->nim }}
            @if ($assistant->is_final)
                <x-heroicon-s-check-circle class="w-7 h-7 text-[#34C759]" />
            @else
                <x-heroicon-s-x-circle class="w-7 h-7 text-[#FF8D28]" />
            @endif
        @else
            Jadwal Kuliah
        @endif
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (Auth::user()->role === 'admin')
        <x-slot:back_button>
            <a href="{{ route('assistant.index') }}"
                class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition-colors w-fit">
                <x-heroicon-s-arrow-left class="w-4 h-4" />
                Kembali
            </a>
        </x-slot:back_button>
    @endif

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white h-11">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
            </span>
            <input type="text" placeholder="Cari..." id="search-course"
                class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <div class="flex items-center gap-4">
            @if (!$assistant->is_final && Auth::user()->role === 'assistant')
                <button
                    class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl bg-[#1E49F0] hover:bg-[#0031C5] text-white font-bold focus:outline-none cursor-pointer transition-colors duration-300"
                    type="button" id="btn-finalize-course">
                    <x-heroicon-s-check-circle class="w-5 h-5" />
                    Finalisasi
                </button>
            @endif
            @if (Auth::user()->role === 'admin' || !$assistant->is_final)
                <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl"
                    type="button" id="btn-create-course" onclick="showDynamicModal()">
                    <x-heroicon-s-plus class="w-5 h-5" />
                    Tambah
                </x-button-primary>
            @endif
        </div>
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

    @if ($assistant->is_final && Auth::user()->role === 'assistant')
        <x-data-table
            url="{{ isset($assistant) ? route('api.course-schedules.table', $assistant->nim) : route('api.course-schedules.table', Auth::user()->username) }}"
            action-url="{{ Auth::user()->role === 'admin' ? '/course/' . $assistant->nim . '/' : '/jadwal-kuliah/' }}"
            :columns="[
                ['label' => 'Hari', 'field' => 'day', 'sortable' => true],
                ['label' => 'Mata Kuliah', 'field' => 'course', 'sortable' => true],
                ['label' => 'Kelas', 'field' => 'name', 'sortable' => true],
                ['label' => 'Jam', 'field' => 'jam', 'sortable' => true],
            ]" :has-actions="false" table-id="laboratorium-table" search-input-id="search-course"
            btn-create-id="btn-create-course" />
    @else
        <x-data-table
            url="{{ isset($assistant) ? route('api.course-schedules.table', $assistant->nim) : route('api.course-schedules.table', Auth::user()->username) }}"
            action-url="{{ Auth::user()->role === 'admin' ? '/course/' . $assistant->nim . '/' : '/jadwal-kuliah/' }}"
            :columns="[
                ['label' => 'Hari', 'field' => 'day', 'sortable' => true],
                ['label' => 'Mata Kuliah', 'field' => 'course', 'sortable' => true],
                ['label' => 'Kelas', 'field' => 'name', 'sortable' => true],
                ['label' => 'Jam', 'field' => 'jam', 'sortable' => true],
            ]" :has-actions="true" table-id="laboratorium-table" search-input-id="search-course"
            btn-create-id="btn-create-course" />
    @endif

    <x-form-modal modal-id="courseModal"
        ajax-url="{{ Auth::user()->role === 'admin' ? route('course.create', $assistant->nim) : route('course-schedule.create') }}"
        form-id="course-form" />

    <script type="module">
        $(document).ready(function() {
            const btnFinalize = $('#btn-finalize-course');

            btnFinalize.on('click', function() {
                Swal.fire({
                    showCancelButton: false,
                    showConfirmButton: false,
                    width: '420px',
                    customClass: {
                        popup: 'my-swal-popup'
                    },
                    html: `
                        <div class="flex flex-col items-center justify-between h-full">
                            <div class="mt-8">
                                <div class="mb-4">
                                    <x-heroicon-s-exclamation-circle class="w-16 h-16 text-[#FF8D28] mx-auto" />
                                </div>
                                <div class="font-bold text-key-primary text-lg mb-2">Finalisasi Data</div>
                                <div class="text-black font-semibold mb-8">Apakah Anda yakin semua jadwal sudah ditambahkan? Setelah finalisasi, jadwal kuliah sudah tidak dapat diperbarui!</div>
                            </div>
                            <div class="flex gap-2 justify-between w-full">
                                <x-button-secondary id="swal-cancel-btn" type="button" class="rounded-xl py-3 px-6 text-sm">Periksa kembali</x-button-secondary>
                                <x-button-primary id="swal-confirm-btn" type="button" class="rounded-xl py-3 px-6 text-sm">Ya, Simpan</x-button-primary>
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
                        $.ajax({
                            url: '{{ route('course.finalize', $assistant->nim) }}',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Gagal!',
                                    xhr.responseJSON.message ||
                                    'Terjadi kesalahan saat finalisasi jadwal.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>

</x-layouts.app>
