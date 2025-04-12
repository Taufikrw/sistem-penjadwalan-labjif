@extends('layouts.app')

@section('title', 'Assistant')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg mb-4">Assistant</h1>

            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('assistant.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Asisten
            </a>

            @forelse ($assistants as $item)
                <div class="bg-gray-100 p-4 mb-4 rounded-lg mt-4">
                    <h3 class="text-lg font-semibold">{{ $item->name }}</h3>
                    <p class="text-gray-700">{{ $item->nim }}</p>
                    <a href="{{ route('assistant.show', $item->nim) }}" class="text-blue-500 hover:underline">View Details</a>
                </div>
            @empty
                <p>Tidak ada data assistant</p>
            @endforelse
        </div>
    </div>
@endsection
