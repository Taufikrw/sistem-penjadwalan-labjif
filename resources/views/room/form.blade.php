@extends('layouts.app')

@section('title', 'Room Form')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg mb-4">Tambah Room</h1>
            @if ($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">{{ $errors->first('error') }}</span>
                </div>
            @endif

            <form
                action="{{ isset($room) ? route('room.update', $room->id) : route('room.store') }}"
                method="POST">
                @csrf
                @if (isset($room))
                    @method('PUT')
                @endif

                <div class="mb-6">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Ruangan:</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', isset($room) ? $room->name : '') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ isset($room) ? 'Update' : 'Create' }}
                </button>
            </form>
        </div>
    </div>
@endsection
