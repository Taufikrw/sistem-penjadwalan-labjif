<x-layouts.app>
    <x-slot:title>
        @if (isset($assistant))
            Jadwal Kuliah - {{ $assistant->name }}
        @else
            Jadwal Kuliah
        @endif
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (isset($assistant))
        <x-slot:back_button>
            <a href="{{ route('assistant.index') }}"
                class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition-colors w-fit">
                <x-heroicon-s-arrow-left class="w-4 h-4" />
                Kembali
            </a>
        </x-slot:back_button>
    @endif

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-[#C9C6C5]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
            </span>
            <input type="text" placeholder="Cari..." id="search-course"
                class="block w-full pl-10 pr-3 py-3 border border-[#C9C6C5] rounded-lg leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl"
            type="button" id="btn-create-course" onclick="showDynamicModal()">
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

    <x-data-table
        url="{{ isset($assistant) ? route('api.course-schedules.table', $assistant->nim) : route('api.course-schedules.table', Auth::user()->username) }}"
        action-url="{{ Auth::user()->role === 'admin' ? '/course/' . $assistant->nim . '/' : '/jadwal-kuliah/' }}"
        :columns="[
            ['label' => 'Hari', 'field' => 'day', 'sortable' => false],
            ['label' => 'Mata Kuliah', 'field' => 'course', 'sortable' => true],
            ['label' => 'Kelas', 'field' => 'name', 'sortable' => true],
            ['label' => 'Jam', 'field' => 'jam', 'sortable' => true],
        ]" :has-actions="true" table-id="laboratorium-table" search-input-id="search-course"
        btn-create-id="btn-create-course" />

    <x-form-modal modal-id="courseModal"
        ajax-url="{{ Auth::user()->role === 'admin' ? route('course.create', $assistant->nim) : route('course-schedule.create') }}"
        form-id="course-form" />

</x-layouts.app>
