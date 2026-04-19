<header class="navbar__menu">
  <div class="container">
    <div class="main_menu">
      {{-- Logo + Hamburger + Mobile Drawer --}}
      <div class="main-logo">
        <div class="toogle_menu">
          <span></span>
          <span></span>
          <span></span>
        </div>

        <div id="mobileMenuOverlay" class="mobile-menu-overlay"></div>

        {{-- Mobile Drawer --}}
        <div id="mobileDrawer" class="mobile-drawer">
          <div class="mobile-drawer-header">
            <a href="{{ home_url('/') }}" class="mobile-drawer-logo">
              @if (has_custom_logo())
                {!! wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['alt' => get_bloginfo('name')]) !!}
              @else
                <span>{!! $siteName !!}</span>
              @endif
            </a>
            <button id="closeMobileDrawer" class="mobile-drawer-close" aria-label="Închide meniul">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>

          <nav class="mobile-drawer-nav">
            <div class="mobile-drawer-panels">
              <div class="mobile-panel mobile-panel-main is-active">
                <ul class="mobile-nav-list">
                  @php
                    $mobile_items = wp_get_nav_menu_items('primary-menu');
                    if (!$mobile_items) {
                      $locations = get_nav_menu_locations();
                      if (isset($locations['primary_navigation'])) {
                        $mobile_items = wp_get_nav_menu_items($locations['primary_navigation']);
                      }
                    }
                  @endphp

                  @if ($mobile_items)
                    @foreach ($mobile_items as $mobile_item)
                      @php
                        $mobile_mega_type = '';
                        foreach ($mobile_item->classes as $class) {
                          if (strpos($class, 'mega-') === 0) {
                            $mobile_mega_type = str_replace('mega-', '', $class);
                            break;
                          }
                        }
                        $has_mobile_mega = !empty($mobile_mega_type);
                      @endphp

                      @if ($has_mobile_mega)
                        <li class="menu-item has-mega-menu-mobile">
                          <div class="mobile-menu-item-wrapper">
                            <a href="{{ esc_url($mobile_item->url) }}">{{ esc_html($mobile_item->title) }}</a>
                            <button class="mobile-mega-toggle" aria-label="Deschide submeniu">
                              <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                            </button>
                          </div>
                          <ul class="mobile-mega-submenu">
                            @if ($mobile_mega_type === 'pachete' && function_exists('have_rows') && have_rows('pachete', 'options'))
                              @while (have_rows('pachete', 'options')) @php the_row() @endphp
                                @if (have_rows('items'))
                                  @while (have_rows('items')) @php the_row() @endphp
                                    <li><a href="{{ get_sub_field('link') }}">{{ get_sub_field('title') }}</a></li>
                                  @endwhile
                                @endif
                              @endwhile
                            @elseif ($mobile_mega_type === 'produse')
                              @php
                                $product_categories = get_terms([
                                  'taxonomy' => 'product_cat',
                                  'hide_empty' => true,
                                  'parent' => 0,
                                  'orderby' => 'name',
                                  'order' => 'ASC',
                                ]);
                              @endphp
                              @if (!empty($product_categories) && !is_wp_error($product_categories))
                                @foreach ($product_categories as $category)
                                  <li><a href="{{ esc_url(get_term_link($category)) }}">{{ esc_html($category->name) }}</a></li>
                                @endforeach
                              @endif
                            @endif
                          </ul>
                        </li>
                      @else
                        <li class="menu-item">
                          <a href="{{ esc_url($mobile_item->url) }}">{{ esc_html($mobile_item->title) }}</a>
                        </li>
                      @endif
                    @endforeach
                  @endif
                </ul>
              </div>
              <div class="mobile-panel mobile-panel-sub">
                <button class="mobile-back-btn">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span>Înapoi</span>
                </button>
                <h3 class="mobile-sub-title"></h3>
                <ul class="mobile-sub-list"></ul>
              </div>
            </div>
          </nav>

          <div class="mobile-drawer-footer">
            <a href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/contul-meu/') }}" class="mobile-drawer-account">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>{{ is_user_logged_in() ? esc_html(wp_get_current_user()->display_name) : 'Contul meu' }}</span>
            </a>
          </div>
        </div>

        {{-- Desktop Logo --}}
        <a href="{{ home_url('/') }}">
          @if (has_custom_logo())
            @php
              $logo_id = get_theme_mod('custom_logo');
              $logo_src = wp_get_attachment_image_src($logo_id, 'full');
            @endphp
            @if ($logo_src)
              <img class="header_logo" src="{{ esc_url($logo_src[0]) }}" alt="{{ get_bloginfo('name') }}">
            @endif
          @else
            <span class="header_logo">{!! $siteName !!}</span>
          @endif
        </a>
      </div>

      {{-- Desktop Search --}}
      <div class="custom-search" role="search">
        <input type="text" class="wc-search-input" placeholder="Caută un produs..." autocomplete="off" aria-label="Caută un produs" aria-autocomplete="list" aria-controls="wc-desktop-results">
        <button aria-label="Căutare produs" class="wc-search-btn">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.2878 12.2878C12.9236 11.6521 13.4279 10.8973 13.772 10.0667C14.1161 9.23601 14.2931 8.34572 14.2931 7.44662C14.2931 6.54752 14.1161 5.65723 13.772 4.82657C13.4279 3.99591 12.9236 3.24116 12.2878 2.6054C11.6521 1.96964 10.8973 1.46533 10.0667 1.12126C9.23601 0.777188 8.34572 0.600098 7.44662 0.600098C6.54752 0.600098 5.65723 0.777188 4.82657 1.12126C3.99591 1.46533 3.24116 1.96964 2.6054 2.6054C1.32143 3.88937 0.600098 5.63081 0.600098 7.44662C0.600098 9.26243 1.32143 11.0039 2.6054 12.2878C3.88937 13.5718 5.63081 14.2931 7.44662 14.2931C9.26243 14.2931 11.0039 13.5718 12.2878 12.2878ZM12.2878 12.2878L16.6001 16.6001" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <div class="wc-search-results" id="wc-desktop-results" aria-live="polite"></div>
      </div>

      {{-- Right Side Icons --}}
      <div class="menu__left">
        <button class="mobile-search-trigger" aria-label="Căutare">
          <svg width="22" height="22" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.2878 12.2878C12.9236 11.6521 13.4279 10.8973 13.772 10.0667C14.1161 9.23601 14.2931 8.34572 14.2931 7.44662C14.2931 6.54752 14.1161 5.65723 13.772 4.82657C13.4279 3.99591 12.9236 3.24116 12.2878 2.6054C11.6521 1.96964 10.8973 1.46533 10.0667 1.12126C9.23601 0.777188 8.34572 0.600098 7.44662 0.600098C6.54752 0.600098 5.65723 0.777188 4.82657 1.12126C3.99591 1.46533 3.24116 1.96964 2.6054 2.6054C1.32143 3.88937 0.600098 5.63081 0.600098 7.44662C0.600098 9.26243 1.32143 11.0039 2.6054 12.2878C3.88937 13.5718 5.63081 14.2931 7.44662 14.2931C9.26243 14.2931 11.0039 13.5718 12.2878 12.2878ZM12.2878 12.2878L16.6001 16.6001" stroke="black" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        {!! \App\favorites_header_badge() !!}

        <a href="{{ function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cos/') }}"
           class="shopping-cart"
           data-mini-cart-trigger
           aria-label="{{ __('Deschide coșul', 'sage') }}"
           aria-controls="miniCartDrawer"
           aria-expanded="false">
          <span class="cart_icon_wrapper">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 3L2.265 3.088C3.585 3.528 4.245 3.748 4.622 4.272C4.999 4.796 5 5.492 5 6.883V9.5C5 12.328 5 13.743 5.879 14.621C6.757 15.5 8.172 15.5 11 15.5H19" stroke="black" stroke-width="1.5" stroke-linecap="round"/>
              <path d="M5 6H16.45C18.505 6 19.533 6 19.978 6.674C20.422 7.349 20.018 8.293 19.208 10.182L18.779 11.182C18.401 12.064 18.212 12.504 17.837 12.752C17.461 13 16.981 13 16.022 13H5M7.5 18C7.89782 18 8.27936 18.158 8.56066 18.4393C8.84196 18.7206 9 19.1022 9 19.5C9 19.8978 8.84196 20.2794 8.56066 20.5607C8.27936 20.842 7.89782 21 7.5 21C7.10218 21 6.72064 20.842 6.43934 20.5607C6.15804 20.2794 6 19.8978 6 19.5C6 19.1022 6.15804 18.7206 6.43934 18.4393C6.72064 18.158 7.10218 18 7.5 18ZM16.5 18C16.8978 18 17.2794 18.158 17.5607 18.4393C17.842 18.7206 18 19.1022 18 19.5C18 19.8978 17.842 20.2794 17.5607 20.5607C17.2794 20.842 16.8978 21 16.5 21C16.1022 21 15.7206 20.842 15.4393 20.5607C15.158 20.2794 15 19.8978 15 19.5C15 19.1022 15.158 18.7206 15.4393 18.4393C15.7206 18.158 16.1022 18 16.5 18Z" stroke="black" stroke-width="1.5"/>
            </svg>
            <span class="count__cart" data-mini-cart-count>{{ function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0 }}</span>
          </span>
          <span>Coș</span>
        </a>

        @if (is_user_logged_in())
          <a class="auth_item" href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/contul-meu/') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0001 1.25C10.7403 1.25 9.5321 1.75044 8.6413 2.64124C7.7505 3.53204 7.25006 4.74022 7.25006 6C7.25006 7.25978 7.7505 8.46796 8.6413 9.35876C9.5321 10.2496 10.7403 10.75 12.0001 10.75C13.2598 10.75 14.468 10.2496 15.3588 9.35876C16.2496 8.46796 16.7501 7.25978 16.7501 6C16.7501 4.74022 16.2496 3.53204 15.3588 2.64124C14.468 1.75044 13.2598 1.25 12.0001 1.25ZM8.75006 6C8.75006 5.13805 9.09247 4.3114 9.70196 3.7019C10.3115 3.09241 11.1381 2.75 12.0001 2.75C12.862 2.75 13.6887 3.09241 14.2982 3.7019C14.9076 4.3114 15.2501 5.13805 15.2501 6C15.2501 6.86195 14.9076 7.6886 14.2982 8.2981C13.6887 8.90759 12.862 9.25 12.0001 9.25C11.1381 9.25 10.3115 8.90759 9.70196 8.2981C9.09247 7.6886 8.75006 6.86195 8.75006 6ZM12.0001 12.25C9.68706 12.25 7.55506 12.776 5.97606 13.664C4.42006 14.54 3.25006 15.866 3.25006 17.5V17.602C3.24906 18.764 3.24806 20.222 4.52706 21.264C5.15606 21.776 6.03706 22.141 7.22706 22.381C8.41906 22.623 9.97406 22.75 12.0001 22.75C14.0261 22.75 15.5801 22.623 16.7741 22.381C17.9641 22.141 18.8441 21.776 19.4741 21.264C20.7531 20.222 20.7511 18.764 20.7501 17.602V17.5C20.7501 15.866 19.5801 14.54 18.0251 13.664C16.4451 12.776 14.3141 12.25 12.0001 12.25ZM4.75006 17.5C4.75006 16.649 5.37206 15.725 6.71106 14.972C8.02706 14.232 9.89506 13.75 12.0011 13.75C14.1051 13.75 15.9731 14.232 17.2891 14.972C18.6291 15.725 19.2501 16.649 19.2501 17.5C19.2501 18.808 19.2101 19.544 18.5261 20.1C18.1561 20.402 17.5361 20.697 16.4761 20.911C15.4191 21.125 13.9741 21.25 12.0001 21.25C10.0261 21.25 8.58006 21.125 7.52406 20.911C6.46406 20.697 5.84406 20.402 5.47406 20.101C4.79006 19.544 4.75006 18.808 4.75006 17.5Z" fill="black"/>
            </svg>
            <span>{{ esc_html(wp_get_current_user()->display_name) }}</span>
          </a>
        @else
          <a class="auth_item open-login-modal" href="{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/contul-meu/') }}" aria-label="Autentificare">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0001 1.25C10.7403 1.25 9.5321 1.75044 8.6413 2.64124C7.7505 3.53204 7.25006 4.74022 7.25006 6C7.25006 7.25978 7.7505 8.46796 8.6413 9.35876C9.5321 10.2496 10.7403 10.75 12.0001 10.75C13.2598 10.75 14.468 10.2496 15.3588 9.35876C16.2496 8.46796 16.7501 7.25978 16.7501 6C16.7501 4.74022 16.2496 3.53204 15.3588 2.64124C14.468 1.75044 13.2598 1.25 12.0001 1.25ZM8.75006 6C8.75006 5.13805 9.09247 4.3114 9.70196 3.7019C10.3115 3.09241 11.1381 2.75 12.0001 2.75C12.862 2.75 13.6887 3.09241 14.2982 3.7019C14.9076 4.3114 15.2501 5.13805 15.2501 6C15.2501 6.86195 14.9076 7.6886 14.2982 8.2981C13.6887 8.90759 12.862 9.25 12.0001 9.25C11.1381 9.25 10.3115 8.90759 9.70196 8.2981C9.09247 7.6886 8.75006 6.86195 8.75006 6ZM12.0001 12.25C9.68706 12.25 7.55506 12.776 5.97606 13.664C4.42006 14.54 3.25006 15.866 3.25006 17.5V17.602C3.24906 18.764 3.24806 20.222 4.52706 21.264C5.15606 21.776 6.03706 22.141 7.22706 22.381C8.41906 22.623 9.97406 22.75 12.0001 22.75C14.0261 22.75 15.5801 22.623 16.7741 22.381C17.9641 22.141 18.8441 21.776 19.4741 21.264C20.7531 20.222 20.7511 18.764 20.7501 17.602V17.5C20.7501 15.866 19.5801 14.54 18.0251 13.664C16.4451 12.776 14.3141 12.25 12.0001 12.25ZM4.75006 17.5C4.75006 16.649 5.37206 15.725 6.71106 14.972C8.02706 14.232 9.89506 13.75 12.0011 13.75C14.1051 13.75 15.9731 14.232 17.2891 14.972C18.6291 15.725 19.2501 16.649 19.2501 17.5C19.2501 18.808 19.2101 19.544 18.5261 20.1C18.1561 20.402 17.5361 20.697 16.4761 20.911C15.4191 21.125 13.9741 21.25 12.0001 21.25C10.0261 21.25 8.58006 21.125 7.52406 20.911C6.46406 20.697 5.84406 20.402 5.47406 20.101C4.79006 19.544 4.75006 18.808 4.75006 17.5Z" fill="black"/>
            </svg>
            <span>Autentificare</span>
          </a>
        @endif
      </div>
    </div>

    {{-- Desktop Navigation --}}
    <div class="navbar__menu__items">
      @php
        $menu_items = wp_get_nav_menu_items('primary-menu');
        if (!$menu_items) {
          $locations = get_nav_menu_locations();
          if (isset($locations['primary_navigation'])) {
            $menu_items = wp_get_nav_menu_items($locations['primary_navigation']);
          }
        }
      @endphp

      <ul class="main-nav-list">
        @if ($menu_items)
          @foreach ($menu_items as $item)
            @php
              $classes = $item->classes;
              $mega_type = '';
              foreach ($classes as $class) {
                if (strpos($class, 'mega-') === 0) {
                  $mega_type = str_replace('mega-', '', $class);
                  break;
                }
              }
              $has_mega = !empty($mega_type);
              $li_classes = array_filter($classes);
              $li_classes[] = 'menu-item';
              if ($has_mega) $li_classes[] = 'has-mega-menu';

              // Detect active menu item
              $is_active = false;
              if ($item->object === 'page' && is_page((int) $item->object_id)) {
                $is_active = true;
              } elseif (function_exists('wc_get_page_id') && $item->object === 'page' && (int) $item->object_id === wc_get_page_id('shop') && (is_shop() || is_product() || is_product_category() || is_product_tag())) {
                $is_active = true;
              } elseif ($item->type === 'custom' && trailingslashit($item->url) === trailingslashit(home_url($_SERVER['REQUEST_URI'] ?? ''))) {
                $is_active = true;
              }
              if ($is_active) $li_classes[] = 'current-menu-item';
            @endphp

            <li class="{{ implode(' ', $li_classes) }}">
              <a href="{{ esc_url($item->url) }}">
                {{ esc_html($item->title) }}
                @if ($has_mega)
                  <div class="mega-arrow">
                    <svg width="15" height="15" viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                @endif
              </a>

              @if ($has_mega)
                <div class="mega-menu-wrapper">
                  <div class="mega-menu-container">
                    <div class="mega-menu-categories">
                      @if (function_exists('have_rows') && have_rows('pachete', 'options'))
                        @while (have_rows('pachete', 'options')) @php the_row() @endphp
                          <ul class="mega-menu-list">
                            @if ($mega_type === 'pachete' && have_rows('items'))
                              @while (have_rows('items')) @php the_row() @endphp
                                <li><a href="{{ get_sub_field('link') }}" data-image="{{ get_sub_field('image') }}">{{ get_sub_field('title') }}</a></li>
                              @endwhile
                            @elseif ($mega_type === 'produse')
                              @php
                                $cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0, 'orderby' => 'name']);
                              @endphp
                              @if (!empty($cats) && !is_wp_error($cats))
                                @foreach ($cats as $cat)
                                  @php $thumb_id = get_term_meta($cat->term_id, 'thumbnail_id', true) @endphp
                                  <li><a href="{{ esc_url(get_term_link($cat)) }}" data-image="{{ $thumb_id ? wp_get_attachment_url($thumb_id) : '' }}">{{ esc_html($cat->name) }}</a></li>
                                @endforeach
                              @endif
                            @endif
                          </ul>
                        @endwhile
                      @endif
                    </div>
                    <div class="mega-menu-image">
                      @if (function_exists('have_rows') && have_rows('mega_menu_image', 'options'))
                        @while (have_rows('mega_menu_image', 'options')) @php the_row() @endphp
                          <img src="{{ get_sub_field('image') }}" alt="{{ esc_attr($item->title) }}" class="mega-menu-img">
                        @endwhile
                      @else
                        <img src="" alt="{{ esc_attr($item->title) }}" class="mega-menu-img">
                      @endif
                    </div>
                  </div>
                </div>
              @endif
            </li>
          @endforeach
        @endif
      </ul>
    </div>
  </div>

  {{-- Mini Cart Drawer moved to layouts/app.blade.php @ body root —
       `.navbar__menu` is `position: sticky; z-index: 999` which creates a
       stacking context that would cap the drawer at 999 vs sticky_price_container's
       1000, hiding the drawer's checkout button behind it on product pages. --}}

  {{-- Mobile Search Popup --}}
  <div class="mobile-search-popup" id="mobileSearchPopup">
    <div class="mobile-search-popup__overlay"></div>
    <div class="mobile-search-popup__content">
      <div class="mobile-search-popup__header">
        <div class="custom-search" role="search">
          <input type="text" class="wc-search-input" placeholder="Caută un produs..." autocomplete="off" aria-label="Caută un produs" aria-autocomplete="list" aria-controls="wc-popup-results">
          <button aria-label="Căutare produs" class="wc-search-btn">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12.2878 12.2878C12.9236 11.6521 13.4279 10.8973 13.772 10.0667C14.1161 9.23601 14.2931 8.34572 14.2931 7.44662C14.2931 6.54752 14.1161 5.65723 13.772 4.82657C13.4279 3.99591 12.9236 3.24116 12.2878 2.6054C11.6521 1.96964 10.8973 1.46533 10.0667 1.12126C9.23601 0.777188 8.34572 0.600098 7.44662 0.600098C6.54752 0.600098 5.65723 0.777188 4.82657 1.12126C3.99591 1.46533 3.24116 1.96964 2.6054 2.6054C1.32143 3.88937 0.600098 5.63081 0.600098 7.44662C0.600098 9.26243 1.32143 11.0039 2.6054 12.2878C3.88937 13.5718 5.63081 14.2931 7.44662 14.2931C9.26243 14.2931 11.0039 13.5718 12.2878 12.2878ZM12.2878 12.2878L16.6001 16.6001" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <div class="wc-search-results" id="wc-popup-results" aria-live="polite"></div>
        </div>
        <button class="mobile-search-popup__close" aria-label="Închide căutarea">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</header>
