{{--
  Sticky toolbar — count rezultate + sort dropdown + view toggle.
  Sort: select-ul are atribut form="catalog-filters" ca să fie submit-uit
  împreună cu sidebar form-ul (definit în sidebar.blade.php).
--}}
@php
  global $wp_query;
  $current_page = max(1, (int) get_query_var('paged'));
  $per_page = (int) get_query_var('posts_per_page');
  if ($per_page <= 0) {
      $per_page = (int) wc_get_loop_prop('per_page', 12);
  }
  $found = (int) ($wp_query->found_posts ?? 0);
  $start = $found > 0 ? (($current_page - 1) * $per_page) + 1 : 0;
  $end = min($current_page * $per_page, $found);
  // Mirror WC native loop/orderby.php behaviour, dar cu label-uri RO.
  $orderby_options = apply_filters('woocommerce_catalog_orderby', [
      'menu_order' => __('Recomandate', 'sage'),
      'popularity' => __('Cele mai vândute', 'sage'),
      'rating'     => __('Cele mai bine notate', 'sage'),
      'date'       => __('Cele mai noi', 'sage'),
      'price'      => __('Preț: crescător', 'sage'),
      'price-desc' => __('Preț: descrescător', 'sage'),
  ]);

  $is_search = function_exists('wc_get_loop_prop') && wc_get_loop_prop('is_search');

  if ($is_search) {
      $orderby_options = array_merge(['relevance' => __('Relevanță', 'sage')], $orderby_options);
      unset($orderby_options['menu_order']);
  }

  if (function_exists('wc_review_ratings_enabled') && ! wc_review_ratings_enabled()) {
      unset($orderby_options['rating']);
  }

  $default_orderby = $is_search
      ? 'relevance'
      : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));

  $current_orderby = isset($_GET['orderby'])
      ? sanitize_key(wp_unslash((string) $_GET['orderby']))
      : $default_orderby;

  if (! array_key_exists($current_orderby, $orderby_options)) {
      $current_orderby = $default_orderby;
  }
@endphp

<div class="toolbar">
  <div class="toolbar-inner">
    <div class="count">
      @if ($found > 0)
        {!! sprintf(
          __('Afișez <strong>%1$d–%2$d</strong> din <strong>%3$d</strong> de rezultate', 'sage'),
          $start, $end, $found
        ) !!}
      @else
        {{ __('Niciun rezultat', 'sage') }}
      @endif
    </div>

    <div class="actions">
      <label class="sr-only" for="catalog-orderby">{{ __('Sortează', 'sage') }}</label>
      <select id="catalog-orderby" name="orderby" form="catalog-filters" data-shop-orderby>
        @foreach ($orderby_options as $value => $label)
          <option value="{{ esc_attr($value) }}" @selected($current_orderby === $value)>{{ esc_html($label) }}</option>
        @endforeach
      </select>

      <div class="view-toggle" role="group" aria-label="{{ esc_attr__('Mod afișare', 'sage') }}">
        <button type="button" class="active" data-view="grid" title="{{ esc_attr__('Grid', 'sage') }}" aria-pressed="true">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
          </svg>
        </button>
        <button type="button" data-view="list" title="{{ esc_attr__('Listă', 'sage') }}" aria-pressed="false">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
            <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>
