@extends('layouts.app')

@section('title', 'Laboratoriums')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <x-topbar />

            @if (session('success'))
                <div class="bg-green-500 text-white px-4 py-3 relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="p-10 flex-1">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Daftar Laboratorium</h1>
                    <div
                        class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
                        <button id="btn-create-lab" onclick="showDynamicModal()"
                            class="bg-secondary font-bold py-2 px-4 rounded border-1 border-tertiary hover:bg-secondary-70 cursor-pointer">
                            <p class="text-tertiary text-sm">Tambah</p>
                        </button>
                    </div>
                </div>

                <x-data-table url="{{ route('api.laboratorium.data') }}" action-url="lab/" :columns="[
                    ['label' => 'Nama', 'field' => 'name'],
                    ['label' => 'Lokasi', 'field' => 'location'],
                    ['label' => 'Kapasitas', 'field' => 'capacity'],
                ]"
                    :has-actions="true" table-id="laboratorium-table"/>
            </div>
        </div>
    </div>

    <x-form-modal modal-id="laboratoriumModal" title="Formulir Laboratorium Baru" ajax-url="{{ route('lab.create') }}"
        action-url="lab/" form-id="lab-form" />
@endsection
