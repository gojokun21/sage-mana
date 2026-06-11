{{-- Trust — 3 celule. Text/link din ACF (grup Home) → fallback seed. Iconițe statice. --}}
@php
  $trust_cells = \App\home_field('trust_cells') ?: [];
  $trust_icons = [
    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3M8 11h6M11 8v6"/></svg>',
    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>',
    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6"/></svg>',
  ];
@endphp
<section class="trust">
  <div class="trust-head">
    <div class="eyebrow" style="margin-bottom:14px">{{ \App\home_field('trust_eyebrow') }}</div>
    <h2>
      {{ \App\home_field('trust_titlu') }}
      <em>{{ \App\home_field('trust_titlu_em') }}</em>
    </h2>
  </div>

  <div class="trust-grid">
    @foreach ($trust_cells as $i => $cell)
      <div class="trust-cell">
        <div class="ico">{!! $trust_icons[$i] ?? $trust_icons[0] !!}</div>
        <h4>{{ $cell['titlu'] ?? '' }}</h4>
        <p>{{ $cell['text'] ?? '' }}</p>
        @if (! empty($cell['link_text']))
          <a href="{{ esc_url($cell['link_url'] ?: '#') }}">{{ $cell['link_text'] }}</a>
        @endif
      </div>
    @endforeach
  </div>
</section>
