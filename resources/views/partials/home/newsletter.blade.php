{{--
  Newsletter inline (homepage). Text din ACF (grup Home) → fallback seed.
  Form-ul trimite la endpoint-ul AJAX existent `natura_popup_subscribe`
  (vezi app/newsletter-popup.php) → TheMarketer add_subscriber. Doar email
  (numele e derivat server-side din local-part). Interceptat în resources/js/home.js.
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
      <form class="news-form"
            method="post"
            action="{{ esc_url(admin_url('admin-ajax.php')) }}"
            data-news-subscribe
            data-nonce="{{ wp_create_nonce('natura_popup_subscribe') }}"
            novalidate>
        <input type="email"
               name="email"
               placeholder="{{ esc_attr(\App\home_field('news_placeholder')) }}"
               aria-label="{{ esc_attr__('Adresa de email', 'sage') }}"
               required>
        {{-- Honeypot: bots fill it, humans don't (matched in the handler). --}}
        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true"
               style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;opacity:0;">
        <button type="submit">{{ \App\home_field('news_button') }}</button>
      </form>
      <p class="news-micro" data-news-status role="status" aria-live="polite">{{ \App\home_field('news_micro') }}</p>
    </div>
  </div>
</section>
