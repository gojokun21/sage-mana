{{-- Retur — procesul în 4 pași. --}}
@php
  $steps = [
    ['n' => '1', 'when' => __('Ziua 0', 'sage'),     'h' => __('Completezi formularul de mai jos.', 'sage'), 'p' => __('Durează <strong>2 minute</strong>. Primești pe email numărul RMA și adresa de retur.', 'sage')],
    ['n' => '2', 'when' => __('Ziua 1–2', 'sage'),   'h' => __('Împachetezi produsul sigilat.', 'sage'),    'p' => __('Cu <strong>ambalaj original + factură</strong>. Notezi numărul RMA vizibil pe colet.', 'sage')],
    ['n' => '3', 'when' => __('Ziua 2–7', 'sage'),   'h' => __('Trimiți coletul prin curier.', 'sage'),     'p' => __('Sameday, FAN sau curierul tău preferat. <strong>Păstrezi AWB-ul</strong> ca dovadă.', 'sage')],
    ['n' => '4', 'when' => __('Ziua 5–14', 'sage'),  'h' => __('Primești banii înapoi.', 'sage'),           'p' => __('Pe <strong>aceeași metodă de plată</strong>. Maxim 14 zile de la primirea coletului la noi.', 'sage')],
  ];
@endphp
<section class="steps-section">
  <div class="steps-inner">
    <div class="steps-head">
      <div class="eyebrow">{{ __('Procesul în 4 pași', 'sage') }}</div>
      <h2>{{ __('Cum returnezi', 'sage') }} <em>{{ __('în 4 pași.', 'sage') }}</em></h2>
    </div>
    <div class="steps-grid">
      @foreach ($steps as $s)
        <div class="step-card">
          <div class="num">{{ $s['n'] }}</div>
          <span class="when">{{ $s['when'] }}</span>
          <h3>{{ $s['h'] }}</h3>
          <p>{!! wp_kses($s['p'], ['strong' => []]) !!}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
