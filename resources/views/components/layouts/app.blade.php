<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <main class="flex">
        <x-sidebar />

        <div class="flex-1 flex flex-col bg-[#F5FAFB]">
            <div class="p-10 flex flex-col gap-6">
                {{ $back_button ?? '' }}
                
                <div class="flex flex-col gap-2 h-14 justify-between">
                    <h1 class="text-3xl text-key-primary font-bold">{{ $title ?? '' }}</h1>
                    <hr class="border-[#F4F0EF]">
                </div>

                {{ $slot }}
            </div>
        </div>
    </main>

    @stack('scripts')
</body>

</html>
