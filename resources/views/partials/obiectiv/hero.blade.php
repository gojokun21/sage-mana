{{-- Obiectiv — hero. Text din ACF (fallback „energie"); ilustrația SVG e statică. --}}
@php
  $eyebrow = \App\simptom_field('hero_eyebrow', __('Obiectiv: Energie', 'sage'));
  $titlu = \App\simptom_field('hero_titlu', __('<em>Energia</em> care ține toată ziua. Fără cafea în plus.', 'sage'));
  $lede = \App\simptom_field('hero_lede', __('B-complex, Q10, magneziu și adaptogeni. Combinația care repornește motorul celular în 2–4 săptămâni.', 'sage'));
  $cta1 = \App\simptom_field('hero_cta_primary', __('Vezi recomandarea principală', 'sage'));
  $cta2 = \App\simptom_field('hero_cta_secondary', __('Compară pachetele', 'sage'));
  $stats = \App\simptom_field('hero_stats', [
    ['text' => __('847 cure vândute', 'sage')],
    ['text' => __('★ 4,8/5', 'sage')],
    ['text' => __('din 312 recenzii', 'sage')],
    ['text' => __('90 zile garanție', 'sage')],
  ]);

  // Imagine hero opțională (ACF). Dacă lipsește, rămâne ilustrația SVG.
  $hero_img = \App\simptom_field('hero_imagine');
@endphp

<section class="obj-hero">
  <div class="obj-hero-inner">
    <div>
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h1>{!! wp_kses($titlu, ['em' => []]) !!}</h1>
      <p class="lede">{{ $lede }}</p>
      <div class="ctas">
        @if ($cta1)
          <a class="btn-terra" href="#reco">{{ $cta1 }}
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          </a>
        @endif
        @if ($cta2)
          <a class="btn-ghost" href="#alts">{{ $cta2 }}</a>
        @endif
      </div>
      <div class="stats">
        @foreach ($stats as $i => $stat)
          @php $txt = is_array($stat) ? ($stat['text'] ?? '') : $stat; @endphp
          @if ($i > 0)<span class="sep">·</span>@endif
          <span class="{{ str_starts_with(trim($txt), '★') ? 'stars' : '' }}">{{ $txt }}</span>
        @endforeach
      </div>
    </div>
    <div class="obj-art" aria-hidden="true">
      @if ($hero_img)
        {!! wp_get_attachment_image($hero_img, 'large', false, ['alt' => esc_attr($titlu ? wp_strip_all_tags($titlu) : ''), 'loading' => 'lazy', 'decoding' => 'async']) !!}
      @else
        <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.5">
          <circle cx="100" cy="100" r="32" fill="currentColor" opacity=".25"/>
          <circle cx="100" cy="100" r="20" fill="currentColor" opacity=".45"/>
          <g stroke-linecap="round" opacity=".7">
            <path d="M100 30 V 50"/><path d="M100 150 V 170"/><path d="M30 100 H 50"/><path d="M150 100 H 170"/>
            <path d="M52 52 L 66 66"/><path d="M134 134 L 148 148"/><path d="M52 148 L 66 134"/><path d="M134 66 L 148 52"/>
          </g>
          <path d="M40 170 Q 80 130, 120 130 T 170 80" stroke-width="2" stroke-linecap="round" opacity=".55"/>
        </svg>
      @endif
    </div>
  </div>
</section>
