{{-- Hub După simptom — hero cu index + căutare + figură corporală. --}}
<section class="hero">
  <div class="hero-grid">
    <div>
      <div class="eyebrow">{{ __('Index · 32 simptome grupate', 'sage') }}</div>
      <h1>{{ __('După simptom.', 'sage') }} <em>{{ __('Nu după ce sună mai bine.', 'sage') }}</em></h1>
      <p class="lede">{{ __('Alege simptomul care te aduce aici, nu numele unui supliment. Înăuntru, găsești ce ți se întâmplă, când e doar o reacție temporară și când merită mai multă atenție. Dacă simptomul tău nu apare aici, scrie-ne pe WhatsApp.', 'sage') }}</p>
      <div class="hero-search">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input
          type="search"
          id="symptomSearch"
          autocomplete="off"
          placeholder="{{ esc_attr__('Caută simptomul (ex: balonare, oboseală)', 'sage') }}"
          aria-label="{{ esc_attr__('Caută simptomul', 'sage') }}"
        />
      </div>
    </div>

    <div class="hero-illu" aria-hidden="true">
      <div class="body-figure">
        <svg viewBox="0 0 200 400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="100" cy="40" r="30"/>
          <path d="M100 70 L100 95 M60 95 Q100 80 140 95 L150 220 Q150 230 140 230 L120 230 L120 380 M80 230 L60 230 Q50 230 50 220 L60 95"/>
          <path d="M80 230 L80 380"/>
          <path d="M70 110 L40 200 M130 110 L160 200"/>
        </svg>
        <div class="body-dot d-head"></div>
        <div class="body-dot d-chest"></div>
        <div class="body-dot d-abdomen"></div>
        <div class="body-dot d-leg"></div>
        <div class="body-dot d-label lh">{{ __('cap', 'sage') }}</div>
        <div class="body-dot d-label lc">{{ __('piept', 'sage') }}</div>
        <div class="body-dot d-label la">{{ __('abdomen', 'sage') }}</div>
        <div class="body-dot d-label ll">{{ __('picioare', 'sage') }}</div>
      </div>
    </div>
  </div>
</section>
