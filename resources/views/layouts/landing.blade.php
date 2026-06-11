<!doctype html>
{{--
  Landing layout — minimal shell for conversion-focused pages.
  Intentionally OMITS the global header/footer/menu/popups to keep the
  visitor's attention on a single goal (form submission). Templates that
  use this layout render their own brand bar + slim footer.
--}}
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())

    {{-- Editorial display serif used by the landing pages (AG1-style). --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500;1,9..144,600&family=Rubik:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap">

    @vite(\App\page_bundles())
  </head>

  <body @php(body_class('landing-body'))>
    @php(wp_body_open())

    <div id="app" class="landing-app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      <main id="main" class="landing-main">
        @yield('content')
      </main>
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
