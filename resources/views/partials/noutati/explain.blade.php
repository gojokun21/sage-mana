{{-- Noutăți — „De ce durează” (carduri din ACF; iconițe statice ciclice). --}}
@php
  $eyebrow = \App\noutati_field('explain_eyebrow', __('De ce durează', 'sage'));
  $titlu = \App\noutati_field('explain_titlu', __('De ce nu lansăm <em>în grabă.</em>', 'sage'));
  $cards = \App\noutati_field('explain_cards', [
    ['titlu' => __('Aprobarea contează.', 'sage'), 'text' => __('În România, suplimentele cu plante necesită <strong>notificare la ANSVSA</strong>. Procesul durează 3–9 luni și verifică siguranța consumatorului. Nu trecem peste el.', 'sage')],
    ['titlu' => __('Formularea finală se schimbă.', 'sage'), 'text' => __('Până la aprobare, <strong>dozajele se pot modifica</strong> pe baza recomandărilor autorităților. Așa că prețurile și specificațiile de mai jos sunt PRELIMINARE.', 'sage')],
    ['titlu' => __('Cantitate limitată la prima cură.', 'sage'), 'text' => __('La lansare vom avea <strong>stoc limitat</strong>. Cei înscriși pe lista de email primesc acces cu 7 zile înainte de public — fără reduceri false, doar prioritate de cumpărare.', 'sage')],
  ]);
  $icons = [
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>',
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M20 7h-9M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>',
  ];
@endphp
<section class="explain">
  <div class="explain-inner">
    <div class="explain-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! \App\noutati_kses($titlu) !!}</h2>
    </div>
    <div class="explain-grid">
      @foreach ($cards as $i => $card)
        <div class="ex-card">
          <div class="ico">{!! $icons[$i % count($icons)] !!}</div>
          <h3>{{ $card['titlu'] ?? '' }}</h3>
          <p>{!! \App\noutati_kses($card['text'] ?? '') !!}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
