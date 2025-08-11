@props(['route', 'routeIs' => null])

<a href="{{ $route }}"
    class="nav-link h-12 flex items-center gap-3 px-4 hover:bg-[#29293A]/23 font-bold rounded-xl {{ Request::routeIs($routeIs) ? 'bg-black/20' : '' }}">
    {{ $slot }}
</a>
