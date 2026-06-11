{{-- Testimoniale text — 3 carduri. Conținut din ACF (grup Home) → fallback seed. --}}
@php
  $testi_cards = \App\home_field('testi_cards') ?: [];
  $star = 'm12 2 3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z';
@endphp
<section class="testi">
  <div class="testi-head">
    <div class="eyebrow" style="margin-bottom:14px">{{ \App\home_field('testi_eyebrow') }}</div>
    <h2>{{ \App\home_field('testi_titlu') }} <em>{{ \App\home_field('testi_titlu_em') }}</em></h2>
  </div>

  <div class="testi-grid">
    @foreach ($testi_cards as $card)
      @php
        $rating = max(0, min(5, (int) ($card['rating'] ?? 5)));
        $nume = $card['nume'] ?? '';
        $initial = $nume !== '' ? mb_strtoupper(mb_substr($nume, 0, 1)) : '·';
      @endphp
      <div class="testi-card">
        <div class="stars-row">
          @for ($s = 1; $s <= 5; $s++)
            @if ($s <= $rating)
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="{{ $star }}"/></svg>
            @else
              <svg class="empty" viewBox="0 0 24 24"><path d="{{ $star }}"/></svg>
            @endif
          @endfor
        </div>
        <blockquote>{{ $card['quote'] ?? '' }}</blockquote>
        <div class="who">
          <div class="avatar">{{ $initial }}</div>
          <div>
            <div class="name">{{ $nume }}</div>
            <div class="role">{{ $card['rol'] ?? '' }}</div>
          </div>
        </div>
        <div class="product-tag">
          <span>{{ $card['produs'] ?? '' }}</span>
          @if (! empty($card['verificat']))
            <span class="vchip">
              <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
              {{ $card['verificat'] }}
            </span>
          @endif
        </div>
      </div>
    @endforeach
  </div>
</section>
