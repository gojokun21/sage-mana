{{--
  Trei semne care apar primele — carduri numerotate cu „Ce ajută de obicei" în
  subsol verde. Conținut static în array-ul $semne.
--}}
@php
  $eyebrow = \App\simptom_field('semne_eyebrow', __('Cum se simte de obicei', 'sage'));
  $titlu = \App\simptom_field('semne_titlu', __('Trei semne <em>care apar primele.</em>', 'sage'));
  $semne = \App\simptom_field('semne_items', [
    [
      'titlu' => __('Pofte intense de dulce sau pâine', 'sage'),
      'desc' => __('La 11 dimineața, 4 după-amiaza sau seara târziu. Nu e foame reală, e cădere de glicemie.', 'sage'),
      'ajuta' => __('Mese cu proteine și grăsimi bune (ouă, avocado, pește), nu carbohidrați simpli dimineața.', 'sage'),
    ],
    [
      'titlu' => __('Oboseală după mese', 'sage'),
      'desc' => __('Mai ales după mese cu paste, orez, pâine. Glicemia urcă brusc, insulina crește, urmează căderea.', 'sage'),
      'ajuta' => __('Plimbare 15 minute după masă, fibre solubile, mese mai mici dar mai dese.', 'sage'),
    ],
    [
      'titlu' => __('Grăsime acumulată în talie', 'sage'),
      'desc' => __('Grosime peste talie disproporționată față de restul corpului. Indică acumulare în ficat și rezistență la insulină.', 'sage'),
      'ajuta' => __('Forță (greutăți) 2x/săptămână, omega-3, somn 7–8h.', 'sage'),
    ],
  ]);
@endphp

<section class="cauze">
  <div class="cauze-head">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
  </div>
  <div class="cauze-grid">
    @foreach ($semne as $i => $semn)
      <div class="cauza-card">
        <div class="num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
        <h3>{{ $semn['titlu'] }}</h3>
        <p>{{ $semn['desc'] }}</p>
        <div class="cauza-foot">
          <strong>{{ __('Ce ajută de obicei', 'sage') }}</strong>
          {{ $semn['ajuta'] }}
        </div>
      </div>
    @endforeach
  </div>
</section>
