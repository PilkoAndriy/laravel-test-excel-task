<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.head')
</head>
<body>
<div class="container mx-auto font-sans text-gray-900 antialiased" id="app">
    @include('partials.header')
    <main>
        @yield('content')
    </main>
</div>
@include('partials.script')
</body>
</html>
