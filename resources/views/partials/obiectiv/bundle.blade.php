{{-- Obiectiv — bundle band (sugestie de combinare). --}}
@php
  $eyebrow = \App\simptom_field('bundle_eyebrow', __('Combină', 'sage'));
  $titlu = \App\simptom_field('bundle_titlu', __('Energie + <em>Imunitate.</em> Pachetul care ține toată toamna.', 'sage'));
  $text = \App\simptom_field('bundle_text', __('Adaugă și Pachetul Imunitate la coș. Economisești 15% pe total, fără cod, automat la finalizare.', 'sage'));
  $cta = \App\simptom_field('bundle_cta', __('Vezi combinația', 'sage'));
  $url = \App\simptom_field('bundle_cta_url', '');
  if (! $url) {
      $url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
  }
@endphp

<section class="bundle-band">
  <div class="bundle-inner">
    <div>
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
      <p>{{ $text }}</p>
    </div>
    <div class="bundle-cta-wrap">
      <a class="bundle-cta" href="{{ esc_url($url) }}">{{ $cta }}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>
