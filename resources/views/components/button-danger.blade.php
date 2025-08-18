<button type="{{ $type }}" {{ $attributes->merge(['class' => 'bg-[#BA1A1A] hover:bg-[#93000A] text-white font-bold py-2 px-4 focus:outline-none cursor-pointer transition-colors duration-300']) }}>
    {{ $slot }}
</button>