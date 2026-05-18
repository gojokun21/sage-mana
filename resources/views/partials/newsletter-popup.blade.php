{{--
  Welcome popup — first-visit lead capture with -10% coupon.

  Renders only when newsletter_popup_should_render() returns true. Visual
  language mirrors partials/newsletter.blade.php (dark-green gradient band on
  the left, white form on the right) so the popup reads as the same brand
  family rather than a generic third-party widget.
--}}

@php
  if (! \App\newsletter_popup_should_render()) {
      return;
  }
  $logo_src = null;
  if (has_custom_logo()) {
      $logo_id = get_theme_mod('custom_logo');
      $img = wp_get_attachment_image_src($logo_id, 'full');
      if ($img) {
          $logo_src = $img[0];
      }
  }
@endphp

<div class="mn-overlay" id="mnOverlay" aria-hidden="true">
  <article class="mn-popup" role="dialog" aria-modal="true" aria-labelledby="mnPopTitle">

    <aside class="mn-pop-l">
      @if ($logo_src)
        <div class="mn-pop-brand">
          <img src="{{ esc_url($logo_src) }}" alt="{{ esc_attr(get_bloginfo('name')) }}">
        </div>
      @endif

      <div class="mn-pop-tag">
        <span class="pip" aria-hidden="true">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 7L9 18l-5-5"></path></svg>
        </span>
        {{ __('Cadou de bun venit', 'sage') }}
      </div>

      <div class="mn-pop-deal">
        <span class="pct">−10%</span>
        <span class="lbl">{{ __('la prima ta comandă', 'sage') }}</span>
        <span class="micro">{!! __('cod personal &middot; valabil <b>30 de zile</b>', 'sage') !!}</span>
      </div>

      <div class="mn-pop-gift">
        <div class="gift-ico" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
            <path d="M4 4h12a3 3 0 0 1 3 3v13H7a3 3 0 0 1-3-3z"></path>
            <path d="M8 8h7M8 12h7M8 16h5"></path>
          </svg>
        </div>
        <div class="info">
          <b>{{ __('+ Ghid „10 obiceiuri pentru energie"', 'sage') }}</b>
          <span class="desc">{{ __('20 de pagini, scrise de Florina Tibil', 'sage') }}</span>
          <span class="meta">{{ __('Trimis pe email în 60 secunde', 'sage') }}</span>
        </div>
      </div>

      <div class="mn-pop-trust">
        <div class="stack" aria-hidden="true">
          <span class="av a1">AM</span>
          <span class="av a2">DR</span>
          <span class="av a3">+</span>
        </div>
        <span>{!! __('<b>12.847</b> oameni în comunitate', 'sage') !!}</span>
      </div>
    </aside>

    <section class="mn-pop-r" id="mnPopR">
      <button class="mn-pop-close" id="mnCloseBtn" type="button" aria-label="{{ esc_attr__('Închide', 'sage') }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
          <path d="M18 6L6 18M6 6l12 12"></path>
        </svg>
      </button>

      <div class="mn-pop-r-inner">
        <div class="mn-pop-eyebrow">{{ __('Bun venit la', 'sage') }} {{ get_bloginfo('name') }}</div>
        <h2 id="mnPopTitle">
          {{ __('Energie reală.', 'sage') }}<br>
          <span class="accent">{{ __('Cadou de la noi.', 'sage') }}</span>
        </h2>

        <p class="lede">
          {{ __('Lasă-ne emailul tău și îți trimitem ghidul + codul −10% în mai puțin de un minut. Maxim 1–2 emailuri pe săptămână, fără spam.', 'sage') }}
        </p>

        <div class="mn-pop-perks">
          <div class="p">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
              <path d="M20 7L9 18l-5-5"></path>
            </svg>
            <span>{!! __('<b>Ghid</b> · 20 pagini · trimis pe email', 'sage') !!}</span>
          </div>
          <div class="p">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
              <path d="M20 7L9 18l-5-5"></path>
            </svg>
            <span>{!! __('<b>−10%</b> cod personal · valabil 30 de zile', 'sage') !!}</span>
          </div>
          <div class="p">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
              <path d="M20 7L9 18l-5-5"></path>
            </svg>
            <span>{!! __('<b>Newsletter</b> săptămânal · zero spam', 'sage') !!}</span>
          </div>
        </div>

        <form id="mnPopForm" novalidate>
          <div class="mn-pop-fields">
            <div class="mn-pop-field">
              <label for="mnPopName">{{ __('Prenume', 'sage') }}</label>
              <div class="mn-pop-input">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                  <circle cx="12" cy="8" r="4"></circle>
                  <path d="M4 21c0-4.5 3.5-8 8-8s8 3.5 8 8"></path>
                </svg>
                <input id="mnPopName" name="name" type="text" placeholder="{{ esc_attr__('Maria', 'sage') }}" autocomplete="given-name" required>
              </div>
            </div>
            <div class="mn-pop-field">
              <label for="mnPopEmail">{{ __('Email', 'sage') }}</label>
              <div class="mn-pop-input">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                  <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                  <path d="M3 7l9 7 9-7"></path>
                </svg>
                <input id="mnPopEmail" name="email" type="email" placeholder="maria@email.ro" autocomplete="email" required>
              </div>
            </div>
          </div>

          {{-- Honeypot — real users can't see it, bots love filling every input. --}}
          <div class="mn-pop-hp" aria-hidden="true">
            <label for="mnPopWebsite">{{ __('Lasă acest câmp gol', 'sage') }}</label>
            <input id="mnPopWebsite" name="website" type="text" tabindex="-1" autocomplete="off">
          </div>

          <div class="mn-pop-msg" id="mnPopMsg" role="alert" aria-live="polite"></div>

          <button class="mn-pop-submit btn-primary" type="submit">
            <span class="mn-pop-submit-label">{{ __('Vreau ghidul + −10%', 'sage') }}</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
              <path d="M5 12h14M13 6l6 6-6 6"></path>
            </svg>
          </button>
        </form>

        <button class="mn-pop-decline" id="mnDeclineBtn" type="button">
          {{ __('Nu, prefer să plătesc preț întreg', 'sage') }}
        </button>

        <div class="mn-pop-foot">
          <span class="it">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <rect x="5" y="11" width="14" height="9" rx="2"></rect>
              <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
            </svg>
            SSL
          </span>
          <span class="it">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <circle cx="12" cy="12" r="9"></circle>
              <path d="M9 12l2 2 4-4"></path>
            </svg>
            GDPR
          </span>
          <span class="it">{{ __('Fără spam', 'sage') }}</span>
        </div>
      </div>

      <div class="mn-pop-success" id="mnPopSuccess" aria-hidden="true">
        <div class="check-circle" aria-hidden="true">
          <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
            <path d="M20 7L9 18l-5-5"></path>
          </svg>
        </div>
        <h2>
          {{ __('Bun venit', 'sage') }}<br>
          <span class="accent">{{ __('în familie!', 'sage') }}</span>
        </h2>
        <p>{{ __('Ghidul tău a plecat pe email. Folosește codul de mai jos la prima comandă.', 'sage') }}</p>
        <div class="code">
          <button type="button" class="code-value" id="mnPopCodeBtn" aria-label="{{ esc_attr__('Copiază codul', 'sage') }}">
            <span id="mnPopCode"></span>
          </button>
          <span class="copy" id="mnPopCopied">{{ __('copiat ✓', 'sage') }}</span>
        </div>
      </div>
    </section>
  </article>
</div>
