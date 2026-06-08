{{-- PDP — ingredient cheie (static, placeholder ACF). --}}
@php
  $ihl_rows = [
    ['lbl' => __('Origine ulei', 'sage'), 'val' => __('<strong>Egipt</strong> — Nigella sativa', 'sage')],
    ['lbl' => __('Metodă', 'sage'), 'val' => __('<strong>Presare la rece</strong> (păstrează bioactivi)', 'sage')],
    ['lbl' => __('Compus principal', 'sage'), 'val' => __('Timochinonă (natural)', 'sage')],
    ['lbl' => __('Vitamina E', 'sage'), 'val' => __('<strong>Naturală</strong> (nu sintetică)', 'sage')],
    ['lbl' => __('Format', 'sage'), 'val' => __('Capsulă moale vegetală', 'sage')],
  ];
@endphp
<section class="ihl">
  <div class="ihl-inner">
    <div class="head">
      <span class="eyebrow">{{ __('Ingredient cheie · spus complet', 'sage') }}</span>
      <h2>{{ __('Chimen negru egiptean.', 'sage') }} <em>{{ __('Presat la rece.', 'sage') }}</em></h2>
    </div>
    <div class="ihl-grid">
      <div class="ihl-art" aria-hidden="true">
        <svg viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="2">
          <g fill="currentColor" opacity=".85">
            <ellipse cx="50" cy="60" rx="9" ry="5" transform="rotate(20 50 60)"/>
            <ellipse cx="80" cy="50" rx="9" ry="5" transform="rotate(-30 80 50)"/>
            <ellipse cx="115" cy="65" rx="9" ry="5" transform="rotate(45 115 65)"/>
            <ellipse cx="150" cy="55" rx="9" ry="5" transform="rotate(-15 150 55)"/>
            <ellipse cx="45" cy="100" rx="9" ry="5" transform="rotate(60 45 100)"/>
            <ellipse cx="155" cy="105" rx="9" ry="5" transform="rotate(-40 155 105)"/>
            <ellipse cx="70" cy="130" rx="9" ry="5" transform="rotate(10 70 130)"/>
            <ellipse cx="130" cy="135" rx="9" ry="5" transform="rotate(-25 130 135)"/>
          </g>
          <path d="M100 90 Q 90 105, 100 130 Q 110 105, 100 90 Z" fill="currentColor" opacity=".45"/>
          <path d="M100 90 Q 90 105, 100 130 Q 110 105, 100 90 Z" stroke="currentColor" stroke-width="1.4"/>
          <rect x="80" y="155" width="40" height="20" rx="10" fill="currentColor" opacity=".55"/>
          <rect x="80" y="155" width="40" height="20" rx="10" stroke="currentColor" stroke-width="1.4"/>
          <line x1="100" y1="155" x2="100" y2="175" stroke="currentColor" stroke-width="1" opacity=".6"/>
        </svg>
        <div class="cap">{{ __('semințe Nigella sativa · ulei presat la rece · capsulă moale vegetală', 'sage') }}</div>
      </div>
      <div>
        <p class="ihl-prose">{!! wp_kses(__('<strong>Nigella sativa egipteană</strong> este recunoscută de secole în tradițiile orientale pentru rolul ei în sănătate. Presarea la rece păstrează compușii bioactivi (timochinonă, acizi grași nesaturați) intacți.', 'sage'), ['strong' => []]) !!}</p>
        <p class="ihl-prose">{!! wp_kses(__('<strong>Vitamina E naturală</strong> amplifică protecția antioxidantă. Capsulele moi vegetale elimină gustul pronunțat al uleiului lichid și asigură doza exactă zilnic.', 'sage'), ['strong' => []]) !!}</p>
        <div class="ihl-table">
          @foreach ($ihl_rows as $row)
            <div class="ihl-row">
              <div class="lbl">{{ $row['lbl'] }}</div>
              <div class="val">{!! wp_kses($row['val'], ['strong' => []]) !!}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
