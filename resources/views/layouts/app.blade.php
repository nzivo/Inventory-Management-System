<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('assets/images/icon.png')}}" sizes="192x192" />

    <title>{{ config('app.name', 'FON IMS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"><!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Flowbite CDN -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.1/dist/flowbite.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app" class="w-full h-[100vh]">
        <main class="py-4 bg-gray-50 dark:bg-gray-900">
            <div class="flex flex-col items-center justify-center px-6 mx-auto md:h-screen lg:py-0">
                <a href="/" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-48 mr-2" src="{{ asset('../../assets/images/fon_logo.png') }}" alt=" logo">
                </a>
                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')
</body>

</html>
