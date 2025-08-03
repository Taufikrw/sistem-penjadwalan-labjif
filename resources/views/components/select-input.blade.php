@props([
    'name',
    'options' => [],
    'placeholder' => 'Pilih salah satu',
    'id' => 'custom-select-' . \Illuminate\Support\Str::random(8),
    'selected' => null, // 1. Tambahkan prop 'selected' untuk menerima nilai yang sudah ada
])

@php
    $containerId = 'container-' . $id;
    $selectedTextId = 'selected-text-' . $id;
    $optionsId = 'options-' . $id;
    $arrowId = 'arrow-' . $id;

    // 2. Logika untuk menentukan teks yang akan ditampilkan
    $displayText = $placeholder;
    $displayClass = 'text-gray-500'; // Kelas untuk placeholder
    if ($selected !== null && isset($options[$selected])) {
        $displayText = $options[$selected];
        $displayClass = 'text-gray-900'; // Kelas untuk nilai yang sudah dipilih
    }
@endphp

<div class="relative w-full" id="{{ $containerId }}">

    <button type="button" id="{{ $id }}"
        class="relative w-full cursor-pointer rounded-xl border {{ $selected !== null && isset($options[$selected]) ? 'border-key-secondary' : 'border-[#C9C6C5]' }} bg-white py-2 px-4 text-left hover:border-[#929090] focus:border-key-secondary focus:outline-none"
        aria-haspopup="listbox" aria-expanded="true">

        {{-- 3. Tampilkan teks dan kelas yang sudah ditentukan --}}
        <span class="block truncate font-medium {{ $displayClass }}" id="{{ $selectedTextId }}">{{ $displayText }}</span>

        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
            <x-heroicon-o-chevron-down id="{{ $arrowId }}-down"
                class="h-4 w-4 text-gray-400 transition-all duration-200" />
            <x-heroicon-o-chevron-up id="{{ $arrowId }}-up"
                class="h-4 w-4 text-gray-400 transition-all duration-200 hidden" />
        </span>
    </button>

    <div id="{{ $optionsId }}"
        class="absolute z-10 mt-2 max-h-40 w-full overflow-auto rounded-xl bg-white py-1 text-base shadow-lg border border-[#C9C6C5] focus:outline-none hidden">
        @foreach ($options as $value => $text)
            <div class="custom-option cursor-pointer select-none relative py-2 pl-4 pr-4 text-black hover:bg-gray-100"
                data-value="{{ $value }}" data-text="{{ $text }}">
                <span class="block truncate">{{ $text }}</span>
            </div>
        @endforeach
    </div>

    {{-- 4. Set nilai awal untuk input tersembunyi --}}
    <input type="hidden" name="{{ $name }}" id="hidden-{{ $id }}" value="{{ $selected }}">
</div>

<script type="module">
    $(document).ready(function() {
        const container = $('#{{ $containerId }}');
        const button = container.find('button');
        const optionsPanel = $('#{{ $optionsId }}');
        const hiddenInput = $('#hidden-{{ $id }}');
        const selectedText = $('#{{ $selectedTextId }}');
        const arrowDown = $('#{{ $arrowId }}-down');
        const arrowUp = $('#{{ $arrowId }}-up');

        function updateBorder() {
            if (hiddenInput.val() && {{ json_encode(array_key_exists($selected, $options)) }}) {
                button.removeClass('border-[#C9C6C5]').addClass('border-key-secondary');
            } else {
                button.removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
            }
        }

        button.on('click', function(e) {
            e.stopPropagation();
            optionsPanel.toggleClass('hidden');
            arrowDown.toggleClass('hidden');
            arrowUp.toggleClass('hidden');
        });

        optionsPanel.find('.custom-option').on('click', function() {
            const value = $(this).data('value');
            const text = $(this).data('text');
            hiddenInput.val(value).trigger('change');
            selectedText.text(text).removeClass('text-gray-500').addClass('text-gray-900');
            optionsPanel.addClass('hidden');
            arrowDown.removeClass('hidden');
            arrowUp.addClass('hidden');
            button.removeClass('border-[#C9C6C5]').addClass('border-key-secondary');
        });

        $(document).on('click', function(e) {
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                optionsPanel.addClass('hidden');
                arrowDown.removeClass('hidden');
                arrowUp.addClass('hidden');
            }
        });

        // Initial border update
        if (hiddenInput.val() && {{ json_encode(array_key_exists($selected, $options)) }}) {
            button.removeClass('border-[#C9C6C5]').addClass('border-key-secondary');
        } else {
            button.removeClass('border-key-secondary').addClass('border-[#C9C6C5]');
        }
    });
</script>
