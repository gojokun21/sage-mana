{{--
  Reusable home product slider.

  Vars:
    $id        string       Swiper name (matches HOME_SLIDER_OPTIONS key).
    $title     string       Section heading.
    $products  \WC_Product[]
    $cta_url   string|null  Optional "view all" link under the slider.
    $cta_text  string|null  Optional CTA label.
    $badge     string|null  'promo' enables the "Populare" + "Promoție" badges
                            overlay on each card.
--}}

@php
  $id = $id ?? 'products';
  $title = $title ?? '';
  $products = $products ?? [];
  $cta_url = $cta_url ?? null;
  $cta_text = $cta_text ?? null;
  $badge = $badge ?? null;
@endphp

@php
  // Use a proper WP_Query loop so `the_post()` fires the `the_post` action,
  // which WC hooks into to set `$GLOBALS['product']` for every partial that
  // declares `global $product;` (content-product.blade.php et al).
  $product_ids = array_values(array_filter(array_map(
    fn ($p) => ($p && method_exists($p, 'get_id')) ? $p->get_id() : null,
    $products
  )));

  $product_query = ! empty($product_ids)
    ? new \WP_Query([
        'post_type' => 'product',
        'post__in' => $product_ids,
        'orderby' => 'post__in',
        'posts_per_page' => count($product_ids),
        'post_status' => 'publish',
        'no_found_rows' => true,
        'ignore_sticky_posts' => true,
      ])
    : null;
@endphp

@if ($product_query && $product_query->have_posts())
  <section class="home-section home-products" aria-labelledby="home-{{ $id }}-title">
    @if ($title)
      <div class="home-section__header">
        <h2 id="home-{{ $id }}-title" class="home-section__title">{{ $title }}</h2>
      </div>
    @endif

    <div class="home-slider">
      <div class="home-slider__swiper swiper" data-home-swiper="{{ $id }}">
        <div class="swiper-wrapper">
          @while ($product_query->have_posts())
            @php
              $product_query->the_post();
              $current_product = $GLOBALS['product'] ?? null;
              $is_on_sale = $current_product && $current_product->is_on_sale();
            @endphp

            <div class="swiper-slide home-product-slide{{ $badge ? ' home-product-slide--'.$badge : '' }}">
              @if ($badge === 'promo')
                <div class="home-promo-badges" aria-hidden="true">
                  <span class="home-promo-badge home-promo-badge--popular">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    {{ __('Populare', 'sage') }}
                  </span>
                  @if ($is_on_sale)
                    <span class="home-promo-badge home-promo-badge--sale">
                      <svg width="14" height="14" viewBox="0 0 16 19" fill="currentColor"><path d="M7.90509 0.629167L8.95509 0L9.15509 1.2075C9.47342 3.11417 10.7759 4.88583 12.2126 6.13083C15.0251 8.56917 15.6409 11.2192 14.7701 13.4067C13.9368 15.5 11.8218 16.9333 9.67509 17.1725L9.17842 17.2275C10.4068 16.4767 11.1951 14.7225 10.9201 13.4117C10.6484 12.1233 9.72176 10.9167 7.94176 9.80333L7.04426 9.24333L6.70926 10.2475C6.51176 10.8408 6.17009 11.3175 5.82259 11.8017C5.65592 12.035 5.48676 12.2708 5.33176 12.5217C4.79592 13.3925 4.65426 14.3608 5.00009 15.3217C5.31509 16.195 5.83426 16.8817 6.55009 17.2625L5.74176 17.1725C3.72676 16.9483 2.24759 16.2592 1.28676 15.1067C0.334256 13.9642 0 12.4933 0 10.9275C0 9.46917 0.599256 7.96583 1.30592 6.715C2.13426 5.25 3.21509 4.03333 4.39509 2.85417C4.59926 3.2625 4.58342 3.4275 5.00342 4.08333C5.54883 2.63497 6.57256 1.41631 7.90509 0.629167Z"/></svg>
                      {{ __('Promoție', 'sage') }}
                    </span>
                  @endif
                </div>
              @endif

              @php wc_get_template_part('content', 'product') @endphp
            </div>
          @endwhile

          @php wp_reset_postdata() @endphp
        </div>
      </div>

      <button type="button"
              class="home-slider__btn home-slider__btn--prev"
              aria-label="{{ esc_attr__('Anterior', 'sage') }}"
              data-home-slider-prev="{{ $id }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <button type="button"
              class="home-slider__btn home-slider__btn--next"
              aria-label="{{ esc_attr__('Următorul', 'sage') }}"
              data-home-slider-next="{{ $id }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div class="home-slider__pagination" data-home-slider-pagination="{{ $id }}"></div>
    </div>

    @if ($cta_url && $cta_text)
      <div class="home-section__cta">
        <a href="{{ esc_url($cta_url) }}" class="btn-primary home-section__cta-link">
          {{ $cta_text }}
        </a>
      </div>
    @endif
  </section>
@endif

