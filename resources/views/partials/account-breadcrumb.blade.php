{{--
  Breadcrumb pentru paginile de cont, ca în machete: „Acasă › Contul meu" pe
  dashboard, „Acasă › Contul meu › <Secțiune>" pe endpoint-uri (Comenzi, Adrese,
  Date personale, Abonamente, etc.). Înlocuiește titlul page-header pe contul logat.
--}}
@php
  $home_url    = home_url('/');
  $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/contul-meu/');
  $endpoint    = (function_exists('WC') && WC()->query) ? WC()->query->get_current_endpoint() : '';
  $items       = function_exists('wc_get_account_menu_items') ? wc_get_account_menu_items() : [];
  $endpoint_label = ($endpoint && isset($items[$endpoint])) ? $items[$endpoint] : '';
@endphp

<nav class="mn-account-breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
  <a href="{{ esc_url($home_url) }}">{{ __('Acasă', 'sage') }}</a>
  <span class="sep" aria-hidden="true">›</span>
  @if ($endpoint_label)
    <a href="{{ esc_url($account_url) }}">{{ __('Contul meu', 'sage') }}</a>
    <span class="sep" aria-hidden="true">›</span>
    <span class="here">{{ $endpoint_label }}</span>
  @else
    <span class="here">{{ __('Contul meu', 'sage') }}</span>
  @endif
</nav>
