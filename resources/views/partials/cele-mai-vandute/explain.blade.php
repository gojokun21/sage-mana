{{-- Cele mai vândute — „Cum am ales” (criterii din ACF; iconițe statice ciclice). --}}
@php
  $eyebrow = \App\bestseller_field('explain_eyebrow', __('Cum am ales', 'sage'));
  $titlu = \App\bestseller_field('explain_titlu', __('Trei criterii reale, <em>fără cifre fabricate.</em>', 'sage'));
  $cards = \App\bestseller_field('explain_cards', [
    ['titlu' => __('Reorder rate, nu volum brut.', 'sage'), 'text' => __('Pentru un brand mic, contează <strong>câți clienți repetă cumpărarea</strong>. Aceste produse au cele mai multe re-cumpărări în 12 luni.', 'sage')],
    ['titlu' => __('Recomandare către prieteni.', 'sage'), 'text' => __('Toate au scor mare la întrebarea <strong>„ai recomanda acest produs?”</strong> în sondajele post-cură. Word-of-mouth, nu publicitate plătită.', 'sage')],
    ['titlu' => __('Acoperire pe nevoi diferite.', 'sage'), 'text' => __('Nu sunt produse din aceeași categorie. Acoperă <strong>digestie, detox, imunitate, sport, articulații</strong>. Probabil unul e exact pentru tine.', 'sage')],
  ]);
  $icons = [
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 3v5h5"/></svg>',
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/></svg>',
  ];
@endphp
<section class="explain">
  <div class="explain-inner">
    <div class="explain-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! \App\bestseller_kses($titlu) !!}</h2>
    </div>
    <div class="explain-grid">
      @foreach ($cards as $i => $card)
        <div class="ex-card">
          <div class="ico">{!! $icons[$i % count($icons)] !!}</div>
          <h3>{{ $card['titlu'] ?? '' }}</h3>
          <p>{!! \App\bestseller_kses($card['text'] ?? '') !!}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
