{{-- Sub 200 lei — banda explicativă. Text din ACF (fallback pe mockup);
     iconițele SVG sunt statice și ciclează după poziția cardului. --}}
@php
  $eyebrow = \App\sub200_field('explain_eyebrow', __('De ce sub 200 lei', 'sage'));
  $titlu = \App\sub200_field('explain_titlu', __('Nu înseamnă suplimente <em>mai slabe.</em>', 'sage'));

  $cards = \App\sub200_field('explain_cards', [
    [
      'titlu' => __('Formulare complete într-un singur produs.', 'sage'),
      'text' => __('Sub 200 lei se cumpără monoproduse. Pentru o problemă specifică (ficat, intestin, imunitate) <strong>un singur supliment cu formulare densă</strong> rezolvă mai bine decât 3 produse subdozate.', 'sage'),
      'link_text' => '', 'link_url' => '',
    ],
    [
      'titlu' => __('Cura înseamnă 30–120 zile, nu o cutie.', 'sage'),
      'text' => __('Prețul afișat e <strong>prețul întregii cure</strong>. Black Seed Elixir 184 lei = 4 luni de protecție. Calcul real pe zi, nu pe cutie — vezi tabelul comparativ.', 'sage'),
      'link_text' => '', 'link_url' => '',
    ],
    [
      'titlu' => __('Când merită să treci la pachet.', 'sage'),
      'text' => __('Dacă ai <strong>2–3 probleme simultan</strong> (ficat + digestie + imunitate slabă), un pachet de 280–330 lei e mai economic decât 3 produse separate.', 'sage'),
      'link_text' => __('Vezi pachetele sub 400 lei', 'sage'), 'link_url' => '',
    ],
  ]);

  // Iconițe statice (ciclează după index).
  $icons = [
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 2v20M2 12h20"/></svg>',
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/></svg>',
  ];
@endphp

<section class="explain">
  <div class="explain-inner">
    <div class="explain-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! \App\sub200_kses($titlu) !!}</h2>
    </div>
    <div class="explain-grid">
      @foreach ($cards as $i => $card)
        <div class="ex-card">
          <div class="ico">{!! $icons[$i % count($icons)] !!}</div>
          <h3>{{ $card['titlu'] ?? '' }}</h3>
          <p>{!! \App\sub200_kses($card['text'] ?? '') !!}</p>
          @if (! empty($card['link_text']))
            <a class="link" href="{{ esc_url($card['link_url'] ?: home_url('/pachete/')) }}">{{ $card['link_text'] }} <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg></a>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>
