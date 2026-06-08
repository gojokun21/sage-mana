{{-- Retur — FAQ (acordeon nativ <details>). --}}
@php
  $faqs = [
    [
      'q' => __('Pot returna după 14 zile?', 'sage'),
      'a' => __('<strong>Nu</strong> pentru dreptul de retragere standard (OUG 34/2014). Există <strong>o excepție</strong>: dacă produsul are defect de fabricație (sigilare ruptă din fabrică, lot expirat, contaminare), Legea 449/2003 îți dă <strong>garanție 2 ani</strong> pentru defecte de conformitate. Scrie-ne și verificăm caz cu caz.', 'sage'),
      'open' => true,
    ],
    [
      'q' => __('Cine plătește transportul retur?', 'sage'),
      'a' => __('<strong>Consumatorul</strong>, conform OUG 34/2014 art. 13(3). Cost estimat: 10–20 lei prin Sameday/FAN. <strong>EXCEPȚIE</strong>: pentru produse primite deteriorate, expirate sau cu defect de fabricație, transportul îl <strong>suportăm noi</strong> — primești AWB prepaid prin email.', 'sage'),
    ],
    [
      'q' => __('Când primesc banii înapoi?', 'sage'),
      'a' => __('În <strong>maxim 14 zile</strong> de la primirea coletului la noi, pe aceeași metodă de plată folosită la comandă. Card → același card; ramburs → cont bancar (îți cerem IBAN-ul); transfer → cont bancar de origine. Procesarea durează 1–3 zile lucrătoare după rambursare.', 'sage'),
    ],
    [
      'q' => __('Pot returna un pachet dacă am început un singur produs?', 'sage'),
      'a' => __('<strong>Nu integral</strong>, conform regulii produselor desigilate. Dar putem face <strong>credit produs</strong> pentru valoarea produselor rămase sigilate din pachet — scrie-ne pe WhatsApp cu numărul comenzii și o poză a produselor încă închise. Procesăm caz cu caz, în 24–48h.', 'sage'),
    ],
    [
      'q' => __('Pot anula returul dacă m-am răzgândit?', 'sage'),
      'a' => __('<strong>Da</strong>, scrie-ne până când coletul ajunge la noi. Dacă ai trimis deja coletul, îl primim înapoi și îți cerem confirmare — fie continuăm cu rambursarea, fie ți-l retrimitem (transport recuperare suportat de tine).', 'sage'),
    ],
  ];
@endphp
<section class="faq">
  <div class="faq-inner">
    <h2>{{ __('Întrebări', 'sage') }} <em>{{ __('frecvente.', 'sage') }}</em></h2>
    <div class="faq-list">
      @foreach ($faqs as $item)
        <details class="faq-item" @if (! empty($item['open'])) open @endif>
          <summary class="faq-q">{{ $item['q'] }}<span class="toggle" aria-hidden="true">+</span></summary>
          <div class="faq-a">{!! wp_kses($item['a'], ['strong' => []]) !!}</div>
        </details>
      @endforeach
    </div>
  </div>
</section>
