@extends('layouts.app')

@section('title', 'Room Form')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <x-topbar />

            @if ($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-error px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->first('error') }}</span>
                </div>
            @endif

            <div class="p-10 flex-1">
                <h1 class="text-3xl font-bold mb-6">
                    @isset($laboratorium)
                        Edit Laboratorium
                    @else
                        Tambah Laboratorium
                    @endisset
                </h1>
    
                <form
                    action="{{ isset($laboratorium) ? route('lab.update', $laboratorium->id) : route('lab.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($laboratorium))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Laboratorium:</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', isset($laboratorium) ? $laboratorium->name : '') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="capacity" class="block text-gray-700 text-sm font-bold mb-2">Kapasitas Lab:</label>
                            <input type="number" min="1" name="capacity" id="capacity"
                                value="{{ old('capacity', isset($laboratorium) ? $laboratorium->capacity : '') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('capacity') border-red-500 @enderror"
                                required>
                            @error('capacity')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Lokasi Lab:</label>
                            <input type="text" name="location" id="location"
                                value="{{ old('location', isset($laboratorium) ? $laboratorium->location : '') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('location') border-red-500 @enderror"
                                required>
                            @error('location')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit"
                            class="bg-secondary hover:bg-secondary-40 text-tertiary border-1 border-tertiary font-bold py-2 px-8 rounded">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
