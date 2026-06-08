{{-- „Câteva simptome cer un medic, nu un site" — card de siguranță. --}}
@php
  $medicSigns = [
    __('Scădere bruscă în greutate fără motiv.', 'sage'),
    __('Durere persistentă mai mult de 2 săptămâni.', 'sage'),
    __('Modificări vizibile în aspectul scaunului sau urinii.', 'sage'),
    __('Stare generală severă (febră ridicată, transpirații nocturne).', 'sage'),
    __('Sângerări inexplicabile.', 'sage'),
    __('Antecedente familiale de afecțiuni serioase.', 'sage'),
  ];
@endphp
<section class="medic">
  <div class="medic-card">
    <div class="ico" aria-hidden="true">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 12h6"/><path d="M12 9v6"/><circle cx="12" cy="12" r="10"/></svg>
    </div>
    <h2>{{ __('Câteva simptome cer să vorbești', 'sage') }} <em>{{ __('cu un medic, nu cu un site.', 'sage') }}</em></h2>
    <p class="lede">{{ __('Dacă ai oricare dintre semnele de mai jos, prioritar este să consulți un specialist, nu să încerci suplimentele singur.', 'sage') }}</p>
    <ul class="medic-list">
      @foreach ($medicSigns as $sign)
        <li>
          <span class="check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span>
          {{ $sign }}
        </li>
      @endforeach
    </ul>
    <div class="medic-foot">{{ __('Un supliment poate susține, dar nu înlocuiește o investigație medicală. Ne pare important să spunem asta clar.', 'sage') }}</div>
  </div>
</section>
