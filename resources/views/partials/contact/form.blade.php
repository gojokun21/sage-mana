{{-- Contact — formular structurat + sidebar.
     Formularul (markup + JS + AJAX + email + stocare) e furnizat de plugin-ul
     `mn-contact-form` prin shortcode-ul [natura_contact_form]. Aici rămâne doar
     layout-ul de pagină (header + grid) și sidebar-ul. --}}
<section class="form-section">
  <div class="form-inner">
    <div class="form-head">
      <div class="eyebrow">{{ __('Formular contact', 'sage') }}</div>
      <h2>{{ __('Sau', 'sage') }} <em>{{ __('scrie-ne direct.', 'sage') }}</em></h2>
      <p>{!! wp_kses(__('Formular structurat. <strong>Categoria pe care o alegi</strong> ne ajută să prioritizăm mai bine — comenzile cu plata eșuată primesc răspuns în 2h, restul în 24h.', 'sage'), ['strong' => []]) !!}</p>
    </div>

    <div class="form-grid">

      {!! do_shortcode('[natura_contact_form]') !!}

      {{-- SIDEBAR --}}
      <div class="info-stack">
        <div class="info-card">
          <h4>{{ __('Ce primești', 'sage') }} <em>{{ __('după trimitere.', 'sage') }}</em></h4>
          <ul>
            <li>{!! wp_kses(__('Email automat de confirmare în <strong>5 minute</strong>', 'sage'), ['strong' => []]) !!}</li>
            <li>{!! wp_kses(__('Număr tichet de referință <strong>#SUP-XXXXX</strong>', 'sage'), ['strong' => []]) !!}</li>
            <li>{!! wp_kses(__('Răspuns personal în <strong>24h</strong> luni–vineri', 'sage'), ['strong' => []]) !!}</li>
          </ul>
        </div>

        <div class="info-card">
          <h4>{{ __('Prioritate', 'sage') }} <em>{{ __('răspuns.', 'sage') }}</em></h4>
          <div class="priority-list">
            <div class="pri-row"><span class="lbl">{{ __('Comenzi cu plata eșuată', 'sage') }}</span><span class="val">2h</span></div>
            <div class="pri-row"><span class="lbl">{{ __('Probleme cu livrare', 'sage') }}</span><span class="val">4h</span></div>
            <div class="pri-row"><span class="lbl">{{ __('Retururi', 'sage') }}</span><span class="val">24h</span></div>
            <div class="pri-row"><span class="lbl">{{ __('Întrebări produse', 'sage') }}</span><span class="val">24–48h</span></div>
            <div class="pri-row"><span class="lbl">{{ __('Parteneriate / presă', 'sage') }}</span><span class="val">48–72h</span></div>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>
