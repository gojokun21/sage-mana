{{--
  My Account sidebar: user header + menu (with SVG icons).
  @see https://woocommerce.com/document/template-structure/
  @version 9.3.0
--}}

@php
  defined('ABSPATH') || exit;

  $current_user = wp_get_current_user();

  $menu_icons = [
    'dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
    'orders' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
    'downloads' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
    'edit-address' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
    'edit-account' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'payment-methods' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
    'customer-logout' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
  ];

  $fallback_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>';

  do_action('woocommerce_before_account_navigation');
@endphp

<nav class="woocommerce-MyAccount-navigation" aria-label="{{ esc_attr__('Account pages', 'woocommerce') }}">
  <div class="account-nav-header">
    <div class="account-avatar">
      {!! get_avatar($current_user->ID, 44) !!}
    </div>
    <div class="account-user-info">
      <span class="account-user-name">{{ esc_html($current_user->display_name) }}</span>
      <span class="account-user-email">{{ esc_html($current_user->user_email) }}</span>
    </div>
  </div>

  <ul>
    @foreach (wc_get_account_menu_items() as $endpoint => $label)
      @php
        $icon = $menu_icons[$endpoint] ?? $fallback_icon;
        $classes = wc_get_account_menu_item_classes($endpoint);
        $is_current = wc_is_current_account_menu_item($endpoint);
      @endphp
      <li class="{{ esc_attr($classes) }}">
        <a href="{{ esc_url(wc_get_account_endpoint_url($endpoint)) }}"
           @if ($is_current) aria-current="page" @endif>
          <span class="nav-icon">{!! $icon !!}</span>
          <span class="nav-label">{{ esc_html($label) }}</span>
        </a>
      </li>
    @endforeach
  </ul>
</nav>

@php do_action('woocommerce_after_account_navigation') @endphp
