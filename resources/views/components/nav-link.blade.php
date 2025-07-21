@props(['route', 'routeIs' => null])

<a href="{{ $route }}"
    class="nav-link h-12 flex items-center gap-3 px-4 hover:bg-key-neutral-variant hover:text-key-secondary font-bold rounded-xl {{ Request::routeIs($routeIs) ? 'bg-key-neutral-variant text-key-secondary' : '' }}">
    {{ $slot }}
</a>
