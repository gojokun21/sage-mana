{{-- About — onestitate radicală: ce NU facem. --}}
@php
  $nots = [
    __('Nu folosim countdown false <em>„expiră în 10 minute"</em>.', 'sage'),
    __('Nu publicăm cifre de vânzări fabricate <em>„1.234 vândute astăzi"</em>.', 'sage'),
    __('Nu folosim <em>„doar X rămase pe stoc"</em> pentru a induce panică.', 'sage'),
    __('Nu trimitem pop-up exit-intent <em>„ești sigur că vrei să pleci?"</em>.', 'sage'),
    __('Nu pre-bifăm abonamente lunare la checkout.', 'sage'),
    __('Nu promitem <em>„satisfacție 100% garantată"</em> — e vag și neonest. Spunem clar: <strong>14 zile retur sigilat, conform legii.</strong>', 'sage'),
  ];
@endphp
<section class="not-doing">
  <div class="not-doing-inner">
    <div class="not-doing-head">
      <div class="eyebrow">{{ __('Onestitate radicală', 'sage') }}</div>
      <h2>{{ __('Ce', 'sage') }} <em>{{ __('nu facem', 'sage') }}</em> {{ __('(și de ce contează).', 'sage') }}</h2>
      <p>{!! wp_kses(__('Cele mai multe branduri spun ce <strong>fac</strong>. Noi spunem și ce <strong>nu facem</strong> — pentru ca să știi ce să <em>nu aștepți</em> de la noi.', 'sage'), ['strong' => [], 'em' => []]) !!}</p>
    </div>
    <div class="nots-grid">
      @foreach ($nots as $not)
        <div class="not-item">
          <div class="x" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg></div>
          <div class="txt">{!! wp_kses($not, ['strong' => [], 'em' => []]) !!}</div>
        </div>
      @endforeach
    </div>
  </div>
</section>
