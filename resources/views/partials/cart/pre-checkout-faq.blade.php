{{-- Pre-checkout FAQ — 4 itemi specifici checkout-ului. Accordion <details> nativ. --}}

@php
  $faqs = [
    [
      'q' => __('Cum se calculează transportul?', 'sage'),
      'a' => __('Livrăm prin <strong>Sameday sau FAN Courier</strong>. Transport <strong>gratuit peste 199 lei</strong>, 19 lei sub. Tu primești comanda în 24–48h pentru orașe mari, 48–72h pentru sate și zone rurale.', 'sage'),
    ],
    [
      'q' => __('Pot returna dacă nu sunt mulțumit?', 'sage'),
      'a' => __('Da. <strong>14 zile garanție · doar dacă produsul este sigilat și în ambalajul original.</strong> Pentru produsele deschise, garanția de calitate (defecte de fabricație) e separată — contactează-ne pe WhatsApp și rezolvăm caz cu caz.', 'sage'),
    ],
    [
      'q' => __('Când vine comanda?', 'sage'),
      'a' => __('<strong>24–48h</strong> pentru orașele mari (București, Cluj, Iași, Timișoara, Brașov, Constanța). <strong>48–72h</strong> pentru sate și zone rurale. Primești AWB pe email și SMS în maximum 6h de la confirmare.', 'sage'),
    ],
    [
      'q' => __('Trebuie să-mi fac cont?', 'sage'),
      'a' => __('<strong>NU.</strong> Checkout-ul e disponibil ca <strong>guest</strong>, fără cont obligatoriu. Ai opțiunea să creezi cont la finalul comenzii dacă vrei să-ți urmărești mai ușor istoricul — dar nu te obligă nimeni.', 'sage'),
    ],
  ];
@endphp

<section class="cart-faq" aria-label="{{ esc_attr__('Întrebări frecvente înainte de checkout', 'sage') }}">
  <div class="cart-faq-inner">
    <div class="cart-faq__eyebrow">{{ __('Înainte să finalizezi', 'sage') }}</div>
    <h2 class="cart-faq__title">
      {{ __('Patru răspunsuri scurte', 'sage') }}
      <em>{{ __('înainte de checkout.', 'sage') }}</em>
    </h2>

    <div class="cart-faq__list">
      @foreach ($faqs as $i => $faq)
        <details class="cart-faq__item" {{ $i === 0 ? 'open' : '' }}>
          <summary class="cart-faq__q">
            <span>{{ $faq['q'] }}</span>
            <span class="cart-faq__toggle" aria-hidden="true">+</span>
          </summary>
          <div class="cart-faq__a"><p>{!! wp_kses_post($faq['a']) !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
