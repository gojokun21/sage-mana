{{-- Obiectiv — recenzii (din ACF, editabile). --}}
@php
  $eyebrow = \App\simptom_field('reviews_eyebrow', __('Recenzii', 'sage'));
  $titlu = \App\simptom_field('reviews_titlu', __('Ce spun cei care <em>folosesc</em>', 'sage'));
  $note = \App\simptom_field('reviews_note', __('Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.', 'sage'));
  $items = \App\simptom_field('reviews_items', [
    ['rating' => 5, 'quote' => __('Pentru a putea ține pasul cu nepoțelul meu, am nevoie constant de energie și suplimente de calitate. Așa că mi-am făcut stocul cu produse Vivens Genetica.', 'sage'), 'by' => __('Maria T.', 'sage')],
    ['rating' => 4, 'quote' => __('După 3 săptămâni am observat că nu mai am acea cădere de la 14:00. Cafeaua a rămas un ritual, nu o necesitate.', 'sage'), 'by' => __('Andrei C.', 'sage')],
    ['rating' => 5, 'quote' => __('Am încercat multe multivitamine. Pachetul Energie e primul cu rezultate vizibile în sub o lună.', 'sage'), 'by' => __('Cristina M.', 'sage')],
  ]);
@endphp

<section class="reviews">
  <div class="reviews-inner">
    <div class="rev-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
    </div>
    <div class="rev-grid">
      @foreach ($items as $rev)
        @php $rating = max(0, min(5, (int) ($rev['rating'] ?? 5))); @endphp
        <div class="rev-card">
          <div class="stars" aria-label="{{ sprintf(__('%d din 5 stele', 'sage'), $rating) }}">
            @for ($s = 1; $s <= 5; $s++)
              <svg viewBox="0 0 24 24" fill="currentColor" @if ($s > $rating) opacity=".4" @endif aria-hidden="true"><path d="m12 2 3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/></svg>
            @endfor
          </div>
          <blockquote>„{{ $rev['quote'] ?? '' }}"</blockquote>
          <span class="by">— {{ $rev['by'] ?? '' }}</span>
        </div>
      @endforeach
    </div>
    @if ($note)
      <p class="demo-note">{{ $note }}</p>
    @endif
  </div>
</section>
