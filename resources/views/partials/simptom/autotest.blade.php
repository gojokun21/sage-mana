{{--
  Autotest — 4 întrebări cu 3 opțiuni (Da / Uneori / Nu) + bloc rezultat.
  Selectarea opțiunilor e interactivă (resources/js/simptom.js): un singur
  răspuns activ per întrebare. `data-default` marchează starea inițială din mockup.
--}}
@php
  $eyebrow = \App\simptom_field('autotest_eyebrow', __('Verificare rapidă', 'sage'));
  $titlu = \App\simptom_field('autotest_titlu', __('Răspunde la 4 întrebări, <em>vezi ce ți se potrivește.</em>', 'sage'));
  $intrebari = \App\simptom_field('autotest_intrebari', [
    ['q' => __('1. Ai pofte de dulce sau pâine la ore predictibile (11 AM, 4 PM, seara)?', 'sage'), 'default' => 0],
    ['q' => __('2. Te simți obosit(ă) după mese cu paste, orez sau pâine?', 'sage'), 'default' => 0],
    ['q' => __('3. Ai acumulat încet în talie în ultimii 2–3 ani, chiar dacă nu mănânci mai mult?', 'sage'), 'default' => 1],
    ['q' => __('4. Te trezești dimineața cu gură amară sau halenă?', 'sage'), 'default' => 1],
  ]);
  $rezultat_strong = \App\simptom_field('autotest_rezultat_strong', __('Răspunsurile sugerează că sistemul ficat-microbiom-glicemie e descalibrat.', 'sage'));
  $rezultat_text = \App\simptom_field('autotest_rezultat_text', __('Schimbările alimentare (proteine la fiecare masă, mai puțini carbohidrați rafinați) plus sprijin cu silimarină și probiotice ajută în 8–12 săptămâni.', 'sage'));
  $optiuni = [__('Da', 'sage'), __('Uneori', 'sage'), __('Nu', 'sage')];
@endphp

<section class="autotest">
  <div class="auto-head">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
  </div>

  <div class="auto-grid">
    @foreach ($intrebari as $intrebare)
      @php $default = (int) ($intrebare['default'] ?? 0); @endphp
      <div class="auto-q">
        <p class="q">{{ $intrebare['q'] }}</p>
        <div class="auto-opts" role="radiogroup">
          @foreach ($optiuni as $i => $optiune)
            <div
              class="auto-opt {{ $i === $default ? 'selected' : '' }}"
              role="radio"
              tabindex="0"
              aria-checked="{{ $i === $default ? 'true' : 'false' }}"
            >{{ $optiune }}</div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>

  <div class="auto-result">
    <div class="body">
      <p class="lbl">{{ __('Rezultat pe baza răspunsurilor', 'sage') }}</p>
      <p class="txt"><strong>{{ $rezultat_strong }}</strong> {{ $rezultat_text }}</p>
    </div>
    <a class="btn-q" href="{{ esc_url(home_url('/test/')) }}">{{ __('Vezi quiz-ul complet (60 sec)', 'sage') }}
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>
