{{--
  Hero homepage — adaptat după mockup-ul `preferinte/Mana Naturii - Homepage.html`.
  Conținut hardcoded (wrapped în __()). ACF `hero_section` nu mai e folosit aici.
--}}
<section class="hero" aria-label="{{ esc_attr__('Prezentare', 'sage') }}">
  <div class="hero-grid">
    <div class="hero-left">
      <div class="eyebrow">{{ __('Ediția de toamnă · lot R24-091', 'sage') }}</div>
      <h1>
        {{ __('Suplimente onest formulate.', 'sage') }}
        <em>{{ __('Fără hype, fără promisiuni de basm.', 'sage') }}</em>
      </h1>
      <p class="lede">
        {!! __('Fiecare produs vine cu <strong>raportul de analiză al lotului</strong>, etichetă INCI scurtă și un protocol în 3 etape. Dacă ceva nu funcționează, îți recomandăm pe altul — sau îți spunem că nu ai nevoie de niciunul.', 'sage') !!}
      </p>
      <div class="hero-ctas">
        <a class="btn btn-primary btn-lg" href="{{ function_exists('wc_get_page_id') ? esc_url(get_permalink(wc_get_page_id('shop'))) : esc_url(home_url('/magazin/')) }}">
          {{ __('Vezi suplimentele', 'sage') }}
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
          </svg>
        </a>
        <a class="btn btn-ghost-green btn-lg" href="#test">{{ __('Fă testul de 60 sec', 'sage') }}</a>
      </div>
      <div class="hero-trust">
        <span class="t">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
          {{ __('Analize publice', 'sage') }}
        </span>
        <span class="t">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
          {{ __('Retur 14 zile', 'sage') }}
        </span>
        <span class="t">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
          {{ __('Anulare abonament în 2 click-uri', 'sage') }}
        </span>
      </div>
    </div>

    <div class="hero-right">
      <div class="hero-stage">
        <img class="hero-img"
             src="{{ esc_url(home_url('/wp-content/uploads/2026/06/ChatGPT-Image-2-iun.-2026-14_23_01.webp')) }}"
             alt="{{ esc_attr__('Suplimente Mâna Naturii', 'sage') }}"
             loading="eager" decoding="async" fetchpriority="high" />
      </div>
    </div>
  </div>
</section>
