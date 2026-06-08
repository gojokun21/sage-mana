{{-- Pachete sub 400 lei — grila celor 4 pachete (sursă: $packs). --}}
<section class="packs">
  <div class="packs-inner">
    <div class="packs-head">
      <h2>{{ __('4 pachete, fiecare pentru', 'sage') }} <em>{{ __('o temă clară.', 'sage') }}</em></h2>
      <div class="meta">{{ __('Ordonate după preț crescător', 'sage') }}</div>
    </div>
    <div class="pack-grid">
      @foreach ($packs as $pack)
        <a class="pack-card" href="{{ esc_url($pack['link']) }}">
          <div class="pack-art {{ $pack['theme'] }}">
            <span class="tema-tag">{{ $pack['tema_tag'] }}</span>
            <span class="duration">{{ $pack['duration'] }}</span>
            <div class="pair">
              @foreach ($pack['bottles'] as $i => $bottle)
                <div class="bot {{ $bottle['style'] }} b{{ $i + 1 }}">
                  <div class="cap"></div>
                  <div class="body">
                    <div class="lbl">{!! implode('<br>', array_map('esc_html', explode('|', $bottle['label']))) !!}</div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="pack-body">
            <h3>{{ $pack['title'] }}</h3>
            <div class="pack-price-row">
              <span class="price">{{ $pack['price'] }}</span>
              <span class="cpd"><strong>{{ $pack['cpd_strong'] }}</strong> · {{ $pack['cpd_rest'] }}</span>
            </div>
            <div class="pack-meta">
              {{ __('2 suplimente', 'sage') }}
              @if ($pack['vegan'])
                <span class="sep">·</span> <span class="vegan">{{ __('Vegan', 'sage') }}</span>
              @endif
              <span class="sep">·</span> {{ $pack['meta_extra'] }}
            </div>
            <div class="pack-tabs">
              <div class="pack-tab">
                <div class="t-lbl">{{ __('Ce conține', 'sage') }}</div>
                <ul class="t-list">
                  @foreach ($pack['contains'] as $item)
                    <li><span class="pn">{{ $item['name'] }}</span><span class="pp">{{ $item['price'] }}</span></li>
                  @endforeach
                </ul>
              </div>
              <div class="pack-tab for-who">
                <div class="t-lbl">{{ __('Pentru cine', 'sage') }}</div>
                <ul class="t-list">
                  @foreach ($pack['for_who'] as $who)
                    <li>{{ $who }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="pack-foot">
              <span class="save">✓ {{ $pack['save'] }}</span>
              <span class="cta">{{ __('Vezi pachetul', 'sage') }}
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
              </span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
