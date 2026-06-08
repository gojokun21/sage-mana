{{--
  Sidebar shop — filtre custom (URL params). Form GET, submit-on-change via JS.
  Bloc-urile cu taxonomii inexistente sunt sărite automat.
--}}
@php
  $categories = \App\shop_category_terms_with_count();
  $formats = \App\shop_attribute_terms(\App\SHOP_TAXONOMY_FORMAT);
  $features = \App\shop_attribute_terms(\App\SHOP_TAXONOMY_FEATURES);
  [$price_min, $price_max] = \App\shop_price_range();

  $sel_cat = \App\shop_param_array('categorie');
  $sel_format = \App\shop_param_array('format');
  $sel_features = \App\shop_param_array('caracteristici');
  $cur_min = isset($_GET['min_price']) ? max($price_min, (int) $_GET['min_price']) : $price_min;
  $cur_max = isset($_GET['max_price']) ? min($price_max, (int) $_GET['max_price']) : $price_max;
  $cur_in_stock = ! empty($_GET['in_stock']);

  // Hardcoded „Pe obiectiv" — link-uri către categorii curate dacă există;
  // altfel se omite link-ul.
  $obiective_slugs = ['energie', 'imunitate', 'focus', 'frumusete', 'detoxifiere'];
  $obiective_labels = [
      'energie' => __('Mai multă energie zilnică', 'sage'),
      'imunitate' => __('Imunitate puternică', 'sage'),
      'focus' => __('Focus mental', 'sage'),
      'frumusete' => __('Frumusețe', 'sage'),
      'detoxifiere' => __('Detoxifiere', 'sage'),
  ];

  $shop_url = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/magazin/');

  // Pe paginile de categorie: form-ul postează pe URL-ul categoriei (filtrele rămân
  // în context) și ascundem filtrul „Categorie" (ești deja într-o categorie).
  $filter_action = $filter_action ?? $shop_url;
  $hide_category_filter = $hide_category_filter ?? false;
@endphp

<aside class="sidebar">
  <form id="catalog-filters" method="get" action="{{ esc_url($filter_action) }}" data-shop-filters>
    <div class="sb-head">
      <h3>{{ __('Filtre', 'sage') }}</h3>
      <a href="{{ esc_url($filter_action) }}">{{ __('Resetează', 'sage') }}</a>
    </div>

    @if (! $hide_category_filter && ! empty($categories))
      <div class="block">
        <h4>{{ __('Categorie', 'sage') }}</h4>
        @foreach ($categories as $cat)
          @php $checked = in_array($cat['slug'], $sel_cat, true); @endphp
          <label class="opt{{ $checked ? ' checked' : '' }}">
            <input type="checkbox" name="categorie[]" value="{{ esc_attr($cat['slug']) }}" @checked($checked) class="opt-input">
            <span class="check" aria-hidden="true"></span>
            <span class="lbl">{{ esc_html($cat['name']) }}</span>
            <span class="ct">{{ (int) $cat['count'] }}</span>
          </label>
        @endforeach
      </div>
    @endif

    @php
      $obiective_links = [];
      foreach ($obiective_slugs as $slug) {
          $term = get_term_by('slug', $slug, 'product_cat');
          if ($term && ! is_wp_error($term)) {
              $obiective_links[] = [
                  'name' => $obiective_labels[$slug] ?? $term->name,
                  'url' => get_term_link($term),
              ];
          }
      }
    @endphp
    @if (! empty($obiective_links))
      <div class="block">
        <h4>{{ __('Pe obiectiv', 'sage') }}</h4>
        @foreach ($obiective_links as $obj)
          <a class="obj-link" href="{{ esc_url($obj['url']) }}">{{ esc_html($obj['name']) }}</a>
        @endforeach
        <a class="obj-extra" href="#obiective">{{ __('+ vezi toate obiectivele', 'sage') }}</a>
      </div>
    @endif

    @if (! empty($formats))
      <div class="block">
        <h4>{{ __('Format', 'sage') }}</h4>
        @foreach ($formats as $f)
          @php $checked = in_array($f['slug'], $sel_format, true); @endphp
          <label class="opt{{ $checked ? ' checked' : '' }}">
            <input type="checkbox" name="format[]" value="{{ esc_attr($f['slug']) }}" @checked($checked) class="opt-input">
            <span class="check" aria-hidden="true"></span>
            <span class="lbl">{{ esc_html($f['name']) }}</span>
          </label>
        @endforeach
      </div>
    @endif

    <div class="block">
      <h4>{{ __('Preț', 'sage') }}</h4>
      <div class="price-slider" data-price-slider data-min="{{ esc_attr($price_min) }}" data-max="{{ esc_attr($price_max) }}">
        <div class="track" aria-hidden="true"></div>
        <div class="fill" aria-hidden="true"></div>
        <input type="range" name="min_price" min="{{ esc_attr($price_min) }}" max="{{ esc_attr($price_max) }}" value="{{ esc_attr($cur_min) }}" step="1" data-handle="min" aria-label="{{ esc_attr__('Preț minim', 'sage') }}">
        <input type="range" name="max_price" min="{{ esc_attr($price_min) }}" max="{{ esc_attr($price_max) }}" value="{{ esc_attr($cur_max) }}" step="1" data-handle="max" aria-label="{{ esc_attr__('Preț maxim', 'sage') }}">
      </div>
      <div class="price-inputs">
        <input type="text" inputmode="numeric" data-price-display="min" value="{{ esc_attr($cur_min) }} {{ __('lei', 'sage') }}" readonly>
        <span>—</span>
        <input type="text" inputmode="numeric" data-price-display="max" value="{{ esc_attr($cur_max) }} {{ __('lei', 'sage') }}" readonly>
      </div>
    </div>

    @if (! empty($features))
      <div class="block">
        <h4>{{ __('Caracteristici', 'sage') }}</h4>
        <div class="feat-chips">
          @foreach ($features as $feat)
            @php $checked = in_array($feat['slug'], $sel_features, true); @endphp
            <label class="chip{{ $checked ? ' active' : '' }}">
              <input type="checkbox" name="caracteristici[]" value="{{ esc_attr($feat['slug']) }}" @checked($checked) class="opt-input">
              <span class="dot" aria-hidden="true"></span>{{ esc_html($feat['name']) }}
            </label>
          @endforeach
        </div>
      </div>
    @endif

    <div class="block">
      <h4>{{ __('Disponibilitate', 'sage') }}</h4>
      <label class="opt{{ $cur_in_stock ? ' checked' : '' }}">
        <input type="checkbox" name="in_stock" value="1" @checked($cur_in_stock) class="opt-input">
        <span class="check" aria-hidden="true"></span>
        <span class="lbl">{{ __('În stoc', 'sage') }}</span>
      </label>
    </div>

    {{-- Fallback submit pentru browsere fără JS (data-shop-filters hijack-uiește schimbările). --}}
    <noscript>
      <button type="submit" class="sidebar-submit">{{ __('Aplică filtre', 'sage') }}</button>
    </noscript>
  </form>
</aside>
