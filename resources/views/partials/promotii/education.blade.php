{{-- Promoții — „De ce reducem prețul, nu calitatea" (3 carduri statice). --}}
@php
  $edu = [
    ['svg' => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/>', 'h' => __('Nu sunt produse expirate.', 'sage'), 'p' => __('Toate produsele în ofertă au minim 12 luni valabilitate la livrare. Verificăm lot cu lot la fiecare expediere.', 'sage')],
    ['svg' => '<path d="M12 2v20M5 9h14M5 15h14"/>', 'h' => __('Reducem marja, nu ingredientele.', 'sage'), 'p' => __('Prețul mai mic vine din marja noastră sau din negocieri cu furnizorii. Niciun compromis pe formulă, dozaj sau materie primă.', 'sage')],
    ['svg' => '<path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/>', 'h' => __('Stoc rotativ lunar.', 'sage'), 'p' => __('Ofertele se schimbă lunar pe baza stocurilor reale. Nu fabricăm urgență artificială, dar cantitățile sunt finite.', 'sage')],
  ];
@endphp
<section class="education">
  <div class="edu-inner">
    <div class="edu-head">
      <div class="eyebrow">{{ __('Onestitate', 'sage') }}</div>
      <h2>{{ __('De ce reducem prețul,', 'sage') }} <em>{{ __('nu calitatea', 'sage') }}</em></h2>
    </div>
    <div class="edu-grid">
      @foreach ($edu as $card)
        <div class="edu-card">
          <div class="ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">{!! $card['svg'] !!}</svg></div>
          <h3>{{ $card['h'] }}</h3>
          <p>{{ $card['p'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
