{{-- Noutăți — FAQ din ACF (native <details>, primul deschis). --}}
@php
  $titlu = \App\noutati_field('faq_titlu', __('Întrebări <em>frecvente.</em>', 'sage'));
  $items = \App\noutati_field('faq_items', [
    ['q' => __('Când exact se lansează?', 'sage'), 'a' => __('Trimestre estimate (Q2–Q4 2026), dar <strong>depinde de aprobări</strong>. Nu putem promite date exacte. Cei înscriși pe lista de email află cu 7 zile înainte de public.', 'sage')],
    ['q' => __('Pot rezerva cu plată acum?', 'sage'), 'a' => __('<strong>NU.</strong> Nu acceptăm plăți până când produsele sunt notificate ANSVSA. Lista de email e gratis, fără obligație, fără card cerut.', 'sage')],
    ['q' => __('Prețul afișat este final?', 'sage'), 'a' => __('<strong>NU.</strong> Este estimat — se poate modifica ușor după aprobare și formularea finală. Putem avea ajustări de ±10–15 lei.', 'sage')],
    ['q' => __('De ce nu lansați mai repede?', 'sage'), 'a' => __('<strong>Brand mic, fără grabă.</strong> Preferăm un lansament corect: aprobare ANSVSA, formulare testată extern, etichetare conformă, stoc corect dimensionat — toate cer timp.', 'sage')],
  ]);
@endphp
<section class="faq">
  <div class="faq-inner">
    <h2>{!! \App\noutati_kses($titlu) !!}</h2>
    <div class="faq-list">
      @foreach ($items as $i => $item)
        <details class="faq-item" @if ($i === 0) open @endif>
          <summary class="faq-q">{{ $item['q'] ?? '' }}<span class="toggle" aria-hidden="true">+</span></summary>
          <div class="faq-a"><p>{!! \App\noutati_kses($item['a'] ?? '') !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
