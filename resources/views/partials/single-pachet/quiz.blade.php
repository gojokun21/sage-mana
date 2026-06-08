{{-- Single PACHET — mini CTA quiz (static). --}}
<section class="pachet-quiz">
  <div class="quiz-card">
    <div class="ico" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
    <h3>{{ __('Nu știi dacă', 'sage') }} <em>{{ __('e pentru tine?', 'sage') }}</em></h3>
    <p>{{ __('Fă testul de 60 sec — îți spunem onest dacă acest pachet merge sau dacă altul e mai potrivit.', 'sage') }}</p>
    <a href="{{ esc_url(home_url('/test/')) }}">{{ __('Mergi la test', 'sage') }}
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>
