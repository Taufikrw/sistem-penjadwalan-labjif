@props(['disabled' => false, 'type' => 'text'])

<input type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border rounded-xl py-3 px-5 text-key-primary border-[#C9C6C5] hover:border-[#929090] focus:outline-none focus:border-key-secondary input-component']) !!}>

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            function checkAndApplyFilledState(inputElement) {
                if ($(inputElement).val().length > 0) {
                    $(inputElement).removeClass('border-[#C9C6C5]').addClass('border-key-secondary');
                } else {
                    $(inputElement).removeClass('border-key-secondary');
                    $(inputElement).addClass('border-[#C9C6C5]');
                }
            }

            $('.input-component').each(function() {
                checkAndApplyFilledState(this);
            });
            
            $('.input-component').on('input change', function() {
                checkAndApplyFilledState(this);
            });
        });
    </script>
@endpush