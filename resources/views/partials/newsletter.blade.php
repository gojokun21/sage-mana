{{--
  Newsletter strip — rendered globally from layouts/app.blade.php right
  before the footer, so it appears on every page regardless of the
  page-specific template. Embed is populated by Omnisend's external script.
--}}

<section class="newsletter-strip" aria-labelledby="newsletter-title">
  <div class="container">
    <div class="newsletter-strip__inner">
      <div class="newsletter-strip__intro">
        <h2 id="newsletter-title" class="newsletter-strip__title">
          {{ __('Abonează-te la newsletter', 'sage') }}
        </h2>
        <p class="newsletter-strip__text">
          {{ __('Primește reduceri exclusive, sfaturi de la specialiști și noutăți despre produse naturale, direct pe e-mail.', 'sage') }}
        </p>
      </div>
      <div class="newsletter-strip__form">
        <div id="omnisend-embedded-v2-6a01d923ab28a246322eea14"></div>
      </div>
    </div>
  </div>
</section>
