<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Laravel 8 / php 8')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>

@include('components.header')
<main>
    @section('main')
    @show
</main>
@include('components.footer')


<script src="{{ asset('js/main.js') }}" defer></script>
@section('additional-scripts')
@show

</body>
</html>
