<div class="bg-tertiary-98 border-b-1 border-[#989898] px-10 h-[4rem] flex items-center justify-end relative"> {{-- Tambahkan 'relative' di sini --}}
    <div class="relative"> {{-- Tambahkan div pembungkus untuk dropdown --}}
        <button id="userMenuButton" class="flex items-center text-xl text-[#7B7878] focus:outline-none">
            <h1>{{ Auth::user()->username }}</h1>
            {{-- Opsional: Tambahkan ikon panah ke bawah --}}
            <svg class="w-4 h-4 ml-1 transform transition-transform duration-200" id="arrowIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        {{-- Dropdown Menu --}}
        <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');
        const arrowIcon = document.getElementById('arrowIcon');

        userMenuButton.addEventListener('click', function () {
            userDropdown.classList.toggle('hidden');
            arrowIcon.classList.toggle('rotate-180'); // Putar ikon panah
        });

        // Tutup dropdown jika klik di luar area dropdown
        document.addEventListener('click', function (event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
                arrowIcon.classList.remove('rotate-180');
            }
        });
    });
</script>