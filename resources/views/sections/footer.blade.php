{{--
  Footer — ported from mana-naturii.
  ACF Options: `footer_section` group with:
    - linkuri_utile (title + items repeater)
    - informatii_legale (title + items repeater)
    - relatii_clienti (title + items repeater)
    - contacte (telefon repeater, mail, program, link_facebook, link_instagram, link_tiktok)

  On mobile, each column becomes an accordion.
--}}

@php
  $tpl_uri = get_stylesheet_directory_uri();
  $anpc_img_1 = $tpl_uri . '/resources/images/litigilor_02.avif';
  $anpc_img_2 = $tpl_uri . '/resources/images/insolventa-persoane.avif';
  $anpc_img_3 = $tpl_uri . '/resources/images/anpc-sol.webp';

  $company_details = apply_filters('natura_footer_company_details', [
    'DIGITAL INTERSTAR S.R.L.',
    'CUI / CIF: 47010850',
    'Nr. Reg. Com.: J24/2121/2022',
    'Sediu: Maramureș, Sat Posta, Comuna Remetea Chioarului, nr. 41, România',
  ]);

  $footer_tagline = apply_filters(
    'natura_footer_tagline',
    __('Soluții complete pentru un corp echilibrat și o viață activă.', 'sage')
  );

  $footer_logo = apply_filters('natura_footer_logo_url', '/wp-content/uploads/2026/04/logo-footer.svg');
  $footer_payment_icon = apply_filters('natura_footer_payment_icon_url', '/wp-content/uploads/2026/04/metoda-de-plata-1.png');
@endphp

<footer class="footer content-info">
  @if (function_exists('have_rows') && have_rows('footer_section', 'options'))
    @while (have_rows('footer_section', 'options'))
      @php the_row() @endphp

      <div class="container">
        <div class="main_footer">
          @if ($footer_logo)
            <div class="logo_footer">
              <a href="{{ esc_url(home_url('/')) }}" aria-label="{{ esc_attr(get_bloginfo('name')) }}">
                <img src="{{ esc_url($footer_logo) }}" alt="{{ esc_attr(get_bloginfo('name')) }}">
              </a>
            </div>
          @endif
          @if ($footer_tagline)
            <p>{{ $footer_tagline }}</p>
          @endif
        </div>

        <div class="footer-columns">
          {{-- Column 1: Linkuri utile (+ ANPC images on desktop, consent link) --}}
          @if (have_rows('linkuri_utile'))
            @while (have_rows('linkuri_utile'))
              @php
                the_row();
                $title = get_sub_field('titlu_principal');
                $items = [];
                if (have_rows('items')) {
                  while (have_rows('items')) {
                    the_row();
                    $items[] = [
                      'titlu' => get_sub_field('titlu'),
                      'link' => get_sub_field('link'),
                    ];
                  }
                }
              @endphp
              <div class="footer-column footer-accordion" data-footer-accordion>
                <h3 class="footer-accordion-header" data-footer-accordion-toggle>
                  {{ esc_html($title) }}
                  <span class="footer-accordion-icon" aria-hidden="true">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                      <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                </h3>
                <ul class="footer-accordion-content">
                  @foreach ($items as $it)
                    <li><a href="{{ esc_url($it['link']) }}">{{ esc_html($it['titlu']) }}</a></li>
                  @endforeach
                  <li>
                    <a href="#" class="open-consent">{{ __('Administrează consimțămintele', 'sage') }}</a>
                  </li>
                </ul>
                <div class="anpc anpc_desktop">
                  <a target="_blank" rel="noopener" href="https://anpc.ro/ce-este-sal/" aria-label="{{ __('ANPC — Soluționarea Alternativă a Litigiilor', 'sage') }}">
                    <img src="{{ esc_url($anpc_img_1) }}" width="200" height="50" alt="ANPC SAL">
                  </a>
                  <a target="_blank" rel="noopener" href="https://ec.europa.eu/consumers/odr/" aria-label="{{ __('Soluționarea online a litigiilor (SOL)', 'sage') }}">
                    <img src="{{ esc_url($anpc_img_2) }}" width="200" height="50" alt="SOL">
                  </a>
                  <a target="_blank" rel="noopener" href="https://consumer-redress.ec.europa.eu/site-relocation_en?event=main.home2.show&lng=RO" aria-label="{{ __('Soluționarea online a litigiilor (SOL)', 'sage') }}">
                    <img src="{{ esc_url($anpc_img_3) }}" width="200" height="50" alt="SOL">
                  </a>
                </div>
              </div>
            @endwhile
          @endif

          {{-- Column 2: Informații legale --}}
          @if (have_rows('informatii_legale'))
            @while (have_rows('informatii_legale'))
              @php
                the_row();
                $title = get_sub_field('titlu_principal');
                $items = [];
                if (have_rows('items')) {
                  while (have_rows('items')) {
                    the_row();
                    $items[] = [
                      'titlu' => get_sub_field('titlu'),
                      'link' => get_sub_field('link'),
                    ];
                  }
                }
              @endphp
              @include('partials.footer-column', [
                'title' => $title,
                'items' => $items,
                'extra_items' => [
                  ['titlu' => 'ANSPDC', 'link' => 'https://www.dataprotection.ro/', 'external' => true],
                ],
              ])
            @endwhile
          @endif

          {{-- Column 3: Relații clienți --}}
          @if (have_rows('relatii_clienti'))
            @while (have_rows('relatii_clienti'))
              @php
                the_row();
                $title = get_sub_field('titlu_principal');
                $items = [];
                if (have_rows('items')) {
                  while (have_rows('items')) {
                    the_row();
                    $items[] = [
                      'titlu' => get_sub_field('titlu'),
                      'link' => get_sub_field('link'),
                    ];
                  }
                }
              @endphp
              @include('partials.footer-column', [
                'title' => $title,
                'items' => $items,
              ])
            @endwhile
          @endif

          {{-- Column 4: Contactează-ne (bespoke layout) --}}
          @if (have_rows('contacte'))
            @while (have_rows('contacte'))
              @php
                the_row();
                $mail = get_sub_field('mail');
                $program = get_sub_field('program');
                $link_fb = get_sub_field('link_facebook');
                $link_ig = get_sub_field('link_instagram');
                $link_tt = get_sub_field('link_tiktok');
              @endphp

              <div class="footer-column footer-accordion" data-footer-accordion>
                <h3 class="footer-accordion-header" data-footer-accordion-toggle>
                  {{ __('Contactează-ne', 'sage') }}
                  <span class="footer-accordion-icon" aria-hidden="true">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                      <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                </h3>

                <ul class="footer-accordion-content footer-contact-list">
                  @if (have_rows('telefon'))
                    @while (have_rows('telefon'))
                      @php
                        the_row();
                        $phone_num = get_sub_field('numar_de_telefon');
                        $phone_link = get_sub_field('link');
                      @endphp
                      @if ($phone_num)
                        <li>
                          <a href="tel:{{ esc_attr($phone_link ?: preg_replace('/\s+/', '', $phone_num)) }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ esc_html($phone_num) }}
                          </a>
                        </li>
                      @endif
                    @endwhile
                  @endif

                  @if ($mail)
                    <li>
                      <a href="mailto:{{ esc_attr($mail) }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.7"/>
                          <path d="M3 7l9 6 9-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ esc_html($mail) }}
                      </a>
                    </li>
                  @endif

                  @if ($program)
                    <li>
                      <span class="footer-contact-static">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                          <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/>
                          <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                        {{ esc_html($program) }}
                      </span>
                    </li>
                  @endif
                </ul>

                <ul class="footer-company-details">
                  @foreach ($company_details as $i => $line)
                    <li>{{ esc_html($line) }}</li>
                  @endforeach
                </ul>

                <div class="footer_social">
                  @if ($link_fb)
                    <a aria-label="{{ __('Deschide pagina noastră de Facebook', 'sage') }}" href="{{ esc_url($link_fb) }}" target="_blank" rel="noopener">
                      <img src="{{ esc_url($tpl_uri . '/resources/images/icons/facebook.svg') }}" alt="">
                    </a>
                  @endif
                  @if ($link_ig)
                    <a aria-label="{{ __('Deschide profilul nostru de Instagram', 'sage') }}" href="{{ esc_url($link_ig) }}" target="_blank" rel="noopener">
                      <img src="{{ esc_url($tpl_uri . '/resources/images/icons/instagram.svg') }}" alt="">
                    </a>
                  @endif
                  @if ($link_tt)
                    <a aria-label="{{ __('Deschide profilul nostru de TikTok', 'sage') }}" href="{{ esc_url($link_tt) }}" target="_blank" rel="noopener">
                      <img src="{{ esc_url($tpl_uri . '/resources/images/icons/tiktok.svg') }}" alt="">
                    </a>
                  @endif
                </div>
              </div>
            @endwhile
          @endif
        </div>

        <div class="anpc anpc_mobile">
          <a target="_blank" rel="noopener" href="https://anpc.ro/ce-este-sal/" aria-label="ANPC SAL">
            <img src="{{ esc_url($anpc_img_1) }}" width="200" height="50" alt="ANPC SAL">
          </a>
          <a target="_blank" rel="noopener" href="https://ec.europa.eu/consumers/odr/" aria-label="SOL">
            <img src="{{ esc_url($anpc_img_2) }}" width="200" height="50" alt="SOL">
          </a>
        </div>
      </div>
    @endwhile
  @endif

  <div class="copyright">
    <div class="container">
      <p>
        {{ sprintf(__('Copyright © %s. Toate drepturile rezervate.', 'sage'), date('Y') . ' ' . get_bloginfo('name')) }}
      </p>
      @if ($footer_payment_icon)
        <div class="footer-payment-icons">
          <img src="{{ esc_url($footer_payment_icon) }}" alt="{{ esc_attr__('Metode de plată', 'sage') }}" height="32" loading="lazy">
        </div>
      @endif
    </div>
  </div>
</footer>
