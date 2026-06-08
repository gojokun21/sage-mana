{{-- Promoții — hero. --}}
<section class="promo-hero">
  <div class="promo-hero-inner">
    <div>
      <div class="eyebrow">{{ __('Promoții', 'sage') }}</div>
      <h1>{{ __('Prețuri mai mici.', 'sage') }} <em>{{ __('Același', 'sage') }}</em> {{ __('respect pentru ce pui în corp.', 'sage') }}</h1>
      <p class="lede">{{ __('Ofertele lunii curente. Nu expiră mâine, nu te stresează cu cronometre. Iei când ești pregătit.', 'sage') }}</p>
      <div class="hero-ctas">
        <a class="btn-terra" href="#offers">{{ __('Vezi toate ofertele', 'sage') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
        <a class="btn-ghost" href="#filtre">{{ __('Filtrează după obiectiv', 'sage') }}</a>
      </div>
      @if ($offer_count > 0)
        <div class="promo-stats">
          <span>{{ sprintf(_n('%d produs în ofertă', '%d produse în ofertă', $offer_count, 'sage'), $offer_count) }}</span>
          @if ($disc_max > 0)
            <span class="sep">·</span>
            <span>{{ $disc_min === $disc_max ? sprintf(__('reduceri de %d%%', 'sage'), $disc_max) : sprintf(__('reduceri între %d–%d%%', 'sage'), $disc_min, $disc_max) }}</span>
          @endif
          <span class="sep">·</span>
          <span>{{ __('stoc rotativ lunar', 'sage') }}</span>
        </div>
      @endif
    </div>
    <div class="promo-hero-art" aria-hidden="true">
      <span class="badge">{{ __('Pick-ul lunii', 'sage') }}</span>
      <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M100 30 C 75 30, 55 50, 55 80 C 55 110, 75 145, 100 170 C 125 145, 145 110, 145 80 C 145 50, 125 30, 100 30 Z" fill="currentColor" opacity=".18"/>
        <path d="M100 50 C 85 50, 75 60, 75 80 C 75 105, 90 130, 100 145 C 110 130, 125 105, 125 80 C 125 60, 115 50, 100 50 Z" fill="currentColor" opacity=".3"/>
        <circle cx="100" cy="85" r="4" fill="currentColor"/>
        <path d="M65 100 L 75 95" stroke-linecap="round"/>
        <path d="M135 100 L 125 95" stroke-linecap="round"/>
      </svg>
    </div>
  </div>
</section>
