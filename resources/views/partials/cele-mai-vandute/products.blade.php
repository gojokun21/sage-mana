{{-- Cele mai vândute — grila de produse REALE (sursă: $bestsellers din WooCommerce).
     Titlurile secțiunii vin din ACF. --}}
@php
  $products_titlu = \App\bestseller_field('products_titlu', __('{count} produse, <em>{count} motive diferite.</em>', 'sage'));
  $products_titlu = str_replace('{count}', (string) $bs_count, $products_titlu);
  $products_meta = \App\bestseller_field('products_meta', __('Ordonate după reorder rate', 'sage'));
@endphp
<section class="products">
  <div class="products-inner">
    <div class="products-head">
      <h2>{!! \App\bestseller_kses($products_titlu) !!}</h2>
      <div class="meta">{{ $products_meta }}</div>
    </div>

    @if ($bs_count === 0)
      <div class="products-empty">
        {{ __('Alege produsele din ACF (tab „Produse (top)”) ca să apară aici.', 'sage') }}
      </div>
    @else
      <div class="grid5">
        @foreach ($bestsellers as $p)
          <div class="pcard">
            <div class="img has-img">
              <span class="rank">★ <span class="num">{{ $p['rank'] }}</span></span>
              @if ($p['vegan'])
                <span class="vegan-tag">{{ __('Vegan', 'sage') }}</span>
              @endif
              <a href="{{ esc_url($p['link']) }}" aria-label="{{ esc_attr($p['name']) }}">{!! $p['img_html'] !!}</a>
              @if ($p['cat'])<span class="cat">{{ $p['cat'] }}</span>@endif
            </div>
            <div class="body">
              <h3><a href="{{ esc_url($p['link']) }}">{{ $p['name'] }}</a></h3>
              @if ($p['sub'])<p class="sub">{{ $p['sub'] }}</p>@endif
              @if (! empty($p['benefits']))
                <ul class="bens">
                  @foreach ($p['benefits'] as $benefit)
                    <li>{{ $benefit }}</li>
                  @endforeach
                </ul>
              @endif
              @if ($p['why'])
                <div class="why-bs">
                  <strong>{{ __('De ce e best-seller', 'sage') }}</strong>
                  {!! \App\bestseller_kses($p['why']) !!}
                </div>
              @endif
              <div class="price-row">
                <div class="price-stack">
                  <span class="price">{!! $p['price_html'] !!}</span>
                  @if ($p['cpd_label'])
                    <span class="cpd"><strong>{{ $p['cpd_label'] }}</strong>@if ($p['days_label']) · {{ $p['days_label'] }}@endif</span>
                  @endif
                </div>
                <a class="cta" href="{{ esc_url($p['link']) }}">{{ $p['cta_label'] }}
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
