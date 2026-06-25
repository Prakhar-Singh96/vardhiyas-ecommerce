<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('meta', 'Vardiyash — Premium Sportswear & Combat Gear')">
  <title>@yield('title', 'Vardhiyas')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  {{-- ✅ Swiper CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

  <link href="{{ asset('frontend/assets/css/custom.css') }}" rel="stylesheet">

  @stack('styles')
</head>
<body>

  @include('frontend.layout.header')

  <main>@yield('content')</main>

  @include('frontend.layout.footer')

  {{-- ✅ NAYE GLOBAL COMPONENTS --}}
  @include('frontend.partials.cart-drawer')
  @include('frontend.partials.checkout-modal')

  <button id="btt" aria-label="Back to top">
    <i class="bi bi-arrow-up"></i>
  </button>

  {{-- ✅ Swiper JS — custom.js se PEHLE --}}
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  {{-- ✅ custom.js with cache-busting (file change hote hi URL badlega) --}}
  <script src="{{ asset('frontend/assets/js/custom.js') }}?v={{ filemtime(public_path('frontend/assets/js/custom.js')) }}"></script>

  @stack('scripts')
</body>
</html>