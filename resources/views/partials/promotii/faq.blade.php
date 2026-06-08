{{-- Promoții — FAQ (static). Accordion nativ <details> → faq-accordion.js. --}}
@php
  $faqs = [
    ['q' => __('Pot combina o ofertă cu cuponul de nou client?', 'sage'), 'a' => __('Da, în majoritatea cazurilor. Cuponul de bun-venit (10%) se aplică peste prețul deja redus, dacă produsul are sub 20% discount. Pentru reduceri mai mari, cele două se exclud reciproc și se aplică cea mai avantajoasă.', 'sage')],
    ['q' => __('Cât timp e valabilă oferta?', 'sage'), 'a' => __('Ofertele rulează lunar, fără countdown la oră sau zi. Sunt valabile cât există stoc. Verificăm la fiecare început de lună ce intră, ce iese, ce continuă.', 'sage')],
    ['q' => __('Ce se întâmplă dacă produsul iese din stoc în timpul comenzii?', 'sage'), 'a' => __('Te contactăm în 24h pe email și WhatsApp. Îți oferim trei opțiuni: alternativă la același preț, păstrarea comenzii cu livrare amânată, sau rambursare integrală. Decizi tu.', 'sage')],
    ['q' => __('Pot returna un produs cumpărat în promoție?', 'sage'), 'a' => __('Da, politica de retur de 14 zile se aplică identic, cu sau fără reducere. Produsul trebuie să fie nedeschis, în ambalajul original. Rambursarea se face integral, fără penalități.', 'sage')],
  ];
@endphp
<section class="faq">
  <div class="faq-inner">
    <div class="faq-head">
      <div class="eyebrow">{{ __('Întrebări frecvente', 'sage') }}</div>
      <h2>{{ __('Ce ne', 'sage') }} <em>{{ __('întrebați', 'sage') }}</em></h2>
    </div>
    <div class="faq-list">
      @foreach ($faqs as $i => $faq)
        <details class="faq-item" {{ $i === 0 ? 'open' : '' }}>
          <summary class="faq-q">
            <span>{{ $faq['q'] }}</span>
            <span class="toggle" aria-hidden="true">+</span>
          </summary>
          <div class="faq-a"><p>{{ $faq['a'] }}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
