{{-- Cele mai vândute — CTA final din ACF. --}}
@php
  $titlu = \App\bestseller_field('cta_titlu', __('Vezi catalogul <em>complet.</em>', 'sage'));
  $text = \App\bestseller_field('cta_text', __('Aceste produse sunt doar punctul de plecare — dacă nu te regăsești aici, restul catalogului are altă soluție pentru tine.', 'sage'));
  $btn_text = \App\bestseller_field('cta_btn_text', __('Vezi toate suplimentele', 'sage'));
  $btn_url = \App\bestseller_field('cta_btn_url', '') ?: (get_post_type_archive_link('product') ?: home_url('/shop/'));
@endphp
<section class="cta-final">
  <div class="cta-final-inner">
    <h2>{!! \App\bestseller_kses($titlu) !!}</h2>
    <p>{!! \App\bestseller_kses($text) !!}</p>
    <a class="btn" href="{{ esc_url($btn_url) }}">{{ $btn_text }}
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>
