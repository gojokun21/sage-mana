{{--
  Vars:
    $has_coupon      bool
    $applied_coupon  string

  Swap target: [data-coupon-shell]. cart.js replaces innerHTML on
  apply/remove response without reloading the page.
--}}

<div class="coupon-shell" data-coupon-shell>
  @if ($has_coupon)
    <div class="applied-coupon-box" role="status">
      <div class="applied-coupon-item">
        <span class="applied-coupon-icon" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M20.59 13.41 12 4.82V2H3v9h2.82L14.4 19.59a2 2 0 0 0 2.83 0l3.36-3.35a2 2 0 0 0 0-2.83Z"
                  stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <circle cx="7" cy="7" r="1.4" fill="currentColor"/>
          </svg>
        </span>

        <div class="applied-coupon-info">
          <span class="applied-coupon-label">{{ __('Cupon activ', 'sage') }}</span>
          <span class="coupon-code-badge">{{ esc_html(strtoupper($applied_coupon)) }}</span>
        </div>

        <button type="button"
                class="remove-coupon-btn"
                data-coupon="{{ esc_attr($applied_coupon) }}"
                aria-label="{{ esc_attr__('Șterge cuponul', 'sage') }}"
                title="{{ esc_attr__('Șterge cuponul', 'sage') }}">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M18 6 6 18M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>
  @else
    <form class="mn-coupon-form" id="mn-ajax-coupon-form" novalidate>
      <div class="coupon-input-wrapper">
        <input type="text"
               name="coupon_code"
               id="mn_coupon_code"
               class="input-text"
               autocomplete="off"
               spellcheck="false"
               placeholder="{{ esc_attr__('Introduceți codul', 'sage') }}" />
        <button type="submit" class="button" id="mn-apply-coupon-btn">
          <span class="btn-text">{{ __('Aplică', 'sage') }}</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  @endif
</div>

<div id="mn-coupon-message" class="mn-coupon-message" aria-live="polite" role="status"></div>
