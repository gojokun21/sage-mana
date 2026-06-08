{{-- „Un obiectiv nu se atinge doar cu un supliment" — card de așteptări realiste. --}}
@php
  $obiectivPoints = [
    __('Suplimentul susține, nu înlocuiește somnul, mișcarea și alimentația.', 'sage'),
    __('Rezultatele reale apar în 4–12 săptămâni de administrare constantă.', 'sage'),
    __('Un singur obiectiv clar bate cinci abordate pe jumătate.', 'sage'),
    __('Dozele active conforme studiilor, nu doze decorative de etichetă.', 'sage'),
    __('Dacă ai o afecțiune sau iei tratament, întreabă întâi medicul.', 'sage'),
    __('Măsoară progresul: cum te simți la 4 săptămâni vs. acum.', 'sage'),
  ];
@endphp
<section class="medic">
  <div class="medic-card">
    <div class="ico" aria-hidden="true">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 12h6"/><path d="M12 9v6"/><circle cx="12" cy="12" r="10"/></svg>
    </div>
    <h2>{{ __('Un obiectiv se atinge cu un sistem,', 'sage') }} <em>{{ __('nu doar cu un supliment.', 'sage') }}</em></h2>
    <p class="lede">{{ __('Suplimentele potrivite accelerează rezultatul, dar funcționează cel mai bine alături de obiceiuri sănătoase. Iată ce ne pare important să spunem clar înainte să alegi.', 'sage') }}</p>
    <ul class="medic-list">
      @foreach ($obiectivPoints as $point)
        <li>
          <span class="check" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span>
          {{ $point }}
        </li>
      @endforeach
    </ul>
    <div class="medic-foot">{{ __('Un supliment poate susține, dar nu înlocuiește un stil de viață sănătos sau o investigație medicală. Ne pare important să spunem asta clar.', 'sage') }}</div>
  </div>
</section>
