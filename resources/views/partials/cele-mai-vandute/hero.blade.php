{{-- Cele mai vândute — hero + notă de onestitate (text din ACF). {count} = nr. produse. --}}
@php
  $eyebrow = \App\bestseller_field('hero_eyebrow', __('Top 5 · Quick link', 'sage'));
  $titlu = \App\bestseller_field('hero_titlu', __('Cele mai <em>vândute.</em>', 'sage'));
  $lede = \App\bestseller_field('hero_lede', __('<strong>{count} produse</strong> pe care clienții noștri le cumpără constant și le recomandă mai departe.', 'sage'));
  $lede = str_replace('{count}', (string) $bs_count, $lede);
  $honest_label = \App\bestseller_field('honest_label', __('Notă de onestitate', 'sage'));
  $honest_body = \App\bestseller_field('honest_body', __('Nu publicăm cifre de vânzări fabricate. Mâna Naturii este un brand mic — nu vindem milioane de unități. În schimb, urmărim reorder rate, recenzii și retenția clienților.', 'sage'));
  $honest_line = \App\bestseller_field('honest_line', __('→ Aceste produse sunt cele cu cea mai mare loialitate măsurată în 12 luni.', 'sage'));
@endphp
<section class="filt-hero">
  <div class="inner">
    <div class="eyebrow">{{ $eyebrow }}</div>
    <h1>{!! \App\bestseller_kses($titlu) !!}</h1>
    <p class="lede">{!! \App\bestseller_kses($lede) !!}</p>
    @if ($honest_body || $honest_line)
      <div class="honest-note">
        @if ($honest_label)<strong>{{ $honest_label }}</strong>@endif
        {{ $honest_body }}
        @if ($honest_line)<span class="body-line">{{ $honest_line }}</span>@endif
      </div>
    @endif
  </div>
</section>
