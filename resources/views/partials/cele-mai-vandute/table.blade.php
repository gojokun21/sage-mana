{{-- Cele mai vândute — tabel rezumat (produse reale: $bestsellers; titluri din ACF). --}}
@php
  $eyebrow = \App\bestseller_field('table_eyebrow', __('Top · cifre transparente', 'sage'));
  $titlu = \App\bestseller_field('table_titlu', __('Reorder rate, <em>nu vânzări fabricate.</em>', 'sage'));
  $intro = \App\bestseller_field('table_intro', __('Coloana „Reorder rate” folosește semne calitative, nu cifre exacte. Rating-ul intern e calculat pe re-cumpărări în 12 luni — nu îl publicăm pe cifre rotunde manipulative.', 'sage'));
  $note = \App\bestseller_field('table_note', __('Aceste rate sunt calculate intern pe baza re-cumpărărilor în 12 luni. <strong>Nu sunt cifre publicabile exact</strong>, dar le folosim pentru a alege ce promovăm.', 'sage'));
@endphp
<section class="cpd-section">
  <div class="cpd-inner">
    <div class="cpd-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! \App\bestseller_kses($titlu) !!}</h2>
      <p>{{ $intro }}</p>
    </div>
    <div class="cpd-table">
      <table>
        <thead>
          <tr>
            <th>{{ __('Produs', 'sage') }}</th>
            <th>{{ __('Categorie', 'sage') }}</th>
            <th>{{ __('Preț', 'sage') }}</th>
            <th>{{ __('Cost / zi', 'sage') }}</th>
            <th>{{ __('Reorder rate', 'sage') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($bestsellers as $p)
            <tr>
              <td class="name">{{ $p['name'] }}@if ($p['sub'])<span class="meta">{{ $p['sub'] }}</span>@endif</td>
              <td class="cat">{{ $p['cat'] }}</td>
              <td>{!! $p['price_html'] !!}</td>
              <td class="cpd-val">{{ $p['cpd_label'] ?: '—' }}</td>
              <td class="rating">{{ str_repeat('★', max(0, min(5, (int) $p['rating']))) }}@if ($p['rating_label'])<span class="lbl">{{ $p['rating_label'] }}</span>@endif</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p class="cpd-note">{!! \App\bestseller_kses($note) !!}</p>
  </div>
</section>
