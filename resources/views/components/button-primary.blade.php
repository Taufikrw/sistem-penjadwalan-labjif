<button type="{{ $type }}" {{ $attributes->merge(['class' => 'bg-key-tertiary hover:bg-key-secondary text-white font-bold py-2 px-4 rounded-2xl focus:outline-none cursor-pointer transition-colors duration-300']) }}>
    {{ $slot }}
</button>