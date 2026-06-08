{{--
  Mituri vs realitate — listă de carduri în 2 coloane (mit italic | realitate).
  Conținut static în array-ul $mituri.
--}}
@php
  $eyebrow = \App\simptom_field('mituri_eyebrow', __('Câteva lucruri pe care le auzim des', 'sage'));
  $titlu = \App\simptom_field('mituri_titlu', __('Mituri despre pofte și glicemie, <em>calm onest.</em>', 'sage'));
  $mituri = \App\simptom_field('mituri_items', [
    [
      'mit'  => __('„Dacă ai pofte, n-ai voință."', 'sage'),
      'real' => __('Poftele sunt semnal biologic, nu defect de caracter. Glicemia care urcă și cade declanșează hormoni de foame (grelină). Îmbunătățind glicemia, poftele scad natural, nu e nevoie de luptă.', 'sage'),
    ],
    [
      'mit'  => __('„Grăsimea în talie vine din mâncare grasă."', 'sage'),
      'real' => __('Grăsimea viscerală (în jurul organelor) vine în principal din exces de carbohidrați rafinați și fructoză (sucuri, dulciuri). Grăsimile bune (ouă, avocado, nuci) ajută saturarea, nu o agravează.', 'sage'),
    ],
    [
      'mit'  => __('„Dacă mănânci mai puțin, slăbești."', 'sage'),
      'real' => __('Restricția calorică fără corectarea glicemiei readuce poftele după 2–3 săptămâni. Schimbarea sustenabilă vine din echilibrarea meselor (proteine + grăsimi + fibre), nu din numărat calorii.', 'sage'),
    ],
  ]);
@endphp

<section class="mituri">
  <div class="mit-head">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
  </div>

  <div class="mit-grid">
    @foreach ($mituri as $mit)
      <div class="mit-card">
        <div class="mit-side">
          <span class="lbl">{{ __('Mit', 'sage') }}</span>
          <p class="txt">{{ $mit['mit'] }}</p>
        </div>
        <div class="real-side">
          <span class="lbl">{{ __('Realitate', 'sage') }}</span>
          <p class="txt">{{ $mit['real'] }}</p>
        </div>
      </div>
    @endforeach
  </div>
</section>
