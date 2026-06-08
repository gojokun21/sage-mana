{{-- Cele mai vândute — bridge către quiz-ul de recomandare. --}}
<section class="bridge-quiz">
  <div class="bridge-quiz-inner">
    <div class="left">
      <div class="eyebrow-gold">{{ __('Ghid onest · 60 secunde', 'sage') }}</div>
      <h2>{{ __('Niciunul nu ți se', 'sage') }} <em>{{ __('potrivește exact?', 'sage') }}</em></h2>
      <p>{{ __('Aceste 5 sunt cele mai cerute, dar nu sunt singurele potrivite pentru tine. Testul de 60 secunde recomandă exact ce ai nevoie pe baza simptomelor și stilului tău de viață.', 'sage') }}</p>
    </div>
    <div class="right-side">
      <a class="quiz-cta" href="{{ esc_url(home_url('/test/')) }}">{{ __('Începe testul', 'sage') }}
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
      <span class="micro">{{ __('7 întrebări · fără email · anonim', 'sage') }}</span>
    </div>
  </div>
</section>
