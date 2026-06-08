{{-- About — cele patru valori (grid 2x2). --}}
<section class="values">
  <div class="values-inner">
    <div class="values-head">
      <div class="eyebrow">{{ __('Cele patru valori', 'sage') }}</div>
      <h2>{{ __('Valorile care', 'sage') }} <em>{{ __('ne definesc.', 'sage') }}</em></h2>
    </div>
    <div class="values-grid">
      <div class="value-card">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg></div>
        <h3>{{ __('Autenticitate.', 'sage') }}</h3>
        <p>{!! wp_kses(__('Tot ce oferim provine din <strong>surse verificate și curate</strong>. Fără ingrediente cu origine neclară, fără shortcut-uri la furnizori.', 'sage'), ['strong' => []]) !!}</p>
      </div>
      <div class="value-card">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
        <h3>{{ __('Calitate', 'sage') }} <em>{{ __('premium.', 'sage') }}</em></h3>
        <p>{!! wp_kses(__('Fără compromisuri, <strong>fără aditivi inutili, fără promisiuni goale</strong>. Dacă o formulă nu rezistă la testare internă, nu intră în catalog.', 'sage'), ['strong' => []]) !!}</p>
      </div>
      <div class="value-card">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></div>
        <h3>{{ __('Transparență.', 'sage') }}</h3>
        <p>{!! wp_kses(__('Vrem să știi <strong>exact ce consumi</strong>, în ce doză, pe ce durată și de ce e valoros pentru tine. Etichete clare, fără caractere mici care ascund.', 'sage'), ['strong' => []]) !!}</p>
      </div>
      <div class="value-card">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 2L4 6v6c0 5.55 3.84 10.74 8 12 4.16-1.26 8-6.45 8-12V6l-8-4z"/></svg></div>
        <h3>{{ __('Respect pentru', 'sage') }} <em>{{ __('natură.', 'sage') }}</em></h3>
        <p>{!! wp_kses(__('Ne ghidăm după <strong>principii de sustenabilitate</strong> și grijă față de mediu. Ambalaje cu impact redus, ingrediente cultivate responsabil.', 'sage'), ['strong' => []]) !!}</p>
      </div>
    </div>
  </div>
</section>
