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
    <main class="flex min-h-screen justify-center items-center" style="background-image: url('{{ asset('assets/images/login-page.png') }}'); background-size: cover; background-position: center;">
        {{ $slot }}
    </main>

    @stack('scripts')
</body>

</html>
