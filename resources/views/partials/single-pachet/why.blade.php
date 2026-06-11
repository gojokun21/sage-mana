{{-- Single PACHET — „Cum lucrează împreună". ACF (grup pachet, seed `natura:pachet-seed`)
     cu fallback: text static + carduri generate din produsele componente. --}}
@php
  global $product;

  $why_kicker = get_field('pk_why_kicker') ?: __('Cum lucrează împreună', 'sage');
  $why_titlu = get_field('pk_why_titlu') ?: __('Produse complementare, <em>ținute în echilibru.</em>', 'sage');

  $why_prose = array_values(array_filter(array_map(static fn ($p) => $p['text'] ?? '', get_field('pk_why_prose') ?: [])));
  if (empty($why_prose)) {
    $why_prose = [
      __('Acest pachet a fost gândit ca un sistem: fiecare produs acoperă o nevoie distinctă, iar împreună <strong>se potențează reciproc</strong>. Nu e o colecție întâmplătoare — e o cură construită să lucreze pe mai multe planuri în același timp.', 'sage'),
      __('Iei produsele în momentele potrivite ale zilei și lași curei timp să-și facă efectul. Rezultatele se construiesc în săptămâni, nu în zile.', 'sage'),
    ];
  }

  // Produsele componente — pentru imaginile din carduri.
  $components = [];
  foreach ($product->get_bundled_items() as $bi) {
      $p = $bi->get_product();
      if ($p) {
          $components[] = $p;
      }
  }

  // Potrivește cardul ACF cu produsul component după numele scurt din `rol`
  // (partea de după ultimul „·", ex. „Combustibilul · Vita Complete+").
  $match_image = static function (string $role) use ($components): int {
      $pos = mb_strrpos($role, '·');
      $short = trim($pos !== false ? mb_substr($role, $pos + 1) : $role);
      if ($short === '') {
          return 0;
      }
      foreach ($components as $p) {
          if (mb_stripos($p->get_name(), $short) !== false) {
              return (int) $p->get_image_id();
          }
      }
      return 0;
  };

  $acf_cards = collect(get_field('pk_why_cards') ?: [])
    ->map(static fn ($c, $i) => [
      'role' => $c['rol'] ?? '',
      'name' => $c['titlu'] ?? '',
      'desc' => $c['text'] ?? '',
      'variant' => $i % 2 === 0 ? 'a' : 'b',
      'image_id' => $match_image((string) ($c['rol'] ?? '')),
    ])
    ->filter(static fn ($c) => $c['name'] !== '')
    ->values()
    ->all();

  if (! empty($acf_cards)) {
    $cards = $acf_cards;
  } else {
    // Fallback: carduri din produsele componente (roluri generice).
    $roles = [__('Fundația', 'sage'), __('Regenerarea', 'sage'), __('Susținerea', 'sage')];
    $cards = [];
    foreach ($components as $i => $p) {
        $cards[] = [
            'role' => ($roles[$i] ?? __('Susținerea', 'sage')) . ' · ' . $p->get_name(),
            'name' => $p->get_name(),
            'desc' => wp_strip_all_tags($p->get_short_description()),
            'variant' => $i % 2 === 0 ? 'b' : 'a',
            'image_id' => (int) $p->get_image_id(),
        ];
    }
  }
@endphp

<section class="pachet-why">
  <div class="why-inner">
    <div class="why-text">
      <div class="kicker">{{ $why_kicker }}</div>
      <h2>{!! wp_kses($why_titlu, ['em' => [], 'strong' => []]) !!}</h2>
      @foreach ($why_prose as $p)
        <p>{!! wp_kses($p, ['strong' => []]) !!}</p>
      @endforeach
    </div>

    @if (! empty($cards))
      <div class="why-cards">
        @foreach ($cards as $card)
          <div class="why-card">
            <div class="head">
              <div class="ill {{ $card['variant'] }}">
                @if (! empty($card['image_id']))
                  {!! wp_get_attachment_image($card['image_id'], 'woocommerce_gallery_thumbnail', false, [
                    'alt' => $card['name'],
                    'loading' => 'lazy',
                    'decoding' => 'async',
                  ]) !!}
                @endif
              </div>
              <div>
                <div class="role-tag">{{ $card['role'] }}</div>
                <h3>{{ $card['name'] }}</h3>
              </div>
            </div>
            @if ($card['desc'])
              <p class="role-line">{!! wp_kses($card['desc'], ['strong' => []]) !!}</p>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
