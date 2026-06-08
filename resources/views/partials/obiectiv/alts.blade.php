{{-- Obiectiv — alte opțiuni (2 produse). ACF Post Object + fallback nume/preț. --}}
@php
  $titlu = \App\simptom_field('alts_titlu', __('Alte opțiuni <em>pentru energie</em>', 'sage'));
  $items = \App\simptom_field('alts_items', [
    ['nume' => __('Black Seed Elixir', 'sage'), 'pret' => '184 lei', 'desc' => __('Și pentru imunitate, și pentru echilibru metabolic. Suport antiinflamator în paralel cu Pachetul Energie.', 'sage'), 'cta' => __('Vezi produs', 'sage')],
    ['nume' => __('Pachet Vitalitate', 'sage'), 'pret' => '499 lei', 'desc' => __('Pentru cei care vor abordare mai largă: energie + frumusețe + echilibru. Cură de 90 zile.', 'sage'), 'cta' => __('Vezi pachet', 'sage')],
  ]);
@endphp

<section class="alts" id="alts">
  <div class="alts-inner">
    <h3>{!! wp_kses($titlu, ['em' => []]) !!}</h3>
    <div class="alts-grid">
      @foreach (array_slice($items, 0, 2) as $i => $item)
        @php
          $pid = (int) ($item['produs'] ?? 0);
          $product = $pid ? wc_get_product($pid) : null;
          $name = $product ? $product->get_name() : ($item['nume'] ?? '');
          $url = $product ? get_permalink($product->get_id()) : home_url('/');
          $img = $product ? $product->get_image_id() : 0;
          $price = $product ? $product->get_price_html() : esc_html($item['pret'] ?? '');
          $cta = $item['cta'] ?: __('Vezi produs', 'sage');
        @endphp
        <a class="alt-card {{ $i === 1 ? 'b2' : '' }}" href="{{ esc_url($url) }}">
          <span class="art">
            @if ($img)
              {!! wp_get_attachment_image($img, 'woocommerce_thumbnail', false, ['loading' => 'lazy', 'decoding' => 'async', 'alt' => $name]) !!}
            @else
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true"><ellipse cx="12" cy="9" rx="4" ry="6" fill="currentColor" opacity=".35"/><path d="M10 15 V 22 H 14 V 15" fill="currentColor" opacity=".25"/></svg>
            @endif
          </span>
          <span class="body">
            <h4>{{ $name }}</h4>
            <span class="price">{!! $price !!}</span>
            <p class="desc">{{ $item['desc'] ?? '' }}</p>
            <span class="cta-ghost">{{ $cta }}</span>
          </span>
        </a>
      @endforeach
    </div>
  </div>
</section>
