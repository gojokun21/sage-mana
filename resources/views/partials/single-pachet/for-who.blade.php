{{-- Single PACHET — „Pentru cine merge / nu merge". Conținut static (mockup). --}}
@php
  $yes = [
      __('Cei care vor o cură completă, nu produse răzlețe', 'sage'),
      __('Stil de viață cu mese procesate, stres cronic, somn dezordonat', 'sage'),
      __('Cei care preferă un singur preț transparent, fără bătăi de cap', 'sage'),
      __('Persoane care vor rezultate construite constant pe termen lung', 'sage'),
      __('Cei care au încercat produse izolate fără efect vizibil', 'sage'),
  ];
  $no = [
      __('Minori sub 12 ani — consultă medicul pediatru', 'sage'),
      __('Femei însărcinate sau care alăptează — consultă medicul', 'sage'),
      __('Persoane cu alergii la ingredientele din formulă', 'sage'),
      __('Cei care caută un efect imediat, peste noapte', 'sage'),
      __('Persoane sub tratament medical — verifică întâi cu medicul', 'sage'),
  ];
@endphp

<section class="pachet-pcine">
  <div class="pcine-inner">
    <div class="pcine-head">
      <div class="kicker">{{ __('Onestitate produs', 'sage') }}</div>
      <h2>{{ __('Pentru cine merge bine. Și pentru cine', 'sage') }} <em>{{ __('nu merge (încă).', 'sage') }}</em></h2>
    </div>
    <div class="pcine-grid">
      <div class="pcine-col yes">
        <h3>{{ __('Merge bine pentru...', 'sage') }}</h3>
        <ul>
          @foreach ($yes as $item)
            <li>{{ $item }}</li>
          @endforeach
        </ul>
      </div>
      <div class="pcine-col no">
        <h3>{{ __('Nu merge (încă) pentru...', 'sage') }}</h3>
        <ul>
          @foreach ($no as $item)
            <li>{{ $item }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</section>
