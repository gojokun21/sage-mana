<?php

/**
 * Conținutul editorial al Home page (template `template-home.blade.php`).
 *
 * Sursă unică de adevăr:
 *   - App\Console\Commands\HomeSeed (`wp acorn natura:home-seed`) populează ACF-ul
 *     paginii Home din acest array (chei = numele câmpurilor ACF din app/acf-home.php).
 *   - partials/home/* îl folosesc ca FALLBACK (prin \App\home_field()) când ACF e gol,
 *     deci pagina arată identic și înainte de seed.
 *
 * Produsele (flagship) și articolele (blog) rămân DINAMICE din WooCommerce/WP —
 * aici doar textul editorial (titluri, descrieri, CTA-uri, carduri statice).
 * Repeater-ele folosesc exact numele sub-câmpurilor ACF ca chei.
 */
defined('ABSPATH') || exit;

return [
    // ---- HERO ----
    'hero_eyebrow' => __('Ediția de toamnă · lot R24-091', 'sage'),
    'hero_titlu' => __('Suplimente onest formulate.', 'sage'),
    'hero_titlu_em' => __('Fără hype, fără promisiuni de basm.', 'sage'),
    'hero_lede' => __('Fiecare produs vine cu <strong>raportul de analiză al lotului</strong>, etichetă INCI scurtă și un protocol în 3 etape. Dacă ceva nu funcționează, îți recomandăm pe altul — sau îți spunem că nu ai nevoie de niciunul.', 'sage'),
    'hero_cta_primary' => __('Vezi suplimentele', 'sage'),
    'hero_cta_secondary' => __('Fă testul de 60 sec', 'sage'),
    'hero_cta_secondary_url' => '#test',
    'hero_trust' => [
        ['text' => __('Analize publice', 'sage')],
        ['text' => __('Retur 14 zile', 'sage')],
        ['text' => __('Anulare abonament în 2 click-uri', 'sage')],
    ],

    // ---- FILOSOFIE ----
    'philo_eyebrow' => __('Filosofia noastră', 'sage'),
    'philo_titlu' => __('Un supliment bun nu ține loc de', 'sage'),
    'philo_titlu_em' => __('medic, somn sau salată.', 'sage'),
    'philo_text' => __('Dar dacă mesele tale sunt deja ok și tot e ceva în neregulă — acolo intervenim noi. Onest, măsurat, cu produsul potrivit pentru tine.', 'sage'),

    // ---- ENTRY POINTS ----
    'entry_eyebrow' => __('De unde începi?', 'sage'),
    'entry_titlu' => __('Alege', 'sage'),
    'entry_titlu_em' => __('drumul tău.', 'sage'),
    'entry_cards' => [
        ['titlu' => __('După obiectiv', 'sage'), 'text' => __('Energie, somn, imunitate, digestie, frumusețe.', 'sage'), 'link_text' => __('Vezi obiectivele', 'sage'), 'url' => '#obiective', 'chip' => ''],
        ['titlu' => __('După simptom', 'sage'), 'text' => __('Balonare, oboseală, ceața mentală, păr fragil, somn agitat.', 'sage'), 'link_text' => __('Vezi simptomele', 'sage'), 'url' => '#simptome', 'chip' => ''],
        ['titlu' => __('Performanță sportivă', 'sage'), 'text' => __('Proteine, creatină, recuperare, focus pre-antrenament.', 'sage'), 'link_text' => __('Vezi categoria', 'sage'), 'url' => '#sport', 'chip' => ''],
        ['titlu' => __('Promoții oneste', 'sage'), 'text' => __('Doar produse care expiră în 4+ luni. Niciun preț scump artificial.', 'sage'), 'link_text' => __('Vezi promoțiile', 'sage'), 'url' => '#promotii', 'chip' => __('Ediția de toamnă', 'sage')],
    ],

    // ---- QUIZ STRIP ----
    'quiz_eyebrow' => __('Test de 60 secunde', 'sage'),
    'quiz_titlu' => __('Nu știi de unde', 'sage'),
    'quiz_titlu_em' => __('să începi?', 'sage'),
    'quiz_text' => __('7 întrebări scurte. Niciun email cerut până nu vrei rezultatul detaliat. Niciun upsell agresiv — dacă nu ai nevoie de nimic, îți spunem direct.', 'sage'),
    'quiz_cta' => __('Începe testul', 'sage'),
    'quiz_cta_url' => home_url('/test/'),
    'quiz_micro' => __('Folosit de 8.234 oameni în ultima lună.', 'sage'),

    // ---- FLAGSHIP (text; produsele sunt dinamice) ----
    'flagship_eyebrow' => __('Cele 3 pe care le recomandăm cel mai des', 'sage'),
    'flagship_titlu' => __('Dacă ar fi să alegem trei,', 'sage'),
    'flagship_titlu_em' => __('ar fi astea.', 'sage'),
    'flagship_slots' => [
        ['eyebrow_class' => 'gold', 'eyebrow_text' => __('Elixir · ediție limitată', 'sage')],
        ['eyebrow_class' => 'green', 'eyebrow_text' => __('Pachet · recomandat', 'sage')],
        ['eyebrow_class' => 'green', 'eyebrow_text' => __('Performanță', 'sage')],
    ],
    'flagship_foot' => __('Vezi tot catalogul', 'sage'),

    // ---- TRUST ----
    'trust_eyebrow' => __('De ce ne-ar putea păsa', 'sage'),
    'trust_titlu' => __('Când cumperi un supliment,', 'sage'),
    'trust_titlu_em' => __('cumperi de fapt încredere.', 'sage'),
    'trust_cells' => [
        ['titlu' => __('Analize de lot publice.', 'sage'), 'text' => __('Fiecare lot are PDF cu valorile măsurate. Nu doar declarat — măsurat în laborator independent.', 'sage'), 'link_text' => __('Vezi un raport exemplu →', 'sage'), 'link_url' => '#analize-exemplu'],
        ['titlu' => __('Retur 14 zile, doar dacă produsul este sigilat.', 'sage'), 'text' => __('Dacă produsul este nedesfăcut și în ambalajul original, îl primim înapoi în 14 zile. Plătim noi transportul de retur.', 'sage'), 'link_text' => '', 'link_url' => ''],
        ['titlu' => __('Anularea abonamentului — 2 click-uri.', 'sage'), 'text' => __('Din cont, fără form, fără sună-ne, fără întrebări tip „ești sigur?". Click, gata.', 'sage'), 'link_text' => '', 'link_url' => ''],
    ],

    // ---- BLOG (text; articolele sunt dinamice) ----
    'blog_eyebrow' => __('Din jurnalul nostru', 'sage'),
    'blog_titlu' => __('Ce scriem,', 'sage'),
    'blog_titlu_em' => __('când nu vindem.', 'sage'),
    'blog_foot' => __('Toate articolele →', 'sage'),

    // ---- TESTIMONIALE ----
    'testi_eyebrow' => __('Ce spun oamenii care ne-au folosit produsele 60+ zile', 'sage'),
    'testi_titlu' => __('Recenzii pe care', 'sage'),
    'testi_titlu_em' => __('nu le-am editat.', 'sage'),
    'testi_cards' => [
        ['rating' => 4, 'quote' => __('„Al treilea flacon. Diferența se vede după lună, dar se vede."', 'sage'), 'nume' => 'Andreea M.', 'rol' => __('38 · Cluj', 'sage'), 'produs' => 'Black Seed Elixir', 'verificat' => __('verificat · 4 luni', 'sage')],
        ['rating' => 5, 'quote' => __('„Am cumpărat pentru imunitate, fără să iau zinc și vitamina C. Trei săptămâni să simt o diferență — energia după-amiezei e alta."', 'sage'), 'nume' => 'Mihai R.', 'rol' => __('42 · București', 'sage'), 'produs' => 'Microflora+', 'verificat' => __('verificat · 2 luni', 'sage')],
        ['rating' => 4, 'quote' => __('„Nu e dulce, exact așa cum scrie. Mi-a luat 2 săptămâni să mă obișnuiesc. Acum nu mai pot la celelalte."', 'sage'), 'nume' => 'Bogdan T.', 'rol' => __('28 · Brașov', 'sage'), 'produs' => 'ChocoProtein', 'verificat' => __('verificat · 3 luni', 'sage')],
    ],

    // ---- NEWSLETTER (inline) ----
    'news_eyebrow' => __('Jurnal lunar', 'sage'),
    'news_titlu' => __('Un email pe lună,', 'sage'),
    'news_titlu_em' => __('niciodată mai des.', 'sage'),
    'news_text' => __('Ultimele lucruri pe care le-am învățat despre suplimentare, plus dacă apare ceva nou în laborator. Fără vouchere, fără „ultima zi de reducere", fără oferte de team-leader.', 'sage'),
    'news_placeholder' => __('email@exemplu.com', 'sage'),
    'news_button' => __('Abonează-mă', 'sage'),
    'news_micro' => __('Nu pre-bifăm nimic. Te dezabonezi din primul email dacă-ți schimbi părerea.', 'sage'),
];
