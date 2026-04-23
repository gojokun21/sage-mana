<div id="ml-cart-modal" class="modal-overlay" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <div class="modal-alert success">
          Produsul a fost adăugat în coșul dvs.
        </div>
        <button type="button" class="modal-close icon icon-close-modal" aria-label="Close">×</button>
      </div>

      <div class="modal-body">

        <div class="modal-product">
          <div class="modal-product-image">
            <img id="ml-modal-image" src="" alt="">
          </div>

          <div class="modal-product-info">
            <div class="modal-product-title">
              <a id="ml-modal-title" href=""></a>
            </div>
            <div id="ml-modal-packaging" class="modal-product-packaging"></div>
          </div>
        </div>

        <div class="modal-product-bottom">
          <span id="ml-modal-shipping"></span>
        </div>

      </div>

      <div class="modal-footer">
        <div class="modal-buttons">
          <button type="button" class="btn-white-border popup_close" aria-label="Close">Continuǎ cumpǎrǎturile</button>
          <a href="{{ esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cos/')) }}" class="btn-green">Mergi la coș</a>
        </div>
      </div>

    </div>
  </div>
</div>
