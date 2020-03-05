<!doctype html>
<html lang="ja">

<head>
  @include('includes.partials.page_meta')
  <link rel="stylesheet" href="{{ asset(mix('assets/css/app.css')) }}" type="text/css">
  <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
</head>

<body>
  <div id="app" class="container">
    @yield('content')
  </div>

  <script src="{{ asset(mix('assets/js/manifest.js')) }}"></script>
  @if(config('app.env') === 'production')
  <script src="{{ asset(mix('assets/js/vendor.js')) }}"></script>
  <script src="{{ asset(mix('assets/js/app.js')) }}"></script>
  @else
  <script src="{{ asset(mix('assets/js/vendor.dev.js')) }}"></script>
  <script src="{{ asset(mix('assets/js/app.dev.js')) }}"></script>
  @endif

</body>

</html>
