{{-- Cele mai vândute — tabel rezumat (sursă: $bestsellers). --}}
<section class="cpd-section">
  <div class="cpd-inner">
    <div class="cpd-head">
      <div class="eyebrow">{{ __('Top 5 · cifre transparente', 'sage') }}</div>
      <h2>{{ __('Reorder rate,', 'sage') }} <em>{{ __('nu vânzări fabricate.', 'sage') }}</em></h2>
      <p>{{ __('Coloana „Reorder rate" folosește semne calitative, nu cifre exacte. Rating-ul intern e calculat pe re-cumpărări în 12 luni — nu îl publicăm pe cifre rotunde manipulative.', 'sage') }}</p>
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
              <td class="name">{{ trim($p['title'].' '.$p['title_em']) }}<span class="meta">{{ $p['t_meta'] }}</span></td>
              <td class="cat">{{ $p['t_cat'] }}</td>
              <td>{{ $p['price'] }}</td>
              <td class="cpd-val">{{ $p['cpd_strong'] }}</td>
              <td class="rating">{{ str_repeat('★', (int) $p['rating_stars']) }}<span class="lbl">{{ $p['rating_lbl'] }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <p class="cpd-note">{{ __('Aceste rate sunt calculate intern pe baza re-cumpărărilor în 12 luni.', 'sage') }} <strong>{{ __('Nu sunt cifre publicabile exact', 'sage') }}</strong>, {{ __('dar le folosim pentru a alege ce promovăm.', 'sage') }}</p>
  </div>
</section>
