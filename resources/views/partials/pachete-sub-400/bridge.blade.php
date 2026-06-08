{{-- Pachete sub 400 lei — bridge către pachetele de 3 produse (sursă: $bridge_packs). --}}
<section class="bridge">
  <div class="bridge-inner">
    <div class="bridge-head">
      <div class="eyebrow">{{ __('Trei sau mai multe probleme simultan?', 'sage') }}</div>
      <h2>{{ __('Pachetele de 3 produse', 'sage') }} <em>{{ __('sunt mai eficiente.', 'sage') }}</em></h2>
      <p>{{ __('Pachetele de 2 suplimente acoperă o singură temă. Dacă ai', 'sage') }} <strong>{{ __('digestie + ten obosit + oboseală cronică', 'sage') }}</strong> {{ __('simultan, pachetele de 3 (457–524 lei) au formulă completă pentru toate trei axele biologice.', 'sage') }}</p>
    </div>
    <div class="bridge-grid">
      @foreach ($bridge_packs as $bp)
        <a class="br-card" href="{{ esc_url($bp['link']) }}">
          <div class="br-art" aria-hidden="true"></div>
          <span class="count">{{ $bp['count'] }}</span>
          <h4>{{ $bp['title'] }}</h4>
          <p class="br-desc">{{ $bp['desc'] }}</p>
          <span class="br-pr">{{ $bp['price'] }}</span>
        </a>
      @endforeach
    </div>
    <div class="bridge-cta-row">
      <a class="all-link" href="{{ esc_url($pachete_url) }}">{{ __('Vezi toate cele 11 pachete', 'sage') }}
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>
