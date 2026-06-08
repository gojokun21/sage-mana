{{-- Pachete sub 400 lei — tabel comparativ pachet vs. produse separate. --}}
<section class="cpd-section">
  <div class="cpd-inner">
    <div class="cpd-head">
      <div class="eyebrow">{{ __('Tabel comparativ', 'sage') }}</div>
      <h2>{{ __('Pachet vs.', 'sage') }} <em>{{ __('produse separate.', 'sage') }}</em></h2>
      <p>{{ __('Economia mică e un detaliu. Ce contează cu adevărat: durata curei e mai lungă (120 vs. 33–50 zile pe monoproduse) și formulele sunt complementare, nu suprapuse.', 'sage') }}</p>
    </div>
    <div class="cpd-table">
      <table>
        <thead>
          <tr>
            <th>{{ __('Pachet', 'sage') }}</th>
            <th>{{ __('Preț pachet', 'sage') }}</th>
            <th>{{ __('Produse separate', 'sage') }}</th>
            <th>{{ __('Economie', 'sage') }}</th>
            <th>{{ __('Cost / zi', 'sage') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($packs as $pack)
            <tr>
              <td class="name">{{ $pack['t_name'] }}<span class="meta">{{ $pack['t_meta'] }}</span></td>
              <td>{{ $pack['price'] }}</td>
              <td>{{ $pack['t_separate'] }}</td>
              <td class="save">{{ $pack['t_save'] }}</td>
              <td class="cpd-val">{{ $pack['cpd_strong'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p class="cpd-note">{{ __('Economia mică e un detaliu. Ce contează e că durata curei e mai lungă și formulele sunt', 'sage') }} <strong>{{ __('complementare, nu doar alăturate', 'sage') }}</strong>.</p>
  </div>
</section>
