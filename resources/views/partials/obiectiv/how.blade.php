{{-- Obiectiv — cum se folosește (3 pași). --}}
@php
  $eyebrow = \App\simptom_field('how_eyebrow', __('Cum se folosește', 'sage'));
  $titlu = \App\simptom_field('how_titlu', __('Trei momente, <em>trei doze.</em>', 'sage'));
  $items = \App\simptom_field('how_items', [
    ['when' => __('Dimineața', 'sage'), 'body' => __('O capsulă din B-complex cu micul dejun, pentru un start susținut. Nu pe stomacul gol.', 'sage')],
    ['when' => __('La prânz', 'sage'), 'body' => __('Magneziu înainte de momentul mort de la 14:00. Previne căderea cognitivă post-prandială.', 'sage')],
    ['when' => __('Seara', 'sage'), 'body' => __('Adaptogenii după cină. Nu interferează cu somnul, dar reglează cortizolul peste noapte.', 'sage')],
  ]);
@endphp

<section class="how">
  <div class="how-inner">
    <div class="how-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
    </div>
    <div class="how-grid">
      @foreach ($items as $i => $step)
        <div class="how-card">
          <span class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="when">{{ $step['when'] ?? '' }}</span>
          <p class="body">{{ $step['body'] ?? '' }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
