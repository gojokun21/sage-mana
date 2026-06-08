{{--
  Template Name: Cele mai vândute
  Pagina filtru „Cele mai vândute" — redesign 1:1 după mockup
  `preferinte/Pagina filtru - Cele mai vandute.html`.

  Conținut editorial CURATAT manual (Top 5 după reorder rate intern, fără cifre
  de vânzări fabricate). Lista celor 5 produse + copy-ul „de ce e best-seller"
  trăiesc în array-ul $bestsellers de mai jos; tabelul rezumat refolosește
  aceeași sursă. Link-urile produselor sunt placeholdere `#` — înlocuiește-le cu
  permalink-urile reale WooCommerce (sau `get_permalink($id)`).

  Scope CSS: `.bestseller-page` (resources/css/cele-mai-vandute.css via
  cele-mai-vandute-bundle.css, încărcat conditional din App\page_bundles()).
--}}

@extends('layouts.app')

@section('content')
  @php
    // Top 5 curatat. `bottles` randează grafica de sticlă (CSS-only) din mockup:
    // fiecare are `style` (matte/amber) și `label` (linii separate prin „|").
    // `trio` => true pentru cardul cu 3 sticle (pachet).
    $bestsellers = [
        [
            'rank' => '#1',
            'theme' => 't-dig',
            'cat' => __('Sănătate Intestinală', 'sage'),
            'vegan' => true,
            'trio' => false,
            'bottles' => [
                ['style' => 'matte', 'label' => 'Microflora+|Lemon'],
            ],
            'title' => __('Microflora+', 'sage'),
            'title_em' => __('Lemon Shots', 'sage'),
            'sub' => __('500 ml · 33 doze · 10 mld UFC · 4 tulpini', 'sage'),
            'benefits' => [
                __('Echilibrul florei intestinale și confort digestiv', 'sage'),
                __('Sprijină sistemul imunitar', 'sage'),
                __('Regenerarea mucoasei intestinale', 'sage'),
            ],
            'why' => __('Reorder rate cel mai mare din catalog. Clienții îl iau preventiv toamnă-iarnă și post-antibiotice. Gustul de lămâie face cura sustenabilă.', 'sage'),
            'price' => __('159 lei', 'sage'),
            'cpd_strong' => __('4,82 lei/zi', 'sage'),
            'cpd_rest' => __('33 zile', 'sage'),
            'link' => '#',
            'cta_label' => __('Vezi produsul', 'sage'),
            // tabel
            't_cat' => __('Digestie', 'sage'),
            't_meta' => __('500 ml · 33 doze · vegan', 'sage'),
            'rating_stars' => 5,
            'rating_lbl' => __('Foarte ridicat', 'sage'),
        ],
        [
            'rank' => '#2',
            'theme' => 't-detox',
            'cat' => __('Detox · 3 produse', 'sage'),
            'vegan' => true,
            'trio' => true,
            'bottles' => [
                ['style' => 'amber', 'label' => 'D-Tox|Ficat'],
                ['style' => 'matte', 'label' => 'Microflora+'],
                ['style' => 'amber', 'label' => 'Black Seed'],
            ],
            'title' => __('Pachet', 'sage'),
            'title_em' => __('Detox Plus', 'sage'),
            'sub' => __('120 zile · 3 suplimente · curățare profundă', 'sage'),
            'benefits' => [
                __('Curățare profundă ficat și sistem digestiv', 'sage'),
                __('Echilibru intestinal post-antibiotice', 'sage'),
                __('Recuperare post-sărbători și exces alimentar', 'sage'),
            ],
            'why' => __('Pachetul cu cea mai mare cerere în ianuarie–februarie (post-sărbători) și septembrie. Clienții observă diferența clară după 30–40 zile pe digestie și energie matinală.', 'sage'),
            'price' => __('457 lei', 'sage'),
            'cpd_strong' => __('3,81 lei/zi', 'sage'),
            'cpd_rest' => __('120 zile', 'sage'),
            'link' => '#',
            'cta_label' => __('Vezi pachetul', 'sage'),
            't_cat' => __('Detox', 'sage'),
            't_meta' => __('3 suplimente · 120 zile · vegan', 'sage'),
            'rating_stars' => 5,
            'rating_lbl' => __('Foarte ridicat', 'sage'),
        ],
        [
            'rank' => '#3',
            'theme' => 't-imun',
            'cat' => __('Imunitate', 'sage'),
            'vegan' => true,
            'trio' => false,
            'bottles' => [
                ['style' => 'amber', 'label' => 'Black Seed|Elixir'],
            ],
            'title' => __('Black Seed', 'sage'),
            'title_em' => __('Elixir', 'sage'),
            'sub' => __('240 capsule · 120 zile · ulei chimen negru + Vit. E', 'sage'),
            'benefits' => [
                __('Protecție imunitară prin timoquinonă', 'sage'),
                __('Echilibru metabolic natural', 'sage'),
                __('Suport cardiovascular', 'sage'),
            ],
            'why' => __('Produs nișat dar cu fani loiali — clienții care îl încep continuă 2–3 cure consecutive. Cost/zi excelent (1,53 lei/zi pentru 4 luni de protecție). Recomandat de medici colaboratori.', 'sage'),
            'price' => __('184 lei', 'sage'),
            'cpd_strong' => __('1,53 lei/zi', 'sage'),
            'cpd_rest' => __('120 zile', 'sage'),
            'link' => '#',
            'cta_label' => __('Vezi produsul', 'sage'),
            't_cat' => __('Imunitate', 'sage'),
            't_meta' => __('240 capsule · 120 zile · vegan', 'sage'),
            'rating_stars' => 4,
            'rating_lbl' => __('Ridicat · fani loiali', 'sage'),
        ],
        [
            'rank' => '#4',
            'theme' => 't-sport',
            'cat' => __('Performanță Sportivă', 'sage'),
            'vegan' => false,
            'trio' => false,
            'bottles' => [
                ['style' => 'amber', 'label' => 'Choco|Protein'],
            ],
            'title' => __('ChocoProtein', 'sage'),
            'title_em' => __('1000g', 'sage'),
            'sub' => __('1000 g · 33 porții · proteină din zer · ciocolată', 'sage'),
            'benefits' => [
                __('Creșterea și menținerea masei musculare', 'sage'),
                __('Refacere musculară după antrenamente', 'sage'),
                __('Aport complet de aminoacizi esențiali', 'sage'),
            ],
            'why' => __('Best-seller în segmentul sportiv. Sportivi recreaționali și profesioniști revin pentru gust și solubilitate. Prețul corect pe porție (6,63 lei) îl face accesibil pentru cură continuă.', 'sage'),
            'price' => __('219 lei', 'sage'),
            'cpd_strong' => __('6,63 lei/porție', 'sage'),
            'cpd_rest' => __('33 porții', 'sage'),
            'link' => '#',
            'cta_label' => __('Vezi produsul', 'sage'),
            't_cat' => __('Sport', 'sage'),
            't_meta' => __('1000 g · 33 porții · proteină din zer', 'sage'),
            'rating_stars' => 4,
            'rating_lbl' => __('Ridicat · segment sportiv', 'sage'),
        ],
        [
            'rank' => '#5',
            'theme' => 't-art',
            'cat' => __('Articulații & Frumusețe', 'sage'),
            'vegan' => false,
            'trio' => false,
            'bottles' => [
                ['style' => 'matte', 'label' => 'Collagen|Joint+'],
            ],
            'title' => __('Collagen Joint+', 'sage'),
            'title_em' => __('Berry', 'sage'),
            'sub' => __('500 ml · 33 doze · 7,2 g peptide colagen tip 1+2+3', 'sage'),
            'benefits' => [
                __('Sprijină sănătatea articulațiilor și mobilitatea', 'sage'),
                __('Menține elasticitatea pielii și țesuturilor', 'sage'),
                __('Susține sinteza naturală de colagen', 'sage'),
            ],
            'why' => __('Cel mai cumpărat în segmentul beauty/articulații. Clienții peste 35 ani revin lunar. Forma lichidă cu gust de fructe de pădure face cura plăcută — retention rate mare.', 'sage'),
            'price' => __('184 lei', 'sage'),
            'cpd_strong' => __('5,58 lei/zi', 'sage'),
            'cpd_rest' => __('33 zile', 'sage'),
            'link' => '#',
            'cta_label' => __('Vezi produsul', 'sage'),
            't_cat' => __('Articulații', 'sage'),
            't_meta' => __('500 ml · 33 doze · colagen lichid', 'sage'),
            'rating_stars' => 4,
            'rating_lbl' => __('Ridicat · 35+', 'sage'),
        ],
    ];
  @endphp

  <div class="bestseller-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <a href="{{ esc_url(get_post_type_archive_link('product') ?: home_url('/shop/')) }}">{{ __('Suplimente', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Cele mai vândute', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.cele-mai-vandute.hero')
    @include('partials.cele-mai-vandute.explain')
    @include('partials.cele-mai-vandute.products', ['bestsellers' => $bestsellers])
    @include('partials.cele-mai-vandute.table', ['bestsellers' => $bestsellers])
    @include('partials.cele-mai-vandute.quiz')
    @include('partials.cele-mai-vandute.faq')
    @include('partials.cele-mai-vandute.cta-final')
  </div>
@endsection
