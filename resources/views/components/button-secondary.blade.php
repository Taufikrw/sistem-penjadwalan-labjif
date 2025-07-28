<button type="{{ $type }}" {{ $attributes->merge(['class' => 'bg-[#F4F0EF] hover:bg-[#E5E2E1] text-key-primary font-bold py-2 px-4 focus:outline-none cursor-pointer transition-colors duration-300']) }}>
    {{ $slot }}
</button>