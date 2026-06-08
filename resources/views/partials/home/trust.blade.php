{{-- Trust section — 3 celule (înlocuiește trust-badges.blade.php). --}}
<section class="trust">
  <div class="trust-head">
    <div class="eyebrow" style="margin-bottom:14px">{{ __('De ce ne-ar putea păsa', 'sage') }}</div>
    <h2>
      {{ __('Când cumperi un supliment,', 'sage') }}
      <em>{{ __('cumperi de fapt încredere.', 'sage') }}</em>
    </h2>
  </div>

  <div class="trust-grid">
    <div class="trust-cell">
      <div class="ico">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
          <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3M8 11h6M11 8v6"/>
        </svg>
      </div>
      <h4>{{ __('Analize de lot publice.', 'sage') }}</h4>
      <p>{{ __('Fiecare lot are PDF cu valorile măsurate. Nu doar declarat — măsurat în laborator independent.', 'sage') }}</p>
      <a href="#analize-exemplu">{{ __('Vezi un raport exemplu →', 'sage') }}</a>
    </div>

    <div class="trust-cell">
      <div class="ico">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
          <path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/>
        </svg>
      </div>
      <h4>{{ __('Retur 14 zile, doar dacă produsul este sigilat.', 'sage') }}</h4>
      <p>{{ __('Dacă produsul este nedesfăcut și în ambalajul original, îl primim înapoi în 14 zile. Plătim noi transportul de retur.', 'sage') }}</p>
    </div>

    <div class="trust-cell">
      <div class="ico">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/><path d="M12 6v6"/>
        </svg>
      </div>
      <h4>{{ __('Anularea abonamentului — 2 click-uri.', 'sage') }}</h4>
      <p>{{ __('Din cont, fără form, fără sună-ne, fără întrebări tip „ești sigur?". Click, gata.', 'sage') }}</p>
    </div>
  </div>
</section>
