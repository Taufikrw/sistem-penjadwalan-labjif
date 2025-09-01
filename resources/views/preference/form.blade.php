<form id="preference-form" action="{{ route('assistant.store-preference') }}" method="POST">
    @csrf
    <div id="title-hidden" class="hidden">
        <div class="flex gap-3 items-center">
            <x-heroicon-s-pencil-square class="w-4 h-4" />
            <span>Edit Preferensi</span>
        </div>
    </div>

    <div class="relative w-full md:w-80 bg-white h-11">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <x-heroicon-s-magnifying-glass class="h-4 w-4 text-[#C9C6C5]" />
        </span>
        <input type="text" placeholder="Cari..." id="search-preference"
            class="block w-full pl-9 h-full pr-3 border border-[#C9C6C5] rounded-xl leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
    </div>

    <div class="w-full overflow-x-auto">
        <div class="h-96 overflow-y-auto">
            <table class="w-full text-sm text-left rtl:text-right text-key-primary border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th scope="col" class="sticky top-0 px-2 py-3 w-8 border-b border-[#E5E2E1] bg-white"></th>
                        <th scope="col" class="sticky top-0 px-4 py-3 border-b border-[#E5E2E1] bg-white">
                            <span class="flex items-center gap-1 font-extrabold sortable cursor-pointer"
                                data-field="kode_praktikum" data-sort-direction="">
                                Mata Kuliah
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody id="set-assistant-body">
                    @foreach ($practicums as $practicum)
                        <tr>
                            <td class="px-2 py-5 border-b border-[#E5E2E1]">
                                <div class="flex items-center justify-center h-full">
                                    <input type="checkbox"
                                        class="form-checkbox rounded text-key-secondary accent-key-secondary cursor-pointer"
                                        name="practicums[]" value="{{ $practicum->kode_praktikum }}"
                                        id="practicum-{{ $practicum->kode_praktikum }}"
                                        @if (in_array($practicum->kode_praktikum, array_column($selectedPracticums, 'kode_praktikum'))) checked @endif />
                                </div>
                            </td>
                            <td class="px-4 py-4 border-b border-[#E5E2E1]" data-field="kode_praktikum">
                                {{ $practicum->name }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 flex justify-between items-center w-full">
        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-preference"
            onclick="closeModal()">
            Batal
        </x-button-secondary>
        <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-preference">
            Simpan
        </x-button-primary>
    </div>
</form>

<script type="module">
    $(document).ready(function() {
        const title = $('#title-hidden').html();
        $('#title').html(title);

        $('#search-preference').on('input', function() {
            const searchValue = $(this).val().toLowerCase();
            $('#set-assistant-body tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
