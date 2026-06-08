{{-- Banda verde închis cu CTA către pagina „După obiectiv". --}}
<section class="cross">
  <div class="cross-inner">
    <div>
      <h2>{{ __('Nu știi', 'sage') }} <em>{{ __('exact', 'sage') }}</em> {{ __('ce să alegi?', 'sage') }}</h2>
      <p>{{ __('Pleacă de la obiectivul tău, nu de la ingrediente. Îți recomandăm produsul potrivit în două click-uri.', 'sage') }}</p>
    </div>
    <a class="cross-cta" href="{{ esc_url(home_url('/dupa-obiectiv/')) }}">
      {{ __('Mergi la După obiectiv', 'sage') }}
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
      </svg>
    </a>
  </div>
</section>
