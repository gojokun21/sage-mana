{{-- FAQ accordion — 3 itemi, primul deschis by default. --}}
@php
  $faqs = [
    [
      'q' => __('Cât durează livrarea?', 'sage'),
      'a' => __('Livrăm în 24–48h pentru toate comenzile plasate până la 14:00. Curieri Sameday și DPD pentru București/Cluj/Iași, Fan Courier pentru restul țării. Transport gratuit la comenzi peste 199 lei, plata ramburs disponibilă.', 'sage'),
    ],
    [
      'q' => __('Pot returna un produs deschis?', 'sage'),
      'a' => __('Da. Ai 14 zile retur pentru produsele desigilate și 90 zile garanție de satisfacție pentru pachetele de cură. Dacă nu funcționează pentru tine, banii înapoi, fără explicații lungi.', 'sage'),
    ],
    [
      'q' => __('Am abonament cu reducere?', 'sage'),
      'a' => __('Da, abonament lunar sau bilunar cu 15% reducere permanentă, transport gratuit la fiecare livrare și posibilitatea de a sări o lună sau a anula oricând. Nu cerem card pentru cel puțin 3 luni.', 'sage'),
    ],
  ];
@endphp

<section class="faq">
  <div class="faq-inner">
    <h2>{{ __('Răspunsuri', 'sage') }} <em>{{ __('scurte', 'sage') }}</em></h2>
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
