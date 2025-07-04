@extends('layouts.app')

@section('title', '404 Not Found')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-800">404</h1>
            <h2 class="text-2xl font-semibold text-gray-600 mt-4">Not Found</h2>
            <p class="text-gray-500 mt-2">{{ isset($message) ? $message : $exception->getMessage() }}</p>
            <p class="text-gray-500 mt-1">The page you are looking for could not be found.</p>
            <a href="{{ route('dashboard') }}"
                class="mt-6 inline-block px-6 py-3 bg-blue-500 text-white font-medium text-sm leading-tight uppercase rounded shadow-md hover:bg-blue-600 hover:shadow-lg focus:bg-blue-600 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-700 active:shadow-lg transition duration-150 ease-in-out">
                Go to Home
            </a>
        </div>
    </div>
@endsection
