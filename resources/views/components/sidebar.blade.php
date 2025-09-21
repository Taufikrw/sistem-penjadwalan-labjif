<div id="sidebar"
    class="relative flex-none w-88 bg-key-primary h-screen flex flex-col justify-between px-3 py-12 transform transition-all duration-300 ease-in-out">
    <nav class="flex flex-col">
        <div id="logo-section" class="flex items-center gap-2 h-10 px-2">
            <x-icon-sidebar-logo class="w-8 h-8 mr-2 js-sidebar-logo-icon" />
            <div class="menu-text transition-opacity duration-150">
                <h3 class="text-lg text-white font-bold">Sistem Penjadwalan Praktikum</h3>
                <h4 class="text-md text-white font-semibold">Lab Jurusan Informatika</h4>
            </div>
        </div>

        <div class="py-2 w-fit ml-auto">
            <button id="minimize-button" class="p-2 rounded-xl bg-black/20 cursor-pointer" type="button">
                <x-heroicon-m-chevron-double-left class="w-4 h-4 text-white" />
            </button>
        </div>

        <button id="maximize-button"
            class="absolute top-24 -right-10 p-2 rounded-xl bg-[#E6E6E6] z-50 cursor-pointer hidden" type="button">
            <x-heroicon-m-chevron-double-right class="w-4 h-4 text-[#5A5A5A]" />
        </button>

        <div id="menu-links" class="flex flex-col gap-2 text-white">
            <x-nav-link route="{{ route('dashboard') }}" :routeIs="['dashboard', 'assistant.edit-biodata', 'assistant.show']">
                <x-heroicon-s-home class="w-5 h-5" />
                <span class="menu-text transition-opacity duration-150">Dashboard</span>
            </x-nav-link>
            @if (Auth::user()->role === 'admin')
                <x-nav-link route="{{ route('assistant.index') }}" routeIs="assistant.*">
                    <x-heroicon-s-user-group class="w-5 h-5" />
                    <span class="menu-text transition-opacity duration-150">Aslab</span>
                </x-nav-link>
                <x-nav-link route="{{ route('practicum.index') }}" routeIs="practicum.*">
                    <x-heroicon-s-book-open class="w-5 h-5" />
                    <span class="menu-text transition-opacity duration-150">Praktikum</span>
                </x-nav-link>
                <x-nav-link route="{{ route('lab.index') }}" routeIs="lab.*">
                    <x-heroicon-s-beaker class="w-5 h-5" />
                    <span class="menu-text transition-opacity duration-150">Laboratorium</span>
                </x-nav-link>
                <x-nav-link route="{{ route('lecturer.index') }}" routeIs="lecturer.*">
                    <x-heroicon-s-academic-cap class="w-5 h-5" />
                    <span class="menu-text transition-opacity duration-150">Dosen</span>
                </x-nav-link>
                <x-nav-link route="{{ route('schedule.index') }}" routeIs="schedule.*">
                    <x-heroicon-s-calendar-date-range class="w-5 h-5" />
                    <span class="menu-text transition-opacity duration-150">Daftar Jadwal Praktikum</span>
                </x-nav-link>
            @endif
            @if (Auth::user()->role === 'assistant')
                <x-nav-link route="{{ route('course.index') }}" routeIs="course.*">
                    <x-heroicon-s-calendar class="w-5 h-5" />
                    <span class="menu-text transition-opacity duration-150">Jadwal Perkuliahan</span>
                </x-nav-link>
                <x-nav-link route="{{ route('schedule.index-assistant') }}" routeIs="schedule.*">
                    <x-heroicon-s-calendar-date-range class="w-5 h-5" />
                    <span class="menu-text transition-opacity duration-150">Jadwal Praktikum</span>
                </x-nav-link>
            @endif
        </div>
    </nav>

    <div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                class="nav-link h-12 flex w-full items-center text-white gap-3 px-4 hover:bg-[#29293A]/23 font-bold rounded-xl cursor-pointer">
                <x-heroicon-s-arrow-left-end-on-rectangle class="w-5 h-5" />
                <span class="menu-text transition-opacity duration-150">Keluar</span>
            </button>
        </form>
    </div>
</div>

<script>
    (function() {
        if (localStorage.getItem('sidebarState') === 'collapsed') {
            const sidebar = document.getElementById('sidebar');
            const logoSection = document.getElementById('logo-section');
            const logoIcon = logoSection.querySelector('.js-sidebar-logo-icon');
            const textContainers = sidebar.querySelectorAll('.menu-text');
            const textElements = sidebar.querySelectorAll('span.menu-text, #logo-section h3, #logo-section h4');
            const navLinks = sidebar.querySelectorAll('#menu-links a.nav-link, #logout-form button');
            const minimizeButton = document.getElementById('minimize-button');
            const maximizeButton = document.getElementById('maximize-button');

            sidebar.classList.remove('w-88');
            sidebar.classList.add('w-18');

            textElements.forEach(el => {
                el.dataset.originalText = el.textContent; // Simpan teks untuk nanti
                el.textContent = '';
                el.classList.add('opacity-0');
            });

            logoSection.classList.remove('gap-2', 'px-2');
            logoSection.classList.add('mx-auto');
            if (logoIcon) logoIcon.classList.remove('mr-2');

            navLinks.forEach(link => {
                link.classList.remove('gap-3', 'px-4');
                link.classList.add('justify-center', 'px-2');
            });

            if (minimizeButton) minimizeButton.parentElement.classList.add('invisible');
            if (maximizeButton) maximizeButton.classList.remove('hidden');
        }
    })();
</script>

<script type="module">
    $(document).ready(function() {

        const textFadeDuration = 150;
        const sidebarResizeDuration = 300;

        function collapseSidebar() {
            $('.menu-text').addClass('opacity-0');

            setTimeout(function() {
                $('#sidebar').removeClass('w-88').addClass('w-18');

                const textElements = $('#sidebar').find(
                    'span.menu-text, #logo-section h3, #logo-section h4');
                textElements.each(function() {
                    $(this).data('original-text', $(this).text());
                    $(this).text('');
                });
                $('#minimize-button').parent().addClass('invisible');
                $('#maximize-button').removeClass('hidden');

                setTimeout(function() {
                    $('#logo-section').removeClass('gap-2 px-2').addClass('mx-auto');
                    $('#logo-section').find('.js-sidebar-logo-icon').removeClass('mr-2');
                    $('#menu-links a.nav-link, #logout-form button').removeClass('gap-3 px-4')
                        .addClass('justify-center px-2');

                    localStorage.setItem('sidebarState', 'collapsed');
                }, sidebarResizeDuration);
            }, textFadeDuration);
        }

        function expandSidebar() {
            $('#logo-section').removeClass('mx-auto').addClass('gap-2 px-2');
            $('#logo-section').find('.js-sidebar-logo-icon').addClass('mr-2');
            $('#menu-links a.nav-link, #logout-form button').removeClass('justify-center px-2').addClass(
                'gap-3 px-4');

            $('#sidebar').removeClass('w-18').addClass('w-88 px-3');
            $('#maximize-button').addClass('hidden');
            $('#minimize-button').parent().removeClass('invisible');

            setTimeout(function() {
                const textElements = $('#sidebar').find(
                    'span.menu-text, #logo-section h3, #logo-section h4');
                textElements.each(function() {
                    let originalText = $(this).data('original-text');

                    if (originalText === undefined) {
                        originalText = this.dataset.originalText;
                    }

                    if (originalText !== undefined) {
                        $(this).text(originalText);
                    }
                });
                $('#sidebar').find('span.menu-text, #logo-section h3, #logo-section h4').removeClass(
                    'opacity-0');

                localStorage.setItem('sidebarState', 'expanded');
            }, sidebarResizeDuration);
        }
        $('#minimize-button').on('click', collapseSidebar);
        $('#maximize-button').on('click', expandSidebar);

        $('#logout-form').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Keluar?',
                text: 'Anda yakin ingin keluar dari aplikasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
        });
    });
</script>
