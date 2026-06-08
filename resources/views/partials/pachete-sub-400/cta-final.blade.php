{{-- Pachete sub 400 lei — CTA final (quiz de recomandare). --}}
<section class="cta-final">
  <div class="cta-final-inner">
    <h2>{{ __('Nu ești sigur', 'sage') }} <em>{{ __('ce pachet ți se potrivește?', 'sage') }}</em></h2>
    <p>{{ __('Testul de 60 secunde îți recomandă onest care din cele 4 pachete e cel mai potrivit — sau dacă ai nevoie de un pachet de 3 produse în loc.', 'sage') }}</p>
    <a class="btn" href="{{ esc_url(home_url('/test/')) }}">{{ __('Începe testul', 'sage') }}
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>
