{{-- About — testimoniale clienți (grid 2x2). Link-urile către produse duc spre
     catalog (slug-urile exacte de PDP pot fi ajustate ulterior). --}}
@php
  $shop = home_url('/magazin/');
  $testimonials = [
    ['quote' => __('Îl iau zilnic de câteva săptămâni. Mă simt mai echilibrată și nu mai am acele căderi de energie din timpul zilei. Îmi place că e vegan și ușor de integrat în rutină.”', 'sage'), 'name' => 'Andreea P.', 'prod' => 'Vita Complete+ Vegan Shots', 'price' => '184 lei'],
    ['quote' => __('Se bea ușor și nu e greoi. L-am introdus în rutina zilnică și simt o diferență, mai ales la articulații.”', 'sage'), 'name' => 'Maria R.', 'prod' => 'Collagen Joint+ Berry', 'price' => '184 lei'],
    ['quote' => __('O folosesc constant și sunt mulțumit. Se dizolvă bine și simt un plus de forță la antrenamente. Exact ce aveam nevoie.”', 'sage'), 'name' => 'Radu I.', 'prod' => 'Creatine Monohydrate Pro', 'price' => '219 lei'],
    ['quote' => __('Nu sunt genul care ia multe suplimente, dar astea chiar mi-au plăcut. Le folosesc când am nevoie de concentrare extra și nu vreau cafea în plus. În plus, gustul e super bun și faptul că sunt jeleuri le face foarte comod de administrat.”', 'sage'), 'name' => 'Anastasia P.', 'prod' => 'LionFocus B6 Jeleuri', 'price' => '124 lei'],
  ];
@endphp
<section class="testi">
  <div class="testi-inner">
    <div class="testi-head">
      <div class="eyebrow">{{ __('Vocea clienților', 'sage') }}</div>
      <h2>{{ __('Ce spun', 'sage') }} <em>{{ __('clienții noștri.', 'sage') }}</em></h2>
      <p>{{ __('Testimoniale reale, neredactate. Numele sunt scurtate la prenume + inițială din motive de confidențialitate.', 'sage') }}</p>
    </div>
    <div class="testi-grid">
      @foreach ($testimonials as $t)
        <div class="testi-card">
          <span class="stars" aria-label="{{ esc_attr__('5 din 5 stele', 'sage') }}">★★★★★</span>
          <p class="quote">{{ $t['quote'] }}</p>
          <div class="meta">
            <div class="person">
              <span class="name">{{ $t['name'] }}</span>
              <span class="prod">{{ $t['prod'] }}</span>
            </div>
            <span class="price-tag">{{ $t['price'] }} <a href="{{ esc_url($shop) }}">{{ __('Vezi produsul →', 'sage') }}</a></span>
          </div>
        </div>
      @endforeach
    </div>
    <p class="testi-disclaimer">{!! wp_kses(__('Aceste testimoniale reflectă <strong>experiența individuală</strong> a clienților. Rezultatele pot varia. Suplimentele alimentare nu înlocuiesc o dietă echilibrată și un stil de viață sănătos.', 'sage'), ['strong' => []]) !!}</p>
  </div>
</section>
