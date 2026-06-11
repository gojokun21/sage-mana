{{--
  Un card-simptom. Variabile: $name, $desc, $chip (opțional), $link (default „#").
  $link vine din ACF (pagina de detaliu /dupa-simptom/<slug>/) prin groups.blade.php.
  data-symptom / data-desc sunt folosite de resources/js/symptom.js pentru filtrare live.
--}}
@php $link = $link ?? '#'; @endphp
<a
  class="grp-card"
  href="{{ esc_url($link) }}"
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
