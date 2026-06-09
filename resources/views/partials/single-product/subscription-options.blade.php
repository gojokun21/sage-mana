{{-- PDP purchase-type selector: Abonament vs O singură dată.
     Rendered inside the add-to-cart form via the `mn_subs_render_pdp_options`
     action (see app/subscriptions.php). Styled by resources/css/pdp.css. --}}
@php
  $discountLabel = rtrim(rtrim(number_format((float) $sub['discount'], 2), '0'), '.');
@endphp

<div class="sub-options" role="radiogroup" aria-label="{{ __('Tip cumpărare', 'sage') }}">
  <label class="sub-card">
    <input type="radio" class="sub-radio" name="mn_sub_purchase_type" value="subscription" checked
           data-price="{{ esc_attr($sub['sub_price']) }}" />
    <span class="radio" aria-hidden="true"></span>
    <span class="body">
      <span class="top-eyebrow">{{ sprintf(__('Avantaj abonament · −%s%%', 'sage'), $discountLabel) }}</span>
      <strong>{{ __('Abonament', 'sage') }}</strong>
      <span class="det">{{ sprintf(__('Livrare la fiecare %d zile, oprești oricând din cont.', 'sage'), (int) $sub['interval']) }}</span>
    </span>
    <span class="price">{!! $sub['price_html'] !!}<small>{{ __('pe livrare', 'sage') }}</small></span>
  </label>

  <label class="sub-card">
    <input type="radio" class="sub-radio" name="mn_sub_purchase_type" value="one_time"
           data-price="{{ esc_attr($sub['base_price']) }}" />
    <span class="radio" aria-hidden="true"></span>
    <span class="body">
      <strong>{{ __('O singură dată', 'sage') }}</strong>
      <span class="det">{{ __('Fără reînnoire automată.', 'sage') }}</span>
    </span>
    <span class="price">{!! $sub['full_html'] !!}<small>{{ __('o singură dată', 'sage') }}</small></span>
  </label>
</div>
