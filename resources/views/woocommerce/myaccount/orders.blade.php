{{--
  My Account → Comenzi. Redesign după mockup `preferinte/Cont - Comenzile mele.html`.
  Scope `.orders-page`. Date REALE din WooCommerce. Filtrarea (chips + căutare),
  paginarea client-side și expand-ul se fac în resources/js/account-orders.js.
--}}
@php
  defined('ABSPATH') || exit;

  $uid = get_current_user_id();

  // All orders for this customer (cap for safety).
  $orders = $uid ? wc_get_orders([
    'customer' => $uid,
    'limit'    => 100,
    'orderby'  => 'date',
    'order'    => 'DESC',
    'type'     => 'shop_order',
  ]) : [];

  // Status → [group, label, badge class]. Groups drive the filter chips.
  $statusMap = function ($status) {
    switch ($status) {
      case 'completed':  return ['delivered', __('Livrat', 'sage'), 'delivered'];
      case 'processing': return ['processing', __('În procesare', 'sage'), 'processing'];
      case 'on-hold':    return ['processing', __('În așteptare', 'sage'), 'processing'];
      case 'pending':    return ['processing', __('Plată în așteptare', 'sage'), 'processing'];
      case 'shipped':    return ['shipped', __('Expediat', 'sage'), 'shipped'];
      case 'cancelled':  return ['cancelled', __('Anulată', 'sage'), 'cancelled'];
      case 'failed':     return ['cancelled', __('Eșuată', 'sage'), 'cancelled'];
      case 'refunded':   return ['returned', __('Returnată', 'sage'), 'returned'];
      default:           return [$status, wc_get_order_status_name($status), 'processing'];
    }
  };

  // Metrics + chip counts.
  $counts = ['all' => 0, 'processing' => 0, 'shipped' => 0, 'delivered' => 0, 'cancelled' => 0, 'returned' => 0];
  $total_spent = 0.0;
  $paid_count = 0;
  $returns = 0;
  $first_order_ts = null;

  foreach ($orders as $o) {
    $counts['all']++;
    [$group] = $statusMap($o->get_status());
    if (isset($counts[$group])) {
      $counts[$group]++;
    }
    if ($o->is_paid()) {
      $total_spent += (float) $o->get_total();
      $paid_count++;
    }
    if ($o->has_status('refunded')) {
      $returns++;
    }
    $created = $o->get_date_created();
    if ($created) {
      $ts = $created->getTimestamp();
      if ($first_order_ts === null || $ts < $first_order_ts) {
        $first_order_ts = $ts;
      }
    }
  }

  $avg = $paid_count > 0 ? $total_spent / $paid_count : 0;
  $retur_url = apply_filters('mn_order_return_url', home_url('/retur/'));
@endphp

<div class="orders-page">

  {{-- HERO --}}
  <div class="op-head">
    <div class="eyebrow">{{ __('Cont · Comenzi', 'sage') }}</div>
    <h1>{!! wp_kses_post(__('Comenzile tale, <em>la un click.</em>', 'sage')) !!}</h1>
    <p>{{ __('Vezi statusul, descarci facturile, ceri retur sau comanzi din nou în câteva secunde.', 'sage') }}</p>
  </div>

  {{-- METRICS --}}
  <div class="op-metrics">
    <div class="op-metric">
      <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
      <div>
        <span class="eyebrow">{{ __('Total comenzi', 'sage') }}</span>
        <div class="big">{{ number_format_i18n($counts['all']) }}</div>
        <div class="lbl">
          @if ($first_order_ts)
            {{ sprintf(__('de la prima ta comandă, %s', 'sage'), wp_date('j F Y', $first_order_ts)) }}
          @else
            {{ __('istoricul comenzilor tale', 'sage') }}
          @endif
        </div>
      </div>
    </div>
    <div class="op-metric">
      <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
      <div>
        <span class="eyebrow">{{ __('Total cumpărat', 'sage') }}</span>
        <div class="big">{!! wc_price($total_spent) !!}</div>
        <div class="lbl">{{ sprintf(__('medie %s pe comandă', 'sage'), html_entity_decode(wp_strip_all_tags(wc_price($avg)))) }}</div>
      </div>
    </div>
    <div class="op-metric">
      <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 3v5h5"/></svg></div>
      <div>
        <span class="eyebrow">{{ __('Retururi', 'sage') }}</span>
        <div class="big">{{ number_format_i18n($returns) }}</div>
        <div class="lbl">{{ $returns === 0 ? __('felicitări pentru alegeri inspirate', 'sage') : __('comenzi returnate', 'sage') }}</div>
      </div>
    </div>
  </div>

  @if (empty($orders))
    {{-- EMPTY STATE --}}
    <div class="op-empty-card">
      <p>{{ __('Nu ai nicio comandă încă.', 'sage') }}</p>
      <a class="op-btn primary" href="{{ esc_url(wc_get_page_permalink('shop')) }}">{{ __('Descoperă produsele', 'sage') }}</a>
    </div>
  @else

    {{-- FILTERS --}}
    <div class="op-filter-bar">
      <div class="op-search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        <input id="opSearch" type="search" placeholder="{{ esc_attr__('Caută după număr comandă sau produs...', 'sage') }}" />
      </div>
      <div class="op-filter-row">
        <div class="op-chip-set" id="opChips">
          <span class="op-chip active" data-status="all">{{ __('Toate', 'sage') }} <span class="ct">({{ $counts['all'] }})</span></span>
          <span class="op-chip" data-status="processing">{{ __('În procesare', 'sage') }} <span class="ct">({{ $counts['processing'] }})</span></span>
          <span class="op-chip" data-status="shipped">{{ __('Expediate', 'sage') }} <span class="ct">({{ $counts['shipped'] }})</span></span>
          <span class="op-chip" data-status="delivered">{{ __('Livrate', 'sage') }} <span class="ct">({{ $counts['delivered'] }})</span></span>
          <span class="op-chip" data-status="cancelled">{{ __('Anulate', 'sage') }} <span class="ct">({{ $counts['cancelled'] }})</span></span>
          <span class="op-chip" data-status="returned">{{ __('Returnate', 'sage') }} <span class="ct">({{ $counts['returned'] }})</span></span>
        </div>
        <select class="op-sort" id="opSort">
          <option value="recent">{{ __('Cele mai recente', 'sage') }}</option>
          <option value="old">{{ __('Cele mai vechi', 'sage') }}</option>
          <option value="amount-asc">{{ __('Suma crescător', 'sage') }}</option>
          <option value="amount-desc">{{ __('Suma descrescător', 'sage') }}</option>
        </select>
      </div>
    </div>

    {{-- ORDERS --}}
    <div class="op-list" id="opList">
      @foreach ($orders as $order)
        @php
          [$group, $label, $badge] = $statusMap($order->get_status());
          $items = $order->get_items();
          $item_count = count($items);
          $qty_total = 0;
          $names = [];
          $thumbs = [];
          foreach ($items as $it) {
            $qty_total += (int) $it->get_quantity();
            $names[] = $it->get_name() . ' <strong>× ' . (int) $it->get_quantity() . '</strong>';
            $p = $it->get_product();
            if ($p && count($thumbs) < 3) {
              $img = $p->get_image_id() ? wp_get_attachment_image_url($p->get_image_id(), 'woocommerce_thumbnail') : wc_placeholder_img_src('woocommerce_thumbnail');
              $thumbs[] = $img;
            }
          }
          $names_html = implode(', ', array_slice($names, 0, 2));
          if ($item_count > 2) {
            $names_html .= ' <span class="more">+' . ($item_count - 2) . ' ' . esc_html__('produse', 'sage') . '</span>';
          }
          $shipping_total = (float) $order->get_shipping_total();
          $created = $order->get_date_created();
          $search = strtolower($order->get_order_number() . ' ' . wp_strip_all_tags(implode(' ', $names)));
          $is_completed = $order->has_status('completed');
          // FGO invoice PDF link (mn-fgo-invoice-email stores it as order meta).
          // When the PDF isn't generated yet, fall back to the order page.
          $invoice_pdf = (string) $order->get_meta('_fgo_invoice_link');
          $invoice_url = apply_filters('mn_order_invoice_url', $invoice_pdf ?: $order->get_view_order_url(), $order);
          $invoice_is_pdf = ! empty($invoice_pdf);
        @endphp
        <article class="op-card" data-status="{{ esc_attr($group) }}" data-amount="{{ esc_attr($order->get_total()) }}" data-date="{{ $created ? $created->getTimestamp() : 0 }}" data-search="{{ esc_attr($search) }}">
          <div class="op-card-head">
            <div class="left">
              <span class="op-num">#{{ esc_html($order->get_order_number()) }}</span>
              <span class="op-date">{{ $created ? wp_date('j F Y', $created->getTimestamp()) : '' }}</span>
            </div>
            <span class="op-badge {{ $badge }}"><span class="dot"></span>{{ $label }}</span>
          </div>

          <div class="op-products">
            <div class="op-thumbs">
              @foreach ($thumbs as $thumb)
                <div class="op-thumb"><img src="{{ esc_url($thumb) }}" alt="" loading="lazy" decoding="async" /></div>
              @endforeach
            </div>
            <div class="op-prod-info">
              <div class="op-prod-list">{!! $names_html !!}</div>
              <div class="op-prod-meta">{{ sprintf(_n('%d produs', '%d produse', $item_count, 'sage'), $item_count) }} · {{ sprintf(_n('%d bucată', '%d bucăți', $qty_total, 'sage'), $qty_total) }}</div>
            </div>
          </div>

          <div class="op-total">
            <span class="amount">{!! $order->get_formatted_order_total() !!}</span>
            <span class="meta">
              <span class="pay-pill">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
                {{ $order->get_payment_method_title() ?: __('Plată', 'sage') }}
              </span>
              <span class="sep">·</span>
              @if ($shipping_total <= 0)
                <span class="free">{{ __('Transport gratuit', 'sage') }}</span>
              @else
                <span>{{ sprintf(__('Transport %s', 'sage'), html_entity_decode(wp_strip_all_tags(wc_price($shipping_total)))) }}</span>
              @endif
            </span>
          </div>

          <div class="op-actions">
            <a class="op-link" href="{{ esc_url($order->get_view_order_url()) }}">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>{{ __('Vezi detalii', 'sage') }}
            </a>

            <a class="op-link" href="{{ esc_url($invoice_url) }}" @if ($invoice_is_pdf) target="_blank" rel="noopener" @endif>
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>{{ __('Descarcă factură', 'sage') }}
            </a>

            @if ($is_completed)
              <a class="op-btn primary" href="{{ esc_url(wp_nonce_url(add_query_arg('order_again', $order->get_id(), wc_get_cart_url()), 'woocommerce-order_again')) }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 3v5h5"/></svg>{{ __('Comandă din nou', 'sage') }}
              </a>
              <a class="op-btn outline" href="{{ esc_url($retur_url) }}">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg>{{ __('Cere retur', 'sage') }}
              </a>
            @endif

            <a class="op-link op-expand-toggle" data-op-expand>
              {{ __('Detalii livrare', 'sage') }}
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="chevron"><path d="m6 9 6 6 6-6"/></svg>
            </a>
          </div>

          {{-- EXPAND: timeline (real dates) + address --}}
          <div class="op-expand">
            @php
              $tl = [];
              if ($order->get_date_created()) { $tl[] = [__('Plasată', 'sage'), $order->get_date_created()]; }
              if ($order->get_date_paid()) { $tl[] = [__('Confirmată', 'sage'), $order->get_date_paid()]; }
              if ($order->get_date_completed()) { $tl[] = [__('Finalizată', 'sage'), $order->get_date_completed()]; }
              $ship_addr = $order->get_formatted_shipping_address();
              $bill_addr = $order->get_formatted_billing_address();
            @endphp

            @if (count($tl) > 1)
              <div class="op-expand-section">
                <div class="op-expand-label">{{ __('Traseu comandă', 'sage') }}</div>
                <div class="op-timeline">
                  @foreach ($tl as $step)
                    <div class="op-tl-step">
                      <div class="dot"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg></div>
                      <span class="ttl">{{ $step[0] }}</span>
                      <span class="when">{{ wp_date('j M · H:i', $step[1]->getTimestamp()) }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <div class="op-expand-section">
              <div class="op-expand-label">{{ __('Detalii livrare', 'sage') }}</div>
              <div class="op-addr-box">
                <div class="op-addr-cell">
                  <span class="k">{{ __('Adresă livrare', 'sage') }}</span>
                  <span class="v">{!! ($ship_addr ?: $bill_addr) ?: esc_html__('—', 'sage') !!}</span>
                </div>
                <div class="op-addr-cell">
                  <span class="k">{{ __('Metodă plată', 'sage') }}</span>
                  <span class="v">{{ $order->get_payment_method_title() ?: __('—', 'sage') }}</span>
                </div>
                <div class="op-addr-cell">
                  <span class="k">{{ __('Status', 'sage') }}</span>
                  <span class="v">{{ $label }}<small>{{ $created ? wp_date('j F Y', $created->getTimestamp()) : '' }}</small></span>
                </div>
              </div>
            </div>
          </div>
        </article>
      @endforeach
    </div>

    {{-- empty filter result --}}
    <div class="op-no-results" id="opNoResults" hidden>{{ __('Nicio comandă nu corespunde filtrelor.', 'sage') }}</div>

    {{-- PAGINATION (client-side, built by JS) --}}
    <div class="op-pagination" id="opPagination"></div>
  @endif

  {{-- HELP --}}
  <div class="op-help">
    <h3>{!! wp_kses_post(__('Ai nevoie de <em>ajutor?</em>', 'sage')) !!}</h3>
    <div class="op-help-grid">
      <a class="op-help-card" href="{{ esc_url(home_url('/contact/')) }}">
        <div class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92z"/></svg></div>
        <div class="copy"><h4>{{ __('Probleme cu o comandă?', 'sage') }}</h4><p>{{ __('WhatsApp sub 1 oră, email 24h.', 'sage') }}</p></div>
      </a>
      <a class="op-help-card" href="{{ esc_url($retur_url) }}">
        <div class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg></div>
        <div class="copy"><h4>{{ __('Cum returnez un produs?', 'sage') }}</h4><p>{{ __('14 zile pentru produse sigilate.', 'sage') }}</p></div>
      </a>
      <a class="op-help-card" href="{{ esc_url(home_url('/intrebari-frecvente/')) }}">
        <div class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="copy"><h4>{{ __('Unde îmi găsesc facturile?', 'sage') }}</h4><p>{{ __('Pe email, în 24h după livrare.', 'sage') }}</p></div>
      </a>
    </div>
  </div>
</div>
