{{-- Sub 200 lei — tabel cost/zi (rândurile LIVE din WC, sortate; titluri din ACF). --}}
@php
  $eyebrow = \App\sub200_field('table_eyebrow', __('Tabel comparativ', 'sage'));
  $titlu = \App\sub200_field('table_titlu', __('Cost real <em>pe zi.</em>', 'sage'));
  $intro = \App\sub200_field('table_intro', __('Sortat crescător după cost/zi. Cele mai eficiente cure sunt cele cu cea mai mică valoare pe zi — durata mai lungă „diluează” prețul cutiei.', 'sage'));
  $note = \App\sub200_field('table_note', __('Nu cumperi cutia, <strong>cumperi cura</strong>. Compară întotdeauna costul pe zi.', 'sage'));
@endphp
<section class="cpd-section">
  <div class="cpd-inner">
    <div class="cpd-head">
      <div class="eyebrow">{{ $eyebrow }}</div>
      <h2>{!! \App\sub200_kses($titlu) !!}</h2>
      <p>{{ $intro }}</p>
    </div>
    <div class="cpd-table">
      <table>
        <thead>
          <tr>
            <th>{{ __('Produs', 'sage') }}</th>
            <th>{{ __('Preț', 'sage') }}</th>
            <th>{{ __('Durată', 'sage') }}</th>
            <th>{{ __('Cost / zi', 'sage') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($table as $p)
            <tr class="{{ $p['days'] >= 120 ? 'best' : '' }}">
              <td class="name">{{ $p['name'] }}@if ($p['cat_name'])<span class="cat">{{ $p['cat_name'] }}</span>@endif</td>
              <td>{!! $p['price_html'] !!}</td>
              <td>{{ $p['duration_label'] ?: '—' }}</td>
              <td class="cpd-val">{{ $p['cpd_label'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p class="cpd-note">{!! \App\sub200_kses($note) !!}</p>
  </div>
</section>
