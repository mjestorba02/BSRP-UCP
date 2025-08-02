<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>BS:RP | Dashboard</title>
  <link rel="icon" href="{{ asset('images/bsrplogo.png') }}" type="image/x-icon">

  {{-- Styles --}}
  @stack('styles')
</head>
<body class="bg-[url('/images/mainbg.jpg')] bg-cover bg-center min-h-screen">

  {{-- Main Page Content --}}
  @yield('content')

  {{-- Scripts --}}
  @stack('scripts')

</body>
</html>