{{-- Noutăți — grila celor 3 tincturi (date editoriale ACF; imagine reală dacă e
     legat un produs WC, altfel sticla desenată). --}}
@php
  $titlu = \App\noutati_field('tinctures_titlu', __('Cele {count} tincturi <em>în dezvoltare.</em>', 'sage'));
  $titlu = str_replace('{count}', (string) $nt_count, $titlu);
  $sub = \App\noutati_field('tinctures_sub', __('Numele și specificațiile sunt placeholder — se finalizează după aprobare.', 'sage'));
@endphp
<section class="tinctures">
  <div class="tinctures-inner">
    <div class="tinctures-head">
      <h2>{!! \App\noutati_kses($titlu) !!}</h2>
      @if ($sub)<p>{{ $sub }}</p>@endif
    </div>

    @if ($nt_count === 0)
      <p style="color:var(--color-text-muted)">{{ __('Adaugă tincturi din ACF (tab „Tincturi”).', 'sage') }}</p>
    @else
      <div class="tgrid">
        @foreach ($tinctures as $t)
          @php
            $theme = ! empty($t['theme']) ? $t['theme'] : '';
            $has_img = ! empty($t['_img_html']);
            $bottle_lines = ! empty($t['bottle_label']) ? array_map('esc_html', explode('|', $t['bottle_label'])) : [];
          @endphp
          <div class="tcard">
            @if (! empty($t['pending_badge']))
              <span class="pending-badge">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                {{ $t['pending_badge'] }}
              </span>
            @endif
            <div class="img {{ $theme }}{{ $has_img ? ' has-img' : '' }}">
              @if ($has_img)
                @if (! empty($t['_link']))<a href="{{ esc_url($t['_link']) }}">{!! $t['_img_html'] !!}</a>@else{!! $t['_img_html'] !!}@endif
              @else
                <div class="dropper">
                  <div class="cap"></div>
                  <div class="bottle"><div class="lbl">{!! implode('<br>', $bottle_lines) !!}</div></div>
                </div>
              @endif
            </div>
            <div class="body">
              @if (! empty($t['cat_chip']))<span class="cat-chip">{{ $t['cat_chip'] }}</span>@endif
              @if (! empty($t['name']))<h3>{!! \App\noutati_kses($t['name']) !!}</h3>@endif
              @if (! empty($t['brand_line']))<p class="brand-line">{{ $t['brand_line'] }}</p>@endif
              @if (! empty($t['role']))<p class="role">{{ $t['role'] }}</p>@endif
              @if (! empty($t['specs']))<p class="specs">{{ $t['specs'] }}</p>@endif
              @if (! empty($t['usage']))
                <div class="usage">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                  <span>{!! \App\noutati_kses($t['usage']) !!}</span>
                </div>
              @endif
              @if (! empty($t['ingredients']) && is_array($t['ingredients']))
                <details class="ingredients">
                  <summary>{{ $t['ingredients_summary'] ?: __('Compoziție', 'sage') }}</summary>
                  <ul class="ing-list">
                    @foreach ($t['ingredients'] as $ing)
                      <li>
                        <div><span class="plant">{{ $ing['plant'] ?? '' }}</span>@if (! empty($ing['latin']))<span class="latin">{{ $ing['latin'] }}</span>@endif</div>
                        @if (! empty($ing['pct']))<span class="pct">{{ $ing['pct'] }}</span>@endif
                      </li>
                    @endforeach
                  </ul>
                </details>
              @endif
              @if (! empty($t['benefits']) && is_array($t['benefits']))
                <ul class="bens">
                  @foreach ($t['benefits'] as $b)
                    @if (! empty($b['text']))<li>{{ $b['text'] }}</li>@endif
                  @endforeach
                </ul>
              @endif
              @if (! empty($t['contraindic_text']))
                <div class="contraindic">
                  @if (! empty($t['contraindic_label']))<strong>{{ $t['contraindic_label'] }}</strong>@endif
                  {{ $t['contraindic_text'] }}
                </div>
              @endif
              @if (! empty($t['contraindic_extra_text']))
                <div class="contraindic extra">
                  @if (! empty($t['contraindic_extra_label']))<strong>{{ $t['contraindic_extra_label'] }}</strong>@endif
                  {{ $t['contraindic_extra_text'] }}
                </div>
              @endif
              @if (! empty($t['status_rows']) && is_array($t['status_rows']))
                <div class="preliminary">
                  @if (! empty($t['status_label']))<span class="lbl">{{ $t['status_label'] }}</span>@endif
                  @foreach ($t['status_rows'] as $r)
                    <div class="row"><span class="k">{{ $r['k'] ?? '' }}</span><span class="v {{ in_array(($r['type'] ?? 'normal'), ['estimate', 'tba'], true) ? $r['type'] : '' }}">{{ $r['v'] ?? '' }}</span></div>
                  @endforeach
                </div>
              @endif
              @if (! empty($t['notify_btn']))
                <a class="notify-btn" href="#notify">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                  {{ $t['notify_btn'] }}
                </a>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
