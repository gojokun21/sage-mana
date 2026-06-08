{{-- Promoții — bară de filtrare (sticky). Filtrare client-side (promotii.js):
     categorie (data-cat) + reducere minimă (data-disc) + sortare. --}}
<div class="filter-bar" id="filtre" data-promo-filters>
  <div class="filter-inner">
    <div class="filter-group">
      <span class="filter-label">{{ __('Categorie', 'sage') }}</span>
      <button type="button" class="chip-filter active" data-filter-cat="all">{{ __('Toate', 'sage') }}</button>
      @foreach ($filter_cats as $slug => $name)
        <button type="button" class="chip-filter" data-filter-cat="{{ esc_attr($slug) }}">{{ esc_html($name) }}</button>
      @endforeach
    </div>

    <div class="filter-sep" aria-hidden="true"></div>

    <div class="filter-group">
      <span class="filter-label">{{ __('Reducere', 'sage') }}</span>
      <button type="button" class="chip-filter active" data-filter-disc="0">{{ __('Orice', 'sage') }}</button>
      <button type="button" class="chip-filter" data-filter-disc="15">15%+</button>
      <button type="button" class="chip-filter" data-filter-disc="20">20%+</button>
      <button type="button" class="chip-filter" data-filter-disc="25">25%+</button>
    </div>

    <div class="filter-sort">
      <span class="filter-label">{{ __('Sortare', 'sage') }}</span>
      <select data-promo-sort aria-label="{{ esc_attr__('Sortare oferte', 'sage') }}">
        <option value="recommended">{{ __('Recomandate', 'sage') }}</option>
        <option value="price-asc">{{ __('Preț crescător', 'sage') }}</option>
        <option value="price-desc">{{ __('Preț descrescător', 'sage') }}</option>
        <option value="discount">{{ __('% Reducere', 'sage') }}</option>
      </select>
    </div>
  </div>
</div>
