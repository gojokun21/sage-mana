{{--
  Mega-meniu „Suplimente” — după mockup `preferinte/Mega-menu - Suplimente.html`.
  Randat în `.mega-menu-wrapper--produse` (header). Tokenii + stilurile sunt în
  resources/css/mega-produse.css (scoped sub `.msup-mega`).

  Date REALE din WooCommerce: coloana „Pe categorie” (product_cat + counts) și
  cardurile featured (produse). Restul vine din ACF options „Meniu” (grup
  group_mega_suplimente) cu fallback pe valorile din mockup.
--}}
@php
  // Helper: permalink după slug de pagină, cu fallback.
  $msup_page = static function (string $slug, string $fb): string {
      $p = get_page_by_path($slug, OBJECT, 'page');
      return $p ? get_permalink($p) : $fb;
  };

  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');

  // --- Coloana 1: categorii reale ---
  $cat_title = \App\msup_field('msup_cat_title', __('Pe categorie', 'sage'));
  $cats = function_exists('get_terms') ? get_terms([
      'taxonomy' => 'product_cat', 'hide_empty' => true,
      'orderby' => 'count', 'order' => 'DESC', 'number' => 10,
  ]) : [];
  $cats = (is_array($cats) && ! is_wp_error($cats)) ? $cats : [];
  $total_products = (int) (wp_count_posts('product')->publish ?? 0);
  $cat_foot = \App\msup_field('msup_cat_foot', __('→ Vezi toate cele {count} de produse', 'sage'));
  $cat_foot = str_replace('{count}', (string) $total_products, $cat_foot);

  // --- Coloana 2: format ---
  $format_title = \App\msup_field('msup_format_title', __('Pe format', 'sage'));
  $formate = \App\msup_field('msup_formate', [
      ['label' => __('Capsule', 'sage'), 'count' => '(3)', 'link' => ''],
      ['label' => __('Lichid · Shots', 'sage'), 'count' => '(4)', 'link' => ''],
      ['label' => __('Pudră · Proteine', 'sage'), 'count' => '(3)', 'link' => ''],
      ['label' => __('Jeleuri', 'sage'), 'count' => '(1)', 'link' => ''],
      ['label' => __('Pachete', 'sage'), 'count' => '(9)', 'link' => ''],
  ]);
  $format_disc = \App\msup_field('msup_format_disclaimer', __('Filtrele se combină în catalog.', 'sage'));

  // --- Coloana 3: quick links ---
  $quick_title = \App\msup_field('msup_quick_title', __('Quick links', 'sage'));
  $quick = \App\msup_field('msup_quick', [
      ['label' => __('Cele mai vândute', 'sage'), 'link' => $msup_page('cele-mai-vandute', $shop_url), 'badge' => ''],
      ['label' => __('Noutăți', 'sage'), 'link' => $msup_page('noutati-in-curand', $shop_url), 'badge' => __('Nou', 'sage')],
      ['label' => __('Sub 200 lei', 'sage'), 'link' => $msup_page('sub-200-lei', $shop_url), 'badge' => ''],
      ['label' => __('Pachete sub 400 lei', 'sage'), 'link' => home_url('/pachete/'), 'badge' => ''],
      ['label' => __('Cum aleg suplimentul potrivit?', 'sage'), 'link' => home_url('/test/'), 'badge' => ''],
  ]);

  // --- Featured: produse reale ---
  $feat_title = \App\msup_field('msup_featured_title', __('Recomandate de echipa noastră', 'sage'));
  $feat_rows = \App\msup_field('msup_featured', []);
  if (empty($feat_rows)) {
      // Fallback: rezolvă câteva slug-uri confirmate.
      $feat_rows = array_filter(array_map(static function ($slug, $why) {
          $p = get_page_by_path($slug, OBJECT, 'product');
          return $p ? ['produs' => $p->ID, 'why' => $why] : null;
      }, [
          'black-seed-elixir',
          'vita-complete-vegan-shots-500-ml-50-shots',
          'microflora-lemon-shots-500-ml-33-shots',
      ], [
          __('Imunitate & echilibru metabolic', 'sage'),
          __('Multivitamine + energie zilnică', 'sage'),
          __('Probiotice lichide, confort digestiv', 'sage'),
      ]));
  }
  $featured = [];
  foreach ($feat_rows as $row) {
      $pid = (int) ($row['produs'] ?? 0);
      $product = ($pid && function_exists('wc_get_product')) ? wc_get_product($pid) : null;
      if (! $product || ! $product->is_visible()) {
          continue;
      }
      $name = $product->get_name();
      $why = ! empty($row['why']) ? $row['why'] : wp_trim_words(wp_strip_all_tags($product->get_short_description()), 6, '…');
      $img_id = $product->get_image_id();
      $thumb = $img_id ? wp_get_attachment_image($img_id, 'thumbnail', false, ['alt' => esc_attr($name), 'loading' => 'lazy']) : '';
      $initials = '';
      foreach (preg_split('/\s+/', trim($name)) as $w) {
          if ($w !== '' && ctype_alpha(mb_substr($w, 0, 1))) {
              $initials .= mb_strtoupper(mb_substr($w, 0, 1));
          }
          if (mb_strlen($initials) >= 2) {
              break;
          }
      }
      $featured[] = [
          'name' => $name, 'why' => $why, 'thumb' => $thumb, 'initials' => $initials ?: 'MN',
          'price' => wc_price($product->get_price()), 'link' => get_permalink($pid),
      ];
  }

  // --- Bandă jos ---
  $bottom_info = \App\msup_field('msup_bottom_info', __('Transport gratuit peste 299 lei • 90 zile garanție • Plata ramburs', 'sage'));
  $bottom_cta_text = \App\msup_field('msup_bottom_cta_text', __('Vezi catalogul complet', 'sage'));
  $bottom_cta_url = \App\msup_field('msup_bottom_cta_url', '') ?: $shop_url;
@endphp

<div class="msup-mega">
  <div class="msup-grid">

    {{-- Col 1: Pe categorie --}}
    <div class="msup-col">
      <h4>{{ $cat_title }}</h4>
      <ul>
        @foreach ($cats as $cat)
          <li><a href="{{ esc_url(get_term_link($cat)) }}">{{ esc_html($cat->name) }} <span class="count">({{ (int) $cat->count }})</span></a></li>
        @endforeach
      </ul>
      <a class="col-foot-link" href="{{ esc_url($shop_url) }}">{{ $cat_foot }}</a>
    </div>

    {{-- Col 2: Pe format --}}
    <div class="msup-col">
      <h4>{{ $format_title }}</h4>
      <ul>
        @foreach ($formate as $f)
          <li>
            @if (! empty($f['link']))
              <a href="{{ esc_url($f['link']) }}">{{ $f['label'] ?? '' }} @if (! empty($f['count']))<span class="count">{{ $f['count'] }}</span>@endif</a>
            @else
              <span class="row-static">{{ $f['label'] ?? '' }} @if (! empty($f['count']))<span class="count">{{ $f['count'] }}</span>@endif</span>
            @endif
          </li>
        @endforeach
      </ul>
      @if ($format_disc)<p class="col-foot-disclaimer">{{ $format_disc }}</p>@endif
    </div>

    {{-- Col 3: Quick links --}}
    <div class="msup-col">
      <h4>{{ $quick_title }}</h4>
      <ul>
        @foreach ($quick as $q)
          <li><a href="{{ esc_url($q['link'] ?? '#') }}">{{ $q['label'] ?? '' }} @if (! empty($q['badge']))<span class="new">{{ $q['badge'] }}</span>@endif</a></li>
        @endforeach
      </ul>
    </div>

    {{-- Col 4+5: Featured --}}
    <div class="msup-feat">
      <h4>{{ $feat_title }}</h4>
      @foreach ($featured as $f)
        <a class="fcard" href="{{ esc_url($f['link']) }}">
          <div class="thumb">@if ($f['thumb']){!! $f['thumb'] !!}@else{{ $f['initials'] }}@endif</div>
          <div class="info">
            <span class="name">{{ $f['name'] }}</span>
            <span class="why">{{ $f['why'] }}</span>
          </div>
          <div class="right-col">
            <span class="price">{!! $f['price'] !!}</span>
            <span class="see">{{ __('Vezi', 'sage') }} →</span>
          </div>
        </a>
      @endforeach
    </div>
  </div>

  {{-- Bandă jos --}}
  <div class="msup-bottom">
    <div class="info">{{ $bottom_info }}</div>
    <a class="btn-cta" href="{{ esc_url($bottom_cta_url) }}">{{ $bottom_cta_text }}
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</div>
