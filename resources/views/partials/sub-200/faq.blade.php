{{-- Sub 200 lei — FAQ din ACF (native <details>, primul deschis). --}}
@php
  $titlu = \App\sub200_field('faq_titlu', __('Întrebări <em>frecvente.</em>', 'sage'));
  $items = \App\sub200_field('faq_items', [
    ['q' => __('Cum aleg între produse?', 'sage'), 'a' => __('Cel mai rapid: <strong>fă testul de 60 secunde</strong> — îți spunem onest care ți se potrivește. Sau, dacă știi clar ce problemă vrei să rezolvi (ficat, intestin, imunitate, focus), citește beneficiile fiecărui produs și compară costul pe zi din tabel.', 'sage')],
    ['q' => __('Cura înseamnă o cutie sau mai multe?', 'sage'), 'a' => __('Depinde de produs. Numărul de zile al curei (durata) e afișat pe fiecare card și în tabel — de la cure scurte de 30–50 zile până la cure lungi de 120 zile. <strong>Prețul afișat e prețul întregii cure</strong>, nu al unei singure cutii.', 'sage')],
    ['q' => __('Pot combina 2 produse sub 200 lei?', 'sage'), 'a' => __('Da, dar verifică întâi pachetele — sunt mai economice. <strong>Microflora+ + D-Tox Ficat individual</strong> = 159 + 139 = 298 lei. <strong>Pachet Confort Digestiv</strong> (aceleași două produse) = 283 lei. Diferența de 15 lei vine din pachet, plus livrare consolidată.', 'sage')],
    ['q' => __('Când văd rezultate?', 'sage'), 'a' => __('Variabil: <strong>2–4 săptămâni</strong> pentru digestie, energie și focus. <strong>4–6 săptămâni</strong> pentru ficat (silimarină) și imunitate. <strong>6–12 săptămâni</strong> pentru piele, păr, unghii și articulații (peptide colagen). Cure lungi (120 zile) sunt formulate pentru menținere și prevenție, nu doar pentru efect rapid.', 'sage')],
  ]);
@endphp
<section class="faq">
  <div class="faq-inner">
    <h2>{!! \App\sub200_kses($titlu) !!}</h2>
    <div class="faq-list">
      @foreach ($items as $i => $item)
        <details class="faq-item" @if ($i === 0) open @endif>
          <summary class="faq-q">{{ $item['q'] ?? '' }}<span class="toggle" aria-hidden="true">+</span></summary>
          <div class="faq-a"><p>{!! \App\sub200_kses($item['a'] ?? '') !!}</p></div>
        </details>
      @endforeach
    </div>
  </div>
</section>
