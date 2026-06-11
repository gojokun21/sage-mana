{{--
  Cele 4 grupe de simptome (index). Conținut din ACF-ul hub-ului (grup
  `group_dupa_simptom_hub`, vezi app/acf-dupa-simptom.php), cu fallback pe
  array-ul static din database/seeds/dupa-simptom-grupe.php — deci pagina arată
  identic și înainte de seed. Fiecare card linkează la pagina lui de detaliu
  (/dupa-simptom/<slug>/) când e setată; altfel href="#".

  Editare conținut: din admin pe pagina hub (ACF), NU aici.
--}}
@php
  $groups = \App\dupa_simptom_grupe();
  $grupe_footer = \App\dupa_simptom_footer();
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
            'link' => $card['link'] ?? '#',
          ])
        @endforeach
      </div>

      @if ($loop->last && $grupe_footer !== '')
        <p class="grp-foot-line">{{ $grupe_footer }} <a href="#">{{ __('Vezi indexul complet (32 simptome) →', 'sage') }}</a></p>
      @endif
    </div>
  </section>
@endforeach

<div class="symptom-noresult" data-symptom-noresult>
  {{ __('Niciun simptom nu se potrivește căutării', 'sage') }} „<b data-symptom-query></b>". {{ __('Scrie-ne pe WhatsApp și te ajutăm.', 'sage') }}
</div>
