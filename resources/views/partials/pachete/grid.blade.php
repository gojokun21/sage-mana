{{--
  Grid-ul celor 11 pachete. Header (eyebrow + h2 + legend) + container .pkg-grid
  cu 11 includes de partials.pachete.card cu metadata hardcodate per pachet.
  Numele bundle-ului, prețul, lista produselor și permalink-ul vin din WC.
--}}

@php
  $pachete = [
    [
      'slug' => 'pachet-imunitate',
      'eyebrow' => __('Imunitate & apărare', 'sage'),
      'hook' => __('Apărare naturală, energie zilnică.', 'sage'),
      'durata' => __('120 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-energie',
      'eyebrow' => __('Energie & metabolism', 'sage'),
      'hook' => __('Energie susținută, ficat curat.', 'sage'),
      'durata' => __('120 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-focus',
      'eyebrow' => __('Focus & claritate', 'sage'),
      'hook' => __('Concentrare, energie cognitivă.', 'sage'),
      'durata' => __('50 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-frumusete',
      'eyebrow' => __('Frumusețe · piele păr unghii', 'sage'),
      'hook' => __('Peptide & nutrienți, într-un singur ritual.', 'sage'),
      'durata' => __('50 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-echilibru',
      'eyebrow' => __('Digestie & frumusețe', 'sage'),
      'hook' => __('Intestin sănătos, piele luminoasă.', 'sage'),
      'durata' => __('33 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-confort-digestiv',
      'eyebrow' => __('Digestie', 'sage'),
      'hook' => __('Probiotice & detox ficat.', 'sage'),
      'durata' => __('120 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-detox',
      'eyebrow' => __('Digestie & detox', 'sage'),
      'hook' => __('Curățare & echilibru.', 'sage'),
      'durata' => __('120 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-vitalitate',
      'eyebrow' => __('Energie & regenerare', 'sage'),
      'hook' => __('Colagen, vitamine și probiotice.', 'sage'),
      'durata' => __('33 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-detox-plus',
      'eyebrow' => __('Curățare profundă', 'sage'),
      'hook' => __('Ficat, intestin, scut antioxidant.', 'sage'),
      'durata' => __('120 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-complex-sanatate',
      'eyebrow' => __('Vitalitate completă · 40+', 'sage'),
      'hook' => __('Fundația, flora și scutul.', 'sage'),
      'durata' => __('50 zile', 'sage'),
    ],
    [
      'slug' => 'pachet-regenerare-celulara',
      'eyebrow' => __('Anti-aging funcțional', 'sage'),
      'hook' => __('Refacere profundă, protecție celulară.', 'sage'),
      'durata' => __('33 zile intensiv', 'sage'),
    ],
  ];
@endphp

<section class="grid-block">
  <div class="grid-block-inner">
    <div class="grid-head">
      <div>
        <div class="eyebrow">{{ __('Cele 11 pachete', 'sage') }}</div>
        <h2>
          {{ __('De la 2 produse pentru o nevoie precisă, până la', 'sage') }}
          <em>{{ __('3 produse care acoperă o axă întreagă.', 'sage') }}</em>
        </h2>
      </div>
      <div class="legend">
        <span class="dot" aria-hidden="true"></span>
        {{ __('Toate vegane sau cu opțiune vegană marcată în pagină.', 'sage') }}
      </div>
    </div>

    <div class="pkg-grid">
      @foreach ($pachete as $p)
        @include('partials.pachete.card', $p)
      @endforeach
    </div>
  </div>
</section>
