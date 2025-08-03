@props(['disabled' => false, 'type' => 'text'])

<div class="relative w-full">
    <input type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' =>
            'border rounded-xl py-2 px-4 pr-10 text-black border-[#C9C6C5] hover:border-[#929090] focus:outline-none focus:border-key-secondary input-component',
    ]) !!}
        @if ($type === 'password') id="password-input-{{ $attributes->get('id', uniqid()) }}" @endif>

    @if ($type === 'password')
        <button type="button"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer input-icon text-[#929090] focus:outline-none"
            onclick="togglePasswordVisibility(this)" aria-label="Toggle password visibility">

            <x-heroicon-s-eye class="h-5 w-5 eye-icon-show" />
            <x-heroicon-s-eye-slash class="h-5 w-5 eye-icon-hide hidden" />
        </button>
    @endif
</div>

@if ($type === 'password')
    <script>
        function togglePasswordVisibility(buttonElement) {
            const inputElement = buttonElement.previousElementSibling;
            const showIcon = buttonElement.querySelector('.eye-icon-show');
            const hideIcon = buttonElement.querySelector('.eye-icon-hide');

            if (inputElement.type === 'password') {
                inputElement.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                inputElement.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }

        document.querySelectorAll('.input-component').forEach(function(input) {
            const button = input.parentElement.querySelector('.input-icon');
            if (!button) return;

            input.addEventListener('focus', function() {
                button.classList.remove('text-[#929090]');
                button.classList.add('text-black');
            });

            input.addEventListener('blur', function() {
                button.classList.remove('text-black');
                button.classList.add('text-[#929090]');
            });
        });
    </script>
@endif
