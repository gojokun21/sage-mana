{{--
  Când la medic — card cu bordură verde, listă de semnale de alarmă (check) și
  notă în subsol. Conținut static.
--}}
@php
  $titlu = \App\simptom_field('medic_titlu', __('Când nu mai e <em>doar pofta de dulce.</em>', 'sage'));
  $lede = \App\simptom_field('medic_lede', __('Dacă oricare dintre semnele de mai jos apare, prioritar este să consulți un medic și să faci analize, nu să încerci suplimentele singur.', 'sage'));
  $semnale = \App\simptom_field('medic_semnale', [
    ['text' => __('Sete excesivă și urinări frecvente (semn potențial de diabet).', 'sage')],
    ['text' => __('Glicemie măsurată peste 100 mg/dL pe nemâncate.', 'sage')],
    ['text' => __('Tensiune arterială peste 130/85 măsurată repetat.', 'sage')],
    ['text' => __('Trigliceride peste 150 sau HDL sub 40 în analize.', 'sage')],
    ['text' => __('Slăbiciune bruscă, vedere încețoșată, amețeli.', 'sage')],
  ]);
  $foot = \App\simptom_field('medic_foot', __('Menționarea unui medic aici nu e clauză legală. Sindromul metabolic se diagnostichează și se tratează sub supraveghere, suplimentele sunt sprijin colateral.', 'sage'));
@endphp

<section class="medic">
  <div class="medic-card">
    <div class="ico">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M9 12h6"/><path d="M12 9v6"/><circle cx="12" cy="12" r="10"/></svg>
    </div>
    <h2>{!! wp_kses($titlu, ['em' => []]) !!}</h2>
    <p class="lede">{{ $lede }}</p>
    <ul class="medic-list">
      @foreach ($semnale as $semnal)
        <li>
          <span class="check"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg></span>
          {{ is_array($semnal) ? ($semnal['text'] ?? '') : $semnal }}
        </li>
      @endforeach
    </ul>
    <div class="medic-foot">{{ $foot }}</div>
  </div>
</section>
