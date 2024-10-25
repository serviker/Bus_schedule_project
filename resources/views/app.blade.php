{{--<!DOCTYPE html>--}}
{{--<html lang="ru">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <meta name="language" content="ru"> <!-- Указываем язык -->--}}
{{--    <title>Bus Schedule</title>--}}
{{--    @routes--}}
{{--    @viteReactRefresh--}}
{{--    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.jsx"])--}}
{{--    @inertiaHead--}}
{{--    <!-- Подключение JS -->--}}
{{--</head>--}}
{{--<body>--}}
{{--@inertia--}}
{{--<div class="container">--}}
{{--    <div id="app">--}}
{{--        @yield('content')--}}
{{--    </div>--}}
{{--    <script type="module" src="{{ asset('js/app.js') }}"></script>--}}

{{--</div>--}}
{{--</body>--}}
{{--</html>--}}
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="language" content="ru"> <!-- Указываем язык -->
    <title>Bus Schedule</title>

    {{-- Подключение Vite для управления ассетами --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
<div class="container">
    <div id="app">
        @yield('content')
    </div>
</div>
</body>
</html>
