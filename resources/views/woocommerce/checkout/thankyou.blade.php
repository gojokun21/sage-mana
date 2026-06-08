{{--
  Thank-you / order-received page — design „Confirmare comandă"
  (după mockup `preferinte/Pagina Confirmare Comanda.html`). Date LIVE din WC.
  Header / announce / footer vin din layout-ul temei. CSS: .or-confirm
  (resources/css/confirmare-comanda.css via cart-bundle.css).
  @see https://woocommerce.com/document/template-structure/
--}}

@php defined('ABSPATH') || exit; @endphp

<div class="woocommerce-order or-confirm">
  @if ($order)
    @php do_action('woocommerce_before_thankyou', $order->get_id()) @endphp

    @if ($order->has_status('failed'))
      {{-- ============================ Plată eșuată ============================ --}}
      <section class="ok-hero">
        <div class="ok-hero-inner">
          <div class="check-stamp" style="background:#b91c1c">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          </div>
          <h1>{{ __('Plata a fost', 'sage') }} <em>{{ __('respinsă.', 'sage') }}</em></h1>
          <p class="sub">{{ __('Banca sau procesatorul a refuzat tranzacția. Poți încerca din nou plata sau alege o altă metodă.', 'sage') }}</p>
          <div class="ok-actions">
            <a class="primary" href="{{ esc_url($order->get_checkout_payment_url()) }}">{{ __('Încearcă plata din nou', 'sage') }}</a>
            @if (is_user_logged_in())
              <a class="outline" href="{{ esc_url(wc_get_page_permalink('myaccount')) }}">{{ __('Contul meu', 'sage') }}</a>
            @endif
          </div>
        </div>
      </section>
    @else
      @php
        $oid        = $order->get_id();
        $first_name = $order->get_billing_first_name() ?: $order->get_shipping_first_name();
        $email      = $order->get_billing_email();
        $created    = $order->get_date_created();

        // Livrare estimată: data comenzii + 2 zile lucrătoare (sare sâmbătă/duminică).
        $est = $created ? clone $created : null;
        if ($est) {
            $added = 0;
            while ($added < 2) {
                $est->modify('+1 day');
                if (! in_array((int) $est->format('N'), [6, 7], true)) {
                    $added++;
                }
            }
        }
        $fmt_when = fn ($d) => $d ? date_i18n('j M · H:i', $d->getTimestamp()) : '';
        $fmt_day  = fn ($d) => $d ? date_i18n('j M', $d->getTimestamp()) : '';

        $shipping_total = (float) $order->get_shipping_total();
        $is_free_ship   = $shipping_total <= 0;
        $ship_label     = $order->get_shipping_method() ?: __('Livrare', 'sage');

        $ship_address = $order->get_formatted_shipping_address() ?: $order->get_formatted_billing_address();
        $phone        = $order->get_billing_phone() ?: $order->get_shipping_phone();

        $modify_url = is_user_logged_in() ? $order->get_view_order_url() : wc_get_page_permalink('myaccount');
        $wa_number  = '40712345678'; // suport WhatsApp
      @endphp

      {{-- ============================ Breadcrumb + Funnel ============================ --}}
      <nav class="breadcrumb" aria-label="{{ esc_attr__('Breadcrumb', 'sage') }}">
        <a href="{{ esc_url(home_url('/')) }}">{{ __('Acasă', 'sage') }}</a>
        <span class="sep" aria-hidden="true">›</span>
        <span class="here">{{ __('Confirmare comandă', 'sage') }}</span>
      </nav>

      <div class="funnel">
        <div class="funnel-inner">
          <span class="step"><span class="n">✓</span>{{ __('Coșul tău', 'sage') }}</span>
          <span class="arrow" aria-hidden="true">›</span>
          <span class="step"><span class="n">✓</span>{{ __('Livrare', 'sage') }}</span>
          <span class="arrow" aria-hidden="true">›</span>
          <span class="step"><span class="n">✓</span>{{ __('Plata', 'sage') }}</span>
          <span class="arrow" aria-hidden="true">›</span>
          <span class="step final"><span class="n">✓</span>{{ __('Confirmare', 'sage') }}</span>
        </div>
      </div>

      {{-- ============================ HERO ============================ --}}
      <section class="ok-hero">
        <div class="ok-hero-inner">
          <div class="check-stamp" aria-hidden="true">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          </div>
          <h1>
            {{ __('Mulțumim', 'sage') }}@if ($first_name), <em>{{ $first_name }}!</em>@else<em>!</em>@endif
          </h1>
          <p class="sub">{{ __('Comanda ta a fost plasată cu succes.', 'sage') }}</p>

          <div class="order-num">
            <span class="lbl">{{ __('Număr comandă', 'sage') }}</span>
            <span class="num">#{{ $order->get_order_number() }}</span>
          </div>

          @if ($email)
            <p class="email-line">
              {!! sprintf(
                wp_kses(__('Ți-am trimis confirmarea la <strong>%s</strong> — verifică și folderul SPAM dacă nu apare în 5 minute.', 'sage'), ['strong' => []]),
                esc_html($email)
              ) !!}
            </p>
          @endif

          <div class="ok-actions">
            <a class="outline" href="{{ esc_url($modify_url) }}">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="m18.5 2.5 3 3L12 15l-4 1 1-4z"/></svg>
              {{ __('Modifică sau anulează comanda', 'sage') }}
            </a>
            <a class="primary" href="{{ esc_url(wc_get_page_permalink('shop')) }}">
              {{ __('Înapoi la magazin', 'sage') }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </section>

      {{-- ============================ MAIN GRID ============================ --}}
      <section class="main-area">
        <div class="main-grid">

          {{-- LEFT --}}
          <div class="left-stack">

            {{-- A. Livrare estimată --}}
            <div class="block">
              <div class="head">
                <h2>{{ __('Livrare', 'sage') }} <em>{{ __('estimată.', 'sage') }}</em></h2>
                @if ($est)
                  <p>{{ sprintf(__('Comanda ajunge la tine în 2 zile lucrătoare — estimat %s.', 'sage'), $fmt_day($est)) }}</p>
                @endif
              </div>
              <div class="track-timeline">
                <div class="track-step done">
                  <div class="dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg></div>
                  <span class="ttl">{{ __('Comandă primită', 'sage') }}</span>
                  <span class="when">{{ $fmt_when($created) }}</span>
                </div>
                <div class="track-step current">
                  <div class="dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3" fill="currentColor"/></svg></div>
                  <span class="ttl">{{ __('În pregătire', 'sage') }}</span>
                  <span class="when">{{ __('în curs', 'sage') }}</span>
                </div>
                <div class="track-step">
                  <div class="dot">3</div>
                  <span class="ttl">{{ __('Predată curierului', 'sage') }}</span>
                  <span class="when">{{ __('în 24h', 'sage') }}</span>
                </div>
                <div class="track-step">
                  <div class="dot">4</div>
                  <span class="ttl">{{ __('Livrată', 'sage') }}</span>
                  <span class="when">@if ($est){{ sprintf(__('%s (estimat)', 'sage'), $fmt_day($est)) }}@endif</span>
                </div>
              </div>
              <div class="track-foot">{!! wp_kses(__('Curierul îți va trimite <strong>SMS</strong> cu intervalul de livrare în dimineața zilei de livrare. Dacă nu ești acasă, programezi redirecționarea direct din SMS.', 'sage'), ['strong' => []]) !!}</div>
            </div>

            {{-- B. Cum să iei produsele --}}
            <div class="block">
              <div class="head">
                <h2>{{ __('Cum să iei', 'sage') }} <em>{{ __('produsele tale.', 'sage') }}</em></h2>
                <p>{{ __('Indicații per produs. Urmează și eticheta din colet.', 'sage') }}</p>
              </div>
              <div class="use-grid">
                @foreach ($order->get_items() as $item)
                  @php
                    $product = $item->get_product();
                    $qty     = $item->get_quantity();

                    // Doză zilnică din ACF (informatie_generala.doza_zilnica), dacă există.
                    $doza = '';
                    if ($product && function_exists('have_rows')) {
                        $pid = $product->get_id();
                        if (have_rows('informatie_generala', $pid)) {
                            while (have_rows('informatie_generala', $pid)) {
                                the_row();
                                $dz = get_sub_field('doza_zilnica');
                                if (! empty($dz['cantitatea']) && ! empty($dz['tipul_dozei'])) {
                                    $doza = $dz['cantitatea'] . ' ' . $dz['tipul_dozei'];
                                }
                            }
                        }
                    }
                    $short = $product ? wp_strip_all_tags($product->get_short_description()) : '';
                  @endphp
                  <div class="use-card">
                    <div class="ph">
                      @if ($product && $product->get_image_id())
                        {!! $product->get_image('woocommerce_thumbnail', ['alt' => esc_attr($item->get_name())]) !!}
                      @endif
                    </div>
                    <div class="info">
                      <h3>{{ $item->get_name() }}</h3>
                      <p class="qty">{{ sprintf(__('cantitate × %d', 'sage'), $qty) }}</p>
                      <div class="use-rows">
                        @if ($doza)
                          <div class="use-row"><span class="k">{{ __('Doză zilnică', 'sage') }}</span><span class="v"><strong>{{ $doza }}</strong></span></div>
                        @endif
                        @if ($short)
                          <div class="use-row full"><span class="k">{{ __('Indicații', 'sage') }}</span><span class="v">{{ $short }}</span></div>
                        @elseif (! $doza)
                          <div class="use-row full"><span class="k">{{ __('Administrare', 'sage') }}</span><span class="v">{{ __('Urmează indicațiile de pe eticheta produsului. Pentru ajutor, scrie-ne pe WhatsApp.', 'sage') }}</span></div>
                        @endif
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            {{-- C. Suport pe parcursul curei (opțional) --}}
            <div class="block">
              <div class="head">
                <h2>{{ __('Suport pe parcursul curei', 'sage') }} <em>{{ __('(opțional).', 'sage') }}</em></h2>
                <p>{{ __('Îți trimitem 3 emailuri scurte care te ajută să rămâi pe traseu și să observi rezultatele.', 'sage') }}</p>
              </div>
              <div class="reminder-box">
                <div class="reminder-emails">
                  <div class="rem-mini">
                    <span class="when">{{ __('Email 1 · la 7 zile', 'sage') }}</span>
                    <h4>{{ __('Primele observații —', 'sage') }} <em>{{ __('ce trebuie să simți', 'sage') }}</em></h4>
                  </div>
                  <div class="rem-mini">
                    <span class="when">{{ __('Email 2 · la mijlocul curei', 'sage') }}</span>
                    <h4>{{ __('Cum reglezi', 'sage') }} <em>{{ __('dacă apare disconfort', 'sage') }}</em></h4>
                  </div>
                  <div class="rem-mini">
                    <span class="when">{{ __('Email 3 · la final', 'sage') }}</span>
                    <h4>{{ __('Evaluare cură —', 'sage') }} <em>{{ __('ce urmează', 'sage') }}</em></h4>
                  </div>
                </div>
                <div class="reminder-action" data-reminder>
                  <label class="cb-row">
                    <div class="cb"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></div>
                    <span class="lbl">{{ __('Da, vreau să primesc cele 3 emailuri.', 'sage') }}</span>
                  </label>
                  <button type="button" class="activate">{{ __('Activează', 'sage') }}</button>
                </div>
                <p class="reminder-fine">{!! wp_kses(__('Pe acest singur email. Te poți dezabona cu 1 click oricând. <strong>Fără newsletter, fără reclame.</strong>', 'sage'), ['strong' => []]) !!}</p>
              </div>
            </div>

          </div>

          {{-- RIGHT: Summary --}}
          <aside class="summary-col">
            <div class="summary">
              <div class="head-row">
                <h3>{{ __('Comanda', 'sage') }} <em>#{{ $order->get_order_number() }}</em></h3>
              </div>

              <div class="mini-items">
                @foreach ($order->get_items() as $item)
                  @php $product = $item->get_product(); @endphp
                  <div class="mini-item">
                    <div class="ph">
                      @if ($product && $product->get_image_id())
                        {!! $product->get_image('woocommerce_gallery_thumbnail', ['alt' => esc_attr($item->get_name())]) !!}
                      @endif
                    </div>
                    <div class="name">{{ $item->get_name() }}<span class="qty">{{ sprintf(__('cantitate × %d', 'sage'), $item->get_quantity()) }}</span></div>
                    <span class="v">{!! $order->get_formatted_line_subtotal($item) !!}</span>
                  </div>
                @endforeach
              </div>

              <div class="divider"></div>

              <div class="row">
                <span>{{ __('Subtotal', 'sage') }}</span>
                <span class="v">{!! wc_price($order->get_subtotal(), ['currency' => $order->get_currency()]) !!}</span>
              </div>

              @if ($order->get_total_discount() > 0)
                <div class="row">
                  <span>{{ __('Reducere', 'sage') }}</span>
                  <span class="v">−{!! wc_price($order->get_total_discount(), ['currency' => $order->get_currency()]) !!}</span>
                </div>
              @endif

              <div class="row {{ $is_free_ship ? 'free' : '' }}">
                <span>{{ $ship_label }}</span>
                @if ($is_free_ship)
                  <span class="v">{{ __('Gratuit', 'sage') }}</span>
                @else
                  <span class="v">{!! wc_price($shipping_total, ['currency' => $order->get_currency()]) !!}</span>
                @endif
              </div>

              <div class="total-row">
                <span class="lbl">{{ $order->is_paid() ? __('Total plătit', 'sage') : __('Total', 'sage') }}</span>
                <span class="v">
                  {!! $order->get_formatted_order_total() !!}
                  @if ($order->is_paid())
                    <span class="paid">{{ __('✓ Plată confirmată', 'sage') }}</span>
                  @endif
                </span>
              </div>

              <div class="meta-list">
                @if ($order->get_payment_method_title())
                  <div class="meta-row">
                    <span class="k">{{ __('Metodă plată', 'sage') }}</span>
                    <span class="v">{!! wp_kses_post($order->get_payment_method_title()) !!}</span>
                  </div>
                @endif
                @if ($ship_address)
                  <div class="meta-row">
                    <span class="k">{{ __('Adresă livrare', 'sage') }}</span>
                    <span class="v">{!! wp_kses_post($ship_address) !!}</span>
                  </div>
                @endif
                @if ($phone)
                  <div class="meta-row">
                    <span class="k">{{ __('Telefon contact', 'sage') }}</span>
                    <span class="v">{{ $phone }}</span>
                  </div>
                @endif
              </div>
            </div>
          </aside>

        </div>
      </section>

      {{-- WC integrations (loyalty points, gateway receipts). Default order
           details table is removed so it doesn't duplicate our summary. --}}
      @php
        remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
        do_action('woocommerce_thankyou_' . $order->get_payment_method(), $oid);
        do_action('woocommerce_thankyou', $oid);
      @endphp

      {{-- ============================ FAQ ============================ --}}
      <section class="faq">
        <div class="faq-inner">
          <h2>{{ __('Întrebări', 'sage') }} <em>{{ __('frecvente.', 'sage') }}</em></h2>
          <div class="faq-list">
            <details class="faq-item" open>
              <summary class="faq-q">{{ __('Când pot anula comanda?', 'sage') }}<span class="toggle" aria-hidden="true">+</span></summary>
              <div class="faq-a">{!! wp_kses(__('Până când intră în pregătire (de regulă <strong>în primele 24h</strong>). După, anulezi <strong>direct la curier</strong> când vine cu pachetul — îl refuzi politicos și nu se procesează plata (sau ți se returnează dacă ai plătit cu card).', 'sage'), ['strong' => []]) !!}</div>
            </details>
            <details class="faq-item">
              <summary class="faq-q">{{ __('Pot returna după primire?', 'sage') }}<span class="toggle" aria-hidden="true">+</span></summary>
              <div class="faq-a">{!! wp_kses(__('<strong>14 zile garanție</strong> conform legii consumatorului — doar pentru produsele <strong>sigilate</strong>, în ambalajul original. Pentru produsele deschise cu defect de fabricație, garanția de calitate e separată — scrie-ne pe WhatsApp și rezolvăm caz cu caz.', 'sage'), ['strong' => []]) !!}</div>
            </details>
            <details class="faq-item">
              <summary class="faq-q">{{ __('Ce fac dacă lipsește un produs din colet?', 'sage') }}<span class="toggle" aria-hidden="true">+</span></summary>
              <div class="faq-a">{!! wp_kses(sprintf(__('Scrie-ne la <strong>office@mananaturii.ro</strong> cu numărul comenzii (#%s) și o poză a coletului deschis. Rezolvăm în <strong>24h lucrătoare</strong> — fie retrimitere, fie refund pentru produsul lipsă.', 'sage'), esc_html($order->get_order_number())), ['strong' => []]) !!}</div>
            </details>
            <details class="faq-item">
              <summary class="faq-q">{{ __('Când vine confirmarea pe email?', 'sage') }}<span class="toggle" aria-hidden="true">+</span></summary>
              <div class="faq-a">{!! wp_kses(__('În maximum <strong>5 minute</strong> de la plasarea comenzii. Verifică și folderul <strong>SPAM</strong> / Promoții. Dacă nu apare în 30 minute, scrie-ne pe WhatsApp și retrimitem.', 'sage'), ['strong' => []]) !!}</div>
            </details>
          </div>
        </div>
      </section>

      {{-- ============================ Support ============================ --}}
      <section class="support">
        <div class="support-inner">
          <h3>{{ __('Ai nevoie de', 'sage') }} <em>{{ __('ajutor?', 'sage') }}</em></h3>
          <p>{{ __('Răspundem rapid pe oricare canal. Cel mai rapid: WhatsApp.', 'sage') }}</p>
          <div class="support-grid">
            <a class="support-card" href="https://wa.me/{{ $wa_number }}" target="_blank" rel="noopener">
              <div class="ico"><svg width="16" height="16" viewBox="0 0 448 512" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.2-157zM223.9 442.3c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 361.5l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.4-186.6 184.4zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg></div>
              <span class="lbl">{{ __('WhatsApp · cel mai rapid', 'sage') }}</span>
              <span class="val">+40 712 345 678</span>
              <span class="hint">{{ __('răspuns în câteva minute, 9–21', 'sage') }}</span>
            </a>
            <a class="support-card" href="mailto:office@mananaturii.ro">
              <div class="ico"><svg width="16" height="16" viewBox="0 0 512 512" fill="currentColor"><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg></div>
              <span class="lbl">{{ __('Email', 'sage') }}</span>
              <span class="val">office@mananaturii.ro</span>
              <span class="hint">{{ __('răspuns în 24h lucrătoare', 'sage') }}</span>
            </a>
            <a class="support-card" href="tel:+40712345678">
              <div class="ico"><svg width="16" height="16" viewBox="0 0 512 512" fill="currentColor"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg></div>
              <span class="lbl">{{ __('Telefon', 'sage') }}</span>
              <span class="val">+40 712 345 678</span>
              <span class="hint">{{ __('luni–vineri, 9–17', 'sage') }}</span>
            </a>
          </div>
        </div>
      </section>
    @endif
  @else
    <section class="ok-hero">
      <div class="ok-hero-inner">
        <h1>{{ __('Comandă', 'sage') }} <em>{{ __('indisponibilă.', 'sage') }}</em></h1>
        <p class="sub">{{ __('Nu am putut găsi detaliile acestei comenzi.', 'sage') }}</p>
        <div class="ok-actions">
          <a class="primary" href="{{ esc_url(wc_get_page_permalink('shop')) }}">{{ __('Înapoi la magazin', 'sage') }}</a>
        </div>
      </div>
    </section>
  @endif
</div>
