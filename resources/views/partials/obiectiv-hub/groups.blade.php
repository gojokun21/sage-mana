{{--
  Cele 5 grupe de obiective (Energie & vitalitate, Corp & metabolism, Minte &
  performanță, Apărare & protecție, Estetic & structură), fiecare cu 2 carduri.
  Sursa obiectivelor = partials/mega-obiectiv. Link-ul fiecărui card se rezolvă
  la pagina reală de obiectiv (copil sub /dupa-obiectiv/{slug}/), cu fallback la
  hubul /dupa-obiectiv/. Structură IDENTICĂ cu partials/symptom/groups —
  `.grp-block` / `.grp-grid` / `.grp-card` + filtrarea din symptom.js.

  Ca să adaugi/modifici un obiectiv: editează array-ul $groups de mai jos.
--}}
@php
  $hub_url = home_url('/dupa-obiectiv/');

  // Slug obiectiv → permalink-ul paginii reale, fallback pe hub.
  $obj_url = static function (string $slug) use ($hub_url) {
      if ($slug !== '') {
          $pg = get_page_by_path('dupa-obiectiv/' . $slug, OBJECT, 'page');
          if ($pg) {
              return get_permalink($pg);
          }
      }
      return $hub_url;
  };

  $groups = [
    [
      'eyebrow'  => __('Grupa 01 · Energie & vitalitate', 'sage'),
      'title'    => __('Când vrei să simți', 'sage'),
      'title_em' => __('energie reală, nu împrumutată.', 'sage'),
      'cards'    => [
        ['name' => __('Mai multă energie zilnică', 'sage'), 'slug' => 'energie',     'chip' => __('Top', 'sage'), 'desc' => __('Te trezești deja obosit, ai nevoie de cafea ca să funcționezi, iar după-amiaza te golești complet.', 'sage')],
        ['name' => __('Anti-aging și longevitate', 'sage'), 'slug' => 'anti-aging',                                'desc' => __('Vrei să încetinești îmbătrânirea celulară și să-ți păstrezi vitalitatea pe termen lung.', 'sage')],
      ],
    ],
    [
      'eyebrow'  => __('Grupa 02 · Corp & metabolism', 'sage'),
      'title'    => __('Când corpul cere', 'sage'),
      'title_em' => __('o resetare din interior.', 'sage'),
      'cards'    => [
        ['name' => __('Detoxifiere și curățare', 'sage'),  'slug' => 'detoxifiere',          'desc' => __('Suport pentru ficat și eliminarea toxinelor după excese, tratamente sau perioade grele.', 'sage')],
        ['name' => __('Sănătate intestinală', 'sage'),     'slug' => 'sanatate-intestinala', 'desc' => __('Echilibrul florei, digestie ușoară, mai puține balonări și disconfort după mese.', 'sage')],
      ],
    ],
    [
      'eyebrow'  => __('Grupa 03 · Minte & performanță', 'sage'),
      'title'    => __('Când mintea trebuie', 'sage'),
      'title_em' => __('să țină pasul cu tine.', 'sage'),
      'cards'    => [
        ['name' => __('Focus și claritate mentală', 'sage'), 'slug' => 'focus',                'desc' => __('Concentrare mai bună, memorie ascuțită, fără ceața mentală care apare după prânz.', 'sage')],
        ['name' => __('Performanță sportivă', 'sage'),       'slug' => 'performanta-sportiva', 'desc' => __('Mai multă forță, recuperare rapidă și energie constantă la fiecare antrenament.', 'sage')],
      ],
    ],
    [
      'eyebrow'  => __('Grupa 04 · Apărare & protecție', 'sage'),
      'title'    => __('Când vrei să te aperi', 'sage'),
      'title_em' => __('înainte să fie nevoie.', 'sage'),
      'cards'    => [
        ['name' => __('Imunitate puternică', 'sage'),  'slug' => 'imunitate',        'desc' => __('Mai puține răceli, recuperare rapidă după boală și apărare susținută tot anul.', 'sage')],
        ['name' => __('Sănătatea inimii', 'sage'),     'slug' => 'sanatatea-inimii', 'desc' => __('Suport pentru tensiune, colesterol echilibrat și un ritm cardiac sănătos.', 'sage')],
      ],
    ],
    [
      'eyebrow'  => __('Grupa 05 · Estetic & structură', 'sage'),
      'title'    => __('Când vrei să arăți', 'sage'),
      'title_em' => __('cât de bine te simți.', 'sage'),
      'cards'    => [
        ['name' => __('Frumusețe — piele, păr, unghii', 'sage'), 'slug' => 'frumusete',        'desc' => __('Piele fermă, păr puternic și unghii rezistente — construite din interior spre exterior.', 'sage')],
        ['name' => __('Oase și articulații', 'sage'),            'slug' => 'oase-articulatii', 'desc' => __('Mobilitate fără durere, articulații suple și oase dense pe termen lung.', 'sage')],
      ],
    ],
  ];
@endphp

@foreach ($groups as $i => $group)
  <section class="grp-block {{ $i % 2 === 0 ? 'tone-a' : 'tone-b' }}">
    <div class="grp-inner">
      <div class="grp-head">
        <div class="eyebrow">{{ $group['eyebrow'] }}</div>
        <h2>{{ $group['title'] }} <em>{{ $group['title_em'] }}</em></h2>
      </div>
      <div class="grp-grid">
        @foreach ($group['cards'] as $card)
          @include('partials.obiectiv-hub.card', [
            'name' => $card['name'],
            'desc' => $card['desc'],
            'url'  => $obj_url($card['slug'] ?? ''),
            'chip' => $card['chip'] ?? null,
          ])
        @endforeach
      </div>
    </div>
  </section>
@endforeach

<div class="symptom-noresult" data-symptom-noresult>
  {{ __('Niciun obiectiv nu se potrivește căutării', 'sage') }} „<b data-symptom-query></b>". {{ __('Scrie-ne pe WhatsApp și te ajutăm.', 'sage') }}
</div>
