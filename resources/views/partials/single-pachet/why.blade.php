{{-- Single PACHET — „Cum lucrează împreună". Textul stâng e static (mockup);
     cardurile din dreapta folosesc numele REALE ale produselor componente. --}}
@php
  global $product;
  $roles = [__('Fundația', 'sage'), __('Regenerarea', 'sage'), __('Susținerea', 'sage')];
  $cards = [];
  $i = 0;
  foreach ($product->get_bundled_items() as $bi) {
      $p = $bi->get_product();
      if (! $p) {
          continue;
      }
      $cards[] = [
          'role' => $roles[$i] ?? __('Susținerea', 'sage'),
          'name' => $p->get_name(),
          'desc' => wp_strip_all_tags($p->get_short_description()),
          'variant' => $i % 2 === 0 ? 'b' : 'a',
      ];
      $i++;
  }
@endphp

<section class="pachet-why">
  <div class="why-inner">
    <div class="why-text">
      <div class="kicker">{{ __('Cum lucrează împreună', 'sage') }}</div>
      <h2>{{ __('Produse complementare,', 'sage') }} <em>{{ __('ținute în echilibru.', 'sage') }}</em></h2>
      <p>{!! wp_kses(__('Acest pachet a fost gândit ca un sistem: fiecare produs acoperă o nevoie distinctă, iar împreună <strong>se potențează reciproc</strong>. Nu e o colecție întâmplătoare — e o cură construită să lucreze pe mai multe planuri în același timp.', 'sage'), ['strong' => []]) !!}</p>
      <p>{{ __('Iei produsele în momentele potrivite ale zilei și lași curei timp să-și facă efectul. Rezultatele se construiesc în săptămâni, nu în zile.', 'sage') }}</p>
    </div>

    @if (! empty($cards))
      <div class="why-cards">
        @foreach ($cards as $card)
          <div class="why-card">
            <div class="head">
              <div class="ill {{ $card['variant'] }}"></div>
              <div>
                <div class="role-tag">{{ $card['role'] }} · {{ $card['name'] }}</div>
                <h3>{{ $card['name'] }}</h3>
              </div>
            </div>
            @if ($card['desc'])
              <p class="role-line">{{ $card['desc'] }}</p>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
