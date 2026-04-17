{{--
  Generic footer column (title + list of links).
  Vars:
    $title        string
    $items        array of ['titlu' => string, 'link' => string]
    $extra_items  array<int, array{titlu:string, link:string, external?:bool}> appended after items
--}}

@php
  $title = $title ?? '';
  $items = $items ?? [];
  $extra_items = $extra_items ?? [];
@endphp

<div class="footer-column footer-accordion" data-footer-accordion>
  <h3 class="footer-accordion-header" data-footer-accordion-toggle>
    {{ esc_html($title) }}
    <span class="footer-accordion-icon" aria-hidden="true">
      <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
        <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  </h3>

  <ul class="footer-accordion-content">
    @foreach ($items as $it)
      @if (! empty($it['titlu']))
        <li><a href="{{ esc_url($it['link'] ?? '#') }}">{{ esc_html($it['titlu']) }}</a></li>
      @endif
    @endforeach

    @foreach ($extra_items as $it)
      <li>
        <a href="{{ esc_url($it['link']) }}"
           @if (! empty($it['external'])) target="_blank" rel="noopener" @endif>
          {{ esc_html($it['titlu']) }}
        </a>
      </li>
    @endforeach
  </ul>
</div>
