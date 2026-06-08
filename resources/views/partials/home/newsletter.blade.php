{{--
  Newsletter inline (homepage only) — distinct de `partials/newsletter.blade.php`
  care e popup-ul global. Aici e doar markup vizual (form-ul nu trimite încă —
  va fi cablat ulterior la endpoint-ul deja existent).
--}}
<section class="news-inline">
  <div class="news-grid">
    <div class="left">
      <div class="eyebrow eyebrow-gold">{{ __('Jurnal lunar', 'sage') }}</div>
      <h2>
        {{ __('Un email pe lună,', 'sage') }}
        <em>{{ __('niciodată mai des.', 'sage') }}</em>
      </h2>
      <p>{{ __('Ultimele lucruri pe care le-am învățat despre suplimentare, plus dacă apare ceva nou în laborator. Fără vouchere, fără „ultima zi de reducere", fără oferte de team-leader.', 'sage') }}</p>
    </div>
    <div class="right">
      <form class="news-form" method="post" action="#newsletter-subscribe" novalidate>
        <input type="email"
               name="email"
               placeholder="{{ esc_attr__('email@exemplu.com', 'sage') }}"
               aria-label="{{ esc_attr__('Adresa de email', 'sage') }}"
               required>
        <button type="submit">{{ __('Abonează-mă', 'sage') }}</button>
      </form>
      <p class="news-micro">{{ __('Nu pre-bifăm nimic. Te dezabonezi din primul email dacă-ți schimbi părerea.', 'sage') }}</p>
    </div>
  </div>
</section>
