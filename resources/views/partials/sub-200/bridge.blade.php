{{-- Sub 200 lei — bridge către pachete. Text din ACF; cardurile LIVE din WC bundles. --}}
@php
  $eyebrow = \App\sub200_field('bridge_eyebrow', __('Mai multe probleme simultan?', 'sage'));
  $titlu = \App\sub200_field('bridge_titlu', __('Pachetele pot fi <em>mai eficiente.</em>', 'sage'));
  $text = \App\sub200_field('bridge_text', __('Dacă te confrunți cu <strong>2–3 simptome diferite</strong> (de exemplu digestie + imunitate + ten obosit), un pachet la 280–400 lei poate fi mai economic decât 3 produse separate — și produsele lucrează sinergic, nu doar alăturat.', 'sage'));
  $link_text = \App\sub200_field('bridge_link_text', __('Vezi toate pachetele sub 400 lei', 'sage'));
  $link_url = \App\sub200_field('bridge_link_url', '') ?: $pachete_url;
@endphp
<section class="bridge">
  <div class="bridge-inner">
    <div class="bridge-text">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! \App\sub200_kses($titlu) !!}</h2>
      <p>{!! \App\sub200_kses($text) !!}</p>
      <a class="all-link" href="{{ esc_url($link_url) }}">{{ $link_text }}
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
      </a>
    </div>
    @if (! empty($bundles))
      <div class="bridge-cards">
        @foreach ($bundles as $b)
          <a class="bc" href="{{ esc_url($b['link']) }}">
            <div class="ph">{!! $b['img_html'] !!}</div>
            <div class="info">
              <h4>{{ $b['name'] }}</h4>
              @if ($b['desc'])<p>{{ $b['desc'] }}</p>@endif
            </div>
            <span class="pr">{!! $b['price_html'] !!}</span>
          </a>
        @endforeach
      </div>
    @endif
  </div>
</section>
