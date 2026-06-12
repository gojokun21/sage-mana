{{--
  Hero homepage. Text editabil din ACF (grup Home, vezi app/acf-home.php), cu
  fallback pe database/seeds/home.php prin \App\home_field(). Vizual dreapta:
  stage-ul verde din machetă (preferinte/Mana Naturii - Homepage.html) cu
  frunze decorative, peste care stă fotografia (ACF hero_image sau implicitul)
  integrată cu mix-blend-mode: multiply (vezi .hero-img în home.css).
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
        <a class="btn btn-ghost-green btn-lg" href="{{ esc_url(\App\home_field('hero_cta_secondary_url') ?: '#test') }}">{{ \App\home_field('hero_cta_secondary') }}</a>
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
        {{-- Frunze decorative din machetă — stau sub fotografie (opacitate mică). --}}
        <svg class="leaf-bg l1" viewBox="0 0 160 200" fill="currentColor" aria-hidden="true"><path d="M80 10 Q70 100, 80 190" stroke="currentColor" stroke-width="2" fill="none"/><ellipse cx="60" cy="50" rx="22" ry="8" transform="rotate(-30 60 50)"/><ellipse cx="100" cy="60" rx="22" ry="8" transform="rotate(30 100 60)"/><ellipse cx="55" cy="90" rx="22" ry="8" transform="rotate(-25 55 90)"/><ellipse cx="105" cy="100" rx="22" ry="8" transform="rotate(25 105 100)"/></svg>
        <svg class="leaf-bg l2" viewBox="0 0 240 240" fill="currentColor" aria-hidden="true"><ellipse cx="120" cy="120" rx="100" ry="32" transform="rotate(-25 120 120)"/><ellipse cx="120" cy="120" rx="90" ry="28" transform="rotate(25 120 120)"/></svg>
        <svg class="leaf-bg l3" viewBox="0 0 110 110" fill="currentColor" aria-hidden="true"><ellipse cx="55" cy="55" rx="40" ry="14" transform="rotate(20 55 55)"/></svg>

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

        {{-- „Nota" — cardul de rating plutitor din machetă, peste fotografie. --}}
        <div class="h-rating-card">
          <div class="stars">
            @for ($s = 0; $s < 5; $s++)
              <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/></svg>
            @endfor
          </div>
          <div class="meta">
            <strong>{{ __('4,8 / 5', 'sage') }}</strong>
            {{ __('12.847 recenzii verificate', 'sage') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
