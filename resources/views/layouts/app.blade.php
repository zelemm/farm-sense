<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Farm Sense') }}</title>

    <link rel="preload" href="{{ mix('/js/app.js') }}" as="script">
    <link rel="preload" href="{{ mix('css/app.css') }}" as="style">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

{{--    <link rel="icon" type="image/png" href="/img/logo-small" />--}}

    <script>
        var user_lang = 'en';
        @auth
        user_lang = '{{ (Auth::user()->lang) ?? 'en' }}';
        var user_type = '{{ Auth::user()->user_type }}';
        @endauth
    </script>

    {{-- Ping CRM --}}
    <script src="https://polyfill.io/v3/polyfill.min.js?features=String.prototype.startsWith" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <link href="{{ asset('css/googleFont.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <script>
        var mapApiKey = '{{ config("services.googlemap.key") }}';
        var env = '{{ config("app.env") }}';
    </script>
</head>
<body>
    <div id="app"></div>
</body>
</html>
