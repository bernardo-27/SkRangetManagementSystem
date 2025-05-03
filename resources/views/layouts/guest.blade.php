<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ranget SK Management System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex flex-col lg:justify-center items-center pt-6 sm:pt-0 ">

            {{-- alert when success registered --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                {{ session('success')}}
            </div>

            <script>
                setTimeout(function(){
                    let alert = document.getElementById('alert-success');
                    if (alert) {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                        setTimeout(() => alert.style.display = 'absolute', 500);
                    }
                }, 2000);
            </script>
        @endif

{{-- image in Register and login/register --}}
            <div>
                <a href="/" class="flex items-center mt-6">
                    <img src="{{ asset('images/ranget.png') }}" alt="Logo" style="height: 100px; width: auto; margin:0 30px 0 0; z-index: 100;">
                    <img src="{{ asset('images/sk-ranget.png') }}" alt="Logo" style="height: 100px; width: auto; margin:0  0 0 30px; z-index: 100;">
                </a>
            </div>
{{-- contents --}}
            <div class="w-full lg:max-w-md bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
