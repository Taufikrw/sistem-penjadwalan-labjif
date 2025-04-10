<div class="w-1/4 bg-gray-800 text-white h-screen p-4">
    <h2 class="text-xl font-bold mb-4">Sidebar</h2>
    <ul>
        <li class="mb-2"><a href="{{ route('dashboard') }}" class="hover:text-gray-300">Dashboard</a></li>
        <li class="mb-2"><a href="{{ route('assistant.index') }}" class="hover:text-gray-300">Assistant</a></li>
        <li class="mb-2"><a href="{{ route('practicum.index') }}" class="hover:text-gray-300">Praktikum</a></li>
        <li class="mb-2"><a href="#" class="hover:text-gray-300">Jadwal Praktikum</a></li>
        <li class="mb-2">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="hover:text-gray-300 bg-transparent border-none cursor-pointer">Logout</button>
            </form>
        </li>
    </ul>
</div>