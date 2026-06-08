{{--
  Template Name: Pachete sub 400 lei
  Pagina filtru „Pachete sub 400 lei" — redesign 1:1 după mockup
  `preferinte/Pagina filtru - Pachete sub 400 lei.html`.

  Conținut editorial CURATAT manual (4 pachete de 2 suplimente, ordonate după
  preț crescător). Lista + copy-ul trăiesc în array-urile $packs / $bridge_packs
  de mai jos; tabelul comparativ refolosește $packs. Link-urile sunt placeholdere
  `#` — înlocuiește-le cu permalink-urile reale WooCommerce (produsele `bundle`).

  Scope CSS: `.packsub-page` (resources/css/packsub.css via packsub-bundle.css,
  încărcat conditional din App\page_bundles()).
--}}

@extends('layouts.app')

@section('content')
  @php
    // 4 pachete de 2 suplimente. `bottles` randează perechea de sticle CSS-only
    // (b1/b2 = înclinări opuse); `label` are linii separate prin „|".
    $packs = [
        [
            'theme' => 't-dig',
            'tema_tag' => __('Digestie', 'sage'),
            'duration' => __('120 zile', 'sage'),
            'bottles' => [
                ['style' => 'matte', 'label' => 'Microflora+'],
                ['style' => 'amber', 'label' => 'D-Tox|Ficat'],
            ],
            'title' => __('Pachet Confort Digestiv', 'sage'),
            'price' => __('283 lei', 'sage'),
            'cpd_strong' => __('2,36 lei/zi', 'sage'),
            'cpd_rest' => __('120 zile', 'sage'),
            'vegan' => true,
            'meta_extra' => __('formulă complementară', 'sage'),
            'contains' => [
                ['name' => __('Microflora+ Lemon Shots', 'sage'), 'price' => __('159 lei individual', 'sage')],
                ['name' => __('D-Tox Ficat', 'sage'), 'price' => __('139 lei individual', 'sage')],
            ],
            'for_who' => [
                __('Balonare după mese, gaze, tranzit haotic, intestin sensibil', 'sage'),
                __('Moleșeală după mese grase, greutate sub coaste', 'sage'),
                __('Recuperare după antibiotice, post-sărbători, exces alimentar', 'sage'),
            ],
            'save' => __('Economisești 15 lei vs. individual', 'sage'),
            'link' => '#',
            // tabel comparativ
            't_name' => __('Confort Digestiv', 'sage'),
            't_meta' => __('Microflora+ + D-Tox Ficat', 'sage'),
            't_separate' => __('298 lei', 'sage'),
            't_save' => __('15 lei', 'sage'),
        ],
        [
            'theme' => 't-detox',
            'tema_tag' => __('Detox', 'sage'),
            'duration' => __('120 zile', 'sage'),
            'bottles' => [
                ['style' => 'amber', 'label' => 'D-Tox|Ficat'],
                ['style' => 'matte', 'label' => 'Microflora+'],
            ],
            'title' => __('Pachet Detox', 'sage'),
            'price' => __('283 lei', 'sage'),
            'cpd_strong' => __('2,36 lei/zi', 'sage'),
            'cpd_rest' => __('120 zile', 'sage'),
            'vegan' => true,
            'meta_extra' => __('susținere ficat & intestin', 'sage'),
            'contains' => [
                ['name' => __('D-Tox Ficat', 'sage'), 'price' => __('139 lei individual', 'sage')],
                ['name' => __('Microflora+ Lemon Shots', 'sage'), 'price' => __('159 lei individual', 'sage')],
            ],
            'for_who' => [
                __('Balonare, tranzit haotic, intestin sensibil, post-antibiotice', 'sage'),
                __('Oboseală fără cauză, ten gălbui, treziri la 2–3 noaptea, halenă matinală', 'sage'),
                __('Slăbit greoi, sensibilitate la cofeină și medicamente', 'sage'),
            ],
            'save' => __('Economisești 15 lei vs. individual', 'sage'),
            'link' => '#',
            't_name' => __('Detox', 'sage'),
            't_meta' => __('D-Tox Ficat + Microflora+', 'sage'),
            't_separate' => __('298 lei', 'sage'),
            't_save' => __('15 lei', 'sage'),
        ],
        [
            'theme' => 't-focus',
            'tema_tag' => __('Focus', 'sage'),
            'duration' => __('50 zile', 'sage'),
            'bottles' => [
                ['style' => 'amber', 'label' => 'LionFocus|B6'],
                ['style' => 'matte', 'label' => 'Vita|Complete+'],
            ],
            'title' => __('Pachet Focus', 'sage'),
            'price' => __('292 lei', 'sage'),
            'cpd_strong' => __('5,84 lei/zi', 'sage'),
            'cpd_rest' => __('50 zile', 'sage'),
            'vegan' => true,
            'meta_extra' => __('țintă cognitivă', 'sage'),
            'contains' => [
                ['name' => __('LionFocus B6 Jeleuri', 'sage'), 'price' => __('124 lei individual', 'sage')],
                ['name' => __('Vita Complete+ Vegan Shots', 'sage'), 'price' => __('184 lei individual', 'sage')],
            ],
            'for_who' => [
                __('Ceață mentală, uitări frecvente, pierderea firului în conversații', 'sage'),
                __('Oboseală cognitivă, moleșeală după-amiaza, dependență de cafea', 'sage'),
                __('Memorie de lucru slabă, sarcini lungi nedușe la capăt', 'sage'),
            ],
            'save' => __('Economisești 16 lei vs. individual', 'sage'),
            'link' => '#',
            't_name' => __('Focus', 'sage'),
            't_meta' => __('LionFocus B6 + Vita Complete+', 'sage'),
            't_separate' => __('308 lei', 'sage'),
            't_save' => __('16 lei', 'sage'),
        ],
        [
            'theme' => 't-bel',
            'tema_tag' => __('Frumusețe & Articulații', 'sage'),
            'duration' => __('33 zile', 'sage'),
            'bottles' => [
                ['style' => 'matte', 'label' => 'Microflora+'],
                ['style' => 'amber', 'label' => 'Collagen|Joint+'],
            ],
            'title' => __('Pachet Echilibru', 'sage'),
            'price' => __('325 lei', 'sage'),
            'cpd_strong' => __('9,85 lei/zi', 'sage'),
            'cpd_rest' => __('33 zile', 'sage'),
            'vegan' => false,
            'meta_extra' => __('intestin + colagen', 'sage'),
            'contains' => [
                ['name' => __('Microflora+ Lemon Shots', 'sage'), 'price' => __('159 lei individual', 'sage')],
                ['name' => __('Collagen Joint+ Berry', 'sage'), 'price' => __('184 lei individual', 'sage')],
            ],
            'for_who' => [
                __('Riduri fine, ten obosit fără strălucire, cearcăne persistente', 'sage'),
                __('Păr fragil, cădere accentuată, unghii care se exfoliază în foi', 'sage'),
                __('Disconfort articular, rigiditate matinală, mobilitate redusă', 'sage'),
            ],
            'save' => __('Economisești 18 lei vs. individual', 'sage'),
            'link' => '#',
            't_name' => __('Echilibru', 'sage'),
            't_meta' => __('Microflora+ + Collagen Joint+', 'sage'),
            't_separate' => __('343 lei', 'sage'),
            't_save' => __('18 lei', 'sage'),
        ],
    ];

    // Bridge — pachete de 2 produse înrudite (link spre hub-ul de pachete mari).
    $bridge_packs = [
        [
            'count' => __('2 produse · 120 zile', 'sage'),
            'title' => __('Pachet Energie', 'sage'),
            'desc' => __('Vita Complete+ + D-Tox Ficat. Energie susținută, ficat curat.', 'sage'),
            'price' => __('306 lei', 'sage'),
            'link' => '#',
        ],
        [
            'count' => __('2 produse · 120 zile', 'sage'),
            'title' => __('Pachet Imunitate', 'sage'),
            'desc' => __('Black Seed + Vita Complete+. Apărare naturală, energie zilnică.', 'sage'),
            'price' => __('349 lei', 'sage'),
            'link' => '#',
        ],
        [
            'count' => __('2 produse · 50 zile', 'sage'),
            'title' => __('Pachet Frumusețe', 'sage'),
            'desc' => __('Collagen + Vita Complete+. Peptide & nutrienți, într-un singur ritual.', 'sage'),
            'price' => __('349 lei', 'sage'),
            'link' => '#',
        ],
    ];

    $pachete_url = home_url('/pachete/');
  @endphp

  <div class="packsub-page">
    <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
      <div class="breadcrumb-inner">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <a href="{{ esc_url($pachete_url) }}">{{ __('Pachete', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Sub 400 lei', 'sage') }}</span>
      </div>
    </nav>

    @include('partials.pachete-sub-400.hero')
    @include('partials.pachete-sub-400.explain', ['pachete_url' => $pachete_url])
    @include('partials.pachete-sub-400.packs', ['packs' => $packs])
    @include('partials.pachete-sub-400.table', ['packs' => $packs])
    @include('partials.pachete-sub-400.bridge', ['bridge_packs' => $bridge_packs, 'pachete_url' => $pachete_url])
    @include('partials.pachete-sub-400.faq')
    @include('partials.pachete-sub-400.cta-final')
  </div>
@endsection
