<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('component.css')
        <title>Karya Asuh</title>
    </head>
    <body class="page-content">
        <div class="row">
            @include('component.navbar')
        </div>
        <div class="container-fluid page-content">
            @yield('page')
        </div>
    </body>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</html>
