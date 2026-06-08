{{-- Hub După obiectiv — hero cu index + căutare + figură (identic cu symptom/hero). --}}
<section class="hero">
  <div class="hero-grid">
    <div>
      <div class="eyebrow">{{ __('Index · 10 obiective grupate', 'sage') }}</div>
      <h1>{{ __('După obiectiv.', 'sage') }} <em>{{ __('Nu după ce e la modă.', 'sage') }}</em></h1>
      <p class="lede">{{ __('Alege ce vrei să obții — mai multă energie, imunitate, focus sau echilibru — nu numele unui supliment. Înăuntru găsești ce contează pentru fiecare obiectiv, ce ingrediente chiar funcționează și de unde să începi. Dacă obiectivul tău nu apare aici, scrie-ne pe WhatsApp.', 'sage') }}</p>
      <div class="hero-search">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input
          type="search"
          id="symptomSearch"
          autocomplete="off"
          placeholder="{{ esc_attr__('Caută obiectivul (ex: energie, imunitate)', 'sage') }}"
          aria-label="{{ esc_attr__('Caută obiectivul', 'sage') }}"
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
        <div class="body-dot d-label lh">{{ __('minte', 'sage') }}</div>
        <div class="body-dot d-label lc">{{ __('imunitate', 'sage') }}</div>
        <div class="body-dot d-label la">{{ __('digestie', 'sage') }}</div>
        <div class="body-dot d-label ll">{{ __('energie', 'sage') }}</div>
      </div>
    </div>
  </div>
</section>
