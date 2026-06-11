{{-- Filosofia noastră — strip text. Text din ACF (grup Home) → fallback seed. --}}
<section class="philo">
  <div class="eyebrow">{{ \App\home_field('philo_eyebrow') }}</div>
  <h2>
    {{ \App\home_field('philo_titlu') }}
    <em>{{ \App\home_field('philo_titlu_em') }}</em>
  </h2>
  <p>{{ \App\home_field('philo_text') }}</p>
</section>
