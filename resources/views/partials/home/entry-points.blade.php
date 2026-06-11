{{-- Entry points — „De unde începi?" Text/link-uri din ACF (grup Home) → fallback
     seed. Iconițele rămân statice pe poziție. Cardul cu „chip" se randează flagship. --}}
@php
  $entry_cards = \App\home_field('entry_cards') ?: [];
  $entry_icons = [
    '<svg width="22" height="22" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true"><path d="M448 256A192 192 0 1 0 64 256a192 192 0 1 0 384 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 80a80 80 0 1 0 0-160 80 80 0 1 0 0 160zm0-224a144 144 0 1 1 0 288 144 144 0 1 1 0-288zM224 256a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 576 512" fill="currentColor" aria-hidden="true"><path d="M142.4 21.9c5.6 16.8-3.5 34.9-20.2 40.5L96 71.1 96 192c0 53 43 96 96 96s96-43 96-96l0-120.9-26.1-8.7c-16.8-5.6-25.8-23.7-20.2-40.5s23.7-25.8 40.5-20.2l26.1 8.7C334.4 19.1 352 43.5 352 71.1L352 192c0 77.2-54.6 141.6-127.3 156.7C231 404.6 278.4 448 336 448c61.9 0 112-50.1 112-112l0-70.7c-28.3-12.3-48-40.5-48-73.3c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3l0 70.7c0 97.2-78.8 176-176 176c-92.9 0-168.9-71.9-175.5-163.1C87.2 334.2 32 269.6 32 192L32 71.1c0-27.5 17.6-52 43.8-60.7l26.1-8.7c16.8-5.6 34.9 3.5 40.5 20.2zM480 224a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 640 512" fill="currentColor" aria-hidden="true"><path d="M96 64c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 160 0 64 0 160c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-64-32 0c-17.7 0-32-14.3-32-32l0-64c-17.7 0-32-14.3-32-32s14.3-32 32-32l0-64c0-17.7 14.3-32 32-32l32 0 0-64zm448 0l0 64 32 0c17.7 0 32 14.3 32 32l0 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l0 64c0 17.7-14.3 32-32 32l-32 0 0 64c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-160 0-64 0-160c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32zM416 224l0 64-192 0 0-64 192 0z"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M0 80L0 229.5c0 17 6.7 33.3 18.7 45.3l176 176c25 25 65.5 25 90.5 0L418.7 317.3c25-25 25-65.5 0-90.5l-176-176c-12-12-28.3-18.7-45.3-18.7L48 32C21.5 32 0 53.5 0 80zm112 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>',
  ];
  $arrow = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>';
@endphp
<section class="entry">
  <div class="entry-head">
    <div class="eyebrow" style="margin-bottom:14px">{{ \App\home_field('entry_eyebrow') }}</div>
    <h2>{{ \App\home_field('entry_titlu') }} <em>{{ \App\home_field('entry_titlu_em') }}</em></h2>
  </div>

  <div class="entry-grid">
    @foreach ($entry_cards as $i => $card)
      @php $ico = $entry_icons[$i] ?? $entry_icons[0]; $is_flagship = ! empty($card['chip']); @endphp
      @if ($is_flagship)
        <a class="entry-card flagship" href="{{ esc_url($card['url'] ?: '#') }}">
          <span class="gold-chip">{{ $card['chip'] }}</span>
          <div class="entry-card__body">
            <div class="ico">{!! $ico !!}</div>
            <h3>{{ $card['titlu'] ?? '' }}</h3>
            <p>{{ $card['text'] ?? '' }}</p>
            <span class="entry-card__link">{{ $card['link_text'] ?? '' }} {!! $arrow !!}</span>
          </div>
        </a>
      @else
        <a class="entry-card" href="{{ esc_url($card['url'] ?: '#') }}">
          <div class="ico">{!! $ico !!}</div>
          <h3>{{ $card['titlu'] ?? '' }}</h3>
          <p>{{ $card['text'] ?? '' }}</p>
          <span class="entry-card__link">{{ $card['link_text'] ?? '' }} {!! $arrow !!}</span>
        </a>
      @endif
    @endforeach
  </div>
</section>
