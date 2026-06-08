{{-- About — standarde producție Vivens Genetica (secțiune verde închis). --}}
@php
  $certs = [
    ['seal' => 'HACCP', 'h' => __('HACCP', 'sage'), 'p' => __('Hazard Analysis Critical Control Points — sistem internațional de management al siguranței alimentare.', 'sage')],
    ['seal' => 'ISO 22000', 'h' => __('ISO 22000', 'sage'), 'p' => __('Standard internațional pentru siguranța alimentară de-a lungul întregului lanț de producție.', 'sage')],
    ['seal' => 'GMP', 'h' => __('GMP', 'sage'), 'p' => __('Good Manufacturing Practice — bune practici de producție pentru consistență și calitate.', 'sage')],
    ['seal' => 'ANSVSA', 'h' => __('ANSVSA notificat', 'sage'), 'p' => __('Toate produsele sunt notificate la autoritatea competentă din România înainte de comercializare.', 'sage')],
  ];
  $points = [
    __('<strong>Ingrediente NON-GMO</strong> verificate la sursă', 'sage'),
    __('<strong>Procese controlate</strong> în laborator pe fiecare lot', 'sage'),
    __('Fiecare formulă <strong>dezvoltată cu un scop clar</strong>', 'sage'),
    __('<strong>Notificare ANSVSA</strong> înainte de lansare', 'sage'),
  ];
@endphp
<section class="standards">
  <div class="standards-inner">
    <div class="standards-head">
      <div class="eyebrow">{{ __('Vivens Genetica · producție', 'sage') }}</div>
      <h2>{{ __('Standardele', 'sage') }} <em>{{ __('din spatele produselor.', 'sage') }}</em></h2>
      <p>{!! wp_kses(__('<strong>Vivens Genetica</strong> este sub-brandul nostru de producție. Produsele sunt realizate în <strong>Uniunea Europeană</strong>, respectând standarde clare și verificabile.', 'sage'), ['strong' => []]) !!}</p>
    </div>
    <div class="cert-grid">
      @foreach ($certs as $c)
        <div class="cert-card">
          <div class="seal">{{ $c['seal'] }}</div>
          <h4>{{ $c['h'] }}</h4>
          <p>{{ $c['p'] }}</p>
        </div>
      @endforeach
    </div>
    <div class="cert-list">
      @foreach ($points as $point)
        <div class="cert-list-item">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
          <span>{!! wp_kses($point, ['strong' => []]) !!}</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
