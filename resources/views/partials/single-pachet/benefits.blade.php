{{-- Single PACHET — beneficii. Listă statică (mockup), generică pentru orice pachet. --}}
@php
  $benefits = [
      __('Cură completă, gândită să acopere o nevoie de la cap la coadă', 'sage'),
      __('Produse care se potențează reciproc, nu doar puse împreună', 'sage'),
      __('Absorbție și efect optimizate prin momentul corect de administrare', 'sage'),
      __('Formule fără gluten, lactoză, soia sau OMG', 'sage'),
      __('Analize de lot publice pentru fiecare produs din pachet', 'sage'),
      __('Preț mai bun decât cumpărarea produselor separat', 'sage'),
      __('Rezultate care se construiesc constant pe parcursul curei', 'sage'),
      __('Susținere reală pe termen lung, nu un boost de o zi', 'sage'),
  ];
@endphp

<section class="pachet-benefits">
  <div class="benefits-inner">
    <div class="kicker">{{ __('Beneficiile pachetului', 'sage') }}</div>
    <h2>{{ __('Ce primești', 'sage') }} <em>{{ __('într-o singură cură.', 'sage') }}</em></h2>
    <ul class="b-grid">
      @foreach ($benefits as $b)
        <li>{{ $b }}</li>
      @endforeach
    </ul>
  </div>
</section>
