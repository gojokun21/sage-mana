{{--
  404 — pagina nu a fost găsită.
--}}

@extends('layouts.app')

@section('content')
  <section class="error-404" aria-labelledby="error-404-title">
    <div class="container">
      <div class="error-404__inner">
        <div class="error-404__badge" aria-hidden="true">404</div>

        <h1 id="error-404-title" class="error-404__title">
          {{ __('Pagina nu a fost găsită', 'sage') }}
        </h1>

        <p class="error-404__subtitle">
          {{ __('Ne pare rău, pagina pe care o cauți nu mai există sau a fost mutată. Încearcă o căutare sau întoarce-te la pagina principală.', 'sage') }}
        </p>

        <form role="search"
              method="get"
              class="error-404__search"
              action="{{ esc_url(home_url('/')) }}">
          <label for="error-404-search" class="sr-only">{{ __('Caută', 'sage') }}</label>
          <input type="search"
                 id="error-404-search"
                 name="s"
                 placeholder="{{ esc_attr__('Ce cauți?', 'sage') }}"
                 value="{{ get_search_query() }}"
                 autocomplete="off">
          <button type="submit" aria-label="{{ esc_attr__('Caută', 'sage') }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
              <path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>{{ __('Caută', 'sage') }}</span>
          </button>
        </form>

        <div class="error-404__actions">
          <a href="{{ esc_url(home_url('/')) }}" class="btn-primary error-404__btn">
            {{ __('Înapoi la pagina principală', 'sage') }}
          </a>
          <a href="{{ esc_url(home_url('/catalog/')) }}" class="error-404__btn error-404__btn--ghost">
            {{ __('Vezi catalog', 'sage') }}
          </a>
        </div>

        <div class="error-404__links">
          <span>{{ __('Sau navighează către', 'sage') }}:</span>
          <a href="{{ esc_url(home_url('/despre-noi/')) }}">{{ __('Despre noi', 'sage') }}</a>
          <a href="{{ esc_url(home_url('/contact/')) }}">{{ __('Contact', 'sage') }}</a>
          @if (function_exists('wc_get_page_permalink'))
            <a href="{{ esc_url(wc_get_page_permalink('myaccount')) }}">{{ __('Contul meu', 'sage') }}</a>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
