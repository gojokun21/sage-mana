{{-- Cele mai vândute — bridge către quiz (text din ACF). --}}
@php
  $eyebrow = \App\bestseller_field('quiz_eyebrow', __('Ghid onest · 60 secunde', 'sage'));
  $titlu = \App\bestseller_field('quiz_titlu', __('Niciunul nu ți se <em>potrivește exact?</em>', 'sage'));
  $text = \App\bestseller_field('quiz_text', __('Aceste produse sunt cele mai cerute, dar nu sunt singurele potrivite pentru tine. Testul de 60 secunde recomandă exact ce ai nevoie pe baza simptomelor și stilului tău de viață.', 'sage'));
  $cta_text = \App\bestseller_field('quiz_cta_text', __('Începe testul', 'sage'));
  $cta_url = \App\bestseller_field('quiz_cta_url', '') ?: home_url('/test/');
  $micro = \App\bestseller_field('quiz_micro', __('7 întrebări · fără email · anonim', 'sage'));
@endphp
<section class="bridge-quiz">
  <div class="bridge-quiz-inner">
    <div class="left">
      <div class="eyebrow-gold">{{ $eyebrow }}</div>
      <h2>{!! \App\bestseller_kses($titlu) !!}</h2>
      <p>{{ $text }}</p>
    </div>
    <div class="right-side">
      <a class="quiz-cta" href="{{ esc_url($cta_url) }}">{{ $cta_text }}
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
      @if ($micro)<span class="micro">{{ $micro }}</span>@endif
    </div>
  </div>
</section>
