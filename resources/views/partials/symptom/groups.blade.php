{{--
  Cele 4 grupe de simptome (Digestiv, Energie & somn, Imunitate & inflamație,
  Performanță & focus), fiecare cu 5 carduri. Conținut static, curatat manual
  după mockup. Tonul de fundal alternează tone-a / tone-b.

  Ca să adaugi/modifici un simptom: editează array-ul $groups de mai jos.
  `title_em` e partea în italic verde din titlul grupei; `chip` e opțional pe card.
--}}
@php
  $groups = [
    [
      'eyebrow'  => __('Grupa 01 · Digestiv', 'sage'),
      'title'    => __('Când ceva nu e', 'sage'),
      'title_em' => __('în ordine cu digestia.', 'sage'),
      'cards'    => [
        ['name' => __('Balonare', 'sage'),               'chip' => '60%', 'desc' => __('Abdomen plin, presiune în pântec, gaze.', 'sage')],
        ['name' => __('Constipație', 'sage'),                              'desc' => __('Mai puțin de 3 tranzite pe săptămână, scaun tare, efort mare.', 'sage')],
        ['name' => __('Reflux și arsuri', 'sage'),                         'desc' => __('Senzație de arsură în piept sau în gât, mai des după mese mari sau seara.', 'sage')],
        ['name' => __('Diaree cronică', 'sage'),                           'desc' => __('Tranzit accelerat repetat, mai mult de 2 săptămâni.', 'sage')],
        ['name' => __('Intoleranțe alimentare', 'sage'),                   'desc' => __('Reacții repetate la aceeași grupă de alimente: lactate, gluten, FODMAP.', 'sage')],
      ],
    ],
    [
      'eyebrow'  => __('Grupa 02 · Energie & somn', 'sage'),
      'title'    => __('Când corpul refuză', 'sage'),
      'title_em' => __('să mai dea randament.', 'sage'),
      'cards'    => [
        ['name' => __('Oboseală cronică', 'sage'),                'desc' => __('Epuizare care nu trece nici după weekend lung. Te trezești deja obosit dimineața.', 'sage')],
        ['name' => __('Ceața mentală', 'sage'),                   'desc' => __('Greutate în concentrare, memorie de scurt termen încetinită, lucrezi prin ceață.', 'sage')],
        ['name' => __('Somn agitat', 'sage'),                     'desc' => __('Treziri nocturne, somn neodihnitor, dimineți grele.', 'sage')],
        ['name' => __('Insomnie de adormire', 'sage'),            'desc' => __('S-au scurs 30+ minute în fiecare seară până te ia somnul.', 'sage')],
        ['name' => __('Energy crash după-amiezii', 'sage'),       'desc' => __('Ora 14–15 te găsește golit, cauți cafea ca să termini ziua.', 'sage')],
      ],
    ],
    [
      'eyebrow'  => __('Grupa 03 · Imunitate & inflamație', 'sage'),
      'title'    => __('Când organismul luptă', 'sage'),
      'title_em' => __('mai des decât trebuie.', 'sage'),
      'cards'    => [
        ['name' => __('Răceli repetate', 'sage'),                 'desc' => __('Mai mult de 4 răceli pe an, durează și 10+ zile fiecare.', 'sage')],
        ['name' => __('Inflamație articulară', 'sage'),           'desc' => __('Durere în genunchi, șolduri sau încheieturi, accentuată dimineața.', 'sage')],
        ['name' => __('Alergii sezoniere puternice', 'sage'),     'desc' => __('Polen, praf, păr de animale, simptome care țin săptămâni.', 'sage')],
        ['name' => __('Piele inflamată', 'sage'),                 'desc' => __('Eczeme, roșeață persistentă, mâncărimi recurente.', 'sage')],
        ['name' => __('Recuperare lentă după boală', 'sage'),     'desc' => __('După viroză, energia revine în 3–4 săptămâni, nu într-una.', 'sage')],
      ],
    ],
    [
      'eyebrow'  => __('Grupa 04 · Performanță & focus', 'sage'),
      'title'    => __('Când vrei mai mult', 'sage'),
      'title_em' => __('de la ce ești deja.', 'sage'),
      'cards'    => [
        ['name' => __('Recuperare musculară lentă', 'sage'),      'desc' => __('Durere musculară care ține 3+ zile după antrenament intens.', 'sage')],
        ['name' => __('Lipsă de focus la antrenament', 'sage'),   'desc' => __('Corpul e ok, dar mintea rătăcește în sală.', 'sage')],
        ['name' => __('Plateau de forță', 'sage'),                'desc' => __('Câteva săptămâni cu aceleași greutăți, niciun progres.', 'sage')],
        ['name' => __('Tonus muscular scăzut', 'sage'),           'desc' => __('Mușchi care arată mai puțin definit, chiar dacă te antrenezi.', 'sage')],
        ['name' => __('Energie sportivă inconsistentă', 'sage'),  'desc' => __('Zi de zi cu variații mari de putere și rezistență.', 'sage')],
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
          @include('partials.symptom.card', [
            'name' => $card['name'],
            'desc' => $card['desc'],
            'chip' => $card['chip'] ?? null,
          ])
        @endforeach
      </div>

      @if ($loop->last)
        <p class="grp-foot-line">{{ __('Mai sunt 12 simptome în index, distribuite în grupe minore (piele, păr, hormoni, ciclu menstrual).', 'sage') }} <a href="#">{{ __('Vezi indexul complet (32 simptome) →', 'sage') }}</a></p>
      @endif
    </div>
  </section>
@endforeach

<div class="symptom-noresult" data-symptom-noresult>
  {{ __('Niciun simptom nu se potrivește căutării', 'sage') }} „<b data-symptom-query></b>". {{ __('Scrie-ne pe WhatsApp și te ajutăm.', 'sage') }}
</div>
