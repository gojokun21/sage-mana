{{-- Single PACHET — FAQ (static). Accordion nativ <details> → animat de faq-accordion.js. --}}
@php
  $faqs = [
    ['q' => __('Când încep să văd rezultate?', 'sage'), 'a' => __('Primele efecte apar de obicei în <strong>2–4 săptămâni</strong>, iar rezultatele se consolidează pe parcursul unei cure complete. Pentru schimbări de durată, recomandăm minimum 3 luni.', 'sage')],
    ['q' => __('Pot lua toate produsele în aceeași zi?', 'sage'), 'a' => __('Da. Produsele din pachet sunt gândite să <strong>lucreze împreună</strong>. Le iei în momentele recomandate ale zilei pentru absorbție optimă.', 'sage')],
    ['q' => __('De ce să iau pachetul și nu produsele separat?', 'sage'), 'a' => __('Pentru că produsele <strong>se potențează reciproc</strong> și, împreună, prețul este mai bun decât cumpărarea separată. Plus că ai o cură completă, fără să-ți bați capul ce să combini.', 'sage')],
    ['q' => __('Conține alergeni?', 'sage'), 'a' => __('Formulele nu conțin <strong>gluten, lactoză, soia sau OMG</strong>. Verifică eticheta fiecărui produs dacă ai alergii specifice.', 'sage')],
    ['q' => __('Pot lua cu alte suplimente sau medicamente?', 'sage'), 'a' => __('În majoritatea cazurilor da. Dacă urmezi un <strong>tratament medical</strong> sau iei anticoagulante, verifică întâi cu medicul.', 'sage')],
    ['q' => __('Cum funcționează garanția de retur?', 'sage'), 'a' => __('Ai <strong>14 zile</strong> să te răzgândești. Returul se face simplu, din cont, fără întrebări.', 'sage')],
  ];
@endphp

{{-- Clasa `faq` (pe lângă `pachet-faq`) activează faq-accordion.js — vezi
     app.js (trigger `.faq .faq-item`) și faq-accordion.js (root query `.faq`). --}}
<section class="faq pachet-faq">
  <div class="faq-inner">
    <div class="faq-head">
      <span class="kicker">{{ __('Întrebări frecvente', 'sage') }}</span>
      <h2>{{ __('Ce ne întrebați despre', 'sage') }} <em>{{ __('acest pachet.', 'sage') }}</em></h2>
    </div>
    <div class="faq-list">
      @foreach ($faqs as $i => $faq)
        <details class="faq-item" {{ $i === 0 ? 'open' : '' }}>
          <summary class="faq-q">
            <span>{{ $faq['q'] }}</span>
            <span class="toggle" aria-hidden="true">+</span>
          </summary>
          <div class="faq-a"><p>{!! wp_kses($faq['a'], ['strong' => []]) !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
