{{-- Sub 200 lei — hero. Text din ACF (fallback pe mockup); numerele sunt derivate
     din produsele WooCommerce. Token {count} → nr. produse. --}}
@php
  $eyebrow = \App\sub200_field('hero_eyebrow', __('Filtru preț · Quick link', 'sage'));
  $titlu = \App\sub200_field('hero_titlu', __('Suplimente <em>sub 200 lei.</em>', 'sage'));
  $lede = \App\sub200_field('hero_lede', __('{count} pentru o singură problemă. Cure de 30–120 zile, fără compromisuri pe formulare.', 'sage'));
  $cpd_tagline = \App\sub200_field('hero_cpd_tagline', __('Compari cura completă, nu cutia.', 'sage'));

  // {count} → „N produse complete” (bold), cu pluralizare.
  $count_phrase = '<strong>' . sprintf(_n('%d produs complet', '%d produse complete', $product_count, 'sage'), $product_count) . '</strong>';
  $lede = str_replace('{count}', $count_phrase, $lede);

  $chip_all = \App\sub200_field('chip_all_label', __('Toate', 'sage'));
  $chip_vegan = \App\sub200_field('chip_vegan_label', __('Vegan', 'sage'));
  $chip_long = \App\sub200_field('chip_long_label', __('Cură lungă · 120+ zile', 'sage'));
  $chip_short = \App\sub200_field('chip_short_label', __('Cură scurtă · 30–50 zile', 'sage'));
@endphp

<section class="filt-hero">
  <div class="inner">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h1>{!! \App\sub200_kses($titlu) !!}</h1>
    <p class="lede">{!! \App\sub200_kses($lede) !!}</p>
    @if ($cheapest && $dearest && $cheapest['cpd'] > 0)
      <p class="cpd-line">
        {{ __('De la', 'sage') }} <strong>{{ $cheapest['cpd_label'] }}</strong> ({{ $cheapest['name'] }})
        {{ __('până la', 'sage') }} <strong>{{ $dearest['cpd_label'] }}</strong> ({{ $dearest['name'] }}).
        @if ($cpd_tagline) {{ $cpd_tagline }} @endif
      </p>
    @endif
    <div class="filter-chips">
      <span class="fc active">{{ $chip_all }} <span class="ct">{{ $product_count }}</span></span>
      @if ($vegan_count > 0)
        <span class="fc">{{ $chip_vegan }} <span class="ct">{{ $vegan_count }} / {{ $product_count }}</span></span>
      @endif
      @if ($long_count > 0)
        <span class="fc">{{ $chip_long }} <span class="ct">{{ $long_count }}</span></span>
      @endif
      @if ($short_count > 0)
        <span class="fc">{{ $chip_short }} <span class="ct">{{ $short_count }}</span></span>
      @endif
    </div>
  </div>
</section>
