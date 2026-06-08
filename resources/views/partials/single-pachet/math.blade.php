{{-- Single PACHET — transparență preț. Date REALE: preț individual fiecare
     produs → total → preț pachet → economisire. --}}
@php
  global $product;

  $rows = [];
  $items_total = 0;

  foreach ($product->get_bundled_items() as $bi) {
      $p = $bi->get_product();
      if (! $p) {
          continue;
      }
      $qty = max(1, (int) $bi->get_quantity('min'));
      $line = (float) $p->get_price() * $qty;
      $items_total += $line;
      $rows[] = [
          'name' => ($qty > 1 ? $qty . '× ' : '') . $p->get_name(),
          'price' => $line,
      ];
  }

  $bundle_price = (float) $product->get_price();
  $saving = $items_total - $bundle_price;
@endphp

@if (! empty($rows))
  <section class="pachet-math">
    <div class="math-card">
      <div class="kicker">{{ __('Transparență preț', 'sage') }}</div>
      <h2>{{ __('Cât costă,', 'sage') }} <em>{{ __('exact.', 'sage') }}</em></h2>
      <p class="lede">{{ __('Mai multe produse, un singur preț.', 'sage') }}</p>

      <div class="math-list">
        @foreach ($rows as $row)
          <div class="math-row">
            <span>{{ $row['name'] }}</span>
            <span class="v">{{ number_format_i18n($row['price'], 0) }} {{ __('lei', 'sage') }}</span>
          </div>
        @endforeach

        <div class="math-row total-row">
          <span>{{ __('Total individual', 'sage') }}</span>
          <span class="v">{{ number_format_i18n($items_total, 0) }} {{ __('lei', 'sage') }}</span>
        </div>

        <div class="math-row bundle-row">
          <span class="lbl">{{ __('Preț pachet', 'sage') }}</span>
          <span class="v">{{ number_format_i18n($bundle_price, 0) }} {{ __('lei', 'sage') }}</span>
        </div>
      </div>

      @if ($saving >= 1)
        <div class="save-chip">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
          {{ sprintf(__('Economisești %s lei', 'sage'), number_format_i18n($saving, 0)) }}
        </div>
      @endif

      <div class="foot-line">{{ __('Un singur preț, transparent. Fără costuri ascunse, fără surprize la checkout.', 'sage') }}</div>
    </div>
  </section>
@endif
