{{--
  Shared shop loop — grid + pagination.
  Used by archive-product (shop) and taxonomy-product_cat (categorii).
--}}

@php
  global $wp_query;
  $current_page = max(1, (int) get_query_var('paged'));
  $per_page = (int) get_query_var('posts_per_page');
  if ($per_page <= 0) {
      $per_page = (int) wc_get_loop_prop('per_page', 12);
  }
  $found = (int) ($wp_query->found_posts ?? 0);
  $max_pages = (int) ($wp_query->max_num_pages ?? 1);
  $start = $found > 0 ? (($current_page - 1) * $per_page) + 1 : 0;
  $end = min($current_page * $per_page, $found);

  // Marker pentru carduri „featured" — folosit de content-product.blade.php.
  // Primele 3 pe pagina 1 primesc CTA solid (verde închis).
  $loop_idx = 0;
@endphp

@if (have_posts())
  <div class="grid">
    @while (have_posts())
      @php
        the_post();
        $GLOBALS['mn_pcard_featured'] = ($current_page === 1 && $loop_idx < 3);
        wc_get_template_part('content', 'product');
        $loop_idx++;
      @endphp
    @endwhile
  </div>

  @php unset($GLOBALS['mn_pcard_featured']); @endphp

  <div class="pagination">
    <span class="info">
      @if ($found > 0)
        {{ sprintf(__('%1$d–%2$d din %3$d afișate', 'sage'), $start, $end, $found) }}
      @endif
    </span>

    @php
      $links = paginate_links([
          'current' => $current_page,
          'total' => $max_pages,
          'type' => 'array',
          'prev_text' => '‹ ' . __('Pagina anterioară', 'sage'),
          'next_text' => __('Pagina următoare', 'sage') . ' ›',
          'end_size' => 1,
          'mid_size' => 1,
      ]);
    @endphp

    @if (is_array($links) && count($links) > 1)
      <nav class="page-nav" aria-label="{{ esc_attr__('Paginare', 'sage') }}">
        @foreach ($links as $link)
          {!! $link !!}
        @endforeach
      </nav>
    @endif
  </div>
@else
  <p class="grid-empty">{{ __('Niciun produs nu corespunde filtrelor selectate.', 'sage') }}</p>
@endif
