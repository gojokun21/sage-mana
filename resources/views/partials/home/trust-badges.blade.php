{{--
  Trust badges — final reassurance row before footer.
  6 cards: UE, HACCP/ISO/GMP, Vegan, Retur 14 zile, Ramburs, WhatsApp.

  Icoane: Lucide (lucide.dev) pentru cele 5 line-icons (stroke 2, lineCap/Join round)
  + Simple Icons WhatsApp brand mark pentru recunoaștere.
--}}

@php
  $whatsapp_phone = '40749492794';
  $whatsapp_url = 'https://wa.me/' . $whatsapp_phone;

  // Lucide line icons — viewBox 24 24, stroke=currentColor.
  $stroke_svg = function (string $paths): string {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $paths . '</svg>';
  };

  $badges = [
    [
      // Lucide: badge-check — certification mark
      'label' => __('Producție în UE', 'sage'),
      'hint'  => __('Fabricat în Uniunea Europeană', 'sage'),
      'svg'   => $stroke_svg('<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/>'),
    ],
    [
      // Lucide: shield-check — quality assurance
      'label' => __('HACCP · ISO · GMP', 'sage'),
      'hint'  => __('Standarde internaționale de calitate', 'sage'),
      'svg'   => $stroke_svg('<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>'),
    ],
    [
      // Lucide: leafy-green — single leaf with vein, recognizable for vegan
      'label' => __('100% Vegan', 'sage'),
      'hint'  => __('Fără ingrediente de origine animală', 'sage'),
      'svg'   => $stroke_svg('<path d="M2 22c1.25-.987 2.27-1.975 3.9-2.2a5.56 5.56 0 0 1 3.8 1.5 4 4 0 0 0 6.187-2.353 3.5 3.5 0 0 0 3.69-5.116A3.5 3.5 0 0 0 20.95 8 3.5 3.5 0 1 0 16 3.05a3.5 3.5 0 0 0-5.831 1.373 3.5 3.5 0 0 0-5.116 3.69 4 4 0 0 0-2.348 6.155C3.499 15.42 4.409 16.712 4.2 18.1 3.926 19.743 3 20.752 2 22"/><path d="M2 22 17 7"/>'),
    ],
    [
      // Lucide: rotate-ccw — return policy
      'label' => __('Retur 14 zile', 'sage'),
      'hint'  => __('Banii înapoi, garantat', 'sage'),
      'svg'   => $stroke_svg('<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>'),
    ],
    [
      // Lucide: banknote — cash on delivery
      'label' => __('Plata ramburs', 'sage'),
      'hint'  => __('Plătești la primirea coletului', 'sage'),
      'svg'   => $stroke_svg('<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>'),
    ],
    [
      // Simple Icons: WhatsApp brand mark (filled, viewBox 24 24)
      'label' => __('Suport WhatsApp', 'sage'),
      'hint'  => __('Răspuns rapid la întrebări', 'sage'),
      'href'  => $whatsapp_url,
      'svg'   => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.002-5.45 4.437-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413"/></svg>',
    ],
  ];
@endphp

<section class="home-section home-trust" aria-labelledby="home-trust-title">
  <div class="home-section__header">
    <h2 id="home-trust-title" class="home-section__title">{{ __('Cumpără cu încredere', 'sage') }}</h2>
    <p class="home-section__subtitle">{{ __('Calitate certificată, livrare rapidă și suport rapid pentru fiecare comandă.', 'sage') }}</p>
  </div>

  <ul class="home-trust__grid" role="list">
    @foreach ($badges as $badge)
      @php $is_link = ! empty($badge['href']); @endphp
      <li class="home-trust__item">
        @if ($is_link)
          <a class="home-trust__card" href="{{ esc_url($badge['href']) }}" target="_blank" rel="noopener">
            <span class="home-trust__icon" aria-hidden="true">{!! $badge['svg'] !!}</span>
            <span class="home-trust__label">{{ $badge['label'] }}</span>
            <span class="home-trust__hint">{{ $badge['hint'] }}</span>
          </a>
        @else
          <div class="home-trust__card">
            <span class="home-trust__icon" aria-hidden="true">{!! $badge['svg'] !!}</span>
            <span class="home-trust__label">{{ $badge['label'] }}</span>
            <span class="home-trust__hint">{{ $badge['hint'] }}</span>
          </div>
        @endif
      </li>
    @endforeach
  </ul>
</section>
