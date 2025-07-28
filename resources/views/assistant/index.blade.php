<x-layouts.app>
    <x-slot:title>
        Daftar Aslab
    </x-slot:title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-[#C9C6C5]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
            </span>
            <input type="text" placeholder="Cari..." id="search-assistant"
                class="block w-full pl-10 pr-3 py-3 border border-[#C9C6C5] rounded-lg leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl" type="button"
            id="btn-create-assistant" onclick="showDynamicModal()">
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

    <x-data-table url="{{ route('api.assistant.table') }}" action-url="assistants/" :columns="[
        ['label' => 'Nama', 'field' => 'name', 'sortable' => true],
        ['label' => 'NIM', 'field' => 'nim', 'sortable' => true],
        ['label' => 'Program Studi', 'field' => 'prodi', 'sortable' => true],
        ['label' => 'Angkatan', 'field' => 'angkatan', 'sortable' => true],
        ['label' => 'Tahun Masuk', 'field' => 'tahun_masuk', 'sortable' => true],
        ['label' => 'Status', 'field' => 'status', 'sortable' => false],
        ['label' => 'Jadwal Praktikum', 'field' => 'jadwal_praktikum', 'sortable' => false],
        ['label' => 'Jadwal Kuliah', 'field' => 'jadwal_kuliah', 'sortable' => false],
    ]" :has-actions="true"
        table-id="assistant-table" search-input-id="search-assistant" btn-create-id="btn-create-assistant"
        primary="nim" />

    <x-form-modal modal-id="assistantModal" ajax-url="{{ route('assistant.create') }}"
        action-url="assistants/" form-id="assistant-form" />
</x-layouts.app>
