{{-- Single PACHET — „Cum se folosește". 3 pași. Conținut static (mockup). --}}
@php
  $steps = [
      [
          'when' => __('Dimineața', 'sage'),
          'title' => __('Primul produs, pe stomacul gol.', 'sage'),
          'text' => __('La trezire, cu 15–30 min înainte de mic dejun, pentru absorbție optimă.', 'sage'),
      ],
      [
          'when' => __('La prânz sau seara', 'sage'),
          'title' => __('Al doilea produs, în timpul mesei.', 'sage'),
          'text' => __('Pur sau diluat cu apă, în timpul unei mese, când nutrienții se absorb cel mai bine.', 'sage'),
      ],
      [
          'when' => __('Durată recomandată', 'sage'),
          'title' => __('Minimum o cură completă.', 'sage'),
          'text' => __('Efectele se construiesc în 4–6 săptămâni. Pentru rezultate de durată, ideal 3 luni.', 'sage'),
      ],
  ];
@endphp

<section class="pachet-timeline">
  <div class="timeline-head">
    <div class="kicker">{{ __('Cum se folosește', 'sage') }}</div>
    <h2>{{ __('Simplu de urmat,', 'sage') }} <em>{{ __('zi de zi.', 'sage') }}</em></h2>
  </div>
  <div class="timeline-grid">
    @foreach ($steps as $idx => $step)
      <div class="tl-step">
        <div class="num">{{ $idx + 1 }}</div>
        <div class="when">{{ $step['when'] }}</div>
        <h4>{{ $step['title'] }}</h4>
        <p>{{ $step['text'] }}</p>
      </div>
    @endforeach
  </div>
</section>
