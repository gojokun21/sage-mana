{{-- About — CTA final. --}}
<section class="cta-final">
  <div class="cta-final-inner">
    <h2>{{ __('Ești pregătit', 'sage') }} <em>{{ __('să începi?', 'sage') }}</em></h2>
    <p>{!! wp_kses(__('Catalogul are <strong>20 de suplimente</strong> și <strong>11 pachete</strong>. Dacă nu știi cu ce să începi, testul de 60 secunde îți recomandă exact ce ți se potrivește — sau dacă nu e momentul să cumperi nimic acum.', 'sage'), ['strong' => []]) !!}</p>
    <div class="cta-buttons">
      <a class="primary" href="{{ esc_url(home_url('/test/')) }}">{{ __('Fă testul de 60 secunde', 'sage') }}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
      <a class="outline" href="{{ esc_url(home_url('/magazin/')) }}">{{ __('Vezi catalogul', 'sage') }}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>
