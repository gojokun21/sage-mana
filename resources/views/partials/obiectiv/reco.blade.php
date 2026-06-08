{{-- Obiectiv — recomandarea principală. Produs WC din ACF (preț/link LIVE),
     cu fallback nume/preț. --}}
@php
  $eyebrow = \App\simptom_field('reco_eyebrow', __('Pick-ul principal', 'sage'));
  $titlu = \App\simptom_field('reco_titlu', __('Pachet <em>Energie</em>', 'sage'));
  $subt = \App\simptom_field('reco_subtitlu', __('Revitalizare & Vitalitate Zilnică · Multivitamine + Adaptogeni Vegan.', 'sage'));
  $durata = \App\simptom_field('reco_durata', __('ajunge 120 de zile', 'sage'));
  $cta = \App\simptom_field('reco_cta', __('Adaugă în coș', 'sage'));
  $benefits = \App\simptom_field('reco_benefits', [
    ['text' => __('Pentru oboseală persistentă, trezire grea, energie scăzută după-amiaza.', 'sage')],
    ['text' => __('Pentru deficite subclinice de B12, magneziu, vitamina D, zinc.', 'sage')],
    ['text' => __('Pentru ten obosit, cearcăne persistente, treziri la 2–3 noaptea.', 'sage')],
  ]);

  $pid = (int) \App\simptom_field('reco_produs', 0);
  $product = $pid ? wc_get_product($pid) : null;

  $url = $product ? get_permalink($product->get_id()) : home_url('/');
  $img = $product ? $product->get_image_id() : 0;
  $price = $product ? $product->get_price_html() : esc_html(\App\simptom_field('reco_pret', '306 lei'));
@endphp

<section class="reco" id="reco">
  <div class="reco-inner">
    <a class="reco-art" href="{{ esc_url($url) }}" aria-label="{{ esc_attr(wp_strip_all_tags($titlu)) }}">
      @if ($img)
        {!! wp_get_attachment_image($img, 'large', false, ['loading' => 'lazy', 'decoding' => 'async', 'alt' => wp_strip_all_tags($titlu)]) !!}
      @else
        <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <rect x="68" y="40" width="64" height="130" rx="10" fill="currentColor" opacity=".25"/>
          <rect x="68" y="40" width="64" height="22" rx="6" fill="currentColor" opacity=".5"/>
          <path d="M82 80 H 118 M 82 92 H 118 M 82 104 H 108" stroke-width="1.2" opacity=".7"/>
          <circle cx="100" cy="135" r="14" fill="currentColor" opacity=".5"/>
          <path d="M93 135 L 100 142 L 110 130" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
        </svg>
      @endif
    </a>
    <div>
      <div class="reco-eye">{{ $eyebrow }}</div>
      <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
      <p class="subt">{{ $subt }}</p>
      <div class="price-row">
        <span class="price">{!! $price !!}</span>
        @if ($durata)<span class="dur">{{ $durata }}</span>@endif
      </div>
      <ul class="benefits">
        @foreach ($benefits as $b)
          @php $txt = is_array($b) ? ($b['text'] ?? '') : $b; @endphp
          <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            {{ $txt }}
          </li>
        @endforeach
      </ul>
      <a class="cta-big" href="{{ esc_url($url) }}">{{ $cta }}
        <svg width="15" height="15" viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/></svg>
      </a>
      <div class="trust-line">
        <span>{{ __('Livrare 24–48h', 'sage') }}</span><span class="sep">·</span>
        <span>{{ __('Retur 14 zile', 'sage') }}</span><span class="sep">·</span>
        <span>{{ __('Plata ramburs', 'sage') }}</span>
      </div>
    </div>
  </div>
</section>
