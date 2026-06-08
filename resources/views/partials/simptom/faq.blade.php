{{--
  FAQ + Quiz — grid 2 coloane: stânga acordeon FAQ (<details> → animat de
  resources/js/faq-accordion.js, lazy-loaded când există `.faq .faq-item`),
  dreapta card sticky „Test de 60 secunde". Conținut static în $faqs.
--}}
@php
  $eyebrow = \App\simptom_field('faq_eyebrow', __('Despre sindrom metabolic', 'sage'));
  $titlu = \App\simptom_field('faq_titlu', __('Răspunsuri <em>scurte.</em>', 'sage'));
  $faqs = \App\simptom_field('faq_items', [
    [
      'q' => __('Cât timp durează până scad poftele de dulce?', 'sage'),
      'a' => __('Primele schimbări apar în <strong>2–3 săptămâni</strong>. Reechilibrarea glicemiei stabilă cere 6–12 săptămâni de mese consistente și somn bun. Suplimentele ajută, dar nu înlocuiesc schimbările alimentare.', 'sage'),
    ],
    [
      'q' => __('Pot să iau Black Seed Elixir și probiotice în același timp?', 'sage'),
      'a' => __('Da, acționează pe căi diferite și sunt complementare. Black Seed ajută echilibrul metabolic, probioticele refac microbiomul.', 'sage'),
    ],
    [
      'q' => __('Am diabet de tip 2, pot lua suplimentele astea?', 'sage'),
      'a' => __('Cere sfatul medicului înainte. Unele plante pot influența glicemia și pot interacționa cu medicația anti-diabetică. Nu înlocuiți niciodată tratamentul prescris.', 'sage'),
    ],
    [
      'q' => __('Ce mănânc dimineața ca să nu am pofte la 11?', 'sage'),
      'a' => __('Ouă + avocado + legume. Sau iaurt natur cu nuci și fructe de pădure. Evită cereale dulci, croissant, suc de portocale, toate acestea urcă rapid glicemia.', 'sage'),
    ],
  ]);
@endphp

<section class="end">
  <div class="end-grid">
    <div class="end-faq">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
      <div class="faq faq-list">
        @foreach ($faqs as $i => $faq)
          <details class="faq-item" {{ $i === 0 ? 'open' : '' }}>
            <summary class="faq-q">
              <h4>{{ $faq['q'] }}</h4>
              <span class="toggle" aria-hidden="true">
                <svg class="ico-plus" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                <svg class="ico-minus" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/></svg>
              </span>
            </summary>
            <div class="faq-a">{!! $faq['a'] !!}</div>
          </details>
        @endforeach
      </div>
    </div>

    <aside class="end-quiz">
      <span class="eyebrow-gold">{{ __('Test de 60 secunde', 'sage') }}</span>
      <p>{{ __('Nu știi de unde să începi? Fă testul nostru și îți spunem', 'sage') }} <em>{{ __('ce protocol ți se potrivește.', 'sage') }}</em></p>
      <a class="btn-terra" href="{{ esc_url(home_url('/test/')) }}">{{ __('Începe testul', 'sage') }}
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
      <div class="small">{{ __('Folosit de 8.354 oameni în ultima lună.', 'sage') }}</div>
    </aside>
  </div>
</section>
