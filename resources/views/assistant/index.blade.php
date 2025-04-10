@extends('layouts.app')

@section('title', 'Assistant')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg">Assistant</h1>
            @forelse ($assistants as $item)
                <div class="bg-gray-100 p-4 mb-4 rounded-lg">
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
