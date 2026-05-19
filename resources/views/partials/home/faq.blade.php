{{--
  FAQ section — întrebări frecvente despre produsele Mâna Naturii.
  Foloseste markup-ul .faq / .faq-item / .faq-q / .faq-a recunoscut de
  faq-accordion.js (lazy-loaded din app.js când selectorul `.faq .faq-item`
  exista in DOM). Primul item se deschide implicit, ca pe pagina de categorie.
--}}

@php
  $faq_items = [
    [
      'q' => __('Cât durează până simt o schimbare?', 'sage'),
      'a' => __('Primele efecte apar în 2–4 săptămâni, iar beneficiile profunde se construiesc în 2–4 luni de administrare constantă.', 'sage'),
    ],
    [
      'q' => __('Pot lua mai multe produse Mâna Naturii în același timp?', 'sage'),
      'a' => __('Da, gama este formulată să funcționeze sinergic, astfel încât produsele se completează reciproc. Urmează indicațiile de administrare de pe eticheta fiecărui produs, iar dacă ai nevoie ajutor, scrie-ne pe WhatsApp.', 'sage'),
    ],
    [
      'q' => __('Ce se întâmplă dacă mă opresc după o cură?', 'sage'),
      'a' => __('Beneficiile rămân pe o perioadă bună după oprire, iar o pauză scurtă de 4–6 săptămâni între cure este chiar recomandată pentru a lăsa organismul să integreze rezultatele. După pauză, cura poate fi reluată oricând, fără efecte de „rebound" sau dependență.', 'sage'),
    ],
    [
      'q' => __('Pot apărea efecte secundare?', 'sage'),
      'a' => __('Majoritatea produselor Vivens Genetica sunt vegane și foarte bine tolerate. În primele zile, mai ales la probiotice sau detox, pot apărea ușoare ajustări digestive (tranzit modificat, balonare temporară), sunt semne normale că organismul se reechilibrează și dispar în câteva zile.', 'sage'),
    ],
    [
      'q' => __('Suplimentele se adresează doar adulților?', 'sage'),
      'a' => __('Da, toate produsele sunt formulate exclusiv pentru adulți (peste 18 ani). Dacă urmezi tratament cronic, ești însărcinată sau alăptezi, consultă medicul înainte.', 'sage'),
    ],
    [
      'q' => __('Cât durează livrarea și ce opțiuni de plată am?', 'sage'),
      'a' => __('Livrăm în 24–48 de ore lucrătoare prin curier. Poți plăti online cu cardul sau ramburs la primire.', 'sage'),
    ],
  ];
@endphp

<section class="home-section home-faq" aria-labelledby="home-faq-title">
  <div class="home-section__header">
    <h2 id="home-faq-title" class="home-section__title">{{ __('Întrebări frecvente', 'sage') }}</h2>
    <p class="home-section__subtitle">{{ __('Răspundem la cele mai des întâlnite întrebări despre cure, livrare și administrare.', 'sage') }}</p>
  </div>

  <div class="home-faq__list faq">
    @foreach ($faq_items as $i => $item)
      @php $is_open = $i === 0; @endphp
      <details class="faq-item" @if ($is_open) open @endif>
        <summary class="faq-q">
          <span class="faq-q__text">{{ $item['q'] }}</span>
          <span class="faq-toggle" aria-hidden="true">{{ $is_open ? '−' : '+' }}</span>
        </summary>
        <div class="faq-a">{!! wp_kses_post($item['a']) !!}</div>
      </details>
    @endforeach
  </div>
</section>
