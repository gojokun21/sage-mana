{{--
  Coupon UI compactă pentru summary (mockup row): aplicat = badge cu cod + remove;
  neaplicat = input + buton Aplică inline. Wrapper [data-coupon-shell] păstrat.

  Vars:
    $has_coupon      bool
    $applied_coupon  string

  Mesajele AJAX vin în #mn-coupon-message (singleton pe pagină).
--}}

<div class="cart-coupon coupon-shell" data-coupon-shell>
  @if ($has_coupon)
    <div class="cart-coupon__applied applied-coupon-box" role="status">
      <div class="cart-coupon__applied-inner">
        <span class="cart-coupon__icon" aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <path d="M20.59 13.41 12 4.82V2H3v9h2.82L14.4 19.59a2 2 0 0 0 2.83 0l3.36-3.35a2 2 0 0 0 0-2.83Z"
                  stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <circle cx="7" cy="7" r="1.4" fill="currentColor"/>
          </svg>
        </span>

        <span class="cart-coupon__code coupon-code-badge">{{ esc_html(strtoupper($applied_coupon)) }}</span>

        <button type="button"
                class="cart-coupon__remove remove-coupon-btn"
                data-coupon="{{ esc_attr($applied_coupon) }}"
                aria-label="{{ esc_attr__('Șterge cuponul', 'sage') }}"
                title="{{ esc_attr__('Șterge cuponul', 'sage') }}">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M18 6 6 18M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>
  @else
    <form class="cart-coupon__form mn-coupon-form" id="mn-ajax-coupon-form" novalidate>
      <input type="text"
             name="coupon_code"
             id="mn_coupon_code"
             class="cart-coupon__input input-text"
             autocomplete="off"
             spellcheck="false"
             placeholder="{{ esc_attr__('Cod cupon', 'sage') }}" />
      <button type="submit" class="cart-coupon__btn button" id="mn-apply-coupon-btn">
        <span class="btn-text">{{ __('Aplică', 'sage') }}</span>
        <span class="btn-spinner" aria-hidden="true"></span>
      </button>
    </form>
  @endif
</div>

<div id="mn-coupon-message" class="mn-coupon-message" aria-live="polite" role="status"></div>
