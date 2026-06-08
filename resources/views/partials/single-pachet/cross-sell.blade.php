{{-- Single PACHET — cross-sell: alte pachete (produse WC de tip bundle).
     Date REALE: nume, preț, imagine, permalink. --}}
@php
  global $product;

  $others = wc_get_products([
      'type' => 'bundle',
      'status' => 'publish',
      'limit' => 4,
      'exclude' => [$product->get_id()],
      'orderby' => 'rand',
  ]);
@endphp

@if (! empty($others))
  <section class="pachet-cross">
    <div class="cross-inner">
      <div class="cross-head">
        <h2>{{ __('Dacă nu e potrivit pentru tine,', 'sage') }} <em>{{ __('vezi celelalte pachete.', 'sage') }}</em></h2>
      </div>
      <div class="cross-grid">
        @foreach ($others as $other)
          @php
            $img_id = $other->get_image_id();
            $short = wp_strip_all_tags($other->get_short_description());
            if (function_exists('mb_strimwidth') && $short !== '') {
                $short = mb_strimwidth($short, 0, 70, '…', 'UTF-8');
            }
          @endphp
          <a class="cross-card" href="{{ esc_url(get_permalink($other->get_id())) }}">
            <div class="ill">
              @if ($img_id)
                {!! wp_get_attachment_image($img_id, 'woocommerce_thumbnail', false, [
                  'alt' => $other->get_name(),
                  'loading' => 'lazy',
                  'decoding' => 'async',
                ]) !!}
              @endif
            </div>
            <h4>{{ $other->get_name() }}</h4>
            @if ($short)
              <p class="desc">{{ $short }}</p>
            @endif
            <span class="pr">{!! $other->get_price_html() !!}</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endif
