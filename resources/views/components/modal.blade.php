<div id="modal" class="fixed inset-0 z-50 hidden overflow-y-auto transition-opacity duration-300 ease-in-out">
    <div id="modalOverlay" class="flex items-center justify-center min-h-screen px-4 py-8 bg-gray-900/75 transition-opacity duration-300 ease-in-out">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 transform transition-all duration-300 sm:my-8 sm:align-middle sm:w-full scale-95 opacity-0" id="modalContent">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold">
                    {{ $title }}
                </h3>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none">
                    &times;
                </button>
            </div>

            <div class="modal-body mt-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<script type="module">
    function closeModal() {
        $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            $('#modal').addClass('hidden');
        }, 300);
    }

    $('#closeModalBtn').on('click', function() {
        closeModal();
    });

    $('#modalOverlay').on('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
