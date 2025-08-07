<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('images/bsrplogo.png') }}" type="image/png">
  <title>@yield('title', 'BS:RP | UCP')</title>

  <style>
    @keyframes spin-slow {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @keyframes spin-reverse-slow {
      0% { transform: rotate(360deg); }
      100% { transform: rotate(0deg); }
    }

    .animate-spin-slow {
      animation: spin-slow 2s linear infinite;
    }

    .animate-spin-reverse-slow {
      animation: spin-reverse-slow 2.5s linear infinite;
    }
  </style>


  {{-- Styles --}}
  @stack('styles')
</head>

<body class="bg-[url('/images/mainbg.jpg')] bg-cover bg-center min-h-screen">
  <!-- Loader Overlay -->
  <div id="loader" class="fixed inset-0 bg-white/0 bg-opacity-0 backdrop-blur-xs z-60 flex flex-col justify-center items-center">
    <div class="relative w-32 h-32">
      <!-- Outer circle -->
      <div class="absolute inset-0 rounded-full border-4 border-[#410000] border-t-transparent animate-spin-slow"></div>

      <!-- Inner circle -->
      <div class="absolute inset-4 rounded-full border-4 border-red-900 border-b-transparent animate-spin-reverse-slow"></div>

      <!-- Logo in center -->
      <img src="{{ asset('images/bsrplogo.png') }}" alt="Logo" class="w-25 h-25 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 animate-spin-slow">
    </div>

    <!-- Loading text -->
    <p class="mt-6 text-lg font-semibold text-white">Loading...</p>
  </div>

  @yield('content')


  {{-- Scripts --}}
  @stack('scripts')

<script>
    // Simulate loading time (2 seconds)
    /*document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('page-content').classList.remove('hidden');
        }, 5000); // You can shorten or lengthen this time as needed
    });*/

  window.addEventListener("load", function () {
    setTimeout(() => {
      document.getElementById("loader").style.display = "none";
    }, 5000);
  });
</script>


</body>
</html>