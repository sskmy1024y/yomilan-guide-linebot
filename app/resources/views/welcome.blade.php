<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('/assets/css/app.css') }}">
</head>

<body>
    <div id="app">
        <app />
    </div>

    <script src="{{ mix('/assets/js/manifest.js') }}"></script>
    @if(config('app.env') === 'production')
    <script src="{{ mix('/assets/js/vendor.js') }}"></script>
    <script src="{{ mix('/assets/js/app.js') }}"></script>
    @else
    <script src="{{ mix('/assets/js/vendor.dev.js') }}"></script>
    <script src="{{ mix('/assets/js/app.dev.js') }}"></script>
    @endif
</body>

</html>
