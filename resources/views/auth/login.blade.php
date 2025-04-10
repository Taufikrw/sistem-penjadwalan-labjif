@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="username" class="block text-gray-700">Username</label>
                        <input id="username" type="text"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm @error('username') border-red-500 @enderror"
                            name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                        @error('username')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700">Password</label>
                        <input id="password" type="password"
                            class="mt-1 p-3 block w-full border-gray-300 rounded-md shadow-sm @error('password') border-red-500 @enderror"
                            name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
