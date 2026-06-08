{{-- Contact — sediu & date legale firmă. Valori placeholder conform mockup. --}}
<section class="sediu">
  <div class="sediu-inner">
    <div class="sediu-head">
      <div class="eyebrow">{{ __('Despre firmă', 'sage') }}</div>
      <h2>{{ __('Sediul și', 'sage') }} <em>{{ __('date legale.', 'sage') }}</em></h2>
    </div>
    <div class="sediu-grid">

      <div class="sediu-card">
        <h3>{{ __('Date', 'sage') }} <em>{{ __('operator.', 'sage') }}</em></h3>
        <div class="sediu-row">
          <span class="k">{{ __('Operator', 'sage') }}</span>
          <span class="v">{{ __('Vivens Genetica SRL', 'sage') }}<small>{{ __('operatorul brandului Mâna Naturii', 'sage') }}</small></span>
        </div>
        <div class="sediu-row">
          <span class="k">{{ __('Adresă sediu', 'sage') }}</span>
          <span class="v">{!! wp_kses(__('Str. Exemplu nr. 12, Sector 1<br>București, cod poștal 010001', 'sage'), ['br' => []]) !!}<small>{{ __('(adresa exactă se confirmă — placeholder)', 'sage') }}</small></span>
        </div>
        <div class="sediu-row">
          <span class="k">CUI</span>
          <span class="v">RO XXXXXXXX<small>{{ __('(placeholder — se completează)', 'sage') }}</small></span>
        </div>
        <div class="sediu-row">
          <span class="k">{{ __('Reg. comerț', 'sage') }}</span>
          <span class="v">J40/XXXXX/2024<small>{{ __('(placeholder)', 'sage') }}</small></span>
        </div>
        <div class="sediu-row">
          <span class="k">IBAN</span>
          <span class="v">{{ __('Se transmite la cerere', 'sage') }}<small>{{ __('pentru facturi B2B sau transfer bancar', 'sage') }}</small></span>
        </div>
        <div class="sediu-row">
          <span class="k">ANSVSA</span>
          <span class="v">{{ __('Notificat — producător/distribuitor suplimente alimentare', 'sage') }}</span>
        </div>
      </div>

      <div class="map-mock" aria-hidden="true">
        <div class="pin">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="label">{{ __('Mâna Naturii · București', 'sage') }}</div>
      </div>

    </div>
  </div>
</section>
