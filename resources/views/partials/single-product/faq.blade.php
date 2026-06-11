{{-- PDP — FAQ. ACF (grup PDP, seed `natura:pdp-seed`) cu fallback static. Accordion nativ <details> → animat de faq-accordion.js. --}}
@php
  $faq_nume = get_field('faq_nume_produs') ?: __('acest produs', 'sage');

  $faqs = collect(get_field('faq_items') ?: [])
    ->map(static fn ($f) => ['q' => $f['intrebare'] ?? '', 'a' => $f['raspuns'] ?? ''])
    ->filter(static fn ($f) => $f['q'] !== '' && $f['a'] !== '')
    ->values()
    ->all();
  if (empty($faqs)) {
    $faqs = [
      ['q' => __('De ce capsule și nu ulei lichid?', 'sage'), 'a' => __('Capsulele <strong>protejează uleiul de oxidare</strong>, sunt mai ușor de luat (fără gust pronunțat) și asigură doza exactă zilnic. Uleiul lichid se oxidează rapid după deschidere și are gust intens.', 'sage')],
      ['q' => __('Pot să-l iau pe termen lung?', 'sage'), 'a' => __('Da, este conceput pentru <strong>utilizare zilnică prelungită</strong>. Multe persoane fac cure de 4–6 luni urmate de pauze scurte.', 'sage')],
      ['q' => __('Există interacțiuni cu medicamente?', 'sage'), 'a' => __('Da, posibil cu <strong>anticoagulante</strong> (timochinona poate avea efect ușor de subțiere a sângelui). Consultă medicul dacă iei warfarină sau medicamente similare.', 'sage')],
      ['q' => __('Câte zile durează un flacon?', 'sage'), 'a' => __('<strong>120 de zile cu 2 capsule/zi.</strong> Aproximativ 4 luni de cură completă.', 'sage')],
      ['q' => __('Pot să-l combin cu alte suplimente?', 'sage'), 'a' => __('Da, mai ales cu <strong>multivitamine și probiotice</strong>. Black Seed Elixir se integrează ușor într-o stivă cu Vita Complete+ sau Microflora+.', 'sage')],
    ];
  }
@endphp
<section class="faq">
  <div class="faq-inner">
    <div class="faq-head">
      <span class="eyebrow">{{ __('Întrebări frecvente', 'sage') }}</span>
      <h2>{{ __('Ce ne întrebați despre', 'sage') }} <em>{{ $faq_nume }}</em>.</h2>
    </div>
    <div class="faq-list">
      @foreach ($faqs as $i => $faq)
        <details class="faq-item" {{ $i === 0 ? 'open' : '' }}>
          <summary class="faq-q">
            <span>{{ $faq['q'] }}</span>
            <span class="toggle" aria-hidden="true">+</span>
          </summary>
          <div class="faq-a"><p>{!! wp_kses($faq['a'], ['strong' => [], 'em' => []]) !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
