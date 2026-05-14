{{--
  Template: Product category (taxonomy product_cat).
  Picked up automatically by WP/WC hierarchy when is_product_category() is true.
  Shop page (/magazin) ramane pe archive-product.blade.php.
--}}

@extends('layouts.app')

@section('content')
  @php
    $term = get_queried_object();
    $shop_page_id = wc_get_page_id('shop');
    $fallback_url = get_the_post_thumbnail_url($shop_page_id, 'full');

    $bg_url = $fallback_url;
    $bg_url_mobile = $fallback_url;

    $header_image = get_field('header_category', $term);
    if ($header_image) {
        $bg_url = is_array($header_image) ? $header_image['url'] : $header_image;
    }

    $mobile_header = get_field('mobile_category', $term);
    if ($mobile_header) {
        $bg_url_mobile = is_array($mobile_header) ? $mobile_header['url'] : $mobile_header;
    } else {
        $bg_url_mobile = $bg_url;
    }

    $hero_alt = single_term_title('', false);
  @endphp

  <div class="archive-product-wrap is-category">
    <div class="header_archive">
      @php do_action('woocommerce_before_main_content') @endphp

      <div class="hero_archive">
        <picture class="hero_archive_picture">
          <source media="(max-width: 768px)" srcset="{{ esc_url($bg_url_mobile) }}">
          <source media="(min-width: 769px)" srcset="{{ esc_url($bg_url) }}">
          <img src="{{ esc_url($bg_url) }}" alt="{{ esc_attr($hero_alt) }}">
        </picture>
        <div class="hero_archive_content">
          <div class="row gy-0 gx-0">
            <div class="col-md-12">
              {{-- Hero category content. Markup static; toate elementele
                   au target ACF (pe term) urmator:
                   - hero_pill_tag (text)
                   - hero_title_line_1 / hero_title_line_2 (text, _2 e subliniat)
                   - hero_lead (textarea, accepta <strong>)
                   - hero_chips (repeater: icon + label)
                   - hero_callout_label / hero_callout_text
                   Pana atunci, WC `woocommerce_shop_loop_header` e omis intentionat — il
                   inlocuim cu structura aceasta editoriala. --}}
              <section class="hero">
                <span class="pill-tag">PILON <span class="sep">•</span> DETOXIFIERE &amp; CURATARE</span>
                <h1>Curata, sustine,<br><span class="underlined">regenereaza ficatul.</span></h1>
                <p class="lead">
                  Extracte standardizate de armurariu (silimarina), anghinare si turmeric care
                  <strong>contribuie la functionarea normala a ficatului</strong> si la
                  <strong>protectia celulelor impotriva stresului oxidativ.</strong>
                  Cure de 30 zile, dovedit istoric, formulat in Romania.
                </p>
                <div class="chips">
                  <span class="chip-pill"><span class="ico">🌿</span> Silimarina standardizata 80%</span>
                  <span class="chip-pill"><span class="ico">📅</span> Cura 30 zile recomandata</span>
                  <span class="chip-pill"><span class="ico">✨</span> Fara aditivi inutili</span>
                </div>
                <div class="hero-callout">
                  <div class="label">PENTRU CINE ESTE RECOMANDAT?</div>
                  <p>Persoane care simt greutate dupa mese bogate, balonare matinala, oboseala fara cauza dupa 40 ani, ten obosit. Util si pentru cei care consuma alcool ocazional, mananca des in oras sau au luat antibiotice / medicatie cronica si vor sa sustina ficatul natural.</p>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
      <div class="breadcrumb_archive">
        <div class="sort_wrapper">
          @php do_action('woocommerce_before_shop_loop') @endphp
        </div>
      </div>
    </div>

    {{-- Feature product — bloc editorial sub hero. Momentan markup static;
         urmatorul pas: legare la ACF pe term sau la un produs WC selectat. --}}
    <section class="feature-product">
      <div class="ribbon">CURA 30 ZILE</div>
      <div class="image">🌿</div>
      <div class="body">
        <div class="kicker">FORMULA DEDICATA • SUPORT HEPATIC</div>
        <h2>D-Tox Ficat</h2>
        <p class="desc">
          Formula concentrata cu silimarina (80%), extract de anghinare si turmeric standardizat.
          <strong>Sustine functionarea normala a ficatului.</strong> Capsule vegetale, fara coloranti / aromatizanti artificiali.
        </p>
        <ul class="features">
          <li>Silimarina 80% standardizata</li>
          <li>Anghinare — suport biliar</li>
          <li>Turmeric (curcumina + bioperin)</li>
          <li>Capsule vegetale, non-OGM</li>
        </ul>
        <div class="price-row">
          <div>
            <div class="price-big">139 lei</div>
            <div class="price-meta">4,63 lei / doza zilnica <span class="dot">•</span> cura 30 zile</div>
          </div>
          <a href="#" class="btn-primary">ADAUGA IN COS →</a>
        </div>
      </div>
    </section>

    <div class="fe_chips_container">
      {!! do_shortcode('[fe_chips]') !!}
    </div>

    @include('partials.shop-loop')

    {{-- Stats / trust strip — sub grila de produse. Markup static; cifrele
         si etichetele pot deveni ACF pe term in iteratia urmatoare. --}}
    <div class="container">
      <section class="stats">
        <div class="item">
          <div class="num">400+</div>
          <div class="lbl">Clienti din toata tara<br>care au facut cura</div>
        </div>
        <div class="item">
          <div class="num">4.9★</div>
          <div class="lbl">Nota medie<br>recenzii verificate</div>
        </div>
        <div class="item">
          <div class="num">14 zile</div>
          <div class="lbl">Retur fara intrebari<br>politica clara</div>
        </div>
        <div class="item">
          <div class="num">EFSA</div>
          <div class="lbl">Mentiuni de sanatate<br>conforme Reg UE 1924</div>
        </div>
      </section>
    </div>

    {{-- Phases — bloc educational sub stats. Markup static, urmeaza ACF. --}}
    <div class="container">
      <section class="phases">
        <h2>Cele 3 faze ale detoxifierii hepatice</h2>
        <p class="intro">Ficatul nu e un "filtru" simplu. Lucreaza in 3 etape complexe pe care le poti sustine prin nutritie &amp; suplimentare adecvate.</p>
        <div class="phase-grid">
          <div class="phase-card">
            <div class="phase-num">1</div>
            <h3>Faza I — Activare</h3>
            <p>Enzimele CYP450 transforma toxinele liposolubile in metaboliti reactivi. Aceasta etapa produce radicali liberi — antioxidantii (vit C, E, silimarina) <strong>contribuie la protectia celulelor.</strong></p>
            <span class="tag-light">Sustinuta de: D-Tox Ficat</span>
          </div>
          <div class="phase-card">
            <div class="phase-num">2</div>
            <h3>Faza II — Conjugare</h3>
            <p>Metabolitii reactivi sunt legati de molecule (glutation, glicina, sulfati) si transformati in compusi solubili in apa. Glutationul, vitaminele B &amp; aminoacizii sustin acest proces.</p>
            <span class="tag-light">Sustinuta de: Pachet Detox</span>
          </div>
          <div class="phase-card">
            <div class="phase-num">3</div>
            <h3>Faza III — Eliminare</h3>
            <p>Compusii solubili sunt eliminati prin bila (fecale) si urina. Hidratarea, fibrele alimentare &amp; o microbiota sanatoasa (probiotice) sunt esentiale pentru ca eliminarea sa fie completa.</p>
            <span class="tag-light">Sustinuta de: Microflora+</span>
          </div>
        </div>
      </section>
    </div>

    {{-- Daily habits — checklist editorial, markup static. --}}
    <div class="container">
      <section class="zilnic">
        <h3>✓ Cum sustii ficatul zilnic — fara supliment</h3>
        <ul>
          <li><span class="chk">✓</span><span><strong>2-2.5 litri apa / zi</strong><span class="sep">—</span>ficatul are nevoie de hidratare pentru a procesa toxinele</span></li>
          <li><span class="chk">✓</span><span><strong>Legume crucifere de 3-4 ori / saptamana</strong> (broccoli, varza, conopida)<span class="sep">—</span>sustin enzimele faza II</span></li>
          <li><span class="chk">✓</span><span><strong>Mese pana la 19:00</strong><span class="sep">—</span>ficatul lucreaza intens noaptea, nu il aglomera cu digestie</span></li>
          <li><span class="chk">✓</span><span><strong>Limit alcool la 2-3 unitati / saptamana</strong><span class="sep">—</span>alcoolul satureaza enzimele &amp; epuizeaza glutationul</span></li>
          <li><span class="chk">✓</span><span><strong>Miscare 30 min / zi</strong><span class="sep">—</span>stimuleaza circulatia hepatica &amp; eliminarea prin transpiratie</span></li>
          <li><span class="chk">✓</span><span><strong>7-9 ore somn</strong><span class="sep">—</span>ficatul are picul activitatii intre 1-3 dimineata, somnul e momentul de regenerare</span></li>
        </ul>
      </section>
    </div>

    {{-- Testimonials — verified reviews (Directiva Omnibus). Markup static,
         urmeaza legare la WC reviews / Trustindex. --}}
    <div class="container">
      <section class="testimonials">
        <h2>Marturii de la cei care au facut cura</h2>
        <p>Recenzii verificate, conform Directivei Omnibus — doar de la persoane care au cumparat efectiv produsul.</p>
        <div class="testi-grid">
          <div class="testi-card">
            <span class="verified">✓ CUMPARATURA VERIFICATA</span>
            <p class="quote">Dupa sarbatori ma simteam greoaie, balonata, lipsita de energie. Am facut Pachetul Detox 30 zile in ianuarie. Am urmat protocolul si am redus alcoolul. Dupa 3 saptamani simteam diferenta clara la energie matinala &amp; greutate dupa mese. Repet anual.</p>
            <div class="author">
              <div class="avatar">CT</div>
              <div class="meta">
                <div class="name">Carmen T., 44 ani</div>
                <div class="sub">Timisoara • cura ianuarie 2025</div>
              </div>
            </div>
          </div>
          <div class="testi-card">
            <span class="verified">✓ CUMPARATURA VERIFICATA</span>
            <p class="quote">Lucrez in restaurant, mananc des seara tarziu si nu intotdeauna sanatos. Am inceput cu D-Tox Ficat 2 cure pe an (primavara &amp; toamna), 30 zile. Apreciez ca nu da efecte adverse, capsulele sunt mici, usor de inghitit. Mi-au scazut markerii AST/ALT la analize.</p>
            <div class="author">
              <div class="avatar">FB</div>
              <div class="meta">
                <div class="name">Florin B., 51 ani</div>
                <div class="sub">Bucuresti • chef bucatar</div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    {{-- FAQ — accordion nativ <details>. Toggle-ul + / − e suprascris din CSS
         (font-size:0 + ::before) ca sa reflecte starea reala a [open], nu doar
         valoarea statica din markup. Markup static, urmeaza ACF repeater. --}}
    <div class="container">
      <section class="faq">
        <h2>Intrebari frecvente — Detoxifiere ficat</h2>
        <p>Raspunsuri clare, onorabile, fara promisiuni gresite.</p>

        <details class="faq-item" open>
          <summary class="faq-q">Cat dureaza pana simt efectul curei?<span class="faq-toggle">−</span></summary>
          <div class="faq-a">Tipic, prima saptamana e perioada de adaptare — unele persoane simt o usoara stare de oboseala ("crisis" detox) cand ficatul incepe sa proceseze mai intens. Din saptamana 2-3, multi clienti raporteaza energie matinala mai buna, balonare redusa, ten mai luminos. Cura de 30 zile e durata recomandata pentru efect complet. Variatiile individuale sunt mari.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-q">Daca am steatoza hepatica (ficat gras) sau hepatita, pot lua aceste produse?<span class="faq-toggle">+</span></summary>
          <div class="faq-a">Suplimentele alimentare nu sunt destinate diagnosticarii, tratarii, vindecarii sau prevenirii bolilor hepatice. Pentru aceste afectiuni, consulta medicul gastroenterolog inainte de a incepe orice cura cu suplimente. Nu inlocuiesc medicatia prescrisa.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-q">Ce inseamna "detox" — chiar elimina toxine?<span class="faq-toggle">+</span></summary>
          <div class="faq-a">"Detox" in marketing e adesea folosit gresit. Corpul are propriul sistem de eliminare (ficat, rinichi, intestine, piele). Suplimentele cu silimarina, anghinare etc. <strong>contribuie la functionarea normala a ficatului</strong> — adica sustin procesele naturale, nu "elimina" toxine in mod magic.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-q">Pot continua medicatia cronica in timpul curei?<span class="faq-toggle">+</span></summary>
          <div class="faq-a">Silimarina poate interactiona cu enzimele CYP450 hepatice si poate modifica metabolismul unor medicamente. Daca iei medicatie cronica (anticoagulante, statine, imunosupresoare, antidiabetice etc.), consulta medicul inainte de a incepe cura.</div>
        </details>

        <details class="faq-item">
          <summary class="faq-q">Trebuie sa tin dieta specifica in timpul curei?<span class="faq-toggle">+</span></summary>
          <div class="faq-a">Nu e obligatorie o dieta restrictiva, dar rezultatele sunt mult mai bune daca reduci alcoolul, mancarea procesata si zaharul rafinat. Cresterea consumului de legume crucifere, apa &amp; fibre amplifica efectul curei.</div>
        </details>
      </section>
    </div>

    {{-- Blog — ultimele 3 articole. Suprascriere editoriala prin ACF
         relationship `related_articles` pe term (TODO: cand ACF e setat). --}}
    @php
      $blog_args = [
          'post_type' => 'post',
          'posts_per_page' => 3,
          'post_status' => 'publish',
          'no_found_rows' => true,
          'ignore_sticky_posts' => true,
      ];

      // Cand editorialul vrea selectie manuala, comuta query-ul pe ID-urile alese.
      $related = function_exists('get_field') ? get_field('related_articles', $term) : null;
      if (! empty($related)) {
          $ids = array_map(static fn ($p) => is_object($p) ? $p->ID : (int) $p, (array) $related);
          $blog_args = [
              'post_type' => 'post',
              'posts_per_page' => 3,
              'post__in' => $ids,
              'orderby' => 'post__in',
              'no_found_rows' => true,
              'ignore_sticky_posts' => true,
          ];
      }

      $blog_posts = new \WP_Query($blog_args);
    @endphp

    @if ($blog_posts->have_posts())
      <div class="container">
        <section class="blog">
          <h2>Citeste si pe blog</h2>
          <p>Articole utile despre sanatatea ficatului bazate pe date stiintifice.</p>
          <div class="blog-grid">
            @while ($blog_posts->have_posts())
              @php
                $blog_posts->the_post();
                $cats = get_the_category();
                $primary_tag = $cats ? strtoupper($cats[0]->name) : '';
                $date_formatted = wp_date('j M Y');
              @endphp
              <a class="blog-card" href="{{ esc_url(get_permalink()) }}">
                <div class="image">
                  @if (has_post_thumbnail())
                    {!! get_the_post_thumbnail(get_the_ID(), 'medium_large', [
                      'loading' => 'lazy',
                      'alt' => esc_attr(get_the_title()),
                    ]) !!}
                  @else
                    <span class="emoji" aria-hidden="true">📰</span>
                  @endif
                </div>
                <div class="body">
                  @if ($primary_tag)
                    <div class="tag">{{ $primary_tag }}</div>
                  @endif
                  <h3>{{ get_the_title() }}</h3>
                  <div class="meta">{{ $date_formatted }}</div>
                </div>
              </a>
            @endwhile
            @php wp_reset_postdata(); @endphp
          </div>
        </section>
      </div>
    @endif

    {{-- Legal disclaimer — Regulamentul UE 1924/2006, ANSVSA, contraindicatii.
         Markup static; cand devin diferite per categorie, mutam in ACF textarea
         (`legal_box_content`) cu acest text ca default. --}}
    <div class="container">
      <div class="legal-box">
        <div class="label">⚠ INFORMATII LEGALE <span class="sep">•</span> IMPORTANT</div>
        <p>Produsele prezentate sunt <strong>suplimente alimentare</strong>, notificate la <strong>ANSVSA</strong>. Mentiunile de sanatate respecta <strong>Regulamentul UE 1924/2006</strong> si listele EFSA: "silimarina contribuie la mentinerea functiei normale a ficatului"; "anghinarea contribuie la digestia normala"; "vitaminele &amp; mineralele contribuie la metabolismul energetic normal".</p>
        <p><strong>Suplimentele alimentare nu sunt destinate diagnosticarii, tratarii, vindecarii sau prevenirii bolilor hepatice (hepatite, ciroza, steatoza patologica, cancer hepatic).</strong> Pentru aceste afectiuni, consulta medicul gastroenterolog. Nu inlocuiesc medicatia prescrisa.</p>
        <p>Persoanele cu litiaza biliara, gravidele &amp; cele care alapteaza, copiii sub 18 ani, persoanele alergice la plantele Asteraceae (armurariu, anghinare, papadie) trebuie sa consulte medicul inainte de utilizare. <strong>A nu se depasi doza zilnica recomandata.</strong></p>
      </div>
    </div>
  </div>{{-- /.archive-product-wrap --}}
@endsection
