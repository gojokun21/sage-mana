{{--
  Definiție simplă — 3 celule cu iconiță, titlu și paragraf. Text din ACF
  (fallback „Sindrom metabolic"); iconițele sunt statice, alocate pe poziție.
--}}
@php
  $eyebrow = \App\simptom_field('def_eyebrow', __('Definiție simplă', 'sage'));
  $titlu = \App\simptom_field('def_titlu', __('Trei sisteme care lucrează <em>mână în mână.</em>', 'sage'));
  $cells = \App\simptom_field('def_cells', [
    ['titlu' => __('Ficatul gestionează glicemia', 'sage'), 'text' => __('Când ficatul e supraîncărcat cu grăsime (steatoză), nu mai poate elibera glucoză echilibrat. Apar vârfuri și prăbușiri.', 'sage')],
    ['titlu' => __('Microbiomul cere ce mănâncă', 'sage'), 'text' => __('Bacteriile care preferă zahărul îți trimit semnale să mănânci zahăr. E o buclă de întărire: mai mult zahăr, mai multe pofte.', 'sage')],
    ['titlu' => __('Glicemia conduce energia', 'sage'), 'text' => __('Vârfurile de glicemie urmate de prăbușiri îți taie energia. După-amiaza la 14:00 e momentul clasic.', 'sage')],
  ]);

  $cell_svgs = [
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 2C9 6 6 8 6 13a6 6 0 0 0 12 0c0-5-3-7-6-11z"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><circle cx="8" cy="10" r="1.5" fill="currentColor"/><circle cx="14" cy="9" r="1.2" fill="currentColor"/><circle cx="13" cy="14" r="1.5" fill="currentColor"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 12h4l3-8 4 16 3-8h4"/></svg>',
  ];
@endphp
<section class="definitie">
  <div class="def-head">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
  </div>
  <div class="def-grid">
    @foreach (array_slice($cells, 0, 3) as $i => $cell)
      <div class="def-cell">
        <div class="ico">{!! $cell_svgs[$i] ?? $cell_svgs[0] !!}</div>
        <h3>{{ $cell['titlu'] ?? '' }}</h3>
        <p>{{ $cell['text'] ?? '' }}</p>
      </div>
    @endforeach
  </div>
</section>
