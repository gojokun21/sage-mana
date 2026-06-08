{{-- Obiectiv — FAQ. Acordeon nativ <details> → animat de faq-accordion.js
     (lazy-load global din app.js pe `.faq .faq-item`). --}}
@php
  $eyebrow = \App\simptom_field('faq_eyebrow', __('Întrebări frecvente', 'sage'));
  $titlu = \App\simptom_field('faq_titlu', __('Ce ne <em>întrebați</em>', 'sage'));
  $items = \App\simptom_field('faq_items', [
    ['q' => __('Când o să simt diferența?', 'sage'), 'a' => __('Majoritatea raportează schimbări vizibile între ziua 14 și ziua 28. Primele semne sunt trezirea mai ușoară și absența căderii de la 14:00. Pentru efecte stabile, recomandăm cura completă de 90–120 zile.', 'sage')],
    ['q' => __('Pot lua împreună cu cafeaua?', 'sage'), 'a' => __('Da, complet compatibil. Cafeaua dă boost imediat, dar nu rezolvă cauza. Pachetul Energie lucrează la nivel celular, în paralel. La 6–8 săptămâni mulți spun că au redus cafeaua de la trei la una pe zi, fără efort.', 'sage')],
    ['q' => __('Cât timp ar trebui să țină o cură?', 'sage'), 'a' => __('Cura standard e 120 de zile (cât conține pachetul). Apoi recomandăm pauză de 4–6 săptămâni înainte de a relua. Cei cu oboseală cronică severă pot ține 6 luni consecutiv, cu acord medical.', 'sage')],
  ]);
@endphp

<section class="faq">
  <div class="faq-inner">
    <div class="faq-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
    </div>
    <div class="faq-list">
      @foreach ($items as $i => $faq)
        <details class="faq-item" {{ $i === 0 ? 'open' : '' }}>
          <summary class="faq-q">
            <span>{{ $faq['q'] ?? '' }}</span>
            <span class="toggle" aria-hidden="true">+</span>
          </summary>
          <div class="faq-a"><p>{!! wp_kses($faq['a'] ?? '', ['strong' => [], 'em' => []]) !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
