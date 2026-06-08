{{-- Promoții — „Pick-ul lunii" = oferta cu cea mai mare reducere (date reale WC). --}}
@if (! empty($pick))
  <section class="pick">
    <div class="pick-inner">
      <a class="pick-art" href="{{ esc_url($pick['link']) }}" aria-label="{{ esc_attr($pick['name']) }}">
        <img src="{{ esc_url($pick['img']) }}" alt="{{ esc_attr($pick['name']) }}" loading="lazy" decoding="async">
      </a>
      <div class="pick-content">
        <div class="pick-eyebrow">{{ __('Pick-ul lunii', 'sage') }}</div>
        <h2>{{ $pick['name'] }}</h2>
        @if ($pick['short'])
          <p class="desc">{{ $pick['short'] }}</p>
        @endif
        <div class="pick-price">
          <span class="old">{!! wc_price($pick['reg']) !!}</span>
          <span class="new">{!! wc_price($pick['sale']) !!}</span>
          <span class="badge-discount">−{{ $pick['disc'] }}%</span>
        </div>
        <div class="pick-save">{{ sprintf(__('Economisești %s față de prețul întreg.', 'sage'), wp_strip_all_tags(wc_price($pick['save']))) }}</div>

        @if ($pick['can_ajax'])
          <a href="{{ esc_url($pick['add_url']) }}"
             class="pick-cta mn-atc-btn product_type_{{ esc_attr($pick['type']) }} add_to_cart_button ajax_add_to_cart"
             data-product_id="{{ esc_attr($pick['id']) }}"
             data-product_sku="{{ esc_attr($pick['sku']) }}"
             data-quantity="1"
             data-product_name="{{ esc_attr($pick['name']) }}"
             data-product_price="{{ esc_attr((string) wc_format_decimal($pick['sale'], wc_get_price_decimals())) }}"
             data-product_url="{{ esc_url($pick['link']) }}"
             rel="nofollow">
            {{ __('Adaugă în coș', 'sage') }}
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg>
          </a>
        @else
          <a class="pick-cta" href="{{ esc_url($pick['link']) }}">
            {{ __('Vezi produsul', 'sage') }}
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          </a>
        @endif

        <div class="pick-stock"><span class="dot-ok" aria-hidden="true"></span>{{ __('Stoc disponibil · livrare 24–48h', 'sage') }}</div>
      </div>
    </div>
  </section>
@endif
