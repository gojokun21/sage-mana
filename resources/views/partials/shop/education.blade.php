{{-- Education „Ce înseamnă doze reale?" + 4 trust badges. --}}
<section class="edu">
  <div class="edu-inner">
    <div class="eyebrow">{{ __('Doze reale', 'sage') }}</div>
    <h2>{{ __('Ce înseamnă', 'sage') }} <em>{{ __('doze reale', 'sage') }}</em>?</h2>
    <p>{!! __('Toate produsele sunt formulate în Uniunea Europeană, cu <strong>dozaje active conforme studiilor clinice</strong>, nu doze decorative pentru etichetă. Fiecare lot trece prin testare independentă, iar materiile prime au certificate de origine. Fără ingrediente puse pentru a face lista mai impresionantă.', 'sage') !!}</p>

    <div class="trust-badges">
      <div class="tb">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <path d="M9 11h6M12 8v6M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16l-7-3-7 3z"/>
        </svg>
        <span>{{ __('Lab tested', 'sage') }}<br/>{{ __('independent', 'sage') }}</span>
      </div>
      <div class="tb">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/>
          <path d="M2 21c0-3 1.85-5.36 5.08-6"/>
        </svg>
        <span>{{ __('Vegan', 'sage') }}<br/>{{ __('options', 'sage') }}</span>
      </div>
      <div class="tb">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
          <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        <span>{{ __('Fabricat', 'sage') }}<br/>{{ __('în UE', 'sage') }}</span>
      </div>
      <div class="tb">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>{{ __('90 zile', 'sage') }}<br/>{{ __('garanție', 'sage') }}</span>
      </div>
    </div>
  </div>
</section>
