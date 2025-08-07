<div id="sidebar"
    class="relative flex-none w-80 bg-white h-screen flex flex-col justify-between px-6 py-12 transform transition-all duration-300 ease-in-out">
    <button id="toggleSidebar" class="absolute top-26 -right-4 p-2 rounded-full bg-white z-1 cursor-pointer">
        <svg id="minimize-icon" class="w-4 h-4 transition-all duration-300 ease-in-out" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <svg id="maximize-icon" class="w-4 h-4 transition-all duration-300 ease-in-out hidden" fill="none"
            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    <nav class="flex flex-col gap-8">
        <div class="flex flex-col">
            <div id="logo-section" class="flex items-center mb-8 gap-2 h-10">
                <img id="logo" src="{{ asset('assets/images/Logo.svg') }}" alt="logo lab jif" class="w-8 h-8 mr-3">
                <div class="menu-text">
                    <h3 class="text-xl text-key-primary font-bold">Sistem Penjadwalan</h3>
                    <h4 class="text-lg text-[#929090] font-semibold">Lab Jurusan Informatika</h4>
                </div>
            </div>
            <hr class="text-[#F4F0EF]">
        </div>
        <div class="flex flex-col gap-2 text-[#929090]">
            <x-nav-link route="{{ route('dashboard') }}" :routeIs="['dashboard', 'assistant.edit-biodata']">
                <x-heroicon-s-home class="w-5 h-5" />
                <span class="menu-text">Dashboard</span>
            </x-nav-link>
            @if (Auth::user()->role === 'admin')
                <x-nav-link route="{{ route('assistant.index') }}" routeIs="assistant.*">
                    <x-heroicon-s-user class="w-5 h-5" />
                    <span class="menu-text">Aslab</span>
                </x-nav-link>
                <x-nav-link route="{{ route('practicum.index') }}" routeIs="practicum.*">
                    <x-heroicon-s-book-open class="w-5 h-5" />
                    <span class="menu-text">Praktikum</span>
                </x-nav-link>
                <x-nav-link route="{{ route('lab.index') }}" routeIs="lab.*">
                    <x-heroicon-s-beaker class="w-5 h-5" />
                    <span class="menu-text">Laboratorium</span>
                </x-nav-link>
                <x-nav-link route="{{ route('schedule.index') }}" routeIs="schedule.*">
                    <x-heroicon-s-calendar-date-range class="w-5 h-5" />
                    <span class="menu-text">Jadwal Praktikum</span>
                </x-nav-link>
            @endif
            @if (Auth::user()->role === 'assistant')
                <x-nav-link route="{{ route('course.index') }}" routeIs="course.*">
                    <x-heroicon-s-calendar class="w-5 h-5" />
                    <span class="menu-text">Jadwal Perkuliahan</span>
                </x-nav-link>
                <x-nav-link route="{{ route('schedule.index-assistant') }}" routeIs="schedule.*">
                    <x-heroicon-s-calendar-date-range class="w-5 h-5" />
                    <span class="menu-text">Jadwal Praktikum</span>
                </x-nav-link>
            @endif
        </div>
    </nav>
    <div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                class="nav-link h-12 flex w-full items-center text-[#929090] gap-3 px-4 hover:bg-key-neutral-variant hover:text-key-secondary font-bold rounded-xl cursor-pointer">
                <x-heroicon-s-arrow-left-end-on-rectangle class="w-5 h-5" />
                <span class="menu-text">Logout</span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            const sidebarState = 'sidebar-expanded';

            let isSidebarExpanded = localStorage.getItem(sidebarState) === 'true';

            if (isSidebarExpanded) {
                $('#sidebar').addClass('w-80 px-6').removeClass('w-20 px-5');
                $('.menu-text').removeClass('hidden');
                $('.nav-link').addClass('px-4').removeClass('px-2');
                $('#minimize-icon').show();
                $('#maximize-icon').hide();
            } else {
                $('#sidebar').addClass('w-20 px-5').removeClass('w-80 px-6');
                $('.menu-text').addClass('hidden');
                $('.nav-link').addClass('px-2').removeClass('px-4');
                $('#minimize-icon').hide();
                $('#maximize-icon').show();
            }

            $('#toggleSidebar').on('click', function() {
                if (isSidebarExpanded) {
                    $('#sidebar').addClass('w-20 px-5').removeClass('w-80 px-6');
                    $('.menu-text').addClass('hidden');
                    $('.nav-link').addClass('px-2').removeClass('px-4');
                    $('#minimize-icon').hide();
                    $('#maximize-icon').show();
                    isSidebarExpanded = false;
                } else {
                    $('#sidebar').addClass('w-80 px-6').removeClass('w-20 px-5');
                    $('.menu-text').removeClass('hidden');
                    $('.nav-link').addClass('px-4').removeClass('px-2');
                    $('#minimize-icon').show();
                    $('#maximize-icon').hide();
                    isSidebarExpanded = true;
                }

                localStorage.setItem(sidebarState, isSidebarExpanded);
            });
        });
    </script>
@endpush
