{{--
  Hero shop (.cat-hero) — text-only. Refolosit pe catalog (archive-product) și
  pe alte pagini cu același scope (.catalog-page) prin override-uri pasate la
  @include, ex. pe template-pachete. Default-urile == copy-ul catalogului.
--}}
@php
  $published = $hero_count ?? (function_exists('wp_count_posts') ? (int) (wp_count_posts('product')->publish ?? 0) : 0);
  $eyebrow = $hero_eyebrow ?? __('Catalog complet', 'sage');
  $h1_count_unit = $hero_h1_count_unit ?? __('de suplimente.', 'sage');
  $h1_tail = $hero_h1_tail ?? __('O singură filozofie: doze', 'sage');
  $h1_em = $hero_h1_em ?? __('reale.', 'sage');
  $h1_fallback = $hero_h1_fallback ?? __('Suplimente alese cu grijă.', 'sage');
  $lede = $hero_lede ?? __('Fără claim-uri vagi, fără ingrediente decorative. Doar formule funcționale, testate, fabricate în UE.', 'sage');
  $stat_count_tmpl = $hero_stat_count_template ?? __('%d produse', 'sage');
@endphp

<section class="cat-hero">
  <div class="inner">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h1>
      @if ($published > 0)
        {{ $published }} {{ $h1_count_unit }}
      @else
        {{ $h1_fallback }}
      @endif
      {{ $h1_tail }}
      <em>{{ $h1_em }}</em>
    </h1>
    @if ($lede)
      <p class="lede">{{ $lede }}</p>
    @endif
    <div class="stats">
      @if ($published > 0)
        <span>{{ sprintf($stat_count_tmpl, $published) }}</span>
        <span class="sep">·</span>
      @endif
      <span class="stars">★ {{ __('4,8', 'sage') }}</span>
      <span>{{ __('din 1.247 recenzii', 'sage') }}</span>
      <span class="sep">·</span>
      <span>{{ __('90 zile garanție', 'sage') }}</span>
    </div>
  </div>
</section>
