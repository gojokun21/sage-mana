{{--
  Newsletter inline (homepage). Text din ACF (grup Home) → fallback seed.
  Form-ul e doar vizual (se cablează ulterior la endpoint-ul existent).
--}}
<section class="news-inline">
  <div class="news-grid">
    <div class="left">
      <div class="eyebrow eyebrow-gold">{{ \App\home_field('news_eyebrow') }}</div>
      <h2>
        {{ \App\home_field('news_titlu') }}
        <em>{{ \App\home_field('news_titlu_em') }}</em>
      </h2>
      <p>{{ \App\home_field('news_text') }}</p>
    </div>
    <div class="right">
      <form class="news-form" method="post" action="#newsletter-subscribe" novalidate>
        <input type="email"
               name="email"
               placeholder="{{ esc_attr(\App\home_field('news_placeholder')) }}"
               aria-label="{{ esc_attr__('Adresa de email', 'sage') }}"
               required>
        <button type="submit">{{ \App\home_field('news_button') }}</button>
      </form>
      <p class="news-micro">{{ \App\home_field('news_micro') }}</p>
    </div>
  </div>
</section>
