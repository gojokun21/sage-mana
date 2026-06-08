{{-- PDP — stiva / pachet recomandat.
     Folosește logica ACF existentă: câmpul `upgrade_pack` (produsul-pachet
     recomandat pentru produsul curent) + beneficiile globale `beneficii_pachete`
     (options). Dacă nu e setat sau pachetul nu e vizibil, secțiunea nu apare.
     Design = mockup `.stiva` / `.pack-card`. --}}
@php
  $pack_field = function_exists('get_field') ? get_field('upgrade_pack', get_the_ID()) : null;
  $pack = null;
  $pack_id = null;
  if ($pack_field) {
      $pack_id = is_object($pack_field) ? $pack_field->ID : (int) $pack_field;
      $pack = function_exists('wc_get_product') ? wc_get_product($pack_id) : null;
  }
@endphp

@if ($pack && $pack->is_visible())
  @php
    $pack_img = wp_get_attachment_image_src(get_post_thumbnail_id($pack_id), 'large');
    $pack_desc = trim(wp_strip_all_tags($pack->get_short_description()));
    $pack_permalink = get_permalink($pack_id);

    // Beneficii pachet (repeater ACF pe pagina de opțiuni) — folosite ca linia
    // de „conținut" sub titlu. Le strângem din beneficiu_1/2/3.
    $pack_benefits = [];
    if (function_exists('have_rows') && have_rows('beneficii_pachete', 'options')) {
        while (have_rows('beneficii_pachete', 'options')) {
            the_row();
            foreach (['beneficiu_1', 'beneficiu_2', 'beneficiu_3'] as $bf) {
                $val = get_sub_field($bf);
                if ($val) {
                    $pack_benefits[] = $val;
                }
            }
        }
    }
  @endphp

  <section class="stiva">
    <div class="stiva-inner">
      <div class="stiva-head">
        <span class="eyebrow">{{ __('Combină inteligent', 'sage') }}</span>
        <h2>{{ __('Pentru un efect mai complet,', 'sage') }} <em>{{ __('alege pachetul.', 'sage') }}</em></h2>
      </div>
      <div class="pack-card">
        <a class="thumb" href="{{ esc_url($pack_permalink) }}" aria-label="{{ esc_attr($pack->get_name()) }}">
          @if ($pack_img)
            <img src="{{ esc_url($pack_img[0]) }}"
                 alt="{{ esc_attr($pack->get_name()) }}"
                 loading="lazy"
                 width="{{ $pack_img[1] }}"
                 height="{{ $pack_img[2] }}">
          @endif
        </a>
        <div>
          <h4><a href="{{ esc_url($pack_permalink) }}">{{ $pack->get_name() }}</a></h4>
          @if ($pack_desc)
            <p class="cap">{{ $pack_desc }}</p>
          @endif
          @if (! empty($pack_benefits))
            <div class="contents">{{ implode(' · ', $pack_benefits) }}</div>
          @endif
        </div>
        <div class="right">
          <span class="pr">{!! $pack->get_price_html() !!}</span>
          <a class="pack-cta" href="{{ esc_url($pack_permalink) }}">{{ __('Vezi pachetul', 'sage') }}
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>
  </section>
@endif
