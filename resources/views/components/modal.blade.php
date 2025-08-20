<div id="{{ $modalId }}"
    class="fixed inset-0 z-50 hidden overflow-y-auto transition-opacity duration-300 ease-in-out">
    <div id="modalOverlay"
        class="flex items-center justify-center min-h-screen bg-gray-900/75 transition-opacity duration-300 ease-in-out">
        <div class="relative bg-white rounded-3xl shadow-xl max-w-5xl w-full p-8 transform transition-all duration-300 sm:my-8 sm:align-middle sm:w-full scale-95 opacity-0"
            id="modalContent">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-key-primary font-bold text-xl">
                    <div class="flex gap-3 items-center">
                        {{ $title ?? 'Modal Title' }}
                    </div>
                </h3>
                <button id="closeModalBtn"
                    class="text-[#929090] hover:text-[#535252] focus:outline-none cursor-pointer">
                    <x-heroicon-s-x-mark class="w-6 h-6" />
                </button>
            </div>

            <hr class="border-[#F4F0EF] mb-4">

            <div class="modal-body min-h-[500px] flex flex-col">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<script type="module">
    $(document).ready(function () {
        const modalId = '{{ $modalId }}';
        window.closeModal = function () {
            $(`#${modalId} #modalContent`).removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(() => {
                $(`#${modalId}`).addClass('hidden');
            }, 300);
        }

        window.openModal = function () {
            $(`#${modalId}`).removeClass('hidden');
            setTimeout(() => {
                $(`#${modalId} #modalContent`).removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 10);
        }
    
        $('#closeModalBtn').on('click', function() {
            window.closeModal();
        });
    
        $(`#${modalId} #modalOverlay`).on('click', function(e) {
            if (e.target === this) {
                window.closeModal();
            }
        });
    });
</script>
