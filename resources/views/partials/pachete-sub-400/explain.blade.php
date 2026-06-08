{{-- Pachete sub 400 lei — „Pachet de 2 vs. pachet de 3". --}}
<section class="explain">
  <div class="explain-inner">
    <div class="explain-head">
      <div class="eyebrow">{{ __('Pachet de 2 vs. pachet de 3', 'sage') }}</div>
      <h2>{{ __('Când alegi', 'sage') }} <em>{{ __('pachetul de 2 suplimente.', 'sage') }}</em></h2>
    </div>
    <div class="explain-grid">
      <div class="ex-card">
        <div class="ico">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
        </div>
        <h3>{{ __('O zonă de probleme = pachet de 2.', 'sage') }}</h3>
        <p>{{ __('Dacă te confrunți cu', 'sage') }} <strong>{{ __('o singură temă', 'sage') }}</strong> {{ __('(digestie, focus, ten), pachetul de 2 suplimente acoperă 80% din nevoia ta. Adăugarea de produse fără nevoie clinică e overspending.', 'sage') }}</p>
      </div>
      <div class="ex-card">
        <div class="ico">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <h3>{{ __('Cură scurtă, 33–120 zile.', 'sage') }}</h3>
        <p>{{ __('Aceste pachete sunt gândite ca', 'sage') }} <strong>{{ __('primă încercare', 'sage') }}</strong>. {{ __('Mai bine o cură completă de 33 zile cu rezultate vizibile decât 3 produse cumpărate și abandonate.', 'sage') }}</p>
      </div>
      <div class="ex-card">
        <div class="ico">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/></svg>
        </div>
        <h3>{{ __('Când merită pachetul de 3.', 'sage') }}</h3>
        <p>{{ __('Dacă ai', 'sage') }} <strong>{{ __('3+ simptome simultane', 'sage') }}</strong> {{ __('sau ai trecut de 40 ani, pachetul de 3 suplimente (457–524 lei) e mai eficient — atinge 3 axe biologice în paralel.', 'sage') }}</p>
        <a class="link" href="{{ esc_url($pachete_url) }}">{{ __('Vezi pachetele mari', 'sage') }} <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg></a>
      </div>
    </div>
  </div>
</section>
