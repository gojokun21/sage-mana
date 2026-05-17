{{--
  Newsletter strip — rendered globally from layouts/app.blade.php right
  before the footer, so it appears on every page regardless of the
  page-specific template. Embed is populated by Omnisend's external script.

  Any element with `data-cmplz-gate="<category>"` stays hidden until Complianz
  confirms consent for that category. Hide rule lives in resources/css/newsletter.css
  (loaded globally via app.css). Used here and on the blog-newsletter sections
  in category.blade.php / template-blog.blade.php — the Omnisend script
  (omnisnippet1.com) is blocked by Complianz until then (see
  complianz-gdpr-premium/integrations/plugins/omnisend.php), so without this
  gating the section renders with an empty form and looks broken.
--}}

<section class="newsletter-strip" aria-labelledby="newsletter-title" data-cmplz-gate="statistics" style="display:none;">
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
        <div id="mktr-embedded-form-container-69d6271f63f0ebe7de0b825b" class="mktr-embedded-form-container"  style="display: none"></div>
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  var gated = document.querySelectorAll('[data-cmplz-gate]');
  if (!gated.length) return;

  var revealCategory = function (category) {
    gated.forEach(function (el) {
      if (el.getAttribute('data-cmplz-gate') === category) {
        el.removeAttribute('data-cmplz-gate');
      }
    });
  };

  var revealAll = function () {
    gated.forEach(function (el) { el.removeAttribute('data-cmplz-gate'); });
  };

  var revealConsented = function () {
    if (typeof window.cmplz_has_consent !== 'function') return;
    gated.forEach(function (el) {
      var cat = el.getAttribute('data-cmplz-gate');
      if (cat && window.cmplz_has_consent(cat)) el.removeAttribute('data-cmplz-gate');
    });
  };

  document.addEventListener('cmplz_status_change', function (e) {
    var d = e && e.detail;
    if (d && d.value === 'allow') revealCategory(d.category);
  });

  document.addEventListener('cmplz_run_after_all_scripts', revealConsented);

  window.addEventListener('load', function () {
    if (typeof window.cmplz_has_consent !== 'function') revealAll();
    else revealConsented();
  });
})();
</script>
