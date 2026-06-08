{{-- Cele mai vândute — FAQ din ACF (native <details>, primul deschis). --}}
@php
  $titlu = \App\bestseller_field('faq_titlu', __('Întrebări <em>frecvente.</em>', 'sage'));
  $items = \App\bestseller_field('faq_items', [
    ['q' => __('Cum știți că acestea sunt best-seller?', 'sage'), 'a' => __('<strong>Reorder rate calculat la 12 luni</strong> + sondaje post-cură („ai recomanda acest produs?”). Nu cifre fake gen „1234 vânduri” sau „top 1 luna aceasta”. Sunt produsele cu cea mai mare loialitate măsurată intern.', 'sage')],
    ['q' => __('Pot lua 2 best-seller-uri simultan?', 'sage'), 'a' => __('Da, dar verifică întâi dacă un <strong>pachet acoperă deja ambele probleme</strong>. Pachetele integrate sunt adesea mai economice decât produsele luate separat.', 'sage')],
    ['q' => __('De ce nu publicați cifre exacte?', 'sage'), 'a' => __('Pentru că am văzut competitori cu cifre suspect de rotunde. <strong>Onestitatea contează mai mult</strong>. Suntem un brand mic — reorder rate-ul real spune mai mult decât un volum brut umflat.', 'sage')],
    ['q' => __('Lista se actualizează?', 'sage'), 'a' => __('Da, o <strong>revizuim trimestrial</strong> pe baza datelor reale. Lista nu e statică — reflectă comportamentul real al clienților, nu marketingul nostru.', 'sage')],
  ]);
@endphp
<section class="faq">
  <div class="faq-inner">
    <h2>{!! \App\bestseller_kses($titlu) !!}</h2>
    <div class="faq-list">
      @foreach ($items as $i => $item)
        <details class="faq-item" @if ($i === 0) open @endif>
          <summary class="faq-q">{{ $item['q'] ?? '' }}<span class="toggle" aria-hidden="true">+</span></summary>
          <div class="faq-a"><p>{!! \App\bestseller_kses($item['a'] ?? '') !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
