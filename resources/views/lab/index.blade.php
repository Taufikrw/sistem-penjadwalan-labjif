<x-layouts.app>
    <x-slot name="title">Daftar Laboratorium</x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white h-11">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
            </span>
            <input type="text" placeholder="Cari..." id="search-lab"
                class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl" type="button"
            id="btn-create-lab" onclick="showDynamicModal()">
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

    <x-data-table url="{{ route('api.laboratorium.table') }}" action-url="labs/" :columns="[
        ['label' => 'Nama', 'field' => 'name', 'sortable' => true],
        ['label' => 'Lokasi', 'field' => 'location', 'sortable' => true],
        ['label' => 'Kapasitas', 'field' => 'capacity', 'sortable' => true],
    ]" :has-actions="true"
        table-id="laboratorium-table" search-input-id="search-lab" btn-create-id="btn-create-lab" />

    <x-form-modal modal-id="laboratoriumModal" ajax-url="{{ route('lab.create') }}" action-url="labs/"
        form-id="lab-form" />
</x-layouts.app>
