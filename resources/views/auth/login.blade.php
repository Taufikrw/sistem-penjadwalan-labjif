@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="flex min-h-screen">
        <div class="w-7xl h-screen pl-20 bg-[#1B277C]">
            <div class="bg-[#0B1870] h-screen justify-center items-center flex flex-col shadow-xl/25">
                <h2 class="text-5xl text-white font-extrabold text-left w-full px-28 mb-12">Login</h2>

                <form method="POST" action="{{ route('login.store') }}" class="w-full px-28">
                    @csrf

                    <div class="mb-4">
                        <input id="username" type="text"
                            class="block w-full py-3 border-b border-[#C8C6C6] placeholder-[#C8C6C6] focus:outline-none focus:border-white text-white bg-transparent @error('username') border-red-500 @enderror"
                            name="username" value="{{ old('username') }}" required autocomplete="username"
                            placeholder="Username">
                        @error('username')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-12">
                        <input id="password" type="password"
                            class="block w-full py-3 border-b border-[#C8C6C6] placeholder-[#C8C6C6] focus:outline-none focus:border-white text-white bg-transparent @error('password') border-red-500 @enderror"
                            name="password" required autocomplete="current-password" placeholder="Password">
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="w-full bg-[#FFAF10] hover:bg-[#ED982E] text-white font-bold py-2 px-4 rounded-4xl focus:outline-none cursor-pointer">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="w-full h-screen pr-20 bg-[#4868FF]">
            <div class="bg-[#4868FF] h-screen justify-center items-center flex flex-col shadow-[10px_0px_10px_0px_rgba(0,0,0,0.1)]">
                <h2 class="text-5xl text-white font-extrabold mb-3 text-center">Asisten Laboratorium</h2>
                <h2 class="text-5xl text-white font-extrabold mb-3 text-center">Jurusan Informatika</h2>
                <img src="{{ asset('assets/images/pair-aslab-transparant.svg') }}" alt="">
            </div>
        </div>
    </div>
@endsection
