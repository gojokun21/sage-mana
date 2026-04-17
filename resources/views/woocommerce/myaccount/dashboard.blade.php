{{--
  My Account dashboard — modern, minimal e-commerce layout.
  @see https://woocommerce.com/document/template-structure/
  @version 4.4.0
--}}

@php
  defined('ABSPATH') || exit;

  $user_id = get_current_user_id();
  $customer = new \WC_Customer($user_id);
  $current_user = wp_get_current_user();

  $all_orders = wc_get_orders([
    'customer' => $user_id,
    'limit' => -1,
    'status' => ['completed', 'processing', 'on-hold'],
    'orderby' => 'date',
    'order' => 'DESC',
  ]);

  $total_orders = count($all_orders);
  $total_spent = array_reduce($all_orders, fn ($c, $o) => $c + (float) $o->get_total(), 0);
  $recent_orders = array_slice($all_orders, 0, 3);

  $billing = [
    'first_name' => $customer->get_billing_first_name(),
    'last_name' => $customer->get_billing_last_name(),
    'address_1' => $customer->get_billing_address_1(),
    'city' => $customer->get_billing_city(),
    'country' => $customer->get_billing_country(),
    'phone' => $customer->get_billing_phone(),
  ];
  $has_billing = trim($billing['address_1']) !== '';

  $shipping_address_1 = $customer->get_shipping_address_1();
  $has_shipping = trim($shipping_address_1) !== '';
@endphp

<div class="account-dashboard">

  <header class="account-dashboard__header">
    <div>
      <p class="account-dashboard__eyebrow">{{ __('Bine ai revenit', 'sage') }}</p>
      <h1 class="account-dashboard__name">{{ esc_html($current_user->display_name) }}</h1>
    </div>

    <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" class="account-dashboard__shop-cta">
      <span>{{ __('Continuă cumpărăturile', 'sage') }}</span>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="5" y1="12" x2="19" y2="12"/>
        <polyline points="12 5 19 12 12 19"/>
      </svg>
    </a>
  </header>

  <div class="account-dashboard__summary">
    <div class="account-summary-item">
      <span class="account-summary-item__label">{{ __('Comenzi', 'sage') }}</span>
      <span class="account-summary-item__value">{{ (int) $total_orders }}</span>
    </div>
    <div class="account-summary-item">
      <span class="account-summary-item__label">{{ __('Total cheltuit', 'sage') }}</span>
      <span class="account-summary-item__value">{!! wc_price($total_spent) !!}</span>
    </div>
    <div class="account-summary-item">
      <span class="account-summary-item__label">{{ __('Membru din', 'sage') }}</span>
      <span class="account-summary-item__value">{{ date_i18n('F Y', strtotime($current_user->user_registered)) }}</span>
    </div>
  </div>

  {{-- RECENT ORDERS --}}
  <section class="account-panel">
    <header class="account-panel__header">
      <h2>{{ __('Comenzile tale recente', 'sage') }}</h2>
      @if (! empty($recent_orders))
        <a href="{{ esc_url(wc_get_endpoint_url('orders')) }}" class="account-link">
          {{ __('Vezi toate', 'sage') }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      @endif
    </header>

    @if (empty($recent_orders))
      <div class="account-panel__empty">
        <p>{{ __('Nu ai plasat încă nicio comandă.', 'sage') }}</p>
        <a href="{{ esc_url(wc_get_page_permalink('shop')) }}" class="btn-primary">
          {{ __('Descoperă produsele', 'sage') }}
        </a>
      </div>
    @else
      <ul class="account-orders">
        @foreach ($recent_orders as $order)
          @php
            $item_count = $order->get_item_count();
            $status = $order->get_status();
            $status_label = wc_get_order_status_name($status);

            $thumbs = [];
            foreach ($order->get_items() as $item) {
              $p = $item->get_product();
              if (! $p) continue;
              $thumb_id = $p->get_image_id();
              if ($thumb_id) {
                $thumbs[] = wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail');
              }
              if (count($thumbs) >= 4) break;
            }
          @endphp

          <li class="account-order">
            <div class="account-order__thumbs" aria-hidden="true">
              @foreach (array_slice($thumbs, 0, 3) as $src)
                <span class="account-order__thumb">
                  <img src="{{ esc_url($src) }}" alt="" loading="lazy" decoding="async">
                </span>
              @endforeach
              @if ($item_count > 3)
                <span class="account-order__thumb account-order__thumb--more">+{{ (int) $item_count - 3 }}</span>
              @endif
            </div>

            <div class="account-order__info">
              <div class="account-order__meta">
                <span class="account-order__number">#{{ $order->get_order_number() }}</span>
                <span class="account-order__dot" aria-hidden="true">·</span>
                <time datetime="{{ esc_attr($order->get_date_created() ? $order->get_date_created()->date('c') : '') }}">
                  {{ wc_format_datetime($order->get_date_created()) }}
                </time>
              </div>
              <div class="account-order__items">
                {{ sprintf(_n('%d produs', '%d produse', $item_count, 'sage'), $item_count) }}
              </div>
            </div>

            <div class="account-order__right">
              <span class="account-status account-status--{{ esc_attr($status) }}">{{ esc_html($status_label) }}</span>
              <span class="account-order__total">{!! $order->get_formatted_order_total() !!}</span>
            </div>

            <a href="{{ esc_url($order->get_view_order_url()) }}" class="account-order__link" aria-label="{{ esc_attr(sprintf(__('Vezi comanda %s', 'sage'), $order->get_order_number())) }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
          </li>
        @endforeach
      </ul>
    @endif
  </section>

  {{-- TWO-COLUMN: addresses + account details --}}
  <div class="account-dashboard__split">
    <section class="account-panel">
      <header class="account-panel__header">
        <h2>{{ __('Adresa de facturare', 'sage') }}</h2>
        <a href="{{ esc_url(wc_get_endpoint_url('edit-address', 'billing')) }}" class="account-link">
          {{ $has_billing ? __('Editează', 'sage') : __('Adaugă', 'sage') }}
        </a>
      </header>

      @if ($has_billing)
        <address class="account-address">
          <strong>{{ esc_html(trim($billing['first_name'] . ' ' . $billing['last_name'])) }}</strong>
          <span>{{ esc_html($billing['address_1']) }}</span>
          <span>{{ esc_html(trim($billing['city'] . ', ' . WC()->countries->countries[$billing['country']] ?? '')) }}</span>
          @if ($billing['phone'])
            <span class="account-address__muted">{{ esc_html($billing['phone']) }}</span>
          @endif
        </address>
      @else
        <p class="account-panel__empty-text">{{ __('Nu ai salvat încă o adresă de facturare.', 'sage') }}</p>
      @endif
    </section>

    <section class="account-panel">
      <header class="account-panel__header">
        <h2>{{ __('Detalii cont', 'sage') }}</h2>
        <a href="{{ esc_url(wc_get_endpoint_url('edit-account')) }}" class="account-link">{{ __('Editează', 'sage') }}</a>
      </header>

      <dl class="account-details">
        <div>
          <dt>{{ __('Email', 'sage') }}</dt>
          <dd>{{ esc_html($current_user->user_email) }}</dd>
        </div>
        <div>
          <dt>{{ __('Utilizator', 'sage') }}</dt>
          <dd>{{ esc_html($current_user->user_login) }}</dd>
        </div>
        <div>
          <dt>{{ __('Parolă', 'sage') }}</dt>
          <dd>••••••••</dd>
        </div>
      </dl>
    </section>
  </div>

  @php
    do_action('woocommerce_account_dashboard');
    do_action('woocommerce_before_my_account');
    do_action('woocommerce_after_my_account');
  @endphp
</div>
