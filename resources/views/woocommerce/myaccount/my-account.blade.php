{{--
  My Account page wrapper — navigation sidebar + content.
  @see https://woocommerce.com/document/template-structure/
  @version 3.5.0
--}}

@php defined('ABSPATH') || exit; @endphp

@php do_action('woocommerce_account_navigation') @endphp

<div class="woocommerce-MyAccount-content">
  @php do_action('woocommerce_account_content') @endphp
</div>
