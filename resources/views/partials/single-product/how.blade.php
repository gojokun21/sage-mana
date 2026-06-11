{{-- PDP — cum îl folosești (3 pași). ACF (grup PDP, seed `natura:pdp-seed`) cu fallback static. --}}
@php
  $how_eyebrow = get_field('how_eyebrow') ?: __('Cum îl folosești', 'sage');
  $how_intro = get_field('how_intro') ?: __('Câte o capsulă cu fiecare masă principală. Răbdare 8–12 săptămâni. Atât.', 'sage');

  $steps = collect(get_field('how_steps') ?: [])
    ->map(static fn ($s, $i) => [
      'step' => str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
      'when' => $s['when'] ?? '',
      'h' => $s['titlu'] ?? '',
      'p' => $s['text'] ?? '',
    ])
    ->values()
    ->all();
  if (empty($steps)) {
    $steps = [
      ['step' => '01', 'when' => __('Dimineața cu micul dejun', 'sage'), 'h' => __('1 capsulă cu apă.', 'sage'), 'p' => __('În timpul mesei. Uleiul se absoarbe mai bine în prezența lipidelor alimentare.', 'sage')],
      ['step' => '02', 'when' => __('Seara cu cina', 'sage'), 'h' => __('1 capsulă cu o masă.', 'sage'), 'p' => __('Total 2 pe zi. Dozajul împărțit pe parcursul zilei menține un nivel constant.', 'sage')],
      ['step' => '03', 'when' => __('Consecvent · 8–12 săptămâni', 'sage'), 'h' => __('Răbdare. Efectul se construiește.', 'sage'), 'p' => __('Efectele pe imunitate și metabolism se construiesc treptat. Diferențele se simt după 4–6 săptămâni.', 'sage')],
    ];
  }
@endphp
<section class="how">
  <div class="how-inner">
    <div class="how-head">
      <span class="eyebrow">{{ $how_eyebrow }}</span>
      <h2>{{ __('Trei pași,', 'sage') }} <em>{{ __('onest și simplu', 'sage') }}</em>.</h2>
      <p>{{ $how_intro }}</p>
    </div>
    <div class="how-grid">
      @foreach ($steps as $s)
        <div class="how-card">
          <span class="step">{{ $s['step'] }}</span>
          <span class="when">{{ $s['when'] }}</span>
          <h3>{{ $s['h'] }}</h3>
          <p>{{ $s['p'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
