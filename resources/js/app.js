
/* ==================== NAVBAR SCROLL HIDE/SHOW ==================== */
(function () {
  var navbarItems = document.querySelector('.navbar__menu__items');
  if (!navbarItems) return;

  var lastScrollY = window.scrollY;
  var ticking = false;
  var isHidden = false;
  var isAnimating = false;
  var animationTimeout = null;

  var scrollThreshold = 150;
  var hideDistance = 80;
  var showDistance = 50;
  var animationDuration = 350;

  var scrollStartY = window.scrollY;
  var currentDirection = null;

  function setAnimating() {
    isAnimating = true;
    clearTimeout(animationTimeout);
    animationTimeout = setTimeout(function () {
      isAnimating = false;
    }, animationDuration);
  }

  function updateNavbar() {
    var currentScrollY = window.scrollY;

    if (isAnimating) {
      lastScrollY = currentScrollY;
      ticking = false;
      return;
    }

    if (currentScrollY <= 20) {
      if (isHidden) {
        setAnimating();
        navbarItems.classList.remove('is-hidden');
        isHidden = false;
      }
      scrollStartY = currentScrollY;
      currentDirection = null;
      lastScrollY = currentScrollY;
      ticking = false;
      return;
    }

    var direction = currentScrollY > lastScrollY ? 'down' : 'up';

    if (direction !== currentDirection) {
      scrollStartY = lastScrollY;
      currentDirection = direction;
    }

    var scrolledDistance = Math.abs(currentScrollY - scrollStartY);

    if (direction === 'down' && !isHidden && currentScrollY > scrollThreshold && scrolledDistance >= hideDistance) {
      setAnimating();
      navbarItems.classList.add('is-hidden');
      isHidden = true;
      scrollStartY = currentScrollY;
    } else if (direction === 'up' && isHidden && scrolledDistance >= showDistance) {
      setAnimating();
      navbarItems.classList.remove('is-hidden');
      isHidden = false;
      scrollStartY = currentScrollY;
    }

    lastScrollY = currentScrollY;
    ticking = false;
  }

  window.addEventListener(
    'scroll',
    function () {
      if (!ticking) {
        requestAnimationFrame(updateNavbar);
        ticking = true;
      }
    },
    { passive: true },
  );
})();

/* ==================== MOBILE SEARCH POPUP ==================== */
(function () {
  var searchPopup = document.getElementById('mobileSearchPopup');
  var searchTrigger = document.querySelector('.mobile-search-trigger');
  if (!searchPopup || !searchTrigger) return;

  var popupOverlay = searchPopup.querySelector('.mobile-search-popup__overlay');
  var popupClose = searchPopup.querySelector('.mobile-search-popup__close');
  var popupInput = searchPopup.querySelector('.wc-search-input');
  var popupResults = searchPopup.querySelector('.wc-search-results');

  function openSearchPopup() {
    searchPopup.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    setTimeout(function () {
      if (popupInput) popupInput.focus();
    }, 350);
  }

  function closeSearchPopup() {
    searchPopup.classList.remove('is-open');
    document.body.style.overflow = '';
    if (popupInput) popupInput.value = '';
    if (popupResults) popupResults.style.display = 'none';
  }

  searchTrigger.addEventListener('click', openSearchPopup);
  if (popupClose) popupClose.addEventListener('click', closeSearchPopup);
  if (popupOverlay) popupOverlay.addEventListener('click', closeSearchPopup);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && searchPopup.classList.contains('is-open')) {
      closeSearchPopup();
    }
  });
})();

/* ==================== MOBILE DRAWER MENU ==================== */
document.addEventListener('DOMContentLoaded', function () {
  var toggleBtn = document.querySelector('.toogle_menu');
  var drawer = document.getElementById('mobileDrawer');
  var overlay = document.getElementById('mobileMenuOverlay');
  var closeBtn = document.getElementById('closeMobileDrawer');

  if (!drawer || !toggleBtn) return;

  var panels = drawer.querySelector('.mobile-drawer-panels');
  var backBtn = drawer.querySelector('.mobile-back-btn');
  var subTitle = drawer.querySelector('.mobile-sub-title');
  var subList = drawer.querySelector('.mobile-sub-list');

  function openDrawer() {
    drawer.classList.add('is-open');
    if (overlay) overlay.classList.add('is-visible');
    document.body.classList.add('menu-open');
  }

  function closeDrawer() {
    closeSubmenu();
    drawer.classList.remove('is-open');
    if (overlay) overlay.classList.remove('is-visible');
    document.body.classList.remove('menu-open');
  }

  function openSubmenu(title, items) {
    if (subTitle) subTitle.textContent = title;
    if (subList) subList.innerHTML = items;
    if (panels) panels.classList.add('show-sub');
  }

  function closeSubmenu() {
    if (panels) panels.classList.remove('show-sub');
  }

  toggleBtn.addEventListener('click', openDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if (overlay) overlay.addEventListener('click', closeDrawer);
  if (backBtn) backBtn.addEventListener('click', closeSubmenu);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
      if (panels && panels.classList.contains('show-sub')) {
        closeSubmenu();
      } else {
        closeDrawer();
      }
    }
  });

  // Mobile mega menu toggle — clone submenu content into sub panel
  drawer.querySelectorAll('.mobile-mega-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      var parentLi = this.closest('.has-mega-menu-mobile');
      var submenu = parentLi ? parentLi.querySelector('.mobile-mega-submenu') : null;
      var titleLink = parentLi ? parentLi.querySelector('.mobile-menu-item-wrapper > a') : null;

      if (submenu && titleLink) {
        openSubmenu(titleLink.textContent.trim(), submenu.innerHTML);
      }
    });
  });

  // Click anywhere on wrapper triggers toggle
  drawer.querySelectorAll('.mobile-menu-item-wrapper').forEach(function (wrapper) {
    wrapper.addEventListener('click', function (e) {
      if (!e.target.closest('a') && !e.target.closest('.mobile-mega-toggle')) {
        var toggle = this.querySelector('.mobile-mega-toggle');
        if (toggle) toggle.click();
      }
    });
  });

  /* ==================== MEGA MENU IMAGE SWITCHER ==================== */
  document.querySelectorAll('.mega-menu-list a[data-image]').forEach(function (link) {
    link.addEventListener('mouseenter', function () {
      var megaMenu = this.closest('.mega-menu-wrapper');
      if (!megaMenu) return;

      var imgElement = megaMenu.querySelector('.mega-menu-img');
      var imageContainer = megaMenu.querySelector('.mega-menu-image');
      var newImage = this.getAttribute('data-image');
      var categoryName = this.textContent.trim();

      // Switch image with fade
      if (imgElement && newImage) {
        imgElement.style.opacity = '0';
        setTimeout(function () {
          imgElement.src = newImage;
          imgElement.style.opacity = '1';
        }, 150);
      }

      // Add label
      if (imageContainer) {
        var oldLabel = imageContainer.querySelector('.mega-menu-image-label');
        if (oldLabel) oldLabel.remove();

        var label = document.createElement('span');
        label.className = 'mega-menu-image-label';
        label.textContent = categoryName;
        imageContainer.appendChild(label);

        setTimeout(function () {
          label.style.opacity = '1';
          label.style.transform = 'translateY(0)';
        }, 50);
      }
    });

    // Preload images
    var imgSrc = link.getAttribute('data-image');
    if (imgSrc) {
      var preload = new Image();
      preload.src = imgSrc;
    }
  });

  // Remove label when leaving mega menu
  document.querySelectorAll('.has-mega-menu').forEach(function (item) {
    item.addEventListener('mouseleave', function () {
      var label = this.querySelector('.mega-menu-image-label');
      if (label) label.remove();
    });
  });
});

/* ==================== AJAX PRODUCT SEARCH ==================== */
(function () {
  var searchTimer = null;
  var currentXHR = null;
  var searchCache = {};
  var activeIndex = -1;

  document.querySelectorAll('.wc-search-input').forEach(function (input) {
    var searchBox = input.closest('.custom-search');
    var resultsBox = searchBox ? searchBox.querySelector('.wc-search-results') : null;
    if (!resultsBox) return;

    input.addEventListener('keyup', function (e) {
      if (['ArrowDown', 'ArrowUp', 'Enter', 'Escape'].indexOf(e.key) !== -1) return;

      var s = input.value.trim();
      activeIndex = -1;

      if (s.length < 2) {
        resultsBox.style.display = 'none';
        return;
      }

      if (searchCache[s]) {
        resultsBox.innerHTML = searchCache[s];
        resultsBox.style.display = 'block';
        return;
      }

      resultsBox.innerHTML = '<div class="wc-search-loading">Se caută...</div>';
      resultsBox.style.display = 'block';

      clearTimeout(searchTimer);
      searchTimer = setTimeout(function () {
        if (currentXHR && currentXHR.readyState !== 4) {
          currentXHR.abort();
        }

        // Use WordPress AJAX if available, else REST API
        var ajaxUrl =
          typeof wc_search_ajax !== 'undefined' ? wc_search_ajax.ajax_url : '/wp-admin/admin-ajax.php';
        var nonce = typeof wc_search_ajax !== 'undefined' ? wc_search_ajax.nonce : '';

        var formData = new FormData();
        formData.append('action', 'wc_custom_search');
        formData.append('search', s);
        formData.append('nonce', nonce);

        currentXHR = new XMLHttpRequest();
        currentXHR.open('POST', ajaxUrl, true);
        currentXHR.onload = function () {
          if (currentXHR.status === 200) {
            searchCache[s] = currentXHR.responseText;
            resultsBox.innerHTML = currentXHR.responseText;
            resultsBox.style.display = 'block';
            activeIndex = -1;
          }
        };
        currentXHR.onerror = function () {
          resultsBox.innerHTML = '<div class="wc-search-item wc-no-results">Eroare la căutare</div>';
          resultsBox.style.display = 'block';
        };
        currentXHR.send(formData);
      }, 300);
    });

    // Keyboard navigation
    input.addEventListener('keydown', function (e) {
      var items = resultsBox.querySelectorAll('.wc-search-item');
      if (!items.length || resultsBox.style.display === 'none') return;

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeIndex = Math.min(activeIndex + 1, items.length - 1);
        highlightItem(items, activeIndex);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeIndex = Math.max(activeIndex - 1, 0);
        highlightItem(items, activeIndex);
      } else if (e.key === 'Enter' && activeIndex >= 0) {
        e.preventDefault();
        var href = items[activeIndex].getAttribute('href');
        if (href) window.location.href = href;
      }
    });
  });

  function highlightItem(items, index) {
    items.forEach(function (item) {
      item.classList.remove('wc-search-active');
    });
    if (items[index]) items[index].classList.add('wc-search-active');
  }

  // Close on outside click
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.wc-search-input') && !e.target.closest('.wc-search-results')) {
      document.querySelectorAll('.wc-search-results').forEach(function (r) {
        r.style.display = 'none';
      });
      activeIndex = -1;
    }
  });

  // Close on ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.wc-search-results').forEach(function (r) {
        r.style.display = 'none';
      });
      activeIndex = -1;
    }
  });
})();

/* ==================== COMPLIANZ CONSENT REOPEN ==================== */
(function () {
  var link = document.querySelector('.open-consent');
  if (!link) return;

  link.addEventListener('click', function (e) {
    var btn = document.querySelector('.cmplz-manage-consent');
    if (!btn) return;
    e.preventDefault();
    btn.click();
  });
})();

/* ==================== SHOP FILTER TOGGLE (mobile) ==================== */
(function () {
  var toggle = document.getElementById('filterToggle');
  var filter = document.getElementById('shopFilter');
  if (!toggle || !filter) return;

  toggle.addEventListener('click', function () {
    var isOpen = filter.classList.toggle('is-open');
    toggle.classList.toggle('is-active', isOpen);
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  toggle.setAttribute('aria-controls', 'shopFilter');
  toggle.setAttribute('aria-expanded', 'false');
})();

/* ==================== EAGER: TOAST (global) ==================== */
import('./toast.js');

/* ==================== LAZY: MINI CART ==================== */
if (document.getElementById('miniCartDrawer')) {
  import('./mini-cart.js');
}

/* ==================== LAZY: ADD-TO-CART MODAL ==================== */
if (document.getElementById('ml-cart-modal')) {
  import('./cart-modal.js');
}

/* ==================== LAZY: FAVORITES ==================== */
if (document.querySelector('.natura-fav-btn') || document.querySelector('.favorite_item')) {
  import('./favorites.js');
}

/* ==================== LAZY: CART PAGE ==================== */
if (document.querySelector('.woocommerce-cart-form')) {
  import('./cart.js');
}

/* ==================== LAZY: CHECKOUT PAGE ==================== */
if (document.querySelector('.woocommerce-checkout')) {
  import('./checkout.js');
}

/* ==================== LAZY: HOME TEMPLATE ==================== */
if (document.querySelector('.home-template')) {
  import('./home.js');
}

/* ==================== LAZY: ABOUT TEMPLATE ==================== */
if (document.querySelector('.about-template')) {
  import('./about.js');
}

/* ==================== LAZY: BLOG (single + related slider) ==================== */
if (document.querySelector('.blog_slider')) {
  import('./blog.js');
}

/* ==================== LAZY: FOOTER ACCORDION ==================== */
if (document.querySelector('[data-footer-accordion]')) {
  import('./footer.js');
}

/* ==================== LAZY: LOGIN MODAL ==================== */
if (document.querySelector('.open-login-modal')) {
  import('./auth.js');
}

/* ==================== LAZY: QTY STEPPER ==================== */
// Load whenever a stepper is on the page OR mini-cart drawer is present
// (mini-cart renders steppers after the first add, so we need it ready).
if (document.querySelector('.qty-stepper') || document.getElementById('miniCartDrawer')) {
  import('./qty-stepper.js');
}

/* ==================== LAZY: PRODUCT ADD-TO-CART INTERCEPT ==================== */
if (document.querySelector('.mn-atc-btn, form.cart')) {
  import('./product-atc.js');
}

/* ==================== LAZY: SINGLE PRODUCT PAGE ==================== */
if (
  document.querySelector('.product-main-swiper') ||
  document.querySelector('.related_products_slider') ||
  document.querySelector('.upsells_products_slider') ||
  document.querySelector('.custom-product-tabs') ||
  document.getElementById('sticky-price-container')
) {
  import('./single-product.js');
}
