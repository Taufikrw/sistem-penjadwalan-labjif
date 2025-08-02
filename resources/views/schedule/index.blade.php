<x-layouts.app>
    <x-slot:title>
        Jadwal Praktikum
    </x-slot>

    <div class="flex items-center justify-between">
        <div class="relative w-full md:w-80 bg-white">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-[#C9C6C5]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
            </span>
            <input type="text" placeholder="Cari..." id="search-schedule-list"
                class="block w-full pl-10 pr-3 py-3 border border-[#C9C6C5] rounded-lg leading-5 placeholder-[#C9C6C5] focus:outline-none focus:ring-1 focus:ring-key-secondary sm:text-md">
        </div>
        <x-button-primary class="flex justify-between items-center px-5 py-3 text-md gap-2 rounded-xl" type="button"
            id="btn-create-schedule-list" onclick="showDynamicModal()">
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

    <div id="card-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
        <div class="cols-span-1 md:col-span-2 lg:col-span-5 py-12">
            <x-icon-spinner class="h-16 w-16 animate-spin mx-auto" />
        </div>
    </div>
</x-layouts.app>

<script type="module">
    $(document).ready(function() {
        const cardContainer = $('#card-container');
        const searchInput = $('#search-schedule-list');

        function loadCard() {
            $.ajax({
                url: '/api/get-year-schedule-list',
                method: 'GET',
                success: function(data) {
                    cardContainer.empty();
                    if (!data.data || data.data.length === 0) {
                        cardContainer.append(`
                                <div
                                    class="rounded-xl bg-white cols-span-1 md:col-span-2 lg:col-span-5 p-4 flex flex-col py-12 justify-center items-center">
                                    <x-icon-no-data class="w-70 mx-auto" />
                                    <span class="font-bold text-sm text-key-primary">Tidak ada data</span>
                                </div>
                            `);
                    } else {
                        data.data.forEach(item => {
                            const card = `
                                    <div class="rounded-xl bg-white p-4 h-40 flex flex-col justify-between border border-[#C9C6C5]">
                                        <div class="year-list text-key-primary">
                                            <h3 class="font-bold text-lg capitalize">Praktikum ${item.semester}</h3>
                                            <p class="text-gray-600">${item.tahun_ajaran}</p>
                                        </div>
                                        <a href="/schedule-detail?semester=${encodeURIComponent(item.semester)}&tahun_ajar=${encodeURIComponent(item.tahun_ajar)}" class="w-full">
                                            <x-button-secondary class="flex items-center px-5 py-3 text-md gap-2 rounded-xl w-full"
                                                type="button" id="btn-create-course">
                                                <x-heroicon-s-eye class="w-5 h-5" />
                                                Detail
                                            </x-button-secondary>
                                        </a>
                                    </div>
                                `;
                            cardContainer.append(card);
                        });
                    }
                }
            });
        }

        loadCard();

        if (searchInput) {
            searchInput.on('input', function() {
                const searchValue = $(this).val().toLowerCase();
                cardContainer.children().each(function() {
                    const yearListText = $(this).find('.year-list').text().toLowerCase();
                    if (yearListText.includes(searchValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        }
    });
</script>
