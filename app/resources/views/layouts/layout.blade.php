<!doctype html>
<html lang="ja">

<head>
  @include('includes.partials.page_meta')
  <link rel="stylesheet" href="{{ mix('/assets/css/app.css') }}" type="text/css">
</head>

<body>
  <div id="app" class="container">
    @yield('content')
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
