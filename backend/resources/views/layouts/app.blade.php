<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Сайт')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="min-h-screen bg-gray-50">
    <x-menu handle="main" />

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <x-menu handle="footer" />
            <div class="text-center text-sm text-gray-400 mt-4">
                &copy; {{ date('Y') }} Все права защищены.
            </div>
        </div>
    </footer>
</div>
</body>
</html>
