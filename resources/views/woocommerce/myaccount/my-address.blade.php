{{--
  My Account → Adrese (listing). Redesign vizual după mockup
  `preferinte/Cont - Adrese de livrare.html`, dar pe modelul NATIV WooCommerce:
  exact 2 adrese reale — Livrare (shipping) + Facturare (billing). Fără agendă
  multi-adresă (add/șterge/max 10/curier/date firmă) pentru că WC nu le stochează.
  „Editează" duce la formularul WC nativ (endpoint edit-address). Scope `.addr-page`.
  @see https://woocommerce.com/document/template-structure/
  @version 9.3.0
--}}
@php
  defined('ABSPATH') || exit;

  $customer_id = get_current_user_id();
  $ship_enabled = ! wc_ship_to_billing_address_only() && wc_shipping_enabled();

  // Tipurile native, în ordinea din mockup (Livrare întâi).
  $types = $ship_enabled
    ? ['shipping' => __('Livrare', 'sage'), 'billing' => __('Facturare', 'sage')]
    : ['billing' => __('Facturare', 'sage')];

  $customer = $customer_id ? new \WC_Customer($customer_id) : null;
  $ro_states = WC()->countries ? (WC()->countries->get_states('RO') ?: []) : [];

  // Iconuri pin per tip (din mockup): casă pentru livrare, factură/clădire pentru facturare.
  $pin_icons = [
    'shipping' => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9.5L12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V9.5z"/></svg>',
    'billing'  => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6M9 9h2"/></svg>',
  ];
  $chip_icons = [
    'shipping' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h13l4 5v5h-3a2 2 0 1 1-4 0H10a2 2 0 1 1-4 0H3V7z"/></svg>',
    'billing'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6M9 9h2"/></svg>',
  ];

  // Pre-construiește datele fiecărei adrese din getterii reali WC.
  $cards = [];
  foreach ($types as $type => $label) {
    $get = function ($field) use ($customer, $type) {
      $method = "get_{$type}_{$field}";
      return ($customer && method_exists($customer, $method)) ? (string) $customer->{$method}() : '';
    };
    $state_code = $get('state');
    $state_name = $state_code !== '' ? ($ro_states[$state_code] ?? $state_code) : '';
    $formatted = wc_get_account_formatted_address($type);

    $cards[$type] = [
      'label'      => $label,
      'is_empty'   => empty($formatted),
      'edit_url'   => wc_get_endpoint_url('edit-address', $type),
      'person'     => trim($get('first_name') . ' ' . $get('last_name')),
      'company'    => $get('company'),
      'phone'      => $get('phone'),
      'address_1'  => $get('address_1'),
      'address_2'  => $get('address_2'),
      'city'       => $get('city'),
      'state'      => $state_name,
      'postcode'   => $get('postcode'),
    ];
  }

  $set_count = count(array_filter($cards, fn ($c) => ! $c['is_empty']));
@endphp

<div class="addr-page">

  {{-- HERO --}}
  <div class="page-head">
    <div class="eyebrow">{{ __('Cont · Adrese', 'sage') }}</div>
    <h1>{!! wp_kses_post(__('Adresele tale, <em>la îndemână.</em>', 'sage')) !!}</h1>
    <p>{{ __('Adresa de livrare și cea de facturare se folosesc automat la checkout. Le poți edita oricând.', 'sage') }}</p>
  </div>

  {{-- ACTIONS / COUNT --}}
  <div class="actions-row">
    <div class="count">
      <strong>{{ sprintf(_n('%d adresă', '%d adrese', $set_count, 'sage'), $set_count) }}</strong>
      {{ __('configurate', 'sage') }}
      <span class="sep-dot"></span>
      {{ __('folosite la checkout', 'sage') }}
    </div>
  </div>

  {{-- GRID --}}
  <div class="addr-grid">
    @foreach ($cards as $type => $card)
      <article class="addr-card {{ $type === 'shipping' ? 'is-default-ship' : '' }} {{ $card['is_empty'] ? 'is-empty' : '' }}">
        <div class="addr-head">
          <div class="lbl-wrap">
            <h3 class="name"><span class="ico-pin">{!! $pin_icons[$type] ?? $pin_icons['billing'] !!}</span>{{ $card['label'] }}</h3>
            <div class="badges">
              @if (! $card['is_empty'])
                @if ($type === 'shipping')
                  <span class="badge ship-default"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Default livrare', 'sage') }}</span>
                @else
                  <span class="badge bill-default"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6 9 17l-5-5"/></svg>{{ __('Default facturare', 'sage') }}</span>
                @endif
              @endif
            </div>
          </div>
          <span class="type-chip">{!! $chip_icons[$type] ?? $chip_icons['billing'] !!}{{ $card['label'] }}</span>
        </div>

        <div class="addr-body">
          @if ($card['is_empty'])
            <p class="addr-empty">{{ __('Nu ai setat încă această adresă. Adaug-o ca să o folosești rapid la checkout.', 'sage') }}</p>
          @else
            @if ($card['company'])
              <span class="person">{{ $card['company'] }}</span>
              @if ($card['person'])<span class="street">{{ $card['person'] }}</span>@endif
            @else
              <span class="person">{{ $card['person'] }}</span>
            @endif
            @if ($card['phone'])
              <span class="phone"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>{{ $card['phone'] }}</span>
            @endif
            @if ($card['address_1'])<span class="street">{{ $card['address_1'] }}</span>@endif
            @if ($card['address_2'])<span class="street">{{ $card['address_2'] }}</span>@endif
            @if ($card['city'] || $card['state'] || $card['postcode'])
              <span class="city-line"><strong>{{ $card['city'] }}</strong>{{ $card['state'] ? ', jud. ' . $card['state'] : '' }}{{ $card['postcode'] ? ', ' . $card['postcode'] : '' }}</span>
            @endif
          @endif
        </div>

        <div class="addr-foot">
          <a class="act" href="{{ esc_url($card['edit_url']) }}">
            @if ($card['is_empty'])
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>{{ __('Adaugă adresa', 'sage') }}
            @else
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>{{ __('Editează', 'sage') }}
            @endif
          </a>
          @php do_action('woocommerce_my_account_after_my_address', $type) @endphp
        </div>
      </article>
    @endforeach
  </div>

  {{-- INFO --}}
  <div class="info-card">
    <div class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></div>
    <div class="txt">{!! wp_kses_post(__('La checkout poți folosi adresele de aici sau introduce una nouă. <strong>Modificările se aplică la următoarea comandă</strong>, nu la cele aflate în procesare.', 'sage')) !!}</div>
  </div>

</div>
