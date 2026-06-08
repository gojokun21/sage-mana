{{--
  Hero secțiunea Hub Pachete — 2 coloane (text + ilustrație decorativă cu 3 sticluțe stivuite).
  Toate textele hardcodate per discuție cu user-ul.
--}}

<section class="hero" aria-label="{{ esc_attr__('Index pachete', 'sage') }}">
  <div class="hero-inner">
    <div class="hero-grid">
      <div class="hero-left">
        <div class="eyebrow">{{ __('Index · 11 pachete gândite', 'sage') }}</div>
        <h1>
          {{ __('Pachete.', 'sage') }}
          <em>{{ __('Combinații care lucrează împreună.', 'sage') }}</em>
        </h1>
        <p class="subline">{{ __('Nu colecții random. Sinergii calibrate.', 'sage') }}</p>
        <p class="lede">{{ __('Fiecare pachet rezolvă o nevoie reală: imunitate, energie, focus, frumusețe, echilibru digestiv, vitalitate, detoxifiere, sănătate după 40, regenerare. Combinațiile sunt gândite ca produsele să lucreze sinergic — nu doar alăturat. Prețul pachetelor e mai mic decât suma produselor individuale. Dacă nu știi ce ți se potrivește, fă quiz-ul de 60 secunde — îți recomandăm onest, fără presiune.', 'sage') }}</p>

        <div class="trust-chips">
          <span class="trust-chip">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            {{ __('Sinergii calibrate', 'sage') }}
          </span>
          <span class="trust-chip">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            {{ __('Preț transparent', 'sage') }}
          </span>
          <span class="trust-chip">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            {{ __('Oprești oricând', 'sage') }}
          </span>
        </div>
      </div>

      <div class="hero-right" aria-hidden="true">
        <div class="hero-illu">
          <div class="pkg-stack">
            <div class="pkg-bot amber b1">
              <div class="cap"></div>
              <div class="body"><div class="label"><div class="seal">B+</div></div></div>
            </div>
            <div class="pkg-bot matte b-mid">
              <div class="cap"></div>
              <div class="body"><div class="label"><div class="seal">V+</div></div></div>
            </div>
            <div class="pkg-bot amber b3">
              <div class="cap"></div>
              <div class="body"><div class="label"><div class="seal">C+</div></div></div>
            </div>
          </div>
          <div class="pkg-caption">{{ __('11 pachete, sinergii calibrate', 'sage') }}</div>
        </div>
      </div>
    </div>
  </div>
</section>
