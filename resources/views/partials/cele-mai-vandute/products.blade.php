{{-- Cele mai vândute — grila celor 5 produse (sursă: $bestsellers). --}}
<section class="products">
  <div class="products-inner">
    <div class="products-head">
      <h2>{{ __('5 produse,', 'sage') }} <em>{{ __('5 motive diferite.', 'sage') }}</em></h2>
      <div class="meta">{{ __('Ordonate după reorder rate', 'sage') }}</div>
    </div>
    <div class="grid5">
      @foreach ($bestsellers as $p)
        <div class="pcard">
          <div class="img {{ $p['theme'] }}">
            <span class="rank">★ <span class="num">{{ $p['rank'] }}</span></span>
            @if ($p['vegan'])
              <span class="vegan-tag">{{ __('Vegan', 'sage') }}</span>
            @endif
            <div class="ph{{ $p['trio'] ? ' trio' : '' }}">
              @foreach ($p['bottles'] as $bottle)
                <div class="bot {{ $bottle['style'] }}">
                  <div class="cap"></div>
                  <div class="body">
                    <div class="lbl">{!! implode('<br>', array_map('esc_html', explode('|', $bottle['label']))) !!}</div>
                  </div>
                </div>
              @endforeach
            </div>
            <span class="cat">{{ $p['cat'] }}</span>
          </div>
          <div class="body">
            <h3>{{ $p['title'] }} <em>{{ $p['title_em'] }}</em></h3>
            <p class="sub">{{ $p['sub'] }}</p>
            <ul class="bens">
              @foreach ($p['benefits'] as $benefit)
                <li>{{ $benefit }}</li>
              @endforeach
            </ul>
            <div class="why-bs">
              <strong>{{ __('De ce e best-seller', 'sage') }}</strong>
              {{ $p['why'] }}
            </div>
            <div class="price-row">
              <div class="price-stack">
                <span class="price">{{ $p['price'] }}</span>
                <span class="cpd"><strong>{{ $p['cpd_strong'] }}</strong> · {{ $p['cpd_rest'] }}</span>
              </div>
              <a class="cta" href="{{ esc_url($p['link']) }}">{{ $p['cta_label'] }}
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
