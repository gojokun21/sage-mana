<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(\App\page_bundles())

    <noscript><style>.home-slider__swiper{opacity:1 !important;}</style></noscript>
  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @include('sections.header')

      {{-- Promo reducere pachete pe mobil: bandă deasupra hero-ului.
           Vizibilă doar ≤1345px (pe desktop apare în nav).
           Folosim forma PHP inline cu paranteze — în acest fișier blocul
           multi-linie ar intra în conflict cu directivele inline de mai sus. --}}
      @php($promo_live = function_exists('App\\bf_is_live') && \App\bf_is_live())
      @php($promo_deadline_ms = $promo_live ? \App\bf_deadline_timestamp() * 1000 : 0)
      @if ($promo_live && $promo_deadline_ms > 0)
        @include('partials.bf-countdown-nav', [
          'deadlineMs' => $promo_deadline_ms,
          'percent' => \App\BF_PERCENT,
          'rootClass' => 'promo-strip',
        ])
      @endif

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif

      @include('partials.newsletter')

      @include('sections.footer')
    </div>

    {{-- Mini Cart Drawer rendered at body root so it escapes the sticky
         navbar's stacking context (z-index: 999) and can layer above the
         single-product sticky price bar (z-index: 1000). --}}
    @include('partials.mini-cart')

    @include('partials.whatsapp-button')

    @include('partials.newsletter-popup')

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
