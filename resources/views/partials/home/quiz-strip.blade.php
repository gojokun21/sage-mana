{{-- Quiz strip — bară verde închisă cu CTA. --}}
<section class="quiz-strip" id="test">
  <div class="quiz-grid">
    <div class="left">
      <div class="eyebrow eyebrow-gold">{{ __('Test de 60 secunde', 'sage') }}</div>
      <h2>{{ __('Nu știi de unde', 'sage') }} <em>{{ __('să începi?', 'sage') }}</em></h2>
      <p>{{ __('7 întrebări scurte. Niciun email cerut până nu vrei rezultatul detaliat. Niciun upsell agresiv — dacă nu ai nevoie de nimic, îți spunem direct.', 'sage') }}</p>
    </div>
    <div class="right">
      <a class="quiz-cta" href="#test-start">
        {{ __('Începe testul', 'sage') }}
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
        </svg>
      </a>
      <div class="micro">{{ __('Folosit de 8.234 oameni în ultima lună.', 'sage') }}</div>
    </div>
  </div>
</section>
