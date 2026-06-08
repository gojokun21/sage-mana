<?php

/**
 * Seed pentru pagina filtru „Noutăți · În curând” (template-noutati.blade.php).
 * Conținut editorial + cele 3 tincturi (date editoriale — produse viitoare).
 * Transcris din preferinte/Pagina filtru - Noutati In curand.html.
 *
 * Folosit de App\seed_noutati (`wp acorn natura:noutati-seed`, link sau Unelte).
 */

return [
    'page' => [
        'title' => 'Noutăți · În curând',
        'slug' => 'noutati-in-curand',

        'hero' => [
            'eyebrow' => 'Noutăți · În curând',
            'titlu' => 'Ce vine <em>în curând.</em>',
            'brand_by' => 'by Vivens Genetica',
            'lede' => '<strong>{count} tincturi</strong> în dezvoltare, în așteptarea aprobărilor oficiale.',
            'disclaimer_label' => 'Important · înainte să citești mai departe',
            'disclaimer_text' => 'Aceste produse <strong>nu sunt încă pe stoc</strong>. Așteptăm aprobările ANSVSA pentru notificarea suplimentelor cu plante. Dacă vrei să fii anunțat când sunt gata, lasă-ți emailul jos — fără pre-comenzi, fără plăți avansate.',
            'cta_text' => 'Anunță-mă la lansare',
            'cta_url' => '', // gol = #notify
        ],

        'explain' => [
            'eyebrow' => 'De ce durează',
            'titlu' => 'De ce nu lansăm <em>în grabă.</em>',
            'cards' => [
                ['titlu' => 'Aprobarea contează.', 'text' => 'În România, suplimentele cu plante necesită <strong>notificare la ANSVSA</strong>. Procesul durează 3–9 luni și verifică siguranța consumatorului. Nu trecem peste el.'],
                ['titlu' => 'Formularea finală se schimbă.', 'text' => 'Până la aprobare, <strong>dozajele se pot modifica</strong> pe baza recomandărilor autorităților. Așa că prețurile și specificațiile de mai jos sunt PRELIMINARE.'],
                ['titlu' => 'Cantitate limitată la prima cură.', 'text' => 'La lansare vom avea <strong>stoc limitat</strong>. Cei înscriși pe lista de email primesc acces cu 7 zile înainte de public — fără reduceri false, doar prioritate de cumpărare.'],
            ],
        ],

        'tinctures_titlu' => 'Cele {count} tincturi <em>în dezvoltare.</em>',
        'tinctures_sub' => 'Numele și specificațiile sunt placeholder — se finalizează după aprobare.',

        'tinctures' => [
            [
                'theme' => 't-vas',
                'pending_badge' => 'În așteptarea aprobării ANSVSA',
                'bottle_label' => 'Dreno|VAS',
                'cat_chip' => 'Circulație & Drenaj',
                'name' => 'Dreno <em>VAS</em>',
                'brand_line' => 'by Vivens Genetica',
                'role' => 'Drenaj vascular și limfatic, suport pentru picioare grele și circulație venoasă.',
                'specs' => 'Extract hidroalcoolic 1:3 · 30 ml · 28–30% alcool · 7 plante',
                'usage' => 'Adulți <strong>25–30 picături de 3 ori/zi</strong>, înainte de masă.',
                'ingredients_summary' => 'Compoziție · 7 plante',
                'ingredients' => [
                    ['plant' => 'Castan sălbatic', 'latin' => 'Aesculus hippocastanum · fruct', 'pct' => '20%'],
                    ['plant' => 'Curcuma', 'latin' => 'Curcuma longa · pulbere', 'pct' => '20%'],
                    ['plant' => 'Păpădie', 'latin' => 'Taraxacum officinale · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Coada calului', 'latin' => 'Equisetum arvense · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Măceș', 'latin' => 'Rosa canina · fruct', 'pct' => '10%'],
                    ['plant' => 'Coada șoricelului', 'latin' => 'Achillea millefolium · părți aeriene', 'pct' => '10%'],
                    ['plant' => 'Gălbenele', 'latin' => 'Calendula officinalis · părți aeriene', 'pct' => '10%'],
                ],
                'benefits' => [
                    ['text' => 'Susține circulația venoasă normală la nivelul membrelor inferioare (castan sălbatic)'],
                    ['text' => 'Sprijină procesele naturale de drenaj și eliminarea excesului de lichide (păpădie, coada calului)'],
                    ['text' => 'Contribuie la protecția vasculară și la confortul circulator (curcuma)'],
                    ['text' => 'Suport antioxidant pentru integritatea țesuturilor (măceș, gălbenele, coada șoricelului)'],
                ],
                'contraindic_label' => 'Contraindicații',
                'contraindic_text' => 'Nu în sarcină, alăptare sau la copii. Conține alcool 28–30% — nu se recomandă persoanelor cărora le este interzis sau limitat consumul de alcool.',
                'contraindic_extra_label' => '',
                'contraindic_extra_text' => '',
                'status_label' => 'Status & preț',
                'status_rows' => [
                    ['k' => 'Notificare', 'v' => 'ANSVSA în curs', 'type' => 'normal'],
                    ['k' => 'Preț', 'v' => 'Se anunță la lansare', 'type' => 'tba'],
                    ['k' => 'Disponibil', 'v' => 'Trimestrul 2–3 · 2026', 'type' => 'normal'],
                ],
                'notify_btn' => 'Anunță-mă când e gata Dreno VAS',
            ],
            [
                'theme' => 't-colon',
                'pending_badge' => 'În așteptarea aprobării ANSVSA',
                'bottle_label' => 'Colon|BALANCE',
                'cat_chip' => 'Digestie & Echilibru Intestinal',
                'name' => 'Colon <em>BALANCE</em>',
                'brand_line' => 'by Vivens Genetica',
                'role' => 'Echilibru intestinal, tranzit normal, reducere balonare și disconfort abdominal.',
                'specs' => 'Extract hidroalcoolic 1:3 · 30 ml · 28–30% alcool · 8 plante',
                'usage' => 'Adulți <strong>25–30 picături de 3 ori/zi</strong>, înainte de masă.',
                'ingredients_summary' => 'Compoziție · 8 plante',
                'ingredients' => [
                    ['plant' => 'Țintaură', 'latin' => 'Centaurium erythraea · părți aeriene', 'pct' => '20%'],
                    ['plant' => 'Mentă', 'latin' => 'Mentha piperita · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Cimbru', 'latin' => 'Thymus vulgaris · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Nucă neagră', 'latin' => 'Juglans nigra · fruct', 'pct' => '10%'],
                    ['plant' => 'Nucă verde', 'latin' => 'Juglans regia · fruct', 'pct' => '10%'],
                    ['plant' => 'Pelin', 'latin' => 'Artemisia absinthium · părți aeriene', 'pct' => '10%'],
                    ['plant' => 'Chimen', 'latin' => 'Carum carvi · părți aeriene', 'pct' => '10%'],
                    ['plant' => 'Coada șoricelului', 'latin' => 'Achillea millefolium · părți aeriene', 'pct' => '10%'],
                ],
                'benefits' => [
                    ['text' => 'Ajută la reglarea tranzitului intestinal (nucă neagră, nucă verde)'],
                    ['text' => 'Favorizează activitatea digestivă prin gustul amar natural (țintaură, pelin)'],
                    ['text' => 'Reduce senzația de balonare și disconfort abdominal (mentă, chimen)'],
                    ['text' => 'Sprijină echilibrul florei intestinale și protejează mucoasa (cimbru, coada șoricelului)'],
                ],
                'contraindic_label' => 'Contraindicații',
                'contraindic_text' => 'Nu în sarcină, alăptare sau la copii. Conține alcool 28–30% — nu se recomandă persoanelor cărora le este interzis sau limitat consumul de alcool.',
                'contraindic_extra_label' => '',
                'contraindic_extra_text' => '',
                'status_label' => 'Status & preț',
                'status_rows' => [
                    ['k' => 'Notificare', 'v' => 'ANSVSA în curs', 'type' => 'normal'],
                    ['k' => 'Preț', 'v' => 'Se anunță la lansare', 'type' => 'tba'],
                    ['k' => 'Disponibil', 'v' => 'Trimestrul 2–3 · 2026', 'type' => 'normal'],
                ],
                'notify_btn' => 'Anunță-mă când e gata Colon BALANCE',
            ],
            [
                'theme' => 't-neuro',
                'pending_badge' => 'În așteptarea aprobării ANSVSA',
                'bottle_label' => 'Neuro|BALANCE',
                'cat_chip' => 'Echilibru Emoțional',
                'name' => 'Neuro <em>BALANCE</em>',
                'brand_line' => 'by Vivens Genetica',
                'role' => 'Echilibru emoțional, suport la stres, claritate mentală și relaxare.',
                'specs' => 'Extract hidroalcoolic 1:3 · 30 ml · 28–30% alcool · 8 plante',
                'usage' => 'Adulți <strong>25–30 picături de 3 ori/zi</strong>, înainte de masă.',
                'ingredients_summary' => 'Compoziție · 8 plante',
                'ingredients' => [
                    ['plant' => 'Roiniță', 'latin' => 'Melissa officinalis · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Sunătoare', 'latin' => 'Hypericum perforatum · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Salvie', 'latin' => 'Salvia officinalis · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Păducel', 'latin' => 'Crataegus monogyna · părți aeriene', 'pct' => '15%'],
                    ['plant' => 'Valeriană', 'latin' => 'Valeriana officinalis · rădăcină', 'pct' => '10%'],
                    ['plant' => 'Busuioc', 'latin' => 'Ocimum basilicum · părți aeriene', 'pct' => '10%'],
                    ['plant' => 'Cătină', 'latin' => 'Hippophae rhamnoides · fruct', 'pct' => '10%'],
                    ['plant' => 'Rozmarin', 'latin' => 'Rosmarinus officinalis · părți aeriene', 'pct' => '10%'],
                ],
                'benefits' => [
                    ['text' => 'Ajută la menținerea echilibrului emoțional în perioade de stres (roiniță, sunătoare)'],
                    ['text' => 'Susține relaxarea, starea de liniște și adaptarea la stresul cotidian (valeriană, busuioc)'],
                    ['text' => 'Contribuie la susținerea funcției cardio-vegetative asociate tensiunii nervoase (păducel)'],
                    ['text' => 'Ajută la menținerea clarității mentale în perioade de suprasolicitare (salvie, rozmarin, cătină)'],
                ],
                'contraindic_label' => 'Contraindicații',
                'contraindic_text' => 'Nu în sarcină, alăptare sau la copii. Conține alcool 28–30%.',
                'contraindic_extra_label' => 'Atenție specială',
                'contraindic_extra_text' => 'Sunătoarea poate interacționa cu anumite medicamente (antidepresive, anticoagulante, contraceptive orale). Consultă medicul înainte de utilizare dacă iei tratament.',
                'status_label' => 'Status & preț',
                'status_rows' => [
                    ['k' => 'Notificare', 'v' => 'ANSVSA în curs', 'type' => 'normal'],
                    ['k' => 'Preț', 'v' => 'Se anunță la lansare', 'type' => 'tba'],
                    ['k' => 'Disponibil', 'v' => 'Trimestrul 3–4 · 2026', 'type' => 'normal'],
                ],
                'notify_btn' => 'Anunță-mă când e gata Neuro BALANCE',
            ],
        ],

        'why' => [
            'eyebrow' => 'Despre formă',
            'titlu' => 'De ce <em>tincturi.</em>',
            'cards' => [
                ['titlu' => 'Formă concentrată, <em>absorbție rapidă.</em>', 'text' => 'Tincturile sunt extracte alcoolice sau glicerinate ale plantelor. Sunt <strong>mai concentrate decât ceaiurile</strong> și se absorb mai rapid decât capsulele — sublingual, prin mucoasa orală, ocolind tractul digestiv pentru câteva minute mai devreme.'],
                ['titlu' => 'Cantitate exactă, <em>gust real.</em>', 'text' => 'Picurătorul îți permite <strong>dozare precisă</strong> — de la 10 la 30 de picături în funcție de nevoie. Nu sunt ascunse într-o capsulă; simți gustul plantelor, ceea ce contribuie la acțiunea reflexă orală și la conectarea senzorială cu remediul.'],
            ],
        ],

        'notify' => [
            'eyebrow' => 'Listă de lansare',
            'titlu' => 'Anunță-mă <em>când sunt gata.</em>',
            'lede' => 'Îți scriem o singură dată per lansare. Fără newsletter, fără spam, fără reclame la alte produse.',
            'email_label' => 'Adresa ta de email',
            'email_placeholder' => 'email@exemplu.ro',
            'which_label' => 'Pentru care tincturi vrei să fii anunțat',
            'consent' => 'Înțeleg că primesc <strong>un singur email</strong> când fiecare tinctură este disponibilă, după care mă pot dezabona oricând cu un click.',
            'submit' => 'Înscrie-mă pe listă',
            'post_line' => 'Nu vindem emailurile. Nu facem retargeting. Nu trimitem newslettere.',
        ],

        'faq' => [
            'titlu' => 'Întrebări <em>frecvente.</em>',
            'items' => [
                ['q' => 'Când exact se lansează?', 'a' => 'Trimestre estimate (Q2–Q4 2026), dar <strong>depinde de aprobări</strong>. Nu putem promite date exacte. Cei înscriși pe lista de email află cu 7 zile înainte de public — așa că e cea mai sigură metodă de a nu rata lansarea.'],
                ['q' => 'Pot rezerva cu plată acum?', 'a' => '<strong>NU.</strong> Nu acceptăm plăți până când produsele sunt notificate ANSVSA. E ilegal să încasezi bani pentru un produs nenotificat. Lista de email e gratis, fără obligație, fără card cerut.'],
                ['q' => 'Prețul afișat este final?', 'a' => '<strong>NU.</strong> Este estimat — se poate modifica ușor (în sus sau în jos) după aprobare și formularea finală. După aprobare ANSVSA putem avea ajustări de ±10–15 lei.'],
                ['q' => 'De ce nu lansați mai repede?', 'a' => '<strong>Brand mic, fără grabă.</strong> Preferăm un lansament corect, nu unul făcut cu jumătate de măsură. Aprobarea ANSVSA, formularea testată extern, etichetarea conformă, stocul inițial — toate cer timp.'],
            ],
        ],

        'cta' => [
            'titlu' => 'Până atunci, vezi ce ai <em>deja la dispoziție.</em>',
            'text' => '<strong>20 de suplimente</strong> și <strong>11 pachete</strong> deja disponibile, cu studii, reviews și analize publice de lot. Probabil ce cauți există deja.',
            'primary_text' => 'Vezi catalogul',
            'primary_url' => '',
            'outline_text' => 'Fă testul de 60 sec',
            'outline_url' => '',
        ],
    ],
];
