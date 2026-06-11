{{-- Single PACHET — beneficii. ACF (grup pachet, seed `natura:pachet-seed`) cu fallback static generic. --}}
@php
  $benefits_titlu = get_field('pk_benefits_titlu') ?: __('Ce primești <em>într-o singură cură.</em>', 'sage');

  $benefits = array_values(array_filter(array_map(static fn ($r) => $r['text'] ?? '', get_field('pk_benefits_items') ?: [])));
  if (empty($benefits)) {
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
  }
@endphp

<section class="pachet-benefits">
  <div class="benefits-inner">
    <div class="kicker">{{ __('Beneficiile pachetului', 'sage') }}</div>
    <h2>{!! wp_kses($benefits_titlu, ['em' => [], 'strong' => []]) !!}</h2>
    <ul class="b-grid">
      @foreach ($benefits as $b)
        <li>{{ $b }}</li>
      @endforeach
    </ul>
  </div>
</section>
