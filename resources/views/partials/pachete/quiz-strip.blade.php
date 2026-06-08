{{--
  Quiz strip — fundal verde primary, CTA spre quiz onest de 60s. Link spre /quiz/ (sau update după nevoie).
--}}

@php
  $quiz_url = home_url('/quiz/');
@endphp

<section class="quiz-strip" aria-label="{{ esc_attr__('Quiz personalizat', 'sage') }}">
  <div class="quiz-inner">
    <div class="quiz-grid">
      <div class="quiz-left">
        <div class="eyebrow-gold">{{ __('Ghid onest · 60 secunde', 'sage') }}</div>
        <h2>
          {{ __('Nu știi unde să începi?', 'sage') }}
          <em>{{ __('Răspunde la 7 întrebări.', 'sage') }}</em>
        </h2>
        <p>{{ __('60 secunde. Fără email, fără cont. La final primești 1–2 sugestii pe care le poți lua sau ignora. Onest.', 'sage') }}</p>
      </div>

      <div class="quiz-right">
        <a class="quiz-cta" href="{{ esc_url($quiz_url) }}">
          {{ __('Începe testul', 'sage') }}
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
        <div class="quiz-chips">
          <span>{{ __('Anonim', 'sage') }}</span>
          <span>{{ __('60 secunde', 'sage') }}</span>
          <span>{{ __('Fără presiune de cumpărare', 'sage') }}</span>
        </div>
      </div>
    </div>
  </div>
</section>
