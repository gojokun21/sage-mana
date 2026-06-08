{{-- Breadcrumb shop. Pe taxonomy product_cat afișează numele categoriei drept curent.
     Pe page templates (ex. template-pachete) acceptă override prin $breadcrumb_here. --}}
@php
  $here = $breadcrumb_here ?? __('Suplimente', 'sage');
  if (! isset($breadcrumb_here)) {
      if (function_exists('is_product_category') && is_product_category()) {
          $term = get_queried_object();
          if ($term && isset($term->name)) {
              $here = $term->name;
          }
      } elseif (function_exists('is_shop') && is_shop()) {
          $shop_id = wc_get_page_id('shop');
          if ($shop_id > 0) {
              $here = get_the_title($shop_id) ?: $here;
          }
      } elseif (function_exists('is_page') && is_page()) {
          $here = get_the_title() ?: $here;
      }
  }
@endphp

<nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
  <div class="breadcrumb-inner">
    <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
    <span class="sep" aria-hidden="true">›</span>
    <span class="here">{{ esc_html($here) }}</span>
  </div>
</nav>
