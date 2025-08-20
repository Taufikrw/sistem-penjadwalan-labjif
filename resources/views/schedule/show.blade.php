<x-layouts.app>
    @if ($semester && $tahunAjaran)
        <x-slot name="title">{{ Str::title('Jadwal Praktikum ' . $semester . ' ' . $tahunAjaran) }}</x-slot>
    @else
        <x-slot name="title">Jadwal Praktikum</x-slot>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <x-button-secondary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl"
                type="button" id="btn-filter-schedule" onclick="">
                <x-heroicon-s-funnel class="w-4 h-4" />
                Filter
            </x-button-secondary>
            <div class="relative w-full md:w-80 bg-white">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-[#C9C6C5]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </span>
                <input type="text" placeholder="Cari..." id="search-schedule"
                    class="block w-full pl-10 pr-3 py-3 border border-[#C9C6C5] rounded-lg leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
            </div>
        </div>
        @if (Auth::user()->role === 'admin')
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
        @endif
    </div>

    <div class="">
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
            ['label' => 'Kelas', 'field' => 'name', 'sortable' => true],
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
            ['label' => 'Kelas', 'field' => 'name', 'sortable' => true],
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
</x-layouts.app>
