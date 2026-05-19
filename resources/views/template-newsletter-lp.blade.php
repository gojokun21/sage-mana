{{--
  Template Name: Landing Page — Newsletter + Ghid
  Description: Conversion-focused landing page used to capture email addresses
  in exchange for the free PDF guide ("10 obiceiuri pentru o energie care
  durează") and a -10% welcome coupon. Reuses the AJAX endpoint
  `natura_popup_subscribe` (see app/newsletter-popup.php) — same form
  contract (name + email + honeypot + nonce), same coupon generation,
  same TheMarketer push.
--}}

@extends('layouts.landing')

@php
  $lp_brand_url   = home_url('/');
  $lp_shop_url    = function_exists('wc_get_page_id') && wc_get_page_id('shop') > 0
      ? get_permalink(wc_get_page_id('shop'))
      : home_url('/magazin/');
  $lp_privacy_url = get_privacy_policy_url() ?: '#';
  $lp_terms_url   = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('terms') : '#';
  $lp_logo_id     = get_theme_mod('custom_logo');
  $lp_signup_id   = 'lp-signup';
@endphp

@section('content')
<div class="newsletter-lp">

  {{-- ─── HEADER ─── --}}
  <header class="lp-header">
    <div class="lp-container lp-row">
      <a class="lp-brand" href="{{ $lp_brand_url }}" aria-label="{{ get_bloginfo('name') }}">
        @if ($lp_logo_id)
          {!! wp_get_attachment_image($lp_logo_id, 'full', false, ['alt' => get_bloginfo('name'), 'class' => 'lp-brand__img']) !!}
        @else
          <span class="lp-brand__text">{{ get_bloginfo('name') }}</span>
        @endif
      </a>
      <div class="lp-header__right">
        <span class="lp-secure">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3l8 3v6c0 5-4 9-8 9s-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg>
          <span>{{ __('Date protejate · GDPR', 'sage') }}</span>
        </span>
        <a href="{{ $lp_shop_url }}">{{ __('Magazin →', 'sage') }}</a>
      </div>
    </div>
  </header>

  {{-- ─── HERO + FORM ─── --}}
  <section class="lp-hero">
    <div class="lp-container">
      <div class="lp-hero__grid">
        <div>
          <div class="lp-hero__tag">
            <span class="lp-hero__pip">★</span>
            {{ __('Ghid gratuit +', 'sage') }} <b>−10%</b> {{ __('la prima comandă', 'sage') }}
          </div>

          <h1>
            {{ __('10 obiceiuri pentru o', 'sage') }}<br>
            <span class="lp-serif">{{ __('energie care durează.', 'sage') }}</span>
          </h1>

          <p class="lp-hero__lede">
            {{ __('Un ghid de 20 de pagini, scris de Florina Tibil. Cele 10 obiceiuri zilnice care îți pot transforma felul în care simți energia, plus cercetarea din spatele fiecăruia.', 'sage') }}
          </p>

          <div class="lp-hero__bullets">
            @foreach ([
              ['<b>Ghid de 20 de pagini</b>, format A4 mobile-friendly, trimis pe email'],
              ['<b>Cod personal de −10%</b>, valabil 30 de zile la prima comandă'],
              ['<b>Newsletter editorial</b>, 1 sau 2 emailuri pe săptămână, fără spam'],
            ] as $bullet)
              <div class="lp-bullet">
                <span class="lp-bullet__ic" aria-hidden="true">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 7L9 18l-5-5"/></svg>
                </span>
                <span>{!! $bullet[0] !!}</span>
              </div>
            @endforeach
          </div>

          <div class="lp-hero__trust">
            <div class="lp-stack" aria-hidden="true">
              <span class="lp-av lp-av--1">AM</span>
              <span class="lp-av lp-av--2">DR</span>
              <span class="lp-av lp-av--3">IO</span>
              <span class="lp-av lp-av--4">+</span>
            </div>
            <div><b>12.847 {{ __('oameni', 'sage') }}</b> &middot; {{ __('au descărcat ghidul deja', 'sage') }}</div>
            <span class="lp-sep" aria-hidden="true">|</span>
            <span class="lp-stars" aria-label="5 din 5 stele">★★★★★</span>
            <span><b>4.9</b> / 5 · 847 {{ __('recenzii verificate', 'sage') }}</span>
          </div>
        </div>

        {{-- Form card --}}
        <aside class="lp-form-card" id="{{ $lp_signup_id }}">
          <span class="lp-form-card__ribbon">{{ __('Acces instant · 100% gratuit', 'sage') }}</span>

          <div class="lp-form-card__eyebrow">{{ __('Înscrie-te în 20 secunde', 'sage') }}</div>
          <h2>{{ __('Primește ghidul +', 'sage') }} <span class="lp-serif">{{ __('codul −10%', 'sage') }}</span> {{ __('pe email.', 'sage') }}</h2>
          <p class="lp-form-card__sub">
            {{ __('Îți trimitem ghidul pe email în mai puțin de un minut. Plus codul tău personal de reducere, valabil 30 de zile.', 'sage') }}
          </p>

          <div class="lp-perks-mini">
            @foreach ([
              '<b>Cadou,</b> ghidul „10 obiceiuri”, 20 de pagini',
              '<b>Cod de −10%</b> personal la prima comandă',
              '<b>Newsletter</b> săptămânal de la Florina și echipa noastră',
            ] as $perk)
              <div class="lp-perks-mini__p">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M20 7L9 18l-5-5"/></svg>
                <span>{!! $perk !!}</span>
              </div>
            @endforeach
          </div>

          <form class="lp-form" id="lpForm" novalidate>
            <div class="lp-form__fields">
              <div class="lp-field">
                <label for="lpName">{{ __('Prenume', 'sage') }}</label>
                <div class="lp-input-wrap">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.5 3.5-8 8-8s8 3.5 8 8"/></svg>
                  <input id="lpName" name="name" type="text" placeholder="Maria" autocomplete="given-name" required>
                </div>
              </div>
              <div class="lp-field">
                <label for="lpEmail">{{ __('Adresă de email', 'sage') }}</label>
                <div class="lp-input-wrap">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 7 9-7"/></svg>
                  <input id="lpEmail" name="email" type="email" placeholder="maria@email.ro" autocomplete="email" required>
                </div>
              </div>
            </div>

            {{-- Honeypot — same field name as the popup so server-side check matches --}}
            <div class="lp-honeypot" aria-hidden="true">
              <label for="lpWebsite">Website</label>
              <input id="lpWebsite" name="website" type="text" tabindex="-1" autocomplete="off">
            </div>

            <div class="lp-check">
              <input type="checkbox" id="lpAgree" checked required>
              <label for="lpAgree">
                {{ __('Sunt de acord cu', 'sage') }} <a href="{{ $lp_privacy_url }}">{{ __('politica de confidențialitate', 'sage') }}</a>
                {{ __('și să primesc newsletter-ul', 'sage') }} {{ get_bloginfo('name') }}. {{ __('Mă pot dezabona oricând.', 'sage') }}
              </label>
            </div>

            <button class="lp-submit" type="submit">
              <span class="lp-submit__label">{{ __('Vreau ghidul + codul −10%', 'sage') }}</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>

            <div class="lp-form__msg" id="lpFormMsg" role="status" aria-live="polite"></div>
          </form>

          {{-- Success state, swapped in by JS on subscribe.
               Codul -10% e livrat prin emailul de welcome trimis de TheMarketer,
               nu il afisam inline ca sa pastram o singura sursa de adevar. --}}
          <div class="lp-form-card__success" id="lpSuccess" hidden>
            <div class="lp-success__check" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 7L9 18l-5-5"/></svg>
            </div>
            <h3>{{ __('Verifică-ți emailul!', 'sage') }}</h3>
            <p>{{ __('Ți-am trimis ghidul și codul tău personal de −10% pe adresa indicată. Verifică și folderul „Promoții” sau „Spam” dacă nu îl vezi imediat.', 'sage') }}</p>
            <a href="{{ $lp_shop_url }}" class="lp-btn lp-btn--ghost">{{ __('Mergi în magazin →', 'sage') }}</a>
          </div>

          <div class="lp-secure-row">
            <span class="lp-secure-row__it">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
              <b>SSL</b> {{ __('securizat', 'sage') }}
            </span>
            <span class="lp-secure-row__it">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 8l9 6 9-6"/><rect x="3" y="6" width="18" height="14" rx="2"/></svg>
              {{ __('Fără spam, garantat', 'sage') }}
            </span>
            <span class="lp-secure-row__it">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/></svg>
              <b>GDPR</b> {{ __('conform', 'sage') }}
            </span>
          </div>
        </aside>
      </div>
    </div>
  </section>

  {{-- ─── WHAT YOU GET ─── --}}
  <section class="lp-section">
    <div class="lp-container">
      <div class="lp-sec-head">
        <span class="lp-eyebrow">{{ __('ce primești în comunitate', 'sage') }}</span>
        <h2>{{ __('Câteva lucruri mici,', 'sage') }}<br><span class="lp-serif">{{ __('alese cu grijă.', 'sage') }}</span></h2>
        <p>{{ __('Maxim 2 emailuri pe săptămână. Te poți dezabona oricând, dintr-un singur click, fără să dai explicații.', 'sage') }}</p>
      </div>

      <div class="lp-perks">
        @php
          $lp_perks = [
            ['num' => '01', 'tone' => '',     'title' => 'Ghiduri lunare exclusive',     'desc' => 'Articole de nutriție de la echipa noastră, scrise pe înțelesul tău, fără jargon științific.',          'icon' => '<path d="M4 4h14a2 2 0 0 1 2 2v14H6a2 2 0 0 1-2-2z"/><path d="M8 8h8M8 12h8M8 16h5"/>'],
            ['num' => '02', 'tone' => 'gold', 'title' => 'Oferte înaintea tuturor',      'desc' => 'Acces prioritar la promoții și produse noi, înainte să apară pe site. Membri primii.',               'icon' => '<path d="M12 2l3 7 7 .5-5 5L18 22l-6-4-6 4 1-7.5L2 9.5l7-.5z"/>'],
            ['num' => '03', 'tone' => 'lime', 'title' => 'Sfaturi de nutriție naturală', 'desc' => 'Idei mici care se potrivesc într-o zi obișnuită, fără diete drastice, fără reguli rigide.',            'icon' => '<path d="M12 2C9 5 6 8 6 13a6 6 0 0 0 12 0c0-5-3-8-6-11z"/>'],
            ['num' => '04', 'tone' => 'sage', 'title' => 'Confidențialitate totală',     'desc' => 'Datele tale rămân la noi, fără partajări terțe, fără remarketing intruziv. Doar emailuri utile.',      'icon' => '<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>'],
          ];
        @endphp
        @foreach ($lp_perks as $perk)
          <article class="lp-perk" @if ($perk['tone']) data-tone="{{ $perk['tone'] }}" @endif>
            <span class="lp-perk__num">{{ $perk['num'] }}</span>
            <div class="lp-perk__ico" aria-hidden="true">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $perk['icon'] !!}</svg>
            </div>
            <h3>{{ __($perk['title'], 'sage') }}</h3>
            <p>{{ __($perk['desc'], 'sage') }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ─── GUIDE PREVIEW ─── --}}
  <section class="lp-section lp-section--tight">
    <div class="lp-container">
      <article class="lp-preview">
        <div>
          <span class="lp-eyebrow">{{ __('ce găsești în ghid', 'sage') }}</span>
          <h2>{{ __('10 obiceiuri simple,', 'sage') }}<br><span class="lp-serif">{{ __('aplicabile chiar de azi.', 'sage') }}</span></h2>
          <p>{{ __('Nu un manual de teorie. Un protocol pe care Florina îl folosește zilnic, cu pași mici, sustenabili, fără reguli inutile.', 'sage') }}</p>

          <div class="lp-toc">
            @foreach ([
              ['n' => '1', 'txt' => 'Lumina naturală pe pleoape, prima oră',         'more' => 'dimineață'],
              ['n' => '2', 'txt' => 'Apa caldă cu lămâie, înainte de cafea',         'more' => 'dimineață'],
              ['n' => '8', 'txt' => 'Susținerea ficatului, sursa ascunsă a energiei','more' => 'internă'],
              ['n' => '+', 'txt' => 'încă 7 obiceiuri, plus surse științifice',      'more' => '→'],
            ] as $item)
              <div class="lp-toc__it">
                <span class="lp-toc__n">{{ $item['n'] }}</span>
                <span>{{ __($item['txt'], 'sage') }}</span>
                <span class="lp-toc__more">{{ __($item['more'], 'sage') }}</span>
              </div>
            @endforeach
          </div>

          <a href="#{{ $lp_signup_id }}" class="lp-btn lp-btn--paper">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            {{ __('Primesc ghidul pe email', 'sage') }}
          </a>
        </div>

        <div class="lp-pdf-stack" aria-hidden="true">
          <div class="lp-pdf-mock">
            <div>
              <span class="lp-pdf__brand">{{ get_bloginfo('name') }}</span>
              <div class="lp-pdf__eyebrow">{{ __('Ghid editorial · Energie Naturală', 'sage') }}</div>
              <h3>10 obiceiuri<br>{{ __('pentru o', 'sage') }}<br><em>{{ __('energie care', 'sage') }}</em><br><em>{{ __('durează', 'sage') }}</em></h3>
              <p class="lp-pdf__tag">{{ __('Cum să ai energie reală, constantă, fără stimulanți.', 'sage') }}</p>
              <div class="lp-pdf__stripe"></div>
            </div>
            <div class="lp-pdf__footer">
              <span>v.{{ date('Y') }} · {{ __('Florina & echipa', 'sage') }}</span>
              <span class="lp-pdf__pages">20 pag · PDF</span>
            </div>
          </div>
        </div>
      </article>
    </div>
  </section>

  {{-- ─── AUTHOR ─── --}}
  <section class="lp-section lp-section--tight">
    <div class="lp-container">
      <article class="lp-author">
        <span class="lp-eyebrow">{{ __('scris de', 'sage') }}</span>
        <h3>Florina Tibil</h3>
        <div class="lp-author__role">{{ __('Fondator', 'sage') }} {{ get_bloginfo('name') }}</div>
        <p class="lp-author__quote">
          {{ __('Am scris ghidul ăsta pentru că primesc, în fiecare zi, aceeași întrebare: de ce sunt obosit(ă) chiar dacă dorm 8 ore? Răspunsul are mai multe straturi, iar paginile astea le pun pe toate la un loc.', 'sage') }}
        </p>
        <div class="lp-author__meta">
          <span class="lp-author__it"><span class="lp-author__num">12.847</span> {{ __('cititori în comunitate', 'sage') }}</span>
        </div>
      </article>
    </div>
  </section>

  {{-- ─── TESTIMONIALS ─── --}}
  <section class="lp-section lp-section--tight">
    <div class="lp-container">
      <div class="lp-sec-head">
        <span class="lp-eyebrow">{{ __('ce spun cititorii ghidului', 'sage') }}</span>
        <h2>{{ __('Mai puțină oboseală,', 'sage') }}<br><span class="lp-serif">{{ __('mai multă lumină.', 'sage') }}</span></h2>
      </div>

      <div class="lp-testi-grid">
        @php
          $lp_testi = [
            ['initials' => 'AM', 'name' => 'Ana M.',   'role' => 'marketing manager · 34 ani', 'quote' => 'Am început cu obiceiul nr. 2, apa caldă cu lămâie înainte de cafea. Sună banal, dar după 10 zile nu mai aveam crashul de la 11. Schimbare reală.'],
            ['initials' => 'DR', 'name' => 'Dan R.',   'role' => 'antreprenor · 41 ani',        'quote' => 'Citisem cărți, podcasturi, totul. Ghidul ăsta e singurul care îmi spune ce să fac, nu doar de ce. Douăzeci de pagini pe care le-am recitit de două ori.'],
            ['initials' => 'IP', 'name' => 'Irina P.', 'role' => 'medic dentist · 38 ani',      'quote' => 'Newsletter-ul lor e singurul pe care îl deschid săptămânal. Scrisori scurte, sfaturi mici, recomandări de produse fără insistență. Profesionist.'],
          ];
        @endphp
        @foreach ($lp_testi as $t)
          <article class="lp-testi">
            <span class="lp-stars" aria-label="5 din 5 stele">★★★★★</span>
            <p>„{{ __($t['quote'], 'sage') }}”</p>
            <div class="lp-testi__who">
              <span class="lp-av">{{ $t['initials'] }}</span>
              <div><b>{{ $t['name'] }}</b><small>{{ __($t['role'], 'sage') }}</small></div>
              <span class="lp-testi__verified">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" aria-hidden="true"><path d="M20 7L9 18l-5-5"/></svg>
                {{ __('Verificat', 'sage') }}
              </span>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ─── FAQ ─── --}}
  <section class="lp-section lp-section--tight">
    <div class="lp-container">
      <div class="lp-sec-head">
        <span class="lp-eyebrow">{{ __('întrebări frecvente', 'sage') }}</span>
        <h2>{{ __('Răspunsuri scurte,', 'sage') }}<br><span class="lp-serif">{{ __('fără înflorituri.', 'sage') }}</span></h2>
      </div>

      <div class="lp-faq">
        @php
          $lp_faq = [
            ['q' => 'Chiar e gratuit? Care e captura?',           'a' => 'Da, e 100% gratuit. Singurul lucru pe care îl primim este permisiunea ta de a-ți scrie 1–2 emailuri pe săptămână. Te poți dezabona oricând cu un click. Codul −10% e bonus de bun venit, valabil la prima comandă, fără valoare minimă.', 'open' => true],
            ['q' => 'Cum primesc ghidul după ce mă înscriu?',     'a' => 'În maxim 60 de secunde îți trimitem un email de bun venit cu ghidul atașat și codul tău personal de reducere. Verifică și folderul „Promoții” sau „Spam” dacă nu îl vezi imediat.'],
            ['q' => 'Cât de des îmi scrieți?',                    'a' => 'Maxim 2 emailuri pe săptămână. Unul cu sfaturi scrise de Florina și echipa, al doilea ocazional, cu produse noi sau oferte exclusive. Niciodată mai mult.'],
            ['q' => 'Pot să mă dezabonez?',                       'a' => 'Oricând. Fiecare email are link de dezabonare în josul mesajului, un singur click. Datele tale sunt șterse complet conform GDPR.'],
            ['q' => 'Datele mele sunt în siguranță?',             'a' => 'Da. Stocăm datele în UE, criptat, fără să le partajăm cu terți. Nu folosim email-ul tău pentru remarketing pe alte platforme. Politica de confidențialitate completă e disponibilă oricând.'],
          ];
        @endphp
        @foreach ($lp_faq as $i => $item)
          <details class="lp-faq__item" @if (! empty($item['open'])) open @endif>
            <summary class="lp-faq__q">
              {{ __($item['q'], 'sage') }}
              <span class="lp-faq__pl" aria-hidden="true">+</span>
            </summary>
            <div class="lp-faq__a">{{ __($item['a'], 'sage') }}</div>
          </details>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ─── FINAL CTA ─── --}}
  <section class="lp-section lp-section--tight">
    <div class="lp-container">
      <div class="lp-cta-band">
        <span class="lp-eyebrow lp-eyebrow--on-dark">{{ __('ultimul pas', 'sage') }}</span>
        <h2>{{ __('Începe cu un email.', 'sage') }}<br><span class="lp-serif">{{ __('Restul vine natural.', 'sage') }}</span></h2>
        <p>{{ __('Te alături celor 12.847 de oameni care au ales să primească săptămânal sfaturi mici, dar puternice, plus codul tău de bun venit.', 'sage') }}</p>

        <form class="lp-cta-form" id="lpCtaForm" data-signup="#{{ $lp_signup_id }}" novalidate>
          <input type="email" name="email" placeholder="maria@email.ro" autocomplete="email" aria-label="{{ __('Adresă de email', 'sage') }}" required>
          <button type="submit">{{ __('Primesc ghidul + −10% →', 'sage') }}</button>
        </form>
        <div class="lp-cta-band__note">{{ __('Fără spam, te poți dezabona oricând, GDPR conform', 'sage') }}</div>
      </div>
    </div>
  </section>

  {{-- ─── FOOTER ─── --}}
  <footer class="lp-footer">
    <div class="lp-container lp-row">
      <div>&copy; {{ date('Y') }} {{ get_bloginfo('name') }} · {{ __('Sat Poșta, Comuna Remetea Chioarului nr. 41, România', 'sage') }}</div>
      <div class="lp-footer__links">
        <a href="{{ $lp_privacy_url }}">{{ __('Politica de confidențialitate', 'sage') }}</a>
        <a href="{{ $lp_terms_url }}">{{ __('Termeni & condiții', 'sage') }}</a>
        <a href="{{ home_url('/contact/') }}">{{ __('Contact', 'sage') }}</a>
      </div>
    </div>
  </footer>

  {{-- Success toast --}}
  <div id="lpToast" class="lp-toast" role="status" aria-live="polite">
    <span class="lp-toast__ic" aria-hidden="true">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 7L9 18l-5-5"/></svg>
    </span>
    <span class="lp-toast__msg">{{ __('Înscriere reușită! Verifică email-ul pentru ghid.', 'sage') }}</span>
  </div>
</div>

{{-- LP-scoped AJAX config. The endpoint `natura_popup_subscribe` lives in
     app/newsletter-popup.php and already creates the WC coupon + pushes to
     TheMarketer. We use a dedicated nonce action name so this LP can be
     hardened separately later without touching the welcome popup. --}}
<script>
window.natura_newsletter_lp = {
  ajax_url: {!! wp_json_encode(admin_url('admin-ajax.php')) !!},
  nonce:    {!! wp_json_encode(wp_create_nonce('natura_popup_subscribe')) !!},
  action:   'natura_popup_subscribe',
  i18n: {
    missing:        {!! wp_json_encode(__('Te rugăm să completezi prenumele și emailul.', 'sage')) !!},
    invalid_email:  {!! wp_json_encode(__('Adresa de email nu pare validă.', 'sage')) !!},
    consent:        {!! wp_json_encode(__('Te rugăm să accepți politica de confidențialitate.', 'sage')) !!},
    working:        {!! wp_json_encode(__('Se trimite...', 'sage')) !!},
    error:          {!! wp_json_encode(__('A apărut o eroare. Încearcă din nou într-un minut.', 'sage')) !!},
    toast_success:  {!! wp_json_encode(__('Înscriere reușită! Verifică email-ul pentru ghid.', 'sage')) !!},
  }
};
</script>
@endsection
