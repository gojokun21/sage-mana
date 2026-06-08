{{-- Noutăți — CTA final din ACF. --}}
@php
  $titlu = \App\noutati_field('cta_titlu', __('Până atunci, vezi ce ai <em>deja la dispoziție.</em>', 'sage'));
  $text = \App\noutati_field('cta_text', __('<strong>20 de suplimente</strong> și <strong>11 pachete</strong> deja disponibile, cu studii, reviews și analize publice de lot. Probabil ce cauți există deja.', 'sage'));
  $b1_text = \App\noutati_field('cta_primary_text', __('Vezi catalogul', 'sage'));
  $b1_url = \App\noutati_field('cta_primary_url', '') ?: (get_post_type_archive_link('product') ?: home_url('/shop/'));
  $b2_text = \App\noutati_field('cta_outline_text', __('Fă testul de 60 sec', 'sage'));
  $b2_url = \App\noutati_field('cta_outline_url', '') ?: home_url('/test/');
@endphp
<section class="cta-final">
  <div class="cta-final-inner">
    <h2>{!! \App\noutati_kses($titlu) !!}</h2>
    <p>{!! \App\noutati_kses($text) !!}</p>
    <div class="cta-buttons">
      @if ($b1_text)
        <a class="primary" href="{{ esc_url($b1_url) }}">{{ $b1_text }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      @endif
      @if ($b2_text)
        <a class="outline" href="{{ esc_url($b2_url) }}">{{ $b2_text }}
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      @endif
    </div>
  </div>
</section>
