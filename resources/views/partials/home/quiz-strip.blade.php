{{-- Quiz strip — bară verde cu CTA. Text din ACF (grup Home) → fallback seed. --}}
@php $quiz_url = \App\home_field('quiz_cta_url') ?: home_url('/test/'); @endphp
<section class="quiz-strip" id="test">
  <div class="quiz-grid">
    <div class="left">
      <div class="eyebrow eyebrow-gold">{{ \App\home_field('quiz_eyebrow') }}</div>
      <h2>{{ \App\home_field('quiz_titlu') }} <em>{{ \App\home_field('quiz_titlu_em') }}</em></h2>
      <p>{{ \App\home_field('quiz_text') }}</p>
    </div>
    <div class="right">
      <a class="quiz-cta" href="{{ esc_url($quiz_url) }}">
        {{ \App\home_field('quiz_cta') }}
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
        </svg>
      </a>
      <div class="micro">{{ \App\home_field('quiz_micro') }}</div>
    </div>
  </div>
</section>
