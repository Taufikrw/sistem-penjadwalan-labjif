<x-layouts.app>
    <x-slot name="title">Daftar Dosen</x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white h-11">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
            </span>
            <input type="text" placeholder="Cari..." id="search-lecturer"
                class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl" type="button"
            id="btn-create-lecturer" onclick="showDynamicModal()">
            <x-heroicon-s-plus class="w-5 h-5" />
            Tambah
        </x-button-primary>
        <div id="deleted-info" class="items-center gap-6 hidden">
            <div id="selected-info" class="text-key-primary font-bold">
                0 Dipilih
            </div>
            <x-button-danger class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl"
                type="button" id="btn-bulk-delete">
                <x-heroicon-s-trash class="w-5 h-5" />
                Hapus
            </x-button-danger>
        </div>
    </div>

    <x-data-table url="{{ route('api.lecturer.table') }}" action-url="lecturers/" :columns="[
        ['label' => 'NIP', 'field' => 'nip', 'sortable' => true],
        ['label' => 'Nama', 'field' => 'name', 'sortable' => true],
    ]" :has-actions="true"
        table-id="lecturer-table" search-input-id="search-lecturer" btn-create-id="btn-create-lecturer" />

    <x-form-modal modal-id="lecturerModal" ajax-url="{{ route('lecturer.create') }}" action-url="lecturers/"
        form-id="lecturer-form" />
</x-layouts.app>
