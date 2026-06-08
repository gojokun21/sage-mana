{{-- FAQ pachete — 4 itemi specifici, primul deschis by default. Accordion nativ <details>. --}}

@php
  $faqs = [
    [
      'q' => __('Pot lua mai multe pachete în paralel?', 'sage'),
      'a' => __('Da, dar verifică <strong>suprapunerile pe vitamine și minerale</strong> ca să nu depășești VNR. Pentru combinații sigure, folosește quiz-ul sau scrie-ne pe WhatsApp — îți spunem onest dacă două pachete merg împreună sau dacă unul singur acoperă deja tot.', 'sage'),
    ],
    [
      'q' => __('Cât durează până văd rezultate?', 'sage'),
      'a' => __('Depinde de pachet și de nevoia ta. Reper general: <strong>digestie și energie</strong> 1–3 săptămâni; <strong>piele, păr, articulații</strong> 6–12 săptămâni; <strong>detoxifiere profundă</strong> 8–12 săptămâni. Fiecare pagină de pachet are timpii proprii detaliați.', 'sage'),
    ],
    [
      'q' => __('Pot opri abonamentul oricând?', 'sage'),
      'a' => __('Da, din contul tău, <strong>fără întrebări</strong>, fără apeluri de retenție, fără birocrație. Plus 14 zile garanție pentru produsele deschise — primești banii înapoi dacă nu te conving.', 'sage'),
    ],
    [
      'q' => __('Care e diferența între pachetele de 2 produse și cele de 3 produse?', 'sage'),
      'a' => __('Pachetele de <strong>2 produse</strong> au focus pe o nevoie principală (imunitate, focus, digestie). Pachetele de <strong>3 produse</strong> acoperă 3 axe simultan — ideal pentru momentele biologice complexe (40+, regenerare profundă, vitalitate completă) sau pentru cei care vor un protocol complet într-un singur ritual.', 'sage'),
    ],
  ];
@endphp

<section class="faq" aria-label="{{ esc_attr__('Întrebări frecvente despre pachete', 'sage') }}">
  <div class="faq-inner">
    <div class="faq-head">
      <div class="eyebrow">{{ __('Întrebări frecvente', 'sage') }}</div>
      <h2>
        {{ __('Ce ne întrebați despre', 'sage') }}
        <em>{{ __('pachete.', 'sage') }}</em>
      </h2>
    </div>

    <div class="faq-list">
      @foreach ($faqs as $i => $faq)
        <details class="faq-item" {{ $i === 0 ? 'open' : '' }}>
          <summary class="faq-q">
            <span>{{ $faq['q'] }}</span>
            <span class="toggle" aria-hidden="true">+</span>
          </summary>
          <div class="faq-a"><p>{!! wp_kses_post($faq['a']) !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
