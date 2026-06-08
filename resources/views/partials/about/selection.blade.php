{{-- About — cum selectăm (3 pași). --}}
@php
  $steps = [
    ['n' => '1', 'h' => __('Plecăm de la <em>o nevoie reală.</em>', 'sage'), 'p' => __('Identificăm o problemă specifică (ficat, digestie, oboseală) și căutăm formularea cu <strong>cele mai multe studii</strong> pe acea zonă. Nu inventăm probleme ca să justificăm produse.', 'sage')],
    ['n' => '2', 'h' => __('Verificăm <em>profilul de siguranță.</em>', 'sage'), 'p' => __('Notificare ANSVSA, ingrediente <strong>NON-GMO</strong>, fără aditivi inutili. Dacă apare o componentă cu profil neclar de siguranță, formularea se refuză.', 'sage')],
    ['n' => '3', 'h' => __('Testăm <em>pe noi întâi.</em>', 'sage'), 'p' => __('Echipa ia <strong>cura completă minim 30 zile</strong>. Dacă nu observăm diferență sau apare disconfort, ajustăm formularea sau renunțăm la produs. Nu lansăm orbește.', 'sage')],
  ];
@endphp
<section class="selection">
  <div class="sel-inner">
    <div class="sel-head">
      <div class="eyebrow">{{ __('Cum selectăm', 'sage') }}</div>
      <h2>{{ __('Cum selectăm', 'sage') }} <em>{{ __('ce intră în catalog.', 'sage') }}</em></h2>
      <p>{!! wp_kses(__('Nu vindem orice supliment. Catalogul nostru are <strong>doar 20 de produse</strong> pentru că am ales să fim selectivi — fiecare produs trebuie să treacă prin trei filtre înainte să ajungă la tine.', 'sage'), ['strong' => []]) !!}</p>
    </div>
    <div class="sel-grid">
      @foreach ($steps as $step)
        <div class="sel-card">
          <div class="num">{{ $step['n'] }}</div>
          <h3>{!! wp_kses($step['h'], ['em' => []]) !!}</h3>
          <p>{!! wp_kses($step['p'], ['strong' => [], 'em' => []]) !!}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
