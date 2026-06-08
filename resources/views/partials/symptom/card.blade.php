{{--
  Un card-simptom. Variabile: $name, $desc, $chip (opțional).
  href „#" placeholder — paginile de detaliu pe simptom nu există încă.
  data-symptom / data-desc sunt folosite de resources/js/symptom.js pentru filtrare live.
--}}
<a
  class="grp-card"
  href="#"
  data-symptom="{{ esc_attr($name) }}"
  data-desc="{{ esc_attr($desc) }}"
>
  <div class="top">
    <h3>{{ $name }}</h3>
    @if (! empty($chip))
      <span class="chip-prev">{{ $chip }}</span>
    @endif
  </div>
  <p class="desc">{{ $desc }}</p>
  <span class="more">{{ __('Vezi simptomul', 'sage') }}
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
  </span>
</a>
