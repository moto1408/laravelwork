<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    @yield('header')
</head>
<body>
    <div class="pos-f-t">
        <nav class="navbar navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
        <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-dark p-4">
                <h5 class="text-white h4">Collapsed content</h5>
                <span class="text-muted">Toggleable via the navbar brand.</span>
            </div>
        </div>
    
    </div>
    <div class="container">
        @yield('content')
    </div>
    @yield('footer')
    @yield('modal')
</body>
</html>
