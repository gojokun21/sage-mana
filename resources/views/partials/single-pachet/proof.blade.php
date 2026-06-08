{{-- Single PACHET — proof strip (4 indicatori). Conținut static (mockup). --}}
@php
  global $product;
  $rating_count = $product->get_rating_count();
  $average = $product->get_average_rating();
  $count = count($product->get_bundled_items());
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
      <strong>{{ __('Cură completă', 'sage') }}</strong>
      <span>{{ __('toate produsele, fără pauze', 'sage') }}</span>
    </div>
    <div class="proof-cell">
      <strong>{{ $count > 0 ? $count : 2 }} {{ __('produse', 'sage') }}</strong>
      <span>{{ __('analize publice pe fiecare lot', 'sage') }}</span>
    </div>
  </div>
</section>
