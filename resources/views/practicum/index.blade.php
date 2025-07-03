@extends('layouts.app')

@section('title', 'Practicum')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex-col">
            <x-topbar />

            @if (session('success'))
                <div class="bg-green-500 text-white px-4 py-3 relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="p-10 flex-1">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Daftar Praktikum</h1>
                    <div
                        class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                        <button id="btn-create-practicum"
                            class="bg-secondary font-bold py-2 px-4 rounded border-1 border-tertiary hover:bg-secondary-70 cursor-pointer">
                            <p class="text-tertiary text-sm">Tambah</p>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="bg-table-header">
                            <tr>
                                <th scope="col"
                                    class="sortable pl-6 py-3 text-left text-sm font-medium text-black tracking-wider cursor-pointer w-1/8"
                                    data-sort="kode_praktikum">
                                    <span class="flex justify-between items-center w-full">
                                        <span>Kode Praktikum</span>
                                        <span class="sort-indicator"></span>
                                    </span>
                                </th>
                                <th scope="col"
                                    class="sortable pl-6 py-3 text-left text-sm font-medium text-black tracking-wider cursor-pointer w-1/2"
                                    data-sort="name">
                                    <span class="flex justify-between items-center w-full">
                                        <span>Nama</span>
                                        <span class="sort-indicator"></span>
                                    </span>
                                </th>
                                <th scope="col"
                                    class="sortable pl-6 py-3 text-left text-sm font-medium text-black tracking-wider cursor-pointer w-2/16"
                                    data-sort="for_prodi">
                                    <span class="flex justify-between items-center w-full">
                                        <span>Program Studi</span>
                                        <span class="sort-indicator"></span>
                                    </span>
                                </th>
                                <th scope="col"
                                    class="sortable pl-6 py-3 text-left text-sm font-medium text-black tracking-wider cursor-pointer w-1/16"
                                    data-sort="semester">
                                    <span class="flex justify-between items-center w-full">
                                        <span>Semester</span>
                                        <span class="sort-indicator"></span>
                                    </span>
                                </th>
                                <th scope="col"
                                    class="pl-6 py-3 text-left text-sm font-medium text-black tracking-wider w-1/16">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="data-list" class="divide-y-2 divide-table-header border-b-2 border-table-header">
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                    <div class="flex justify-center items-center gap-4">
                                        <div
                                            class="border-gray-300 h-8 w-8 animate-spin rounded-full border-5 border-t-secondary">
                                        </div>
                                        Loading...
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="pagination"></div>
                </div>
            </div>
        </div>
    </div>

    <x-modal title="Form Praktikum">
        @include('practicum.form')
    </x-modal>
@endsection

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            let currentSortBy = 'kode_praktikum';
            let currentSortOrder = 'asc';
            let currentPage = 1;

            function loadAllPracticum(sortBy = currentSortBy, sortOrder = currentSortOrder, page = 1) {
                currentSortBy = sortBy;
                currentSortOrder = sortOrder;
                currentPage = page;
                $.ajax({
                    url: '{{ route('api.practicum.data') }}' + '?sort_by=' + sortBy + '&sort_order=' +
                        sortOrder + '&page=' + page,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#data-list').html(
                            `<tr>
                                <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                    <div class="flex justify-center items-center gap-4">
                                        <div class="border-gray-300 h-8 w-8 animate-spin rounded-full border-5 border-t-secondary"></div>
                                        Loading...
                                    </div>
                                </td>
                            </tr>`
                        );
                    },
                    success: function(response) {
                        $('#data-list').empty();
                        if (response.data.data.length > 0) {
                            $.each(response.data.data, function(index, item) {
                                let row = `
                                    <tr>
                                        <td class="pl-6 py-4 whitespace-nowrap">${item.kode_praktikum}</td>
                                        <td class="pl-6 py-4 whitespace-nowrap">${item.name}</td>
                                        <td class="pl-6 py-4 whitespace-nowrap">${item.for_prodi}</td>
                                        <td class="pl-6 py-4 whitespace-nowrap">${item.semester}</td>
                                        <td class="pl-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                            <button data-id="${item.kode_praktikum}" class="btn-edit"><x-heroicon-o-pencil-square class="w-5 h-5 text-extended-1 hover:text-extended-light cursor-pointer"/></button>
                                            <button data-id="${item.kode_praktikum}" class="btn-delete"><x-heroicon-o-trash class="w-5 h-5 text-error hover:text-error-darked cursor-pointer"/></button>
                                        </td>
                                    </tr>
                                `;
                                $('#data-list').append(row);
                            });
                        } else {
                            $('#data-list').html(
                                `<tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-neutral-50">
                                        Tidak ada data asisten.
                                    </td>
                                </tr>`
                            );
                        }

                        renderPagination(response.data);
                    },
                    error: function(xhr, status, error) {
                        $('#data-list').html(
                            '<tr><td colspan="5" class="text-center text-red-500">Gagal memuat data. Silakan coba lagi.</td></tr>'
                        );
                    }
                });
            }

            function renderPagination(response) {
                let paginationHtml = '<div class="flex justify-end mt-6">';
                if (response.last_page > 1) {
                    for (let i = 1; i <= response.last_page; i++) {
                        paginationHtml +=
                            `<button class="pagination-btn px-3 py-1 mx-1 rounded cursor-pointer ${i === response.current_page ? 'bg-secondary text-white hover:bg-secondary-70' : 'bg-gray-200 hover:bg-gray-300'}" data-page="${i}">${i}</button>`;
                    }
                }
                paginationHtml += '</div>';
                $('#pagination').html(paginationHtml);
            }

            $(document).on('click', '.sortable', function() {
                let sortBy = $(this).data('sort');
                let sortOrder = 'asc';

                // Toggle sort order jika kolom yang sama diklik
                if (currentSortBy === sortBy) {
                    sortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
                }

                // Panggil ulang AJAX dengan parameter baru
                loadAllPracticum(sortBy, sortOrder);

                // Update indikator sort
                $('.sort-indicator').html('');
                if (sortOrder === 'asc') {
                    $(this).find('.sort-indicator').html(
                        `<x-heroicon-o-arrow-up class="w-4 h-4 text-black"/>`);
                } else {
                    $(this).find('.sort-indicator').html(
                        `<x-heroicon-o-arrow-down class="w-4 h-4 text-black"/>`);
                }
            });

            $(document).on('click', '.pagination-btn', function() {
                let page = $(this).data('page');
                loadAllPracticum(currentSortBy, currentSortOrder, page);
            });

            function openModal() {
                $('#modal').removeClass('hidden');
                setTimeout(() => {
                    $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                }, 10);
            }

            loadAllPracticum();

            $('#btn-create-practicum').on('click', function() {
                openModal();

                $.ajax({
                    url: '{{ route('practicum.create') }}',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#modal .modal-body').html(`
                            <div class="flex justify-center items-center gap-4">
                                <div class="border-gray-300 h-8 w-8 animate-spin rounded-full border-5 border-t-secondary"></div>
                                Loading...
                            </div>
                        `);
                    },
                    success: function(response) {
                        $('#modal .modal-body').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#modal .modal-body').html(
                            '<p class="text-red-500 text-center">Gagal memuat formulir. Silakan coba lagi.</p>'
                        );
                    }
                });
            });

            $(document).on('submit', '#form-practicum', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let formData = form.serialize();
                $('.text-red-500').remove();
                $('input, textarea').removeClass('border-red-500');

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        form.find('button[type="submit"]').removeClass('px-8').html(`
                            <div class="flex justify-center items-center gap-2 px-4">
                                <div class="border-gray-100 h-5 w-5 animate-spin rounded-full border-3 border-t-tertiary"></div>
                                Mengirim...
                            </div>
                        `).prop('disabled', true);
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        form.find('button[type="submit"]').text('Submit').prop('disabled',
                            false);

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                let inputElement = $(`[name="${key}"]`);
                                inputElement.addClass('border-red-500');
                                inputElement.after(
                                    `<p class="text-red-500 text-sm mt-1">${value[0]}</p>`
                                );
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Validasi!',
                                text: xhr.responseJSON.message ||
                                    'Mohon periksa kembali input Anda.',
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: xhr.responseJSON.message ||
                                    'Maaf, terjadi kesalahan pada server. Silakan coba lagi.',
                                showConfirmButton: true
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.btn-edit', function() {
                let kodePraktikum = $(this).data('id');
                openModal();

                $.ajax({
                    url: '{{ url('practicum') }}/' + kodePraktikum,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#modal .modal-body').html(`
                            <div class="flex justify-center items-center gap-4">
                                <div class="border-gray-300 h-8 w-8 animate-spin rounded-full border-5 border-t-secondary"></div>
                                Loading...
                            </div>
                        `);
                    },
                    success: function(response) {
                        $('#modal .modal-body').html(response);
                    },
                    error: function(xhr, status, error) {
                        modalBody.html(
                            '<p class="text-red-500 text-center">Gagal memuat formulir. Silakan coba lagi.</p>'
                        );
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                let kodePraktikum = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Apakah Anda yakin ingin menghapus praktikum ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url('practicum') }}/' + kodePraktikum,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    loadAllPracticum();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON.message ||
                                        'Terjadi kesalahan saat menghapus praktikum.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
