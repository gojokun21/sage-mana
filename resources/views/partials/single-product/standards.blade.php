{{-- PDP — standarde de producție. ACF (grup PDP, seed `natura:pdp-seed`) cu fallback static; iconițele rămân statice, pe poziție. --}}
@php
  $icons = [
    '<path d="M12 2 4 6v6c0 5 4 9 8 10 4-1 8-5 8-10V6z"/>',
    '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4" fill="currentColor"/>',
    '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
  ];

  $cards = collect(get_field('stand_cards') ?: [])
    ->map(static fn ($c, $i) => [
      'svg' => $icons[$i % count($icons)],
      'h' => $c['titlu'] ?? '',
      'p' => $c['text'] ?? '',
    ])
    ->values()
    ->all();
  if (empty($cards)) {
    $cards = [
      ['svg' => $icons[0], 'h' => __('Origine trasabilă', 'sage'), 'p' => __('Nigella sativa egipteană dintr-o regiune dedicată cultivării. Fără broker intermediar.', 'sage')],
      ['svg' => $icons[1], 'h' => __('Presare la rece', 'sage'), 'p' => __('Fără solvenți, fără căldură care distruge bioactivii. Timochinona și acizii grași rămân intacți.', 'sage')],
      ['svg' => $icons[2], 'h' => __('Test de puritate pe fiecare lot', 'sage'), 'p' => __('Analize publice, lot tracker pe ambalaj. Fără metale grele, pesticide sau micotoxine.', 'sage')],
    ];
  }
@endphp
<section class="stand">
  <div class="stand-inner">
    <div class="stand-head">
      <span class="eyebrow">{{ __('Standarde de producție', 'sage') }}</span>
      <h2>{{ __('De ce', 'sage') }} <em>{{ __('arată altfel', 'sage') }}</em> {{ __('versiunea noastră.', 'sage') }}</h2>
    </div>
    <div class="stand-grid">
      @foreach ($cards as $c)
        <div class="stand-card">
          <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">{!! $c['svg'] !!}</svg></div>
          <h4>{{ $c['h'] }}</h4>
          <p>{{ $c['p'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
