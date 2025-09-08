<x-layouts.app>
    <x-slot:title>
        Daftar Aslab
    </x-slot:title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative flex items-center">
                <x-filter type="assistant" :filters="$filters ?? []" />
            </div>
            <div class="relative w-full md:w-80 bg-white h-11">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
                </span>
                <input type="text" placeholder="Cari..." id="search-assistant"
                    class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
            </div>
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
        ['label' => 'NIM', 'field' => 'nim', 'sortable' => true],
        ['label' => 'Nama', 'field' => 'name', 'sortable' => true],
        ['label' => 'Prodi', 'field' => 'prodi_angkatan', 'sortable' => true],
        ['label' => 'Tahun Masuk', 'field' => 'tahun_masuk', 'sortable' => true, 'tooltip' => 'Tahun Masuk Aslab'],
        ['label' => 'Status', 'field' => 'status', 'sortable' => false],
        ['label' => 'Jadwal Kuliah', 'field' => 'jadwal_kuliah', 'sortable' => false],
        ['label' => 'Jadwal Praktikum', 'field' => 'jadwal_praktikum', 'sortable' => false],
    ]" :has-actions="true"
        table-id="assistant-table" search-input-id="search-assistant" btn-create-id="btn-create-assistant"
        primary="nim" />

    <x-form-modal modal-id="assistantModal" ajax-url="{{ route('assistant.create') }}" action-url="assistants/"
        form-id="assistant-form" />
</x-layouts.app>
