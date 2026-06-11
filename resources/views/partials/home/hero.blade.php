{{--
  Hero homepage. Text editabil din ACF (grup Home, vezi app/acf-home.php), cu
  fallback pe database/seeds/home.php prin \App\home_field(). Imaginea: ACF
  hero_image dacă e setată, altfel imaginea implicită.
--}}
@php
  $shop_url = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/magazin/');
  $hero_img = \App\home_field('hero_image');
  $hero_trust = \App\home_field('hero_trust') ?: [];
@endphp
<section class="hero" aria-label="{{ esc_attr__('Prezentare', 'sage') }}">
  <div class="hero-grid">
    <div class="hero-left">
      <div class="eyebrow">{{ \App\home_field('hero_eyebrow') }}</div>
      <h1>
        {{ \App\home_field('hero_titlu') }}
        <em>{{ \App\home_field('hero_titlu_em') }}</em>
      </h1>
      <p class="lede">
        {!! wp_kses(\App\home_field('hero_lede'), ['strong' => [], 'em' => []]) !!}
      </p>
      <div class="hero-ctas">
        <a class="btn btn-primary btn-lg" href="{{ esc_url($shop_url) }}">
          {{ \App\home_field('hero_cta_primary') }}
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
          </svg>
        </a>
        <a class="btn btn-ghost-green btn-lg" href="#test">{{ \App\home_field('hero_cta_secondary') }}</a>
      </div>
      <div class="hero-trust">
        @foreach ($hero_trust as $t)
          <span class="t">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            {{ is_array($t) ? ($t['text'] ?? '') : $t }}
          </span>
        @endforeach
      </div>
    </div>

    <div class="hero-right">
      <div class="hero-stage">
        @if ($hero_img)
          {!! wp_get_attachment_image($hero_img, 'large', false, [
            'class' => 'hero-img',
            'alt' => esc_attr__('Suplimente Mâna Naturii', 'sage'),
            'loading' => 'eager',
            'decoding' => 'async',
            'fetchpriority' => 'high',
          ]) !!}
        @else
          <img class="hero-img"
               src="{{ esc_url(home_url('/wp-content/uploads/2026/06/ChatGPT-Image-2-iun.-2026-14_23_01.webp')) }}"
               alt="{{ esc_attr__('Suplimente Mâna Naturii', 'sage') }}"
               loading="eager" decoding="async" fetchpriority="high" />
        @endif
      </div>
    </div>
  </div>
</section>
