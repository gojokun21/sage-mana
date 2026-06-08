{{--
  Checkout — redesign după mockup `preferinte/Pagina Checkout.html`.

  Structură (.checkout-page scope):
    - breadcrumb + funnel (pas 2/4 „Livrare" activ) + hero
    - .co-grid (2 coloane):
      LEFT .form-stack — 3 blocuri card:
        1. Date livrare  → guest/login tabs + billing/shipping fields + metode livrare
        2. Metodă de plată → gateway-uri WC (styled ca .pay-list) + cupon
        3. Confirmare → terms + Plasează comanda + creează cont
      RIGHT .summary-col — sumar comandă (#order_review AJAX) + reassurance

  Toate hook-urile și selectoarele funcționale WooCommerce sunt păstrate intacte
  (customer_details, payment, order_review, terms, place_order, nonce). Logica din
  app/checkout.php (PJ/PF, sector, fără email, discount card, cascade adresă) și
  resources/js/checkout.js rămân neschimbate — acesta e doar un restyle structural.

  @see https://woocommerce.com/document/template-structure/
  @version 9.4.0
--}}

@php
  defined('ABSPATH') || exit;

  if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
  }
@endphp

<div class="checkout-page">

  <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
    <div class="breadcrumb-inner">
      <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <a href="{{ esc_url(wc_get_cart_url()) }}">{{ __('Coșul tău', 'sage') }}</a>
      <span class="sep" aria-hidden="true">›</span>
      <span class="here">{{ __('Checkout', 'sage') }}</span>
    </div>
  </nav>

  @include('partials.checkout.funnel')

  <section class="co-hero">
    <div class="co-hero-inner">
      <div>
        <h1>{{ __('Checkout.', 'sage') }}</h1>
        <p class="sub">{!! wp_kses(__('Comanda ta în <strong>3 minute</strong>. <em>Fără cont obligatoriu.</em>', 'sage'), ['strong' => [], 'em' => []]) !!}</p>
      </div>
      <span class="secure-line">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        {{ __('Pagină securizată · plată procesată securizat', 'sage') }}
      </span>
    </div>
  </section>

  @php do_action('woocommerce_before_checkout_form', $checkout) @endphp

  <form name="checkout"
        method="post"
        class="checkout woocommerce-checkout checkout-two-columns"
        action="{{ esc_url(wc_get_checkout_url()) }}"
        enctype="multipart/form-data">

    <div class="checkout-grid co-grid">

      {{-- ============ LEFT ============ --}}
      <div class="checkout-left form-stack">

        {{-- BLOC 1 — Date livrare --}}
        <div class="block">
          <div class="head">
            <div class="num">1</div>
            <h2>{{ __('Date', 'sage') }} <em>{{ __('livrare.', 'sage') }}</em></h2>
          </div>

          @if ($checkout->get_checkout_fields())
            @php do_action('woocommerce_checkout_before_customer_details') @endphp

            <div id="customer_details">
              <div class="billing-fields">
                @php do_action('woocommerce_checkout_billing') @endphp
              </div>

              <div class="shipping-fields">
                @php do_action('woocommerce_checkout_shipping') @endphp
              </div>
            </div>

            @php do_action('woocommerce_checkout_after_customer_details') @endphp
          @endif

          @if (WC()->cart->needs_shipping() && WC()->cart->show_shipping())
            <div id="shipping-section" class="checkout-shipping-wrapper">
              <h3 class="shipping_title">{{ __('Metodă de livrare', 'sage') }}</h3>
              <div class="shipping-methods-list">
                @php wc_cart_totals_shipping_html() @endphp
              </div>
            </div>
          @endif
        </div>

        {{-- BLOC 2 — Metodă de plată + cupon --}}
        <div class="block">
          <div class="head">
            <div class="num">2</div>
            <h2>{{ __('Metodă', 'sage') }} <em>{{ __('de plată.', 'sage') }}</em></h2>
          </div>

          <div id="payment-section" class="checkout-payment-wrapper">
            @php woocommerce_checkout_payment() @endphp
          </div>

          @if (wc_coupons_enabled())
            <div class="checkout-coupon coupon-box" data-checkout-coupon>
              <button type="button"
                      class="checkout-coupon__toggle"
                      aria-expanded="false"
                      aria-controls="checkout-coupon-panel">
                <span class="checkout-coupon__toggle-label">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" width="18" height="18">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/>
                    <path d="M9 9v.01"/><path d="M15 15v.01"/><path d="m15 9-6 6"/>
                  </svg>
                  <span>{{ __('Ai un cod de reducere?', 'sage') }}</span>
                </span>
                <svg class="checkout-coupon__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" width="16" height="16">
                  <path d="m6 9 6 6 6-6"/>
                </svg>
              </button>

              <div class="checkout-coupon__panel" id="checkout-coupon-panel" hidden>
                <label for="checkout_coupon_code" class="sr-only">{{ __('Cod de reducere', 'sage') }}</label>
                <div class="checkout-coupon__row coupon-row">
                  <input type="text"
                         id="checkout_coupon_code"
                         class="checkout-coupon__input"
                         placeholder="{{ esc_attr__('Introdu codul aici', 'sage') }}"
                         autocomplete="off"
                         spellcheck="false"
                         enterkeyhint="done">
                  <button type="button" class="checkout-coupon__apply">{{ __('Aplică', 'sage') }}</button>
                </div>
                <div class="checkout-coupon__message" role="alert" aria-live="polite"></div>
              </div>
            </div>
          @endif
        </div>

        {{-- BLOC 3 — Confirmare + Plasează comanda --}}
        <div class="block">
          <div class="head">
            <div class="num">3</div>
            <h2>{{ __('Confirmare.', 'sage') }}</h2>
          </div>

          <div class="checkout-place-order-wrapper">
            <div class="form-row place-order">
              <noscript>
                {{ sprintf(__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order.', 'woocommerce'), '', '') }}
                <br/>
                <button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="{{ esc_attr__('Update totals', 'woocommerce') }}">
                  {{ __('Update totals', 'woocommerce') }}
                </button>
              </noscript>

              @php wc_get_template('checkout/terms.php') @endphp

              @php do_action('woocommerce_review_order_before_submit') @endphp

              @php
                $order_button_text = apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce'));
                echo apply_filters(
                  'woocommerce_order_button_html',
                  '<button type="submit" class="button alt place-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'
                );
              @endphp

              @php do_action('woocommerce_review_order_after_submit') @endphp

              {!! wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', true, false) !!}
            </div>

            <p class="place-note">{!! wp_kses(__('Comanda intră în pregătire imediat ce o confirmi. <em>Primești AWB pe email și SMS în maximum 6h.</em>', 'sage'), ['em' => []]) !!}</p>
          </div>
        </div>

      </div>

      {{-- ============ RIGHT — sumar ============ --}}
      <aside class="checkout-right summary-col">
        @php do_action('woocommerce_checkout_before_order_review_heading') @endphp
        <div class="summary">
          @php do_action('woocommerce_checkout_before_order_review') @endphp
          <div class="head-row">
            <h3>{{ __('Sumarul tău', 'sage') }}</h3>
            <a class="edit-link" href="{{ esc_url(wc_get_cart_url()) }}">{{ __('← Modifică coșul', 'sage') }}</a>
          </div>

          <div id="order_review" class="woocommerce-checkout-review-order">
            @php woocommerce_order_review() @endphp
          </div>

          @php do_action('woocommerce_checkout_after_order_review') @endphp

          <div class="reassure">
            <div class="item">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
              <span>{!! wp_kses(__('<strong>14 zile garanție</strong> · doar dacă produsul este sigilat', 'sage'), ['strong' => []]) !!}</span>
            </div>
            <div class="item">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="8" width="15" height="10" rx="1"/><path d="M18 11h2l3 3v4h-5"/><circle cx="7" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
              <span>{!! wp_kses(__('<strong>Livrare 24–48h</strong> · prin Sameday sau FAN', 'sage'), ['strong' => []]) !!}</span>
            </div>
            <div class="item">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              <span>{!! wp_kses(__('<strong>Plată securizată</strong> · date criptate, nu stocăm cardul', 'sage'), ['strong' => []]) !!}</span>
            </div>
          </div>
        </div>
      </aside>

    </div>
  </form>

  @php do_action('woocommerce_after_checkout_form', $checkout) @endphp

</div>
