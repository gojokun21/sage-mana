{{--
  Mega-meniu „După simptom" — după mockup `preferinte/Mega-menu - Dupa simptom.html`.
  Randat în interiorul `.mega-menu-wrapper` din header (deschidere pe hover via CSS).
  Structura (5 coloane grupate + card featured + bară WhatsApp) e curatată manual;
  linkurile se rezolvă la paginile de simptom după slug (seedate cu natura:simptom-seed).
--}}
@php
  $hub = home_url('/dupa-simptom/');

  // Rezolvă slug-ul de pagină la permalink; fallback pe hub dacă lipsește.
  $ms_url = static function ($slug) use ($hub) {
      if (! $slug) {
          return $hub;
      }
      $p = get_page_by_path($slug, OBJECT, 'page');
      return $p ? get_permalink($p) : $hub;
  };

  // Iconițe Font Awesome 6 Free (solid), inline ca SVG cu clasă `.leaf`.
  $fa = [
      'wind' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M288 32c0 17.7 14.3 32 32 32l32 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128c-17.7 0-32 14.3-32 32s14.3 32 32 32l320 0c53 0 96-43 96-96s-43-96-96-96L320 0c-17.7 0-32 14.3-32 32zm64 352c0 17.7 14.3 32 32 32l32 0c53 0 96-43 96-96s-43-96-96-96L32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-32 0c-17.7 0-32 14.3-32 32zM128 512l32 0c53 0 96-43 96-96s-43-96-96-96L32 320c-17.7 0-32 14.3-32 32s14.3 32 32 32l128 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-32 0c-17.7 0-32 14.3-32 32s14.3 32 32 32z"/></svg>',
      'droplet' => '<svg class="leaf" viewBox="0 0 384 512" fill="currentColor" aria-hidden="true"><path d="M192 512C86 512 0 426 0 320C0 228.8 130.2 57.7 166.6 11.7C172.6 4.2 181.5 0 191.1 0l1.8 0c9.6 0 18.5 4.2 24.5 11.7C253.8 57.7 384 228.8 384 320c0 106-86 192-192 192zM96 336c0-8.8-7.2-16-16-16s-16 7.2-16 16c0 61.9 50.1 112 112 112c8.8 0 16-7.2 16-16s-7.2-16-16-16c-44.2 0-80-35.8-80-80z"/></svg>',
      'candy' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M348.8 131.5c3.7-2.3 7.9-3.5 12.2-3.5c12.7 0 23 10.3 23 23l0 5.6c0 9.9-5.1 19.1-13.5 24.3L30.1 393.7C.1 412.5-9 451.9 9.7 481.9s58.2 39.1 88.2 20.4L438.4 289.5c45.8-28.6 73.6-78.8 73.6-132.8l0-5.6C512 67.6 444.4 0 361 0c-28.3 0-56 8-80.1 23L254.1 39.7c-30 18.7-39.1 58.2-20.4 88.2s58.2 39.1 88.2 20.4l26.8-16.8zM298.4 49.8c9.2-5.7 19.1-10.1 29.4-13.1L348 97.5c-5.7 1.4-11.2 3.7-16.3 6.8l-12.6 7.9L298.4 49.8zm88.5 52.7l46.2-46.2c8.5 6.5 16.1 14.1 22.6 22.6l-46.2 46.2c-5.1-9.6-13-17.5-22.6-22.6zm28.9 59.3l61.6 20.5c-2.2 10.5-5.8 20.7-10.5 30.2l-62-20.7c6.2-8.8 10.1-19.1 11-30.1zm-86.1 82.5l60.4 37.7-30.2 18.9-60.4-37.7 30.2-18.9zm-107.2 67l60.4 37.7-30.2 18.9-60.4-37.7 30.2-18.9zM119.3 375.7l60.4 37.7-30.2 18.9L89.1 394.6l30.2-18.9z"/></svg>',
      'virus' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 11.5c0 49.9-60.3 74.9-95.6 39.6L120.2 75C107.7 62.5 87.5 62.5 75 75s-12.5 32.8 0 45.3l8.2 8.2C118.4 163.7 93.4 224 43.5 224L32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l11.5 0c49.9 0 74.9 60.3 39.6 95.6L75 391.8c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l8.2-8.2c35.3-35.3 95.6-10.3 95.6 39.6l0 11.5c0 17.7 14.3 32 32 32s32-14.3 32-32l0-11.5c0-49.9 60.3-74.9 95.6-39.6l8.2 8.2c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-8.2-8.2c-35.3-35.3-10.3-95.6 39.6-95.6l11.5 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-11.5 0c-49.9 0-74.9-60.3-39.6-95.6l8.2-8.2c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-8.2 8.2C348.3 118.4 288 93.4 288 43.5L288 32zM176 224a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm128 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>',
      'bolt' => '<svg class="leaf" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288l111.5 0L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7l-111.5 0L349.4 44.6z"/></svg>',
      'bed' => '<svg class="leaf" viewBox="0 0 640 512" fill="currentColor" aria-hidden="true"><path d="M32 32c17.7 0 32 14.3 32 32l0 256 224 0 0-160c0-17.7 14.3-32 32-32l224 0c53 0 96 43 96 96l0 224c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-32-224 0-32 0L64 416l0 32c0 17.7-14.3 32-32 32s-32-14.3-32-32L0 64C0 46.3 14.3 32 32 32zm144 96a80 80 0 1 1 0 160 80 80 0 1 1 0-160z"/></svg>',
      'brain' => '<svg class="leaf" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M184 0c30.9 0 56 25.1 56 56l0 400c0 30.9-25.1 56-56 56c-28.9 0-52.7-21.9-55.7-50.1c-5.2 1.4-10.7 2.1-16.3 2.1c-35.3 0-64-28.7-64-64c0-7.4 1.3-14.6 3.6-21.2C21.4 367.4 0 338.2 0 304c0-31.9 18.7-59.5 45.8-72.3C37.1 220.8 32 207 32 192c0-30.7 21.6-56.3 50.4-62.6C80.8 123.9 80 118 80 112c0-29.9 20.6-55.1 48.3-62.1C131.3 21.9 155.1 0 184 0zM328 0c28.9 0 52.6 21.9 55.7 49.9c27.8 7 48.3 32.1 48.3 62.1c0 6-.8 11.9-2.4 17.4c28.8 6.2 50.4 31.9 50.4 62.6c0 15-5.1 28.8-13.8 39.7C493.3 244.5 512 272.1 512 304c0 34.2-21.4 63.4-51.6 74.8c2.3 6.6 3.6 13.8 3.6 21.2c0 35.3-28.7 64-64 64c-5.6 0-11.1-.7-16.3-2.1c-3 28.2-26.8 50.1-55.7 50.1c-30.9 0-56-25.1-56-56l0-400c0-30.9 25.1-56 56-56z"/></svg>',
      'spa' => '<svg class="leaf" viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M183.1 235.3c33.7 20.7 62.9 48.1 85.8 80.5c7 9.9 13.4 20.3 19.1 31c5.7-10.8 12.1-21.1 19.1-31c22.9-32.4 52.1-59.8 85.8-80.5C437.6 207.8 490.1 192 546 192l9.9 0c11.1 0 20.1 9 20.1 20.1C576 360.1 456.1 480 308.1 480L288 480l-20.1 0C119.9 480 0 360.1 0 212.1C0 201 9 192 20.1 192l9.9 0c55.9 0 108.4 15.8 153.1 43.3zM301.5 37.6c15.7 16.9 61.1 71.8 84.4 164.6c-38 21.6-71.4 50.8-97.9 85.6c-26.5-34.8-59.9-63.9-97.9-85.6c23.2-92.8 68.6-147.7 84.4-164.6C278 33.9 282.9 32 288 32s10 1.9 13.5 5.6z"/></svg>',
      'bone' => '<svg class="leaf" viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M153.7 144.8c6.9 16.3 20.6 31.2 38.3 31.2l192 0c17.7 0 31.4-14.9 38.3-31.2C434.4 116.1 462.9 96 496 96c44.2 0 80 35.8 80 80c0 30.4-17 56.9-42 70.4c-3.6 1.9-6 5.5-6 9.6s2.4 7.7 6 9.6c25 13.5 42 40 42 70.4c0 44.2-35.8 80-80 80c-33.1 0-61.6-20.1-73.7-48.8C415.4 350.9 401.7 336 384 336l-192 0c-17.7 0-31.4 14.9-38.3 31.2C141.6 395.9 113.1 416 80 416c-44.2 0-80-35.8-80-80c0-30.4 17-56.9 42-70.4c3.6-1.9 6-5.5 6-9.6s-2.4-7.7-6-9.6C17 232.9 0 206.4 0 176c0-44.2 35.8-80 80-80c33.1 0 61.6 20.1 73.7 48.8z"/></svg>',
      'dumbbell' => '<svg class="leaf" viewBox="0 0 640 512" fill="currentColor" aria-hidden="true"><path d="M96 64c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 160 0 64 0 160c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-64-32 0c-17.7 0-32-14.3-32-32l0-64c-17.7 0-32-14.3-32-32s14.3-32 32-32l0-64c0-17.7 14.3-32 32-32l32 0 0-64zm448 0l0 64 32 0c17.7 0 32 14.3 32 32l0 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 64c0 17.7-14.3 32-32 32l-32 0 0 64c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-160 0-64 0-160c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32zM416 224l0 64-192 0 0-64 192 0z"/></svg>',
  ];

  $columns = [
      [
          'label' => __('CORP', 'sage'), 'ctx' => __('· digestiv & detox', 'sage'),
          'items' => [
              ['ttl' => __('Balonare și digestie greoaie', 'sage'), 'micro' => __('Microbiom, enzime, ficat, în această ordine', 'sage'), 'slug' => 'balonare', 'icon' => $fa['wind']],
              ['ttl' => __('Cură detox & curățare', 'sage'), 'micro' => __('După sărbători, antibiotice sau alcool', 'sage'), 'slug' => 'cura-detox', 'icon' => $fa['droplet']],
              ['ttl' => __('Sindrom metabolic & pofte la dulce', 'sage'), 'micro' => __('Ficat, microbiom, glicemie în echilibru', 'sage'), 'slug' => null, 'icon' => $fa['candy']],
          ],
      ],
      [
          'label' => __('CORP', 'sage'), 'ctx' => __('· imunitate & rezistență', 'sage'),
          'items' => [
              ['ttl' => __('Răceli frecvente & imunitate slăbită', 'sage'), 'micro' => __('Vit C, D3, Zinc, timochinonă', 'sage'), 'slug' => 'raceli-frecvente', 'icon' => $fa['virus']],
              ['ttl' => __('Oboseală cronică & lipsă de energie', 'sage'), 'micro' => __('B-complex, Q10, magneziu, motorul celular', 'sage'), 'slug' => 'oboseala-cronica', 'icon' => $fa['bolt']],
          ],
      ],
      [
          'label' => __('MINTE', 'sage'), 'ctx' => __('· cognitiv & emoțional', 'sage'),
          'items' => [
              ['ttl' => __('Stres, anxietate & somn agitat', 'sage'), 'micro' => __('Magneziu bisglicinat, B6, adaptogene', 'sage'), 'slug' => 'stres-si-somn', 'icon' => $fa['bed']],
              ['ttl' => __('Lipsă de concentrare & ceață mentală', 'sage'), 'micro' => __('B6, B12, omega-3, Lion\'s Mane', 'sage'), 'slug' => 'ceata-mentala', 'icon' => $fa['brain']],
          ],
      ],
      [
          'label' => __('ESTETIC & STRUCTURĂ', 'sage'), 'ctx' => '',
          'items' => [
              ['ttl' => __('Păr care cade, unghii fragile, ten stins', 'sage'), 'micro' => __('Biotină, zinc, seleniu, colagen', 'sage'), 'slug' => 'par-si-ten', 'icon' => $fa['spa']],
              ['ttl' => __('Articulații care scârțâie, mobilitate redusă', 'sage'), 'micro' => __('Colagen, vitamina D, mișcare', 'sage'), 'slug' => 'articulatii', 'icon' => $fa['bone']],
          ],
      ],
  ];
@endphp

<div class="ms-mega">
  <div class="ms-grid">
    @foreach ($columns as $col)
      <div class="ms-col">
        <h4>
          <span class="swatch"></span>{{ $col['label'] }}
          @if ($col['ctx'])
            <span class="ctx">{{ $col['ctx'] }}</span>
          @endif
        </h4>
        <ul>
          @foreach ($col['items'] as $it)
            <li>
              <a class="symptom" href="{{ esc_url($ms_url($it['slug'])) }}">
                {!! $it['icon'] !!}
                <span class="body">
                  <span class="ttl">{{ $it['ttl'] }}</span>
                  <span class="micro">{{ $it['micro'] }}</span>
                </span>
                <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    @endforeach

    {{-- Col 5: Sport & recuperare + card featured --}}
    <div class="ms-col">
      <h4><span class="swatch"></span>{{ __('SPORT & RECUPERARE', 'sage') }}</h4>
      <ul>
        <li>
          <a class="symptom" href="{{ esc_url($ms_url('recuperare-antrenament')) }}">
            {!! $fa['dumbbell'] !!}
            <span class="body">
              <span class="ttl">{{ __('Recuperare lentă după antrenament', 'sage') }}</span>
              <span class="micro">{{ __('Proteină, creatină, electroliți', 'sage') }}</span>
            </span>
            <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
          </a>
        </li>
      </ul>

      <a class="ms-featured" href="{{ esc_url($hub) }}">
        <span class="photo">
          <svg class="glyph" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M6.5 6.5h11M6.5 17.5h11M9 6.5v11M15 6.5v11M4 9h2.5M4 15h2.5M17.5 9H20M17.5 15H20"/></svg>
        </span>
        <span class="lbl">{{ __('Ghid de performanță sportivă', 'sage') }}</span>
        <span class="lk">
          <span>{{ __('Vezi tot ghidul', 'sage') }}</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
        </span>
      </a>
    </div>
  </div>

  {{-- Bară de jos — CTA WhatsApp --}}
  <div class="ms-bottom">
    <div class="left">
      <span class="dot">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
      </span>
      <span class="txt">
        <strong>{{ __('Simptomele tale nu sunt pe listă? Sau ai mai multe deodată?', 'sage') }}</strong>
        <span>{{ __('Specialiștii noștri răspund de luni–sâmbătă, 9–19. Discreție garantată.', 'sage') }}</span>
      </span>
    </div>
    <a class="btn-wa" href="https://wa.me/40749492794" target="_blank" rel="noopener">
      <span class="wa-ic">
        <svg width="16" height="16" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
      </span>
      {{ __('Vorbește cu un specialist pe WhatsApp', 'sage') }}
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
    </a>
  </div>
</div>
