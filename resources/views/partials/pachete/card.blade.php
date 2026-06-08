{{--
  Card pachet (.pkg-card) — un singur item din grid-ul de pe Hub Pachete.

  Input (toate hardcodate de la apelant):
    $slug    string  slug-ul produsului bundle din WC (ex. 'pachet-imunitate')
    $eyebrow string  text mic deasupra titlului (ex. 'Imunitate & apărare')
    $hook    string  linia italic sub titlu (ex. 'Apărare naturală, energie zilnică.')
    $durata  string  pilula din colțul dreapta-sus (ex. '120 zile')

  Date dinamice din WC:
    - bundle name (h3)
    - permalink (anchor pentru cardul întreg)
    - imaginile produselor incluse (afișate în .pkg-art)
    - lista produselor din bundle (ul.prods)
    - preț + calcul economisire vs. suma item-urilor individuale
--}}

@php
  $product = get_page_by_path($slug, OBJECT, 'product');
  $bundle = $product ? wc_get_product($product->ID) : null;
@endphp

@if ($bundle && $bundle->get_type() === 'bundle')
  @php
    $items_total = 0;
    $items_names = [];

    foreach ($bundle->get_bundled_items() as $bi) {
        $p = $bi->get_product();
        if (! $p) {
            continue;
        }
        $qty = max(1, (int) $bi->get_quantity('min'));
        $items_total += (float) $p->get_price() * $qty;
        $items_names[] = $p->get_name();
    }

    $bundle_price = (float) $bundle->get_price();
    $saving = $items_total - $bundle_price;
    $fmt_lei = static fn ($v) => number_format_i18n((float) $v, 0) . ' lei';
    $count = count($items_names);
    $bundle_image_id = $bundle->get_image_id();
  @endphp

  <a class="pkg-card" href="{{ esc_url(get_permalink($bundle->get_id())) }}">
    <div class="pkg-art">
      @if ($bundle_image_id)
        {!! wp_get_attachment_image($bundle_image_id, 'woocommerce_thumbnail', false, [
          'class' => 'pkg-art-img',
          'alt' => $bundle->get_name(),
          'loading' => 'lazy',
          'decoding' => 'async',
        ]) !!}
      @endif
      @if ($count > 0)
        <span class="count-pill">{{ $count }} {{ $count === 1 ? __('produs', 'sage') : __('produse', 'sage') }}</span>
      @endif
      <span class="duration">{{ $durata }}</span>
    </div>

    <div class="eyeb">{{ $eyebrow }}</div>
    <h3>{{ $bundle->get_name() }}</h3>
    <p class="hook">{{ $hook }}</p>

    @if (! empty($items_names))
      <ul class="prods">
        @foreach (array_slice($items_names, 0, 3) as $name)
          <li>{{ $name }}</li>
        @endforeach
      </ul>
    @endif

    <div class="foot">
      <div class="price-area">
        <span class="price">{{ $fmt_lei($bundle_price) }}</span>
        @if ($saving >= 1)
          <span class="save">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            {{ sprintf(__('Economisești %s', 'sage'), $fmt_lei($saving)) }}
          </span>
        @else
          <span class="save transparent">{{ __('Preț transparent', 'sage') }}</span>
        @endif
      </div>
      <span class="more">
        {{ __('Vezi detalii', 'sage') }}
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
      </span>
    </div>
  </a>
@endif
