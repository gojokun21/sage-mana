{{-- Noutăți — hero + disclaimer (text din ACF). {count} = nr. tincturi. --}}
@php
  $eyebrow = \App\noutati_field('hero_eyebrow', __('Noutăți · În curând', 'sage'));
  $titlu = \App\noutati_field('hero_titlu', __('Ce vine <em>în curând.</em>', 'sage'));
  $brand_by = \App\noutati_field('hero_brand_by', __('by Vivens Genetica', 'sage'));
  $lede = \App\noutati_field('hero_lede', __('<strong>{count} tincturi</strong> în dezvoltare, în așteptarea aprobărilor oficiale.', 'sage'));
  $lede = str_replace('{count}', (string) $nt_count, $lede);
  $disc_label = \App\noutati_field('disclaimer_label', __('Important · înainte să citești mai departe', 'sage'));
  $disc_text = \App\noutati_field('disclaimer_text', __('Aceste produse <strong>nu sunt încă pe stoc</strong>. Așteptăm aprobările ANSVSA pentru notificarea suplimentelor cu plante. Dacă vrei să fii anunțat când sunt gata, lasă-ți emailul jos — fără pre-comenzi, fără plăți avansate.', 'sage'));
  $cta_text = \App\noutati_field('hero_cta_text', __('Anunță-mă la lansare', 'sage'));
  $cta_url = \App\noutati_field('hero_cta_url', '') ?: '#notify';
@endphp
<section class="filt-hero">
  <div class="inner">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h1>{!! \App\noutati_kses($titlu) !!}</h1>
    @if ($brand_by)<span class="brand-by">{{ $brand_by }}</span>@endif
    <p class="lede">{!! \App\noutati_kses($lede) !!}</p>
    @if ($disc_text)
      <div class="big-disclaimer">
        @if ($disc_label)<strong>{{ $disc_label }}</strong>@endif
        <span class="inline">{!! \App\noutati_kses($disc_text) !!}</span>
      </div>
    @endif
    @if ($cta_text)
      <a class="hero-cta" href="{{ esc_url($cta_url) }}">{{ $cta_text }}
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14"/><path d="m5 12 7 7 7-7"/></svg>
      </a>
    @endif
  </div>
</section>
