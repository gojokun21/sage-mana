{{--
  My Account · Dashboard — design „Cont - Dashboard" (mockup).
  Coloana principală D1–D8 cu date LIVE din WooCommerce + mn-loyalty.
  Scope CSS `.account-dash` (resources/css/account-dashboard.css via account-bundle.css).
  Reorder add-to-cart: resources/js/account-dashboard.js (lazy pe `.account-dash`).
  Sidebar-ul + layout-ul vin din navigation.blade.php / myaccount.css.
--}}

@php
  defined('ABSPATH') || exit;

  $user_id      = get_current_user_id();
  $current_user = wp_get_current_user();
  $customer     = new \WC_Customer($user_id);
  $first_name   = $customer->get_first_name() ?: $current_user->display_name;

  // Toate comenzile relevante (recente întâi).
  $all_orders = wc_get_orders([
    'customer' => $user_id,
    'limit'    => -1,
    'status'   => ['completed', 'processing', 'on-hold'],
    'orderby'  => 'date',
    'order'    => 'DESC',
  ]);
  $total_orders = count($all_orders);
  $total_spent  = array_reduce($all_orders, fn ($c, $o) => $c + (float) $o->get_total(), 0.0);

  // Comandă „în curs" = cea mai recentă processing / on-hold.
  $active_order = null;
  foreach ($all_orders as $o) {
    if (in_array($o->get_status(), ['processing', 'on-hold'], true)) { $active_order = $o; break; }
  }

  // Reorder — produse distincte cumpărate, cu ultima dată comandată.
  $reorder = [];
  foreach ($all_orders as $o) {
    $odate = $o->get_date_created();
    foreach ($o->get_items() as $item) {
      $pid = $item->get_product_id();
      $product = $item->get_product();
      if (! $pid || ! $product || isset($reorder[$pid]) || ! $product->is_purchasable()) continue;
      $reorder[$pid] = [
        'product' => $product,
        'name'    => $item->get_name(),
        'price'   => $product->get_price_html(),
        'last'    => $odate ? date_i18n('j F', $odate->getTimestamp()) : '',
        'image'   => $product->get_image_id() ? wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail') : '',
      ];
    }
    if (count($reorder) >= 3) break;
  }
  $reorder = array_slice($reorder, 0, 3, true);
  $purchased_count = 0;
  foreach ($all_orders as $o) { $purchased_count += count($o->get_items()); }

  // Loyalty (mn-loyalty).
  $loy = class_exists('MN_Loyalty_Account') ? \MN_Loyalty_Account::data($user_id) : null;

  // Retururi (CPT natura_rma legat de emailul clientului).
  $returns_count = 0;
  if (post_type_exists('natura_rma') && $current_user->user_email) {
    $returns_count = count(get_posts([
      'post_type'   => 'natura_rma',
      'post_status' => 'private',
      'fields'      => 'ids',
      'numberposts' => 50,
      'meta_key'    => '_rma_email',
      'meta_value'  => $current_user->user_email,
    ]));
  }

  // Recomandări — produse featured (fallback: cele mai noi).
  $recs = wc_get_products(['featured' => true, 'limit' => 3, 'status' => 'publish', 'orderby' => 'date', 'order' => 'DESC']);
  if (count($recs) < 3) {
    $recs = wc_get_products(['limit' => 3, 'status' => 'publish', 'orderby' => 'date', 'order' => 'DESC']);
  }

  // Recenzii lăsate de utilizator.
  $reviews_left = (int) get_comments([
    'user_id' => $user_id,
    'type'    => 'review',
    'status'  => 'approve',
    'count'   => true,
  ]);
  $reviews_todo = max(0, $purchased_count - $reviews_left);

  // Scurtături din meniul de cont real.
  $menu_items = function_exists('wc_get_account_menu_items') ? wc_get_account_menu_items() : [];
  $sc_icons = [
    'orders'          => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
    'subscriptions'   => '<path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 3v5h5"/>',
    'edit-address'    => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
    'edit-account'    => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'payment-methods' => '<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/>',
    'cod-fidelitate'  => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
    'downloads'       => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
    'customer-logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
  ];
  $sc_fallback = '<circle cx="12" cy="12" r="10"/>';
  $chevron = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>';
  $arrow   = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>';
  $thumb_tones = ['', 't2', 't3'];
@endphp

<div class="account-dash">

  {{-- D1: HERO --}}
  <div class="hero">
    <div class="hc">
      <h1>{{ __('Bună,', 'sage') }} <em>{{ $first_name }}.</em></h1>
      <span class="date">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        {{ sprintf(__('Astăzi e %s', 'sage'), date_i18n('l, j F Y')) }}
        <span class="dotsep"></span>
        <span class="weather">{{ __('o zi bună de îngrijit de tine', 'sage') }}</span>
      </span>
    </div>
    <a class="profile-link" href="{{ esc_url(wc_get_endpoint_url('edit-account')) }}">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      {{ __('Vezi profil complet', 'sage') }}
    </a>
  </div>

  {{-- D2: ORDER BANNER (doar dacă există o comandă în curs) --}}
  @if ($active_order)
    @php
      $st = $active_order->get_status();
      $names = [];
      foreach ($active_order->get_items() as $it) { $names[] = $it->get_name(); if (count($names) >= 2) break; }
      $names_str = implode(__(' și ', 'sage'), array_map('esc_html', $names));
      $more = $active_order->get_item_count() - count($names);
      $created = $active_order->get_date_created();
      $awb = '';
      foreach (['_tracking_number', '_aftership_tracking_number', '_shipo_awb', '_awb'] as $mk) {
        $v = $active_order->get_meta($mk);
        if (is_string($v) && $v !== '') { $awb = $v; break; }
      }
      $step2_done = ($st === 'processing');
    @endphp
    <div class="order-banner">
      <div class="ob-top">
        <div class="copy">
          <span class="ob-lbl">{{ __('Comandă în curs', 'sage') }}</span>
          <h2>{{ __('Comanda', 'sage') }} <span class="order-id">{{ $active_order->get_order_number() }}</span> {{ __('e', 'sage') }} <em>{{ __('în drum.', 'sage') }}</em></h2>
          <p class="ob-desc">
            @if ($names_str){!! $names_str !!}@if ($more > 0) {{ sprintf(_n('și încă %d produs', 'și încă %d produse', $more, 'sage'), $more) }}@endif. @endif
            {{ __('Te anunțăm când ajunge la tine.', 'sage') }}
          </p>
          @if ($awb)
            <span class="ob-courier">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6H19M7 13L5.4 5"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg>
              {{ sprintf(__('AWB %s', 'sage'), esc_html($awb)) }}
            </span>
          @endif
        </div>
      </div>

      <div class="ob-timeline">
        <div class="tl-step done">
          <div class="tl-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></div>
          <span class="tl-lbl">{{ __('Plasată', 'sage') }}</span>
          <span class="tl-date">{{ $created ? date_i18n('j M', $created->getTimestamp()) : '' }}</span>
        </div>
        <div class="tl-line {{ $step2_done ? 'done' : '' }}"></div>
        <div class="tl-step {{ $step2_done ? 'done' : 'now' }}">
          <div class="tl-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></div>
          <span class="tl-lbl">{{ __('Confirmată', 'sage') }}</span>
          <span class="tl-date">{{ wc_get_order_status_name($st) }}</span>
        </div>
        <div class="tl-line"></div>
        <div class="tl-step now">
          <div class="tl-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6H19M7 13L5.4 5"/></svg></div>
          <span class="tl-lbl">{{ __('În livrare', 'sage') }}</span>
          <span class="tl-date">{{ __('în curând', 'sage') }}</span>
        </div>
        <div class="tl-line"></div>
        <div class="tl-step future">
          <div class="tl-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg></div>
          <span class="tl-lbl">{{ __('Livrată', 'sage') }}</span>
          <span class="tl-date">{{ __('estimat', 'sage') }}</span>
        </div>
      </div>

      <div class="ob-actions">
        @if ($awb)
          <a class="ob-btn primary" href="https://www.fancourier.ro/awb-tracking/?awb={{ rawurlencode($awb) }}" target="_blank" rel="noopener">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ __('Urmărește coletul', 'sage') }}
          </a>
        @endif
        <a class="ob-btn link" href="{{ esc_url($active_order->get_view_order_url()) }}">{{ __('Vezi detalii comandă', 'sage') }}</a>
      </div>
    </div>
  @endif

  {{-- D3: METRICS --}}
  <div class="metrics-grid">
    <div class="metric-card">
      <div class="mc-top">
        <div class="mc-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6H19M7 13L5.4 5"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg></div>
        <span class="mc-tag">{{ __('Comenzi totale', 'sage') }}</span>
      </div>
      <div class="mc-val">{{ number_format_i18n($total_orders) }}<span class="unit">{{ _n('comandă', 'comenzi', $total_orders, 'sage') }}</span></div>
      <div class="mc-sub"><strong>{!! wp_strip_all_tags(wc_price($total_spent)) !!}</strong> {{ __('cheltuiți', 'sage') }}</div>
      <a class="mc-link" href="{{ esc_url(wc_get_endpoint_url('orders')) }}">{{ __('Vezi istoric', 'sage') }} {!! $arrow !!}</a>
    </div>

    <div class="metric-card">
      <div class="mc-top">
        <div class="mc-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2C12 2 8 6 8 12C8 16 10 19 12 22C14 19 16 16 16 12C16 6 12 2 12 2Z"/></svg></div>
        <span class="mc-tag">{{ __('Puncte Constant', 'sage') }}</span>
      </div>
      <div class="mc-val">{{ $loy ? number_format_i18n($loy['balance']) : '0' }}<span class="unit">{{ __('puncte', 'sage') }}</span></div>
      <div class="mc-sub">{{ __('echivalent', 'sage') }} <strong>{!! $loy ? wp_strip_all_tags(wc_price($loy['balance_value'])) : wp_strip_all_tags(wc_price(0)) !!}</strong></div>
      <a class="mc-link" href="{{ esc_url(wc_get_account_endpoint_url('cod-fidelitate')) }}">{{ __('Folosește-le', 'sage') }} {!! $arrow !!}</a>
    </div>

    <div class="metric-card">
      <div class="mc-top">
        <div class="mc-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7v6h6"/><path d="M3 13a9 9 0 1 0 3-6.7L3 9"/></svg></div>
        <span class="mc-tag">{{ __('Retururi', 'sage') }}</span>
      </div>
      <div class="mc-val">{{ number_format_i18n($returns_count) }}<span class="unit">{{ _n('retur', 'retururi', $returns_count, 'sage') }}</span></div>
      <div class="mc-sub">{{ $returns_count === 0 ? __('toate alegerile, la țintă', 'sage') : __('în procesare', 'sage') }}</div>
      <a class="mc-link" href="{{ esc_url(home_url('/retur/')) }}">{{ __('Cum cer retur', 'sage') }} {!! $arrow !!}</a>
    </div>

    <div class="metric-card">
      <div class="mc-top">
        <div class="mc-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <span class="mc-tag">{{ __('Recomandări', 'sage') }}</span>
      </div>
      <div class="mc-val">{{ $loy ? number_format_i18n($loy['referral']['friends']) : '0' }}<span class="unit">{{ _n('prieten', 'prieteni', $loy ? (int) $loy['referral']['friends'] : 0, 'sage') }}</span></div>
      <div class="mc-sub"><strong>{{ $loy ? number_format_i18n($loy['referral']['points']) : '0' }} {{ __('puncte', 'sage') }}</strong> {{ __('câștigate', 'sage') }}</div>
      <a class="mc-link" href="{{ esc_url(wc_get_account_endpoint_url('cod-fidelitate')) }}">{{ __('Invită prieteni', 'sage') }} {!! $arrow !!}</a>
    </div>
  </div>

  {{-- D4: REORDER --}}
  @if (! empty($reorder))
    <div class="section-card">
      <div class="sec-head">
        <div class="ht">
          <h2><span class="ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 3v5h5"/></svg></span>{{ __('Comandă', 'sage') }} <em>{{ __('din nou.', 'sage') }}</em></h2>
          <p class="htxt">{{ __('Produse pe care le-ai cumpărat înainte. Click pe «+» le adaugă în coș.', 'sage') }}</p>
        </div>
        <a class="see-all" href="{{ esc_url(wc_get_endpoint_url('orders')) }}">{{ sprintf(__('Vezi toate (%d)', 'sage'), $total_orders) }}</a>
      </div>
      <div class="reorder-grid">
        @foreach (array_values($reorder) as $i => $ro)
          <div class="ro-card">
            <div class="ro-thumb {{ $thumb_tones[$i] ?? '' }}">
              @if ($ro['image'])
                <img src="{{ esc_url($ro['image']) }}" alt="" loading="lazy" decoding="async">
              @else
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 21h8M12 17v4M5 3h14l-2 12H7L5 3z"/></svg>
              @endif
            </div>
            <div class="ro-info">
              <span class="nm">{{ $ro['name'] }}</span>
              <span class="pr">{!! $ro['price'] !!}</span>
              @if ($ro['last'])<span class="last">{{ sprintf(__('Ultima dată: %s', 'sage'), $ro['last']) }}</span>@endif
            </div>
            <button class="ro-add" type="button" data-add-to-cart="{{ esc_attr($ro['product']->get_id()) }}" data-product-name="{{ esc_attr($ro['name']) }}" aria-label="{{ esc_attr(sprintf(__('Adaugă %s în coș', 'sage'), $ro['name'])) }}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
            </button>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  {{-- D5: RECOMMENDATIONS --}}
  @if (! empty($recs))
    <div class="recco-wrap">
      <div class="sec-head" style="margin:0">
        <div class="ht">
          <h2><span class="ico" style="background:#fff;border:1px solid var(--rule)"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L9 8l-6 1 4.5 4.5L6 20l6-3 6 3-1.5-6.5L21 9l-6-1z"/></svg></span>{{ __('S-ar putea', 'sage') }} <em>{{ __('să-ți placă.', 'sage') }}</em></h2>
          <p class="htxt">{{ __('O selecție de favorite ale comunității. Sugestii personalizate apar când activezi recomandările din Date personale.', 'sage') }}</p>
        </div>
      </div>

      <div class="recco-notice">
        <div class="ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg></div>
        <div class="txt">
          <span class="t">{{ __('Recomandări pe baza istoricului tău', 'sage') }}</span>
          <span class="s">{{ __('Le poți activa oricând. Între timp, ți-am pregătit câteva sugestii generale.', 'sage') }}</span>
        </div>
        <a class="cta" href="{{ esc_url(wc_get_endpoint_url('edit-account')) }}">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
          {{ __('Activează recomandările', 'sage') }}
        </a>
      </div>

      <div class="recco-grid">
        @foreach ($recs as $i => $rp)
          <a class="rc-card" href="{{ esc_url(get_permalink($rp->get_id())) }}">
            <div class="rc-thumb {{ $thumb_tones[$i] ?? '' }}">
              @if ($rp->get_image_id())
                <img src="{{ esc_url(wp_get_attachment_image_url($rp->get_image_id(), 'woocommerce_thumbnail')) }}" alt="" loading="lazy" decoding="async">
              @else
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M12 2C12 2 8 6 8 12C8 16 10 19 12 22C14 19 16 16 16 12C16 6 12 2 12 2Z"/></svg>
              @endif
            </div>
            <div class="rc-info">
              <span class="nm">{{ $rp->get_name() }}</span>
              <span class="tagline">{{ wp_trim_words(wp_strip_all_tags($rp->get_short_description()), 8, '…') ?: __('Formulat onest, dozaj măsurat.', 'sage') }}</span>
            </div>
            <div class="rc-foot">
              <span class="pr">{!! $rp->get_price_html() !!}</span>
              <span class="see">{{ __('Vezi', 'sage') }} {!! $arrow !!}</span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  @endif

  {{-- D6: SHORTCUTS --}}
  <div class="section-card">
    <div class="sec-head">
      <div class="ht">
        <h2><span class="ico"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span>{{ __('Scurtături', 'sage') }} <em>{{ __('rapide.', 'sage') }}</em></h2>
      </div>
    </div>
    <div class="shortcuts-grid">
      @foreach ($menu_items as $endpoint => $label)
        @if ($endpoint === 'dashboard') @continue @endif
        @php
          $is_logout = $endpoint === 'customer-logout';
          $icon = $sc_icons[$endpoint] ?? $sc_fallback;
        @endphp
        <a class="sc-card {{ $is_logout ? 'danger' : '' }}" href="{{ esc_url(wc_get_account_endpoint_url($endpoint)) }}">
          <span class="sci"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $icon !!}</svg></span>
          <span class="scl">{{ esc_html($label) }}@if ($endpoint === 'cod-fidelitate' && $loy)<span class="scchip">{{ $loy['tier']['label'] ?? 'Constant' }}</span>@endif</span>
          <span class="scc">{!! $chevron !!}</span>
        </a>
      @endforeach
    </div>
  </div>

  {{-- D7: REVIEW REMINDER --}}
  @if ($reviews_todo > 0)
    <div class="review-card">
      <div class="rv-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></div>
      <div class="rv-copy">
        <h3>{{ __('Ne ajută', 'sage') }} <em>{{ __('review-urile tale.', 'sage') }}</em></h3>
        <p>{!! wp_kses(sprintf(__('Ai lăsat <strong>%1$s</strong> și mai sunt <strong>%2$s</strong> care așteaptă părerea ta.', 'sage'),
          sprintf(_n('%d review', '%d review-uri', $reviews_left, 'sage'), $reviews_left),
          sprintf(_n('%d produs', '%d produse', $reviews_todo, 'sage'), $reviews_todo)
        ), ['strong' => []]) !!}</p>
        <span class="points-hint">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 2C12 2 8 6 8 12C8 16 10 19 12 22C14 19 16 16 16 12C16 6 12 2 12 2Z"/></svg>
          {{ __('puncte Constant per review verificat', 'sage') }}
        </span>
      </div>
      <a class="rv-btn" href="{{ esc_url(wc_get_endpoint_url('orders')) }}">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>
        {{ __('Lasă un review', 'sage') }}
      </a>
    </div>
  @endif

  {{-- D8: SUPPORT FOOTER --}}
  <div class="support-foot">
    {!! wp_kses(sprintf(
      __('Ai nevoie de ajutor? Scrie-ne pe WhatsApp %1$s sau pe %2$s.', 'sage'),
      '<a href="https://wa.me/40749492794" target="_blank" rel="noopener">+40 749 492 794</a>',
      '<a href="mailto:suport@mananaturii.ro">suport@mananaturii.ro</a>'
    ), ['a' => ['href' => [], 'target' => [], 'rel' => []]]) !!}
  </div>

  @php
    do_action('woocommerce_account_dashboard');
    do_action('woocommerce_before_my_account');
    do_action('woocommerce_after_my_account');
  @endphp
</div>
