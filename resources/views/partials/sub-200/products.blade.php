{{-- Sub 200 lei — grila de produse (LIVE din WooCommerce, sursă: $products).
     Doar titlurile secțiunii vin din ACF; cardurile rămân din WooCommerce. --}}
@php
  $products_titlu = \App\sub200_field('products_titlu', __('{count} produse, fiecare <em>cu rol clar.</em>', 'sage'));
  $products_titlu = str_replace('{count}', (string) $product_count, $products_titlu);
  $products_meta = \App\sub200_field('products_meta', __('Ordonat după preț crescător · cură completă inclusă', 'sage'));
  $products_empty = \App\sub200_field('products_empty', __('Momentan nu avem produse sub 200 lei disponibile. Revino curând — catalogul se actualizează.', 'sage'));
@endphp
<section class="products">
  <div class="products-inner">
    <div class="products-head">
      <h2>{!! \App\sub200_kses($products_titlu) !!}</h2>
      <div class="meta">{{ $products_meta }}</div>
    </div>

    @if ($product_count === 0)
      <div class="products-empty">
        {{ $products_empty }}
      </div>
    @else
      <div class="grid6">
        @foreach ($products as $p)
          <div class="pcard">
            <div class="img {{ $p['theme'] }}">
              @if ($p['vegan'] || $p['duration_label'])
                <div class="tags">
                  @if ($p['vegan'])<span class="tg vegan">{{ __('Vegan', 'sage') }}</span>@endif
                  @if ($p['duration_label'])<span class="tg">{{ $p['duration_label'] }}</span>@endif
                </div>
              @endif
              <a href="{{ esc_url($p['link']) }}" aria-label="{{ esc_attr($p['name']) }}">{!! $p['img_html'] !!}</a>
              @if ($p['cat_name'])<span class="cat">{{ $p['cat_name'] }}</span>@endif
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
              <div class="price-row">
                <div class="price-stack">
                  <span class="price">{!! $p['price_html'] !!}</span>
                  @if ($p['cpd'] > 0)
                    <span class="cpd"><strong>{{ $p['cpd_label'] }}</strong> · {{ $p['duration_label'] }}</span>
                  @endif
                </div>
                <a class="cta" href="{{ esc_url($p['link']) }}">{{ __('Vezi produsul', 'sage') }}
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
