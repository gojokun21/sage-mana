{{--
  Quote banner — italic family-promise quote, paragraph and signature on
  a dark green background with decorative circles.
--}}

<section class="home-section home-quote-banner" aria-label="{{ esc_attr__('Promisiunea noastră', 'sage') }}">
  <div class="home-quote-banner__inner">
    <span class="home-quote-banner__circle home-quote-banner__circle--tl" aria-hidden="true"></span>
    <span class="home-quote-banner__circle home-quote-banner__circle--br" aria-hidden="true"></span>

    <blockquote class="home-quote-banner__quote">
      &ldquo;{{ __('Selectăm doar ce am da familiei noastre', 'sage') }}&rdquo;
    </blockquote>

    <p class="home-quote-banner__text">
      {!! wp_kses_post(__('Fiecare supliment <strong>Vivens Genetica</strong> este formulat în UE și testat în laborator pentru puritate. Fără compromisuri, fără ingrediente pe care nu le-am consuma noi înșine.', 'sage')) !!}
    </p>

    <p class="home-quote-banner__sign">
      &mdash; {{ __('ECHIPA MÂNA NATURII', 'sage') }}
    </p>
  </div>
</section>
