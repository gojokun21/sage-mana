{{-- Promoții — grid oferte (toate produsele la reducere, fără pick).
     Atributele data-* sunt citite de promotii.js pentru filtrare/sortare client-side. --}}
<section class="offers" id="offers">
  <div class="offers-inner">
    <div class="offers-head">
      <h2>{{ __('Toate', 'sage') }} <em>{{ __('ofertele', 'sage') }}</em></h2>
      <span class="offers-count" data-promo-count>{{ sprintf(_n('%d produs', '%d produse', $offer_count, 'sage'), $offer_count) }}</span>
    </div>

    <div class="grid-offers" data-promo-grid>
      @foreach ($grid as $o)
        <article class="offer-card"
                 data-cat="{{ esc_attr($o['cat_slug']) }}"
                 data-disc="{{ esc_attr($o['disc']) }}"
                 data-price="{{ esc_attr($o['sale']) }}">
          <span class="discount-badge">−{{ $o['disc'] }}%</span>
          <a class="offer-art" href="{{ esc_url($o['link']) }}" aria-label="{{ esc_attr($o['name']) }}">
            <img src="{{ esc_url($o['img']) }}" alt="{{ esc_attr($o['name']) }}" loading="lazy" decoding="async">
          </a>
          <div class="offer-body">
            @if ($o['cat_name'])
              <span class="offer-cat">{{ esc_html($o['cat_name']) }}</span>
            @endif
            <h3><a href="{{ esc_url($o['link']) }}">{{ $o['name'] }}</a></h3>
            @if ($o['short'])
              <p class="desc">{{ $o['short'] }}</p>
            @endif
            <div class="offer-price">
              <span class="old">{!! wc_price($o['reg']) !!}</span>
              <span class="new">{!! wc_price($o['sale']) !!}</span>
            </div>
            <a class="offer-cta" href="{{ esc_url($o['link']) }}">{{ __('Vezi produs', 'sage') }}</a>
          </div>
        </article>
      @endforeach
    </div>

    <div class="offers-empty" data-promo-empty hidden>
      {{ __('Niciun produs nu se potrivește filtrelor selectate.', 'sage') }}
    </div>
  </div>
</section>
