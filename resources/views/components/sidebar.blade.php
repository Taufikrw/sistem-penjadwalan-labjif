<div class="w-[320px] bg-primary text-white h-screen">
    <nav class="mt-10 px-6">
        <ul>
            @if (Auth::user()->role === 'admin')
                <li class="mb-1">
                    <a href="{{ route('dashboard') }}"
                        class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs('dashboard') ? 'bg-primary-20' : '' }}">Dashboard</a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('assistant.index') }}"
                        class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs(['assistant.index', 'assistant.create', 'assistant.edit', 'assistant.showCourse', 'assistant.showSchedule', 'course.create', 'course.edit']) ? 'bg-primary-20' : '' }}">Asisten</a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('practicum.index') }}"
                        class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs(['practicum.index', 'practicum.create', 'practicum.edit']) ? 'bg-primary-20' : '' }}">Praktikum</a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('lab.index') }}"
                        class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs(['lab.index', 'lab.create', 'lab.edit']) ? 'bg-primary-20' : '' }}">Laboratorium</a>
                </li>
            @endif
            @if (Auth::user()->role === 'assistant')
                <li class="mb-1">
                    <a href="{{ route('dashboard.assistant') }}" class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs('dashboard.assistant') ? 'bg-primary-20' : '' }}">Dashboard</a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('history.index') }}" class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs('history.index') ? 'bg-primary-20' : '' }}">Riwayat</a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('course.index') }}" class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs(['course.index', 'course-schedule.create', 'course-schedule.edit']) ? 'bg-primary-20' : '' }}">Jadwal Perkuliahan</a>
                </li>
            @endif
            <li class="mb-1">
                <a href="{{ route('schedule.index') }}"
                    class="block py-3 px-4 hover:bg-primary-20 rounded-xl {{ Request::routeIs(['schedule.index', 'schedule.edit', 'schedule.create', 'schedule.set-assistant', 'schedule.edit-assistant']) ? 'bg-primary-20' : '' }}">Jadwal
                    Praktikum</a>
            </li>
        </ul>
    </nav>
</div>
