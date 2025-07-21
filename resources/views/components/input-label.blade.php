@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-lg text-key-primary']) }}>
    {{ $value ?? $slot }}
</label>
