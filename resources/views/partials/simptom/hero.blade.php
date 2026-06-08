{{--
  Hero — titlu mare cu accent italic verde, lede, 3 meta-chips și o ilustrație
  în card. Text din ACF (fallback „Sindrom metabolic"); SVG-urile sunt statice.
--}}
@php
  $eyebrow = \App\simptom_field('hero_eyebrow', __('După simptom', 'sage'));
  $titlu = \App\simptom_field('hero_titlu', __('Poftele la dulce. <em>Nu e lipsă de voință,</em> e biologie.', 'sage'));
  $lede = \App\simptom_field('hero_lede', __('Dacă simți nevoia de zahăr la 11 dimineața sau 4 după-amiaza, dacă te-ai îngrășat în talie fără să mănânci mai mult, nu ești „slăbit caracterial". Glicemia urcă și cade, ficatul stochează grăsime, microbiomul cere mai mulți carbohidrați. Sistemul îți cere zahăr pentru că e descalibrat.', 'sage'));

  $chips = \App\simptom_field('hero_chips', [
    ['text' => __('1 din 3 adulți români are sindrom metabolic', 'sage')],
    ['text' => __('Glicemia se reechilibrează în 6–12 săptămâni', 'sage')],
    ['text' => __('Poftele scad odată cu glicemia', 'sage')],
  ]);

  // SVG-uri decorative, alocate pe poziție (max 3).
  $chip_svgs = [
    '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
    '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>',
  ];
@endphp
<section class="hero">
  <div class="hero-grid">
    <div class="hero-left">
      <div class="eyebrow with-rule">{{ $eyebrow }}</div>
      <h1>{!! wp_kses($titlu, ['em' => []]) !!}</h1>
      <p class="lede">{{ $lede }}</p>
      <div class="hero-meta">
        @foreach (array_slice($chips, 0, 3) as $i => $chip)
          <span class="meta-chip">
            {!! $chip_svgs[$i] ?? $chip_svgs[0] !!}
            {{ is_array($chip) ? ($chip['text'] ?? '') : $chip }}
          </span>
        @endforeach
      </div>
    </div>

    <div class="hero-illu" aria-hidden="true">
      <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
        <circle cx="100" cy="100" r="78" stroke="currentColor" fill="none" opacity=".55"/>
        <path d="M30 110 Q55 70 80 110 T130 110 T180 110" stroke-width="2.2"/>
        <circle cx="55" cy="86" r="3" fill="currentColor"/>
        <circle cx="105" cy="86" r="3" fill="currentColor"/>
        <circle cx="155" cy="86" r="3" fill="currentColor"/>
      </svg>
    </div>
  </div>
</section>
