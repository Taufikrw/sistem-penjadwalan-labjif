<x-layouts.guest>
    <div class="w-1/2 bg-white flex items-center justify-center px-32 rounded-l-3xl flex-col relative">
        <img src="{{ asset('assets/images/Logo.svg') }}" alt="logo lab jif" class="absolute top-14 right-14">
        <h2 class="text-5xl text-key-primary font-extrabold text-left w-full @error('validation') mb-6 @enderror mb-12">
            Login</h2>

        @error('validation')
            <span
                class="bg-[#FFEDEA] border border-[#FFB4AB] h-14 w-full flex justify-center items-center text-md gap-3 rounded mb-6">
                {{ $message }}
                <x-heroicon-s-x-mark class="w-5 h-5 text-red-500" />
            </span>
        @enderror

        <form method="POST" action="{{ route('login.store') }}" class="w-full">
            @csrf

            <div class="mb-4">
                <x-input-label for="username" class="mb-2" value="Username" />
                <x-text-input name="username" id="username" class="w-full" :value="old('username')" placeholder="Username"
                    required />
                @error('username')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-16">
                <x-input-label for="password" class="mb-2" value="Password" />
                <x-text-input name="password" id="password" type="password" class="w-full" placeholder="Password"
                    required />
                @error('password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <x-button-primary class="w-full py-4 text-xl rounded-2xl" type="submit">
                    Login
                </x-button-primary>
            </div>
        </form>
    </div>

    <div class="w-1/2 text-white flex flex-col items-center justify-center p-8 rounded-r-3xl relative overflow-hidden"
        style="background-image: url('{{ asset('assets/images/login-page.png') }}'); background-size: cover; background-position: center;">
        <div class="mb-4 w-full px-8">
            <h2 class="text-5xl font-extrabold mb-2">Sistem Penjadwalan</h2>
            <h2 class="text-5xl font-extrabold">Lab Jurusan Informatika</h2>
        </div>
        <img src="{{ asset('assets/images/pair-aslab-transparant.svg') }}" alt="">
    </div>
</x-layouts.guest>
