<!doctype html>
<html lang="ja">

<head>
  @include('includes.partials.page_meta')

  @if(config('app.env') === 'production')
  <link rel="stylesheet" href="{{ secure_asset(mix('assets/css/app.css')) }}" type="text/css">
  @else
  <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}" type="text/css">
  @endif

  <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
</head>

<body>
  <div id="app" class="container">
    @yield('content')
  </div>

  @if(config('app.env') === 'production')
  <script src="{{ secure_asset(mix('assets/js/manifest.js')) }}"></script>
  <script src="{{ secure_asset(mix('assets/js/vendor.js')) }}"></script>
  <script src="{{ secure_asset(mix('assets/js/app.js')) }}"></script>
  @else
  <script src="{{ mix('assets/js/manifest.js') }}"></script>
  <script src="{{ mix('assets/js/vendor.dev.js') }}"></script>
  <script src="{{ mix('assets/js/app.dev.js') }}"></script>
  @endif

</body>

</html>
