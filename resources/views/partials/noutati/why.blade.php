{{-- Noutăți — „De ce tincturi” (carduri din ACF). --}}
@php
  $eyebrow = \App\noutati_field('why_eyebrow', __('Despre formă', 'sage'));
  $titlu = \App\noutati_field('why_titlu', __('De ce <em>tincturi.</em>', 'sage'));
  $cards = \App\noutati_field('why_cards', [
    ['titlu' => __('Formă concentrată, <em>absorbție rapidă.</em>', 'sage'), 'text' => __('Tincturile sunt extracte alcoolice sau glicerinate ale plantelor. Sunt <strong>mai concentrate decât ceaiurile</strong> și se absorb mai rapid decât capsulele — sublingual, prin mucoasa orală, ocolind tractul digestiv pentru câteva minute mai devreme.', 'sage')],
    ['titlu' => __('Cantitate exactă, <em>gust real.</em>', 'sage'), 'text' => __('Picurătorul îți permite <strong>dozare precisă</strong> — de la 10 la 30 de picături în funcție de nevoie. Nu sunt ascunse într-o capsulă; simți gustul plantelor, ceea ce contribuie la acțiunea reflexă orală și la conectarea senzorială cu remediul.', 'sage')],
  ]);
@endphp
<section class="why-tinc">
  <div class="why-tinc-inner">
    <div class="why-tinc-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! \App\noutati_kses($titlu) !!}</h2>
    </div>
    <div class="why-tinc-grid">
      @foreach ($cards as $card)
        <div class="why-card">
          <h3>{!! \App\noutati_kses($card['titlu'] ?? '') !!}</h3>
          <p>{!! \App\noutati_kses($card['text'] ?? '') !!}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
