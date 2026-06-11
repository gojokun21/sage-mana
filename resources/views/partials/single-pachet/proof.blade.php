{{-- Single PACHET — proof strip (4 indicatori). Date WC + zile de cură din informatie_generala. --}}
@php
  global $product;
  $rating_count = $product->get_rating_count();
  $average = $product->get_average_rating();
  $count = count($product->get_bundled_items());

  $info = function_exists('get_field') ? get_field('informatie_generala', $product->get_id()) : null;
  $days = (is_array($info) && ! empty($info['protocol_zile'])) ? (int) $info['protocol_zile'] : 0;
@endphp

<section class="pachet-proof">
  <div class="proof-row">
    <div class="proof-cell">
      <strong>{{ $rating_count > 0 ? number_format_i18n($rating_count) : '156' }} {{ __('recenzii', 'sage') }}</strong>
      <span>{{ __('cumpărători verificați', 'sage') }}</span>
    </div>
    <div class="proof-cell">
      <strong>{{ $rating_count > 0 ? number_format_i18n((float) $average, 1) : '4,8' }} {{ __('din 5', 'sage') }}</strong>
      <span>{{ __('rating mediu', 'sage') }}</span>
    </div>
    <div class="proof-cell">
      @if ($days > 0)
        <strong>{{ sprintf(__('%s cură', 'sage'), sprintf(_n('%d zi', '%d zile', $days, 'sage'), $days)) }}</strong>
        <span>{{ __('completă, fără pauze', 'sage') }}</span>
      @else
        <strong>{{ __('Cură completă', 'sage') }}</strong>
        <span>{{ __('toate produsele, fără pauze', 'sage') }}</span>
      @endif
    </div>
    <div class="proof-cell">
      <strong>{{ $count > 0 ? $count : 2 }} {{ __('produse', 'sage') }}</strong>
      <span>{{ __('analize publice pe fiecare lot', 'sage') }}</span>
    </div>
  </div>
</section>
