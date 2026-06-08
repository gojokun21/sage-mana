{{--
  Retur — secțiunea formular. Formularul (markup + JS + AJAX + email + stocare)
  vine din plugin-ul mn-contact-form prin [natura_rma_form]. Aici rămân doar
  layout-ul, sidebar-ul și panoul de confirmare (populat de rma.js pe succes
  prin hook-urile data-rma-*).
--}}
@php
  $return_address = class_exists('MN_Rma_Fields') ? \MN_Rma_Fields::return_address() : "Mâna Naturii / Vivens Genetica SRL\nStr. Exemplu nr. 12\nBucurești, Sector 2\nCod poștal: 010001";
@endphp
<section class="form-section">
  <div class="form-inner">

    <div class="form-head" data-rma-form-head>
      <div class="eyebrow">{{ __('Formular cerere retur', 'sage') }}</div>
      <h2>{{ __('Cere returul', 'sage') }} <em>{{ __('comenzii tale.', 'sage') }}</em></h2>
      <p>{{ __('Completezi datele de mai jos, primești pe email numărul RMA și adresa de retur în maximum 5 minute.', 'sage') }}</p>
    </div>

    <div class="form-grid-cols" data-rma-form-wrap>

      {!! do_shortcode('[natura_rma_form]') !!}

      {{-- SIDEBAR --}}
      <aside class="form-side">
        <div class="side-card">
          <h4>{{ __('Ce primești', 'sage') }} <em>{{ __('după trimitere.', 'sage') }}</em></h4>
          <ul>
            <li>{!! wp_kses(__('Email cu numărul RMA <strong>#RT-XXXXX</strong> în maxim 5 minute', 'sage'), ['strong' => []]) !!}</li>
            <li>{!! wp_kses(__('Adresa de retur și <strong>instrucțiunile complete</strong>', 'sage'), ['strong' => []]) !!}</li>
            <li>{{ __('Pași clari pentru împachetare și expediere', 'sage') }}</li>
          </ul>
        </div>

        <div class="side-card">
          <h4>{{ __('Adresa', 'sage') }} <em>{{ __('de retur.', 'sage') }}</em></h4>
          <div class="addr">{!! nl2br(esc_html($return_address)) !!}</div>
          <div class="warn-rma">
            <strong>{{ __('Atenție', 'sage') }}</strong>
            {!! wp_kses(__('Menționează numărul <strong>RMA</strong> vizibil pe colet. Coletele fără RMA nu pot fi procesate.', 'sage'), ['strong' => []]) !!}
          </div>
        </div>

        <div class="side-card">
          <h4>{{ __('Ai nevoie de', 'sage') }} <em>{{ __('ajutor înainte?', 'sage') }}</em></h4>
          <div class="contacts">
            <a href="https://wa.me/40712345678" target="_blank" rel="noopener"><svg width="14" height="14" viewBox="0 0 448 512" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.2-157zM223.9 442.3c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 361.5l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.4-186.6 184.4zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2z"/></svg><span><strong>{{ __('WhatsApp', 'sage') }}</strong>+40 712 345 678</span></a>
            <a href="mailto:office@mananaturii.ro"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/></svg><span><strong>{{ __('Email', 'sage') }}</strong>office@mananaturii.ro</span></a>
          </div>
        </div>
      </aside>

    </div>

    {{-- POST-SUBMIT (afișat de rma.js pe succes) --}}
    <div class="post-submit" data-rma-success hidden>
      <div class="post-inner">
        <div class="ok-stamp" aria-hidden="true">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        <h2>{{ __('Cererea ta de retur', 'sage') }} <em>{{ __('a fost înregistrată.', 'sage') }}</em></h2>
        <div class="rma-num">
          <span class="lbl">{{ __('Număr RMA', 'sage') }}</span>
          <span class="num" data-rma-number>#RT-XXXXX</span>
        </div>
        <p>{!! wp_kses(__('Îți trimitem pe email la <strong data-rma-email>email</strong> instrucțiunile complete și adresa de retur în maxim 5 minute. Verifică și folderul SPAM.', 'sage'), ['strong' => ['data-rma-email' => []]]) !!}</p>
        <div class="ret-addr">
          <div class="lbl">{{ __('Adresa de retur pe care o vei folosi', 'sage') }}</div>
          <div class="addr">{!! nl2br(esc_html($return_address)) !!}{{ "\n" }}{{ __('RMA:', 'sage') }} #<span data-rma-addr-num>RT-XXXXX</span></div>
        </div>
        <button type="button" class="download-btn" data-rma-print>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          {{ __('Printează / salvează RMA-ul', 'sage') }}
        </button>
      </div>
    </div>

  </div>
</section>
