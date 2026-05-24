{{--
  „Recenziile clienților noștri" — carusel de citate pe single product (după taburi).
  HARDCODAT temporar (conținut + poze demo din exemplu) ca să vedem designul.
  Legăm ACF ulterior. Slider Swiper în resources/js/single-product.js
  (.product-reviews__swiper, 2 slide-uri vizibile pe desktop/tabletă).
--}}
@php
  $base = 'https://mananaturii.ro/';
  $reviews = [
    ['img' => $base . 'wp-content/uploads/2026/05/IMG_8387.webp',      'quote' => 'Jeleurile pentru focus și concentrare sunt foarte bune, cu gust delicios de afine, și sunt foarte ușor de luat, în special că nu prea îmi plac pastilele.'],
    ['img' => $base . 'wp-content/uploads/2026/05/IMG_8390.webp', 'quote' => 'Pentru a putea ține pasul cu nepoțelul meu, am nevoie constant de energie și suplimente de calitate. Așa ca mi-am făcut stocul cu produse Vivens Genetica.'],
    ['img' => $base . 'wp-content/uploads/2026/05/IMG_8386.webp',    'quote' => 'Folosesc magneziu bisglicinat 900 mg pentru mai multă energie, funcție musculară și suport osos.'],
    ['img' => $base . 'wp-content/uploads/2026/05/IMG_8385.jpg',   'quote' => 'Sunt mamă a doi copilași, mereu eram obosită, balonată și nu eram în apele mele. De când am decoperit capsulele Vivens Genetica pentru detoxifiere, pot să spun că sunt alt om. Recomand din toată inima mea.'],
      ['img' => $base . 'wp-content/uploads/2026/05/41e49d61-e2e1-4b88-a601-0aa5d83b4fcc.webp',    'quote' => 'Microflora+ mă ajută să am o digestie echilibrată și sănătoasă'],
    ['img' => $base . 'wp-content/uploads/2026/05/060e37c1-c669-4e4b-b1d5-488925bb6632.webp',   'quote' => 'Îmi place că am într-un singur produs toate vitamintele și mineralele de care am nevoie, iar probioticele microîncapsulate din Microflora+ m-au scăpat de tranzitul haotic pe care îl aveam după o perioadă de antibiotice'],
  ];
@endphp

<section class="product-reviews" aria-labelledby="product-reviews-title">
  <h2 id="product-reviews-title" class="product-reviews__title">{{ __('Recenziile clienților noștri', 'sage') }}</h2>

  <div class="product-reviews__slider">
    <div class="swiper product-reviews__swiper">
      <div class="swiper-wrapper">
        @foreach ($reviews as $r)
          <div class="swiper-slide">
            <figure class="product-review-card">
              <div class="product-review-card__photo">
                <img src="{{ esc_url($r['img']) }}" alt="{{ esc_attr__('Recenzie client', 'sage') }}" width="120" height="120" loading="lazy" decoding="async">
              </div>
              <div class="product-review-card__body">
                <blockquote class="product-review-card__quote">{{ $r['quote'] }}</blockquote>
              </div>
            </figure>
          </div>
        @endforeach
      </div>
    </div>

    <button type="button" class="product-reviews__nav product-reviews__nav--prev" aria-label="{{ esc_attr__('Anterior', 'sage') }}">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <button type="button" class="product-reviews__nav product-reviews__nav--next" aria-label="{{ esc_attr__('Următorul', 'sage') }}">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    <div class="product-reviews__pagination"></div>
  </div>
</section>
