{{--
  My Account → Editează adresă. Entry-point WC pentru endpoint-ul `edit-address`:
  când nu se editează nimic ($load_address gol) afișează listingul (my-address),
  altfel formularul real WooCommerce, restilizat după mockup
  `preferinte/Cont - Adrese de livrare.html` (form-group / field-block). Scope `.addr-form-page`.
  Câmpurile, nonce-ul și salvarea rămân native WC. $load_address + $address vin de la WC.
  @see https://woocommerce.com/document/template-structure/
  @version 9.3.0
--}}
@php
  defined('ABSPATH') || exit;

  $load_address = $load_address ?? '';
  $address = $address ?? [];

  $page_title = ('billing' === $load_address)
    ? __('Adresă de facturare', 'sage')
    : __('Adresă de livrare', 'sage');

  do_action('woocommerce_before_edit_account_address_form');
@endphp

@if (! $load_address)
  @php wc_get_template('myaccount/my-address.php') @endphp
@else
  <div class="addr-form-page">

    <div class="page-head">
      <div class="eyebrow">{{ __('Cont · Adrese', 'sage') }}</div>
      <h1>{!! wp_kses_post(apply_filters('woocommerce_my_account_edit_address_title', $page_title, $load_address)) !!}</h1>
      <p>{{ __('Completează datele de mai jos. Se salvează în contul tău și se folosesc automat la checkout.', 'sage') }}</p>
    </div>

    <form method="post" novalidate class="addr-form">
      <div class="woocommerce-address-fields">
        @php do_action("woocommerce_before_edit_address_form_{$load_address}") @endphp

        <div class="woocommerce-address-fields__field-wrapper">
          @php
            foreach ($address as $key => $field) {
              woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value']));
            }
          @endphp
        </div>

        @php do_action("woocommerce_after_edit_address_form_{$load_address}") @endphp

        <p class="addr-form-actions">
          <a class="btn-cancel" href="{{ esc_url(wc_get_endpoint_url('edit-address')) }}">{{ __('Anulează', 'sage') }}</a>
          <button type="submit" class="btn-save" name="save_address" value="{{ esc_attr__('Salvează adresa', 'sage') }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>
            {{ __('Salvează adresa', 'sage') }}
          </button>
          @php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce') @endphp
          <input type="hidden" name="action" value="edit_address" />
        </p>
      </div>
    </form>

  </div>
@endif

@php do_action('woocommerce_after_edit_account_address_form') @endphp
