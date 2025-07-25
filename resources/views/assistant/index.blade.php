<x-layouts.app>
    <x-slot:title>
        Daftar Aslab
    </x-slot:title>

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-[#C9C6C5]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
            </span>
            <input type="text" placeholder="Cari..."
                class="block w-full pl-10 pr-3 py-3 border border-[#C9C6C5] rounded-lg leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl" type="button">
            <x-heroicon-s-plus class="w-5 h-5" />
            Tambah
        </x-button-primary>
    </div>

    <div class="rounded-lg w-full overflow-x-auto px-4 bg-white">
        <table class="w-full text-sm text-left rtl:text-right text-key-primary">
            <thead class="border-b border-[#E5E2E1]">
                <tr>
                    <th scope="col" class="px-2 py-3 w-8">
                        <div class="flex items-center justify-center h-full">
                            <input type="checkbox" id="selectAllCheckboxes"
                                class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer" />
                        </div>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Nama
                            <span class="flex flex-col ml-1">
                                <x-icon-sort-up class="w-2 h-2 text-[#4B57AC]" />
                                <x-icon-sort-down class="w-2 h-2 text-[#4B57AC]" />
                            </span>
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            NIM
                            <span class="flex flex-col ml-1">
                                <x-icon-sort-up class="w-2 h-2 text-[#4B57AC]" />
                                <x-icon-sort-down class="w-2 h-2 text-[#4B57AC]" />
                            </span>
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Program Studi
                            <span class="flex flex-col ml-1">
                                <x-icon-sort-up class="w-2 h-2 text-[#4B57AC]" />
                                <x-icon-sort-down class="w-2 h-2 text-[#4B57AC]" />
                            </span>
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Angkatan
                            <span class="flex flex-col ml-1">
                                <x-icon-sort-up class="w-2 h-2 text-[#4B57AC]" />
                                <x-icon-sort-down class="w-2 h-2 text-[#4B57AC]" />
                            </span>
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Tahun Masuk
                            <span class="flex flex-col ml-1">
                                <x-icon-sort-up class="w-2 h-2 text-[#4B57AC]" />
                                <x-icon-sort-down class="w-2 h-2 text-[#4B57AC]" />
                            </span>
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Status
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Jadwal Praktikum
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Jadwal Kuliah
                        </span>
                    </th>
                    <th scope="col" class="px-4 py-3">
                        <span class="flex items-center gap-1 font-extrabold">
                            Aksi
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assistants as $item)
                    <tr class="border-b border-[#E5E2E1]">
                        <td class="px-2 py-5">
                            <div class="flex items-center justify-center h-full">
                                <input type="checkbox"
                                    class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer"
                                    name="selected[]" value="{{ $item->nim }}" />
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            {{ $item->name }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $item->nim }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $item->prodi }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $item->angkatan }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $item->tahun_masuk }}
                        </td>
                        <td class="px-4 py-4">
                            @if ($item->status === 'aktif')
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    Aktif
                                </span>
                            @elseif ($item->status === 'non-aktif')
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                    Non-Aktif
                                </span>
                            @else
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('assistant.showCourse', $item->nim) }}"
                                class="flex items-center gap-2 hover:text-key-secondary">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                Detail
                            </a>
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('assistant.showSchedule', $item->nim) }}"
                                class="flex items-center gap-2 hover:text-key-secondary">
                                <x-heroicon-o-eye class="w-4 h-4" />
                                Detail
                            </a>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('assistant.edit', $item->nim) }}">
                                    <x-heroicon-s-pencil-square
                                        class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary" />
                                </a>
                                <form action="{{ route('assistant.delete', $item->nim) }}" method="POST"
                                    class="flex">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Yakin ingin menghapus asisten ini?')"><x-heroicon-s-trash
                                            class="w-5 h-5 text-[#BCC2FF] hover:text-key-secondary cursor-pointer" /></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="border-b border-[#E5E2E1]">
                        <td colspan="10" class="px-6 py-12 text-center text-key-primary">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script type="module">
            $(document).ready(function() {
                // Select/Deselect all checkboxes
                $('#selectAllCheckboxes').on('change', function() {
                    $('.form-checkbox').not('#selectAllCheckboxes').prop('checked', this.checked);
                    // Remove indeterminate state
                    this.indeterminate = false;
                });

                // Handle individual checkbox changes
                $('.form-checkbox').not('#selectAllCheckboxes').on('change', function() {
                    const total = $('.form-checkbox').not('#selectAllCheckboxes').length;
                    const checked = $('.form-checkbox').not('#selectAllCheckboxes').filter(':checked').length;
                    const selectAll = $('#selectAllCheckboxes').get(0);

                    if (checked === 0) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                    } else if (checked === total) {
                        selectAll.checked = true;
                        selectAll.indeterminate = false;
                    } else {
                        selectAll.checked = false;
                        selectAll.indeterminate = true; // Show minus state
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>
