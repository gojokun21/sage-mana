{{-- Sub 200 lei — CTA final din ACF. Token {count} → nr. produse. --}}
@php
  $titlu = \App\sub200_field('cta_titlu', __('Încă nu știi <em>ce ți se potrivește?</em>', 'sage'));
  $text = \App\sub200_field('cta_text', __('Testul de 60 secunde îți recomandă onest care din cele {count} produse e cel mai potrivit — sau dacă ai nevoie de un pachet în loc.', 'sage'));
  $text = str_replace('{count}', (string) $product_count, $text);
  $btn_text = \App\sub200_field('cta_btn_text', __('Începe testul', 'sage'));
  $btn_url = \App\sub200_field('cta_btn_url', '') ?: home_url('/test/');
@endphp
<section class="cta-final">
  <div class="cta-final-inner">
    <h2>{!! \App\sub200_kses($titlu) !!}</h2>
    <p>{{ $text }}</p>
    <a class="btn" href="{{ esc_url($btn_url) }}">{{ $btn_text }}
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </a>
  </div>
</section>
