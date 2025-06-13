<div class="w-[320px] bg-primary text-white h-screen">
    <nav class="mt-10 px-6">
        <ul>
            <li class="mb-1">
                <a href="{{ route('dashboard') }}"
                    class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs('dashboard') ? 'bg-primary-20' : '' }}">Dashboard</a>
            </li>
            <li class="mb-1">
                <a href="{{ route('assistant.index') }}"
                    class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs(['assistant.index', 'assistant.create', 'assistant.edit', 'assistant.showCourse', 'course.create', 'course.edit']) ? 'bg-primary-20' : '' }}">Asisten</a>
            </li>
            <li class="mb-1">
                <a href="{{ route('practicum.index') }}"
                    class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs('practicum.index') ? 'bg-primary-20' : '' }}">Praktikum</a>
            </li>
            <li class="mb-1">
                <a href="{{ route('room.index') }}"
                    class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs('room.index') ? 'bg-primary-20' : '' }}">Laboratorium</a>
            </li>
            <li class="mb-1">
                <a href="{{ route('schedule.index') }}"
                    class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs('schedule.index') ? 'bg-primary-20' : '' }}">Jadwal
                    Praktikum</a>
            </li>
        </ul>
    </nav>
</div>
