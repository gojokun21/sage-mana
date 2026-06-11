<?php

/**
 * Date seed pentru secțiunile editoriale PDP — transcrise din mockup-urile
 * `preferinte/PDP - *.html`. Cheia este slug-ul produsului WooCommerce.
 *
 * Consumat de App\seed_pdp() (app/pdp-seed.php). Câmpurile ACF sunt definite
 * în app/acf-pdp.php (grup `group_pdp_editorial`). `excerpt` suprascrie
 * post_excerpt (descrierea scurtă afișată în hero).
 *
 * Notă: în mockup-ul Collagen Joint+ doar prima întrebare FAQ avea răspuns;
 * restul răspunsurilor au fost completate concis, consecvent cu datele
 * produsului — de revizuit editorial dacă e cazul.
 *
 * Structura:
 *  - 'products'            → conținut editorial PDP per slug (ACF grup PDP + excerpt)
 *  - 'informatie_generala' → corecții pe grupul `informatie_generala` per slug:
 *    `forma` = text ambalaj (afișat în badge-uri), `protocol_zile` = STRICT
 *    numeric (zile de cură) — din el se calculează costul/zi în coș,
 *    template-sub-200 și template-cele-mai-vandute.
 */

return [

    // ===================================================================
    // informatie_generala — forma + protocol_zile (toate produsele)
    // ===================================================================
    'informatie_generala' => [
        // Produse simple
        'lionfocus-b6-jeleuri' => ['forma' => '60 jeleuri', 'protocol_zile' => 30],
        'vita-complete-vegan-shots-500-ml-50-shots' => ['forma' => '500 ml · 50 doze', 'protocol_zile' => 50],
        'collagen-joint-berry-500-ml' => ['forma' => '500 ml · 33 doze', 'protocol_zile' => 33],
        'microflora-lemon-shots-500-ml-33-shots' => ['forma' => '500 ml · 33 doze', 'protocol_zile' => 33],
        'd-tox-ficat' => ['forma' => '120 capsule', 'protocol_zile' => 120],
        'black-seed-elixir' => ['forma' => '240 capsule', 'protocol_zile' => 120],
        'creatine-monohidrate-pro-1000g' => ['forma' => '1000 g · 200 porții', 'protocol_zile' => 200],
        'icetea-whey-lemon-500g' => ['forma' => '500 g · 16 porții', 'protocol_zile' => 16],
        'chocoprotein-1000g' => ['forma' => '1000 g · 33 porții', 'protocol_zile' => 33],

        // Pachete — protocol numeric (înainte: text „Ajunge X de zile" care
        // strica calculul cost/zi). Forma rămâne goală.
        'pachet-focus' => ['forma' => '', 'protocol_zile' => 50],
        'pachet-detox' => ['forma' => '', 'protocol_zile' => 120],
        'pachet-imunitate' => ['forma' => '', 'protocol_zile' => 120],
        'pachet-frumusete' => ['forma' => '', 'protocol_zile' => 50],
        'pachet-echilibru' => ['forma' => '', 'protocol_zile' => 33],
        'pachet-energie' => ['forma' => '', 'protocol_zile' => 120],
        'pachet-complex-sanatate' => ['forma' => '', 'protocol_zile' => 120],
        'pachet-detox-plus' => ['forma' => '', 'protocol_zile' => 120],
        'pachet-vitalitate' => ['forma' => '', 'protocol_zile' => 50],
        'pachet-confort-digestiv' => ['forma' => '', 'protocol_zile' => 120],
        'pachet-regenerare-celulara' => ['forma' => '', 'protocol_zile' => 120],
    ],

    // ===================================================================
    // Conținut editorial PDP
    // ===================================================================
    'products' => [

        // -------------------------------------------------------------------
        // Black Seed Elixir
        // -------------------------------------------------------------------
        'black-seed-elixir' => [
            'excerpt' => '<strong>Ulei de Nigella sativa egiptean încapsulat.</strong> Vitamina E naturală. 4 luni de susținere pentru imunitate, inimă și echilibru metabolic.',
            'pdp_eyebrow' => 'Capsule · imunitate & sănătatea inimii',
            'pdp_subline' => 'ulei de chimen negru egiptean presat la rece · vitamina E naturală · 240 capsule · 4 luni.',
            'ihl' => [
                'eyebrow' => 'Ingredient cheie · spus complet',
                'titlu' => 'Chimen negru egiptean. <em>Presat la rece.</em>',
                'caption' => 'semințe Nigella sativa · ulei presat la rece · capsulă moale vegetală',
                'prose' => [
                    '<strong>Nigella sativa egipteană</strong> este recunoscută de secole în tradițiile orientale pentru rolul ei în sănătate. Presarea la rece păstrează compușii bioactivi (timochinonă, acizi grași nesaturați) intacți.',
                    '<strong>Vitamina E naturală</strong> amplifică protecția antioxidantă. Capsulele moi vegetale elimină gustul pronunțat al uleiului lichid și asigură doza exactă zilnic.',
                ],
                'rows' => [
                    ['lbl' => 'Origine ulei', 'val' => '<strong>Egipt</strong> — Nigella sativa'],
                    ['lbl' => 'Metodă', 'val' => '<strong>Presare la rece</strong> (păstrează bioactivi)'],
                    ['lbl' => 'Compus principal', 'val' => 'Timochinonă (natural)'],
                    ['lbl' => 'Vitamina E', 'val' => '<strong>Naturală</strong> (nu sintetică)'],
                    ['lbl' => 'Format', 'val' => 'Capsulă moale vegetală'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum îl folosești',
                'intro' => 'Câte o capsulă cu fiecare masă principală. Răbdare 8–12 săptămâni. Atât.',
                'steps' => [
                    ['when' => 'Dimineața cu micul dejun', 'titlu' => '1 capsulă cu apă.', 'text' => 'În timpul mesei. Uleiul se absoarbe mai bine în prezența lipidelor alimentare.'],
                    ['when' => 'Seara cu cina', 'titlu' => '1 capsulă cu o masă.', 'text' => 'Total 2 pe zi. Dozajul împărțit pe parcursul zilei menține un nivel constant.'],
                    ['when' => 'Consecvent · 8–12 săptămâni', 'titlu' => 'Răbdare. Efectul se construiește.', 'text' => 'Efectele pe imunitate și metabolism se construiesc treptat. Diferențele se simt după 4–6 săptămâni.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Persoane care vor susținere imunitară zilnică',
                    'Cei cu sensibilitate cardiovasculară (familie cu istoric)',
                    'Perioade de stres sau oboseală cronică',
                    'Oricine vrea o sursă naturală de antioxidanți',
                ],
                'nu' => [
                    'Minori sub 12 ani',
                    'Alergie la Nigella sativa sau familia Ranunculaceae',
                    'Femei însărcinate sau care alăptează — consultă medicul',
                    'Persoane sub tratament anticoagulant — consultă medicul',
                ],
            ],
            'stand' => [
                ['titlu' => 'Origine trasabilă', 'text' => 'Nigella sativa egipteană dintr-o regiune dedicată cultivării. Fără broker intermediar.'],
                ['titlu' => 'Presare la rece', 'text' => 'Fără solvenți, fără căldură care distruge bioactivii. Timochinona și acizii grași rămân intacți.'],
                ['titlu' => 'Test de puritate pe fiecare lot', 'text' => 'Analize publice, lot tracker pe ambalaj. Fără metale grele, pesticide sau micotoxine.'],
            ],
            'faq' => [
                'nume' => 'Black Seed Elixir',
                'items' => [
                    ['q' => 'De ce capsule și nu ulei lichid?', 'a' => 'Capsulele <strong>protejează uleiul de oxidare</strong>, sunt mai ușor de luat (fără gust pronunțat) și asigură doza exactă zilnic. Uleiul lichid se oxidează rapid după deschidere și are gust intens.'],
                    ['q' => 'Pot să-l iau pe termen lung?', 'a' => 'Da, este conceput pentru <strong>utilizare zilnică prelungită</strong>. Multe persoane fac cure de 4–6 luni urmate de pauze scurte.'],
                    ['q' => 'Există interacțiuni cu medicamente?', 'a' => 'Da, posibil cu <strong>anticoagulante</strong> (timochinona poate avea efect ușor de subțiere a sângelui). Consultă medicul dacă iei warfarină sau medicamente similare.'],
                    ['q' => 'Câte zile durează un flacon?', 'a' => '<strong>120 de zile cu 2 capsule/zi.</strong> Aproximativ 4 luni de cură completă.'],
                    ['q' => 'Pot să-l combin cu alte suplimente?', 'a' => 'Da, mai ales cu <strong>multivitamine și probiotice</strong>. Black Seed Elixir se integrează ușor într-o stivă cu Vita Complete+ sau Microflora+.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // ChocoProtein 1000g
        // -------------------------------------------------------------------
        'chocoprotein-1000g' => [
            'excerpt' => 'Proteină din zer cu <strong>gust real de ciocolată</strong>. 33 porții într-un borcan. Pentru construcție musculară și refacere — fără zahăr adăugat.',
            'pdp_eyebrow' => 'Pudră · performanță sportivă',
            'pdp_subline' => 'concentrat zer >75% proteină · 1000 g · 33 porții · 30 g/zi · gust intens ciocolată.',
            'ihl' => [
                'eyebrow' => 'Ingredient · spus complet',
                'titlu' => 'Concentrat de zer. <em>Plus ciocolată reală.</em>',
                'caption' => 'cacao real + concentrat zer >75% proteină · profil complet aminoacizi',
                'prose' => [
                    'Concentratul de zer (<strong>whey concentrate</strong>) este cea mai populară formă de proteină din zer: <strong>peste 75% proteină</strong>, profil complet de aminoacizi esențiali, asimilare bună. Echilibrul ideal între puritate și preț.',
                    'Gustul de <strong>ciocolată provine din cacao real</strong>, fără zahăr adăugat. O șansă reală să iubești proteina din shake, nu doar să o tolerezi.',
                ],
                'rows' => [
                    ['lbl' => 'Tip proteină', 'val' => '<strong>Concentrat din zer</strong> (whey concentrate)'],
                    ['lbl' => 'Proteină / 100g', 'val' => '<strong>peste 75%</strong> proteină'],
                    ['lbl' => 'Aminoacizi esențiali', 'val' => 'Profil complet (BCAA)'],
                    ['lbl' => 'Aditivi', 'val' => '<strong>0</strong> zahăr · fără îndulcitori artificiali'],
                    ['lbl' => 'Alergeni', 'val' => 'Lapte, soia (lecitină)'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum îl folosești',
                'intro' => 'O măsură (30g) în apă sau lapte. Bei imediat. Hidratezi în paralel. Atât.',
                'steps' => [
                    ['when' => 'Post-antrenament', 'titlu' => '1 măsură (30g) în 300–400ml apă sau lapte vegetal.', 'text' => 'Agită bine, bei imediat. Fereastra optimă post-efort: 30–60 minute. Cele mai bune rezultate pe construcție musculară.'],
                    ['when' => 'Între mese', 'titlu' => 'Ca gustare bogată în proteină.', 'text' => 'Ideal la 2–3 ore între mesele principale. Susține aportul proteic constant pe parcursul zilei.'],
                    ['when' => 'Hidratare în paralel', 'titlu' => 'Minim 2 litri apă/zi.', 'text' => 'Proteina necesită apă pentru metabolizare. Fără hidratare suficientă, digestia se îngreunează inutil.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Sportivi de forță și tonifiere',
                    'Persoane active care se antrenează regulat',
                    'Perioade de creștere musculară sau menținere',
                    'Cei cu nevoie crescută de proteină în dietă',
                ],
                'nu' => [
                    'Alergie la lapte (lactalbumină) sau soia',
                    'Intoleranță severă la lactoză',
                    'Vegani — există variante vegetale alternative',
                    'Minori sub 16 ani',
                ],
            ],
            'stand' => [
                ['titlu' => 'Concentrat de zer premium', 'text' => 'Peste 75% proteină, profil complet de aminoacizi. Calitate consistentă în fiecare lot.'],
                ['titlu' => 'Test de puritate pe fiecare lot', 'text' => 'Analize publice, lot tracker pe ambalaj. Conținut de proteină verificat în laborator.'],
                ['titlu' => 'Fabricat în UE', 'text' => 'Facilități certificate, fără adaos de zahăr sau coloranți. Cacao real, fără arome sintetice.'],
            ],
            'faq' => [
                'nume' => 'ChocoProtein',
                'items' => [
                    ['q' => 'Care e diferența față de IceTea Whey Lemon?', 'a' => '<strong>ChocoProtein</strong> e concentrat de zer, gust cremos de ciocolată — bine pentru sala. <strong>IceTea Whey</strong> e izolat (mai pur, lactoză redusă), gust clar de ceai rece cu lămâie — bine pentru cardio sau vara. Funcție similară, experiență diferită.'],
                    ['q' => 'Conține zahăr?', 'a' => '<strong>Nu.</strong> Fără zahăr adăugat, fără îndulcitori artificiali. Gustul de ciocolată vine din cacao real.'],
                    ['q' => 'Pot să-l combin cu lapte?', 'a' => 'Da, în <strong>apă sau lapte</strong> (animal sau vegetal). Cu apă e mai ușor pe stomac, cu lapte mai cremos. Tu alegi.'],
                    ['q' => 'Câte doze are un borcan?', 'a' => '<strong>33 porții de 30g.</strong> Aproximativ 1 lună cu o porție/zi.'],
                    ['q' => 'Pot să-l iau dacă nu mă antrenez intens?', 'a' => 'Da, ca <strong>sursă de proteină de calitate</strong>. Aportul proteic e important și pentru persoanele active fără sală — recuperare, menținere musculară, sațietate.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // Collagen Joint+ Berry
        // -------------------------------------------------------------------
        'collagen-joint-berry-500-ml' => [
            'excerpt' => '<strong>7200 mg colagen hidrolizat per doză</strong>, plus complexul complet pentru articulații (MSM, glucozamină, condroitină, acid hialuronic, curcumă). Formă lichidă cu absorbție rapidă, aromă naturală de fructe de pădure.',
            'pdp_eyebrow' => 'Colagen lichid · articulații & piele',
            'pdp_subline' => 'colagen hidrolizat tip I+II+III, MSM · glucozamină · condroitină · 33 doze.',
            'ihl' => [
                'eyebrow' => 'Ingredientul cheie · spus complet',
                'titlu' => 'Colagen <em>hidrolizat</em> tip I, II și III.',
                'caption' => 'colagen hidrolizat marin & bovin · peptide 2–5 kDa',
                'prose' => [
                    'plus complexul complet pentru articulații și mobilitate.',
                ],
                'rows' => [
                    ['lbl' => 'Colagen hidrolizat', 'val' => '<strong>7200 mg per doză</strong> · <em>tip I + II + III, peptide cu absorbție rapidă, biodisponibilitate dovedită</em>'],
                    ['lbl' => 'MSM', 'val' => '<strong>600 mg</strong> · <em>sulf organic biodisponibil, suport antiinflamator natural</em>'],
                    ['lbl' => 'Glucozamină + Condroitină', 'val' => '<strong>500 + 400 mg</strong> · <em>blocuri esențiale pentru cartilaje și sinoviu</em>'],
                    ['lbl' => 'Acid hialuronic', 'val' => '<strong>50 mg</strong> · <em>hidratare țesuturi conjunctive și piele</em>'],
                    ['lbl' => 'Curcumă + cofactori', 'val' => '<strong>100 mg curcumă</strong> · Vit. C · Vit. D3 · seleniu · <em>cofactori pentru sinteza naturală de colagen</em>'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Protocol simplu · 3 etape',
                'intro' => 'Începi cu o doză mică, observi 7 zile, apoi treci pe doza activă. Nu există grabă.',
                'steps' => [
                    ['when' => 'Zilele 1–7', 'titlu' => 'Doză mică, organism nou.', 'text' => '7,5 ml dimineața, pe stomacul gol. Te obișnuiești cu aroma și verifici toleranța. Hidratează-te bine pe parcursul zilei.'],
                    ['when' => 'Zilele 8–33', 'titlu' => 'Doză activă, observă schimbarea.', 'text' => '15 ml plin dimineața sau seara. Articulațiile răspund tipic în 3–4 săptămâni: mobilitate mai bună, recuperare mai rapidă după efort.'],
                    ['when' => 'După 33 zile', 'titlu' => 'Întreținere lungă, apoi pauză.', 'text' => 'Ține 2–3 luni consecutive, apoi pauză 4 săptămâni înainte de o nouă cură. Efectul colagenului e cumulativ.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Persoane active și sportivi care vor recuperare și mobilitate',
                    'Rigiditate matinală sau disconfort articular ușor',
                    'Cei care vor menținerea fermității pielii și a țesuturilor',
                    'Suport pentru sinteza naturală de colagen după 30 de ani',
                ],
                'nu' => [
                    'Copii sub 12 ani',
                    'Sarcină sau alăptare — întreabă-ți medicul mai întâi',
                    'Persoane alergice la pește (urme de colagen marin)',
                    'Tratament anticoagulant — interacțiuni teoretice cu curcumă',
                ],
            ],
            'stand' => [
                ['titlu' => 'Origine trasabilă', 'text' => 'Colagen bovin de pășune și marin certificat, fără broker intermediar. Lot și fermă pe etichetă.'],
                ['titlu' => 'Hidroliză enzimatică', 'text' => 'Peptide 2–5 kDa, absorbție rapidă. Biodisponibilitate dovedită clinic, nu doar pe ambalaj.'],
                ['titlu' => 'Sticlă PET ambră', 'text' => 'Protecție UV reală pentru ingrediente sensibile. Fără aluminiu, fără bisfenol.'],
            ],
            'faq' => [
                'nume' => 'Collagen Joint+',
                'items' => [
                    ['q' => 'Cât timp trebuie să-l iau ca să simt o diferență?', 'a' => 'Onest: <strong>între 4 și 8 săptămâni pentru schimbări observabile la mobilitate articulară</strong>, și 8–12 săptămâni pentru efect pe piele. Colagenul are efect cumulativ — nu sare-n ochi a doua zi, dar după 6 săptămâni de constanță vei observa diferența. Dacă după 8 săptămâni nu simți nimic, scrie-ne — poate că nu e suplimentul potrivit pentru tine.'],
                    ['q' => 'Se poate lua împreună cu Vita Complete+ sau Black Seed Elixir?', 'a' => 'Da, se combină fără probleme — vitaminele din <strong>Vita Complete+</strong> (în special vitamina C) sunt chiar cofactori pentru sinteza naturală de colagen. Atenție doar la curcumă dacă iei anticoagulante.'],
                    ['q' => 'Cum se păstrează după deschidere?', 'a' => '<strong>La frigider</strong>, bine închis, consumat în 30 de zile de la deschidere. Sticla ambră protejează de lumină, dar căldura degradează ingredientele active.'],
                    ['q' => 'De ce e formă lichidă și nu capsule?', 'a' => '<strong>7200 mg de colagen nu încap în capsule</strong> — ar însemna 10+ capsule pe zi. Forma lichidă permite doza completă într-o singură administrare, cu absorbție rapidă.'],
                    ['q' => 'Conține zahăr sau îndulcitori artificiali?', 'a' => '<strong>Nu.</strong> Fără zahăr adăugat, fără îndulcitori artificiali. Aroma de fructe de pădure e naturală.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // Creatine Monohidrate Pro 1000g
        // -------------------------------------------------------------------
        'creatine-monohidrate-pro-1000g' => [
            'excerpt' => '<strong>5 g creatină monohidrat pură per doză.</strong> Fără aditivi, fără arome, fără zahăr. 200 doze într-un borcan, suficient pentru 6 luni de antrenamente.',
            'pdp_eyebrow' => 'Pudră · performanță sportivă',
            'pdp_subline' => 'creatină monohidrat 100% pură · 1000 g · 200 doze · 5 g/zi.',
            'ihl' => [
                'eyebrow' => 'Ingredient · spus complet',
                'titlu' => 'Creatină monohidrat. <em>Pură.</em> Atât.',
                'caption' => 'creatină monohidrat · 100% puritate · biodisponibilitate maximă',
                'prose' => [
                    'Creatina monohidrat este <strong>cea mai studiată formă din nutriția sportivă</strong>, cu sute de studii clinice care confirmă efectul pe forță, putere și recuperare. Restabilește rezervele de ATP la nivel muscular, exact unde e nevoie. Nu există versiune mai eficientă, doar versiuni mai scumpe care nu adaugă nimic peste.',
                    'Versiunea noastră e <strong>100% pură, fără aditivi</strong>. O amesteci cu apă, suc sau shake — fără gust, fără reziduuri.',
                ],
                'rows' => [
                    ['lbl' => 'Formă', 'val' => '<strong>Monohidrat</strong> · cea mai studiată formă'],
                    ['lbl' => 'Puritate', 'val' => '<strong>100%</strong> creatină monohidrat'],
                    ['lbl' => 'Aditivi', 'val' => '<strong>0</strong> · fără arome, zahăr, coloranți'],
                    ['lbl' => 'Solubilitate', 'val' => 'Dizolvare rapidă în apă sau băutură'],
                    ['lbl' => 'Test microbiologic', 'val' => 'Pe fiecare lot, raport public'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum îl folosești',
                'intro' => 'Faza de încărcare e opțională. Dacă nu te grăbești, mergi direct la întreținere — la fel ajungi acolo.',
                'steps' => [
                    ['when' => 'Faza de încărcare · opțional', 'titlu' => '5 g × 4 ori/zi, 5–7 zile.', 'text' => 'Saturare rapidă a rezervelor musculare. Începi să simți diferența în prima săptămână, în loc de a treia.'],
                    ['when' => 'Întreținere', 'titlu' => '5 g/zi, în fiecare zi.', 'text' => 'O linguriță rasă. Momentul nu contează — pre-antrenament, post-antrenament, dimineața. Consistența contează.'],
                    ['when' => 'Hidratare', 'titlu' => 'Minim 2 litri apă/zi.', 'text' => 'Creatina trage apa în celule musculare. Fără hidratare suficientă apar crampe și efectul se diminuează.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Sportivi de forță și rezistență',
                    'Persoane active care se antrenează regulat (≥ 3 ori/săpt)',
                    'Cei care urmăresc creșterea masei musculare și a forței',
                    'Antrenamente intense sau faze de progres fizic',
                ],
                'nu' => [
                    'Minori sub 18 ani',
                    'Persoane cu probleme renale — consultă medicul mai întâi',
                    'Femei însărcinate sau care alăptează',
                    'Cei care nu se antrenează deloc (n-are rost)',
                ],
            ],
            'stand' => [
                ['titlu' => 'Materie primă premium', 'text' => 'Creatină monohidrat de calitate farmaceutică, sursă verificată. Fără diluare cu agenți de încărcare.'],
                ['titlu' => 'Test de puritate pe fiecare lot', 'text' => 'Analize publice, lot tracker pe ambalaj. Vezi ce ai cumpărat, nu doar ce promitem.'],
                ['titlu' => 'Fabricat în UE', 'text' => 'Facilități certificate, fără urmă de metale grele. Standarde europene de siguranță alimentară.'],
            ],
            'faq' => [
                'nume' => 'Creatine Pro',
                'items' => [
                    ['q' => 'Trebuie să fac faza de încărcare?', 'a' => 'Nu obligatoriu. Poți merge direct cu <strong>5 g/zi</strong>, doar că efectul saturației apare după 3–4 săptămâni în loc de o săptămână. Pentru majoritatea oamenilor, schema simplă e mai ușor de respectat pe termen lung.'],
                    ['q' => 'Când o iau — înainte sau după antrenament?', 'a' => 'Nu contează momentul. Contează <strong>consistența zilnică</strong>. Multe persoane preferă post-antrenament, cu un shake de proteine, pentru că deja își fac un ritual acolo.'],
                    ['q' => 'Mă îngrașă?', 'a' => 'Creatina trage apă în mușchi, deci poți câștiga <strong>1–2 kg de masă musculară hidratată</strong> în primele săptămâni. Nu e grăsime. E semn că funcționează.'],
                    ['q' => 'Pot să o combin cu proteină?', 'a' => 'Da, e combinația cea mai comună. <strong>ChocoProtein sau IceTea Whey + creatină</strong> post-antrenament — un singur shake care acoperă recuperarea și saturarea musculară.'],
                    ['q' => 'De ce nu are aromă?', 'a' => 'Pentru că nu vrem aditivi inutili. O amesteci cu apă, suc, shake de proteine sau orice altceva îți place. Tu alegi gustul, noi facem treaba serioasă.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // D-Tox Ficat
        // -------------------------------------------------------------------
        'd-tox-ficat' => [
            'excerpt' => '<strong>200 mg silimarină pură per capsulă</strong>, completată cu anghinare și păpădie. Pentru susținerea naturală a ficatului în perioade dezechilibrate.',
            'pdp_eyebrow' => 'Capsule · detoxifiere & ficat',
            'pdp_subline' => '200 mg silimarină per doză · armurariu + anghinare + păpădie · 120 capsule · 100% vegan.',
            'ihl' => [
                'eyebrow' => 'Ingredient cheie · spus complet',
                'titlu' => 'Silimarină din armurariu. <em>Doză eficientă.</em>',
                'caption' => 'Silybum marianum · extract standardizat 200 mg silimarină per doză',
                'prose' => [
                    'Silimarina este <strong>flavonolignanul activ din armurariu</strong> (Silybum marianum), cea mai studiată componentă fitoterapeutică pentru funcția hepatică. 200 mg per doză este o concentrație utilă, nu un marketing pe etichetă.',
                    'Completată cu <strong>anghinare</strong> (sursă de cinarină, susține secreția biliară) și <strong>rădăcină de păpădie</strong> (tradițional folosită pentru funcția digestivă), formula acoperă întreaga axă ficat-bilă-digestie.',
                ],
                'rows' => [
                    ['lbl' => 'Silimarină / doză', 'val' => '<strong>200 mg</strong> · concentrație utilă'],
                    ['lbl' => 'Sursă armurariu', 'val' => '<strong>Silybum marianum</strong>, extract standardizat'],
                    ['lbl' => 'Plante complementare', 'val' => 'Anghinare + rădăcină de păpădie'],
                    ['lbl' => 'Format', 'val' => 'Capsulă vegetală'],
                    ['lbl' => 'Aditivi', 'val' => '<strong>0</strong> · fără zahăr, fără alergeni, 100% vegan'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum o folosești',
                'intro' => 'O capsulă pe zi. Constanță minimum 6 săptămâni. Restul vine din stilul de viață paralel.',
                'steps' => [
                    ['when' => 'Dimineața sau seara', 'titlu' => '1 capsulă pe zi, cu masa.', 'text' => 'Un pahar mare de apă, în timpul mesei sau imediat după. Silimarina se absoarbe mai bine în prezența lipidelor alimentare.'],
                    ['when' => 'Constanță · 6–12 săptămâni', 'titlu' => 'Răbdare. Efectul se construiește.', 'text' => 'Susținerea hepatică nu se simte a doua zi. Minimum 6 săptămâni constant pentru a observa o diferență la digestie și energie generală.'],
                    ['when' => 'Stil de viață paralel', 'titlu' => 'Sprijină ficatul, nu îl iartă.', 'text' => 'Redu alcoolul, hidratează-te, mănâncă fibre și legume verzi. D-Tox Ficat sprijină, nu compensează miraculos.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Stil de viață cu mese grele sau alcool ocazional',
                    'Perioade de stres metabolic (post-sărbători, deplasări)',
                    'Cure sezoniere de primăvară și toamnă',
                    'Persoane care iau medicamente cronice — consultă medicul mai întâi',
                ],
                'nu' => [
                    'Minori sub 18 ani',
                    'Alergie la armurariu sau familia Asteraceae',
                    'Femei însărcinate sau care alăptează — consultă medicul',
                    'Persoane cu obstrucție biliară diagnosticată',
                ],
            ],
            'stand' => [
                ['titlu' => 'Extract standardizat', 'text' => 'Silimarină cuantificată per doză, nu doar „extract de armurariu". Concentrație utilă, garantată pe etichetă.'],
                ['titlu' => 'Test de puritate pe fiecare lot', 'text' => 'Fără metale grele, micotoxine sau pesticide. Buletinul de analize disponibil pentru fiecare lot.'],
                ['titlu' => 'Fabricat în UE', 'text' => 'Facilități certificate, capsule vegane, fără ingrediente de origine animală. Standarde europene de siguranță.'],
            ],
            'faq' => [
                'nume' => 'D-Tox Ficat',
                'items' => [
                    ['q' => 'Pot să le iau dacă deja iau medicamente?', 'a' => 'Silimarina poate <strong>interacționa cu unele medicamente</strong> metabolizate hepatic. Întreabă medicul tău înainte, mai ales dacă iei anticoagulante, statine sau medicamente psihiatrice.'],
                    ['q' => 'Câte zile durează un flacon?', 'a' => '<strong>120 de zile cu 1 capsulă/zi.</strong> Aproximativ 4 luni de cură continuă, sau două cure sezoniere de câte 2 luni cu pauze între.'],
                    ['q' => 'Se ia înainte sau după masă?', 'a' => 'Cu masa sau imediat după. <strong>Silimarina se absoarbe mai bine în prezența lipidelor</strong> alimentare, așa că la o masă cu puține grăsimi e ok să adaugi câteva nuci sau o linguriță de ulei de măsline.'],
                    ['q' => 'Pot să fac cură doar primăvara și toamna?', 'a' => 'Da, e o opțiune validă. Mulți o folosesc ca o <strong>cură sezonieră de 6–8 săptămâni</strong>, alteori după perioade de exces alimentar (sărbători, vacanțe, perioade de stres metabolic).'],
                    ['q' => 'E vegan și fără alergeni?', 'a' => 'Da: <strong>capsulă vegetală</strong>, fără gluten, lactoză, soia sau zahăr adăugat. Singurul alergen potențial e armurariul în sine — dacă ai alergie la familia Asteraceae (margarete, ambrozie), nu îl lua.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // IceTea Whey Lemon 500g
        // -------------------------------------------------------------------
        'icetea-whey-lemon-500g' => [
            'excerpt' => 'Alternativa <strong>răcoritoare</strong> la shake-ul cremos. Izolat de zer pur, fără zahăr, gust de ceai rece cu lămâie. Textură clară, ușor de băut chiar și vara.',
            'pdp_eyebrow' => 'Pudră · performanță sportivă',
            'pdp_subline' => 'izolat zer >80% proteină · 500 g · 16 doze · 30 g/zi · gust ceai rece cu lămâie.',
            'ihl' => [
                'eyebrow' => 'Ingredient · spus complet',
                'titlu' => 'Izolat de zer. <em>Curat și răcoritor.</em>',
                'caption' => 'izolat de zer · peste 80% proteină · gust ceai rece lămâie',
                'prose' => [
                    'Izolatul de zer (<strong>whey isolate</strong>) e cea mai pură formă de proteină din zer: peste 80% proteină după purificare, lactoza redusă semnificativ, grăsimile aproape eliminate. Pentru cei sensibili la lactoză, e varianta cea mai blândă.',
                    'Gustul de <strong>ceai rece cu lămâie</strong> face din shake o băutură, nu o obligație. Textură clară, nu cremoasă. Bun rece, în zilele când shake-ul cu lapte pare prea greu.',
                ],
                'rows' => [
                    ['lbl' => 'Tip proteină', 'val' => '<strong>Izolat din zer</strong> (whey isolate)'],
                    ['lbl' => 'Proteină / 100g', 'val' => '<strong>peste 80%</strong> proteină pură'],
                    ['lbl' => 'Lactoză', 'val' => 'Redusă semnificativ (purificată)'],
                    ['lbl' => 'Aditivi', 'val' => '<strong>0</strong> zahăr · fără îndulcitori artificiali'],
                    ['lbl' => 'Alergeni', 'val' => 'Lapte, gluten'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum îl folosești',
                'intro' => 'O măsură (30g) în apă rece. Bei imediat. Hidratezi în paralel. Atât.',
                'steps' => [
                    ['when' => 'Post-antrenament', 'titlu' => '1 măsură (30g) în 300–400ml apă rece.', 'text' => 'Agită bine, bei imediat. Proteinele se absorb în 30–60 minute, fix în fereastra optimă post-efort.'],
                    ['when' => 'Zilele fără antrenament', 'titlu' => 'Ca gustare între mese.', 'text' => 'La 2–3 ore între mese, sau dimineața dacă micul dejun e sărac în proteină. Susține menținerea masei musculare.'],
                    ['when' => 'Hidratare în paralel', 'titlu' => 'Minim 2 litri apă/zi.', 'text' => 'Proteina cere apă pentru metabolizare. Fără hidratare suficientă, digestia se îngreunează inutil.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Sportivi care vor o variantă răcoritoare, nu shake cremos',
                    'Antrenamente cardio sau perioade de definire musculară',
                    'Cei sensibili la shake-urile cremoase clasice',
                    'Persoane care preferă băuturi clare vara, post-antrenament',
                ],
                'nu' => [
                    'Alergici la lapte sau gluten',
                    'Intoleranță severă la lactoză (chiar dacă e redusă)',
                    'Vegani — produsul conține proteină de zer (origine lactată)',
                    'Minori sub 16 ani',
                ],
            ],
            'stand' => [
                ['titlu' => 'Izolat de zer premium', 'text' => 'Peste 80% proteină după purificare, nu concentrat. Forma cea mai curată din toate variantele de zer.'],
                ['titlu' => 'Test de puritate pe fiecare lot', 'text' => 'Analize publice, lot tracker pe ambalaj. Conținutul real de proteină verificat în laborator.'],
                ['titlu' => 'Fabricat în UE', 'text' => 'Facilități certificate, fără adaos de zahăr, coloranți sau îndulcitori artificiali. Doar proteină și aromă naturală.'],
            ],
            'faq' => [
                'nume' => 'IceTea Whey',
                'items' => [
                    ['q' => 'Care e diferența față de ChocoProtein?', 'a' => '<strong>ChocoProtein</strong> e concentrat de zer, gust cremos de ciocolată, textură ca de shake clasic. <strong>IceTea Whey</strong> e izolat (mai pur, lactoză redusă), gust clar de ceai rece cu lămâie, textură de băutură. Funcție similară pe partea de proteină, experiență de consum total diferită.'],
                    ['q' => 'Pot să-l iau dacă sunt intolerant la lactoză?', 'a' => 'Lactoza e <strong>redusă semnificativ</strong> în izolat (procesul de purificare elimină majoritatea). Persoanele cu intoleranță medie îl tolerează în general fără probleme. Pentru intoleranță severă, consultă medicul mai întâi.'],
                    ['q' => 'Conține zahăr?', 'a' => 'Nu. <strong>Fără zahăr adăugat</strong>, fără îndulcitori artificiali (sucraloză, aspartam). Aroma vine din ingrediente naturale pentru ceai rece cu lămâie.'],
                    ['q' => 'Câte doze are un sac?', 'a' => '<strong>16 doze de 30g fiecare.</strong> Aproximativ două săptămâni la o doză/zi, sau o lună la o doză la două zile.'],
                    ['q' => 'Pot să îl combin cu apa minerală?', 'a' => 'Da, dar <strong>carbonatarea poate face spumă</strong> la agitare. Recomandăm apă plată rece pentru cea mai bună experiență de consum și textură clară.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // LionFocus B6 (jeleuri)
        // -------------------------------------------------------------------
        'lionfocus-b6-jeleuri' => [
            'excerpt' => '<strong>Lion\'s Mane + B6</strong> în jeleuri cu gust de afine. Pentru focus susținut, fără jitter ca la cafea, fără pastile.',
            'pdp_eyebrow' => 'Jeleuri · focus & claritate mentală',
            'pdp_subline' => 'Coama Leului + vitamina B6 · 60 jeleuri · 30 zile · vegan, fără zahăr.',
            'ihl' => [
                'eyebrow' => 'Ingredient cheie · spus complet',
                'titlu' => 'Coama Leului. <em>O ciupercă pentru minte limpede.</em>',
                'caption' => 'Hericium erinaceus · extract standardizat + vitamina B6',
                'prose' => [
                    'Hericium erinaceus (<strong>Lion\'s Mane</strong>) este folosit de secole în medicina tradițională asiatică pentru susținerea funcțiilor cognitive. Cercetările moderne se concentrează pe rolul ei în neuroplasticitate și claritatea gândirii.',
                    'Combinat cu <strong>vitamina B6</strong> (esențială pentru funcționarea normală a sistemului nervos), oferă suport real pentru focus și claritate. În format de jeleu vegan cu afine — fără zahăr, fără gelatină animală, fără pastile de înghițit.',
                ],
                'rows' => [
                    ['lbl' => 'Lion\'s Mane', 'val' => '<strong>Extract standardizat</strong> Hericium erinaceus'],
                    ['lbl' => 'Vitamina B6 / doză', 'val' => 'Contribuție zilnică recomandată acoperită'],
                    ['lbl' => 'Format', 'val' => '<strong>Jeleuri</strong> (gummies)'],
                    ['lbl' => 'Gust', 'val' => 'Afine natural'],
                    ['lbl' => 'Aditivi', 'val' => '<strong>0</strong> zahăr · vegan · fără gelatină animală'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum îl folosești',
                'intro' => 'Două jeleuri dimineața. Răbdare 4–6 săptămâni. Stack-uire cu obiceiuri bune. Atât.',
                'steps' => [
                    ['when' => 'Dimineața', 'titlu' => '2 jeleuri cu primul tău mic dejun.', 'text' => 'Sau pe stomacul gol cu un pahar de apă. Le mesteci, nu le înghiți întregi — au gust de afine, nu de pastilă.'],
                    ['when' => 'Consecvent · 4–6 săptămâni', 'titlu' => 'Răbdare. Efectul se construiește.', 'text' => 'Lion\'s Mane nu e un shot de cafea. Așteaptă-te la diferențe vizibile după 3–4 săptămâni de utilizare consecventă.'],
                    ['when' => 'Stack-uire cu obiceiuri', 'titlu' => 'Suplimentul amplifică, nu înlocuiește.', 'text' => 'Funcționează cel mai bine cu somn 7+ ore, hidratare 2L/zi, mișcare regulată. Restul ține de tine.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Profesioniști în muncă cognitivă intensă',
                    'Studenți în sesiune sau în pregătire de examene',
                    'Persoane care vor să reducă dependența de cafea',
                    'Oricine simte ceață mentală sau lipsă de claritate',
                ],
                'nu' => [
                    'Minori sub 16 ani',
                    'Alergie la ciuperci',
                    'Femei însărcinate sau care alăptează — consultă medicul',
                    'Cei sub tratament psihiatric activ — interacțiuni posibile, consultă medicul',
                ],
            ],
            'stand' => [
                ['titlu' => 'Extract standardizat', 'text' => 'Lion\'s Mane cu polizaharide cuantificate per doză, nu doar pulbere generică de ciupercă.'],
                ['titlu' => 'Test de puritate pe fiecare lot', 'text' => 'Analize publice, lot tracker pe ambalaj. Fără metale grele, pesticide sau micotoxine.'],
                ['titlu' => 'Fabricat în UE', 'text' => 'Facilități certificate, jeleuri fără gelatină animală. 100% vegan, standarde europene de siguranță.'],
            ],
            'faq' => [
                'nume' => 'LionFocus B6',
                'items' => [
                    ['q' => 'Câte zile durează un borcan?', 'a' => '<strong>30 de zile cu 2 jeleuri/zi.</strong> 60 jeleuri total. Suficient pentru o cură completă lunară.'],
                    ['q' => 'Pot să le combin cu cafea?', 'a' => 'Da, dar majoritatea utilizatorilor <strong>reduc gradat cafeaua</strong> după 3–4 săptămâni. Lion\'s Mane oferă claritate fără jitter sau crashuri.'],
                    ['q' => 'Conțin gelatină animală?', 'a' => '<strong>Nu.</strong> Jeleuri 100% vegane, fără gelatină. Folosim pectină din fructe ca agent de gelificare.'],
                    ['q' => 'Când simt efectul?', 'a' => 'Lion\'s Mane <strong>nu e un stimulant</strong>, e un susținător cognitiv. Diferențe vizibile după 3–4 săptămâni de utilizare consecventă. Dacă aștepți efect ca de cafea în prima zi, vei fi dezamăgit.'],
                    ['q' => 'Pot să iau mai mult de 2/zi?', 'a' => 'Nu recomandăm depășirea dozei. <strong>Mai mult nu înseamnă mai bine</strong> cu Lion\'s Mane — corpul folosește cantitatea optimă, restul se elimină.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // Microflora+ Lemon Shots
        // -------------------------------------------------------------------
        'microflora-lemon-shots-500-ml-33-shots' => [
            'excerpt' => '<strong>10 miliarde de probiotice microîncapsulate</strong> într-un shot de lămâie. Pentru un intestin echilibrat și imunitate susținută zilnic.',
            'pdp_eyebrow' => 'Lichid · probiotice & digestie',
            'pdp_subline' => '10 mld UFC microîncapsulate · vitamina C · L-glutamină · 500 ml · 33 doze · vegan.',
            'ihl' => [
                'eyebrow' => 'Tehnologie · spus complet',
                'titlu' => 'Probiotice microîncapsulate. <em>Ajung intacte unde contează.</em>',
                'caption' => 'strat lipidic protector · 10 mld UFC viabile per doză',
                'prose' => [
                    'Probioticele simple sunt <strong>distruse în acid gastric</strong> înainte să ajungă în intestin. Microîncapsularea le protejează printr-un strat lipidic, permițându-le să ajungă vii la nivelul intestinal, acolo unde au efect.',
                    '<strong>Vitamina C</strong> susține imunitatea, <strong>L-glutamina</strong> hrănește mucoasa intestinală. Împreună, o stivă completă pentru un intestin sănătos, nu doar un probiotic în lipsa de context.',
                ],
                'rows' => [
                    ['lbl' => 'UFC / doză', 'val' => '<strong>10 miliarde</strong> probiotice viabile'],
                    ['lbl' => 'Tehnologie', 'val' => '<strong>Microîncapsulare lipidică</strong>'],
                    ['lbl' => 'Vitamina C', 'val' => 'Contribuție zilnică acoperită'],
                    ['lbl' => 'L-glutamină', 'val' => 'Pentru regenerarea mucoasei intestinale'],
                    ['lbl' => 'Aditivi', 'val' => '<strong>0</strong> zahăr · vegan · aromă naturală de lămâie'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum îl folosești',
                'intro' => 'Un shot dimineața, pe stomacul gol. Răbdare 4–8 săptămâni. Mâncare cu fibre alături. Atât.',
                'steps' => [
                    ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '15 ml (1 shot) la trezire.', 'text' => 'Cu 15–30 min înainte de mic dejun. Mediul gastric e mai puțin acid atunci, deci mai multe bacterii bune ajung viu în intestin.'],
                    ['when' => 'Consecvent · 4–8 săptămâni', 'titlu' => 'Răbdare. Flora se construiește.', 'text' => 'Echilibrul florei intestinale ia timp. Diferențe vizibile la digestie și energie după 2–4 săptămâni de utilizare zilnică.'],
                    ['when' => 'Stack-uire cu fibre', 'titlu' => 'Hrana lor preferată.', 'text' => 'Probioticele au nevoie de prebiotice (fibre) ca să prospere. Legume, fructe, ovăz — alimente cu fibre la fiecare masă.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Post-antibiotice, pentru refacerea florei intestinale',
                    'Persoane cu balonare sau disconfort digestiv',
                    'Perioade de stres alimentar sau imunitate slăbită',
                    'Schimbări de rutină, călătorii, jet lag',
                ],
                'nu' => [
                    'Minori sub 12 ani — consultă medicul pediatru',
                    'Persoane imunocompromise sever — consultă medicul',
                    'Alergie la lămâie sau citrice',
                    'Alergie la prebiotice tip inulină',
                ],
            ],
            'stand' => [
                ['titlu' => 'Tehnologie microîncapsulare', 'text' => 'Probiotice protejate cu strat lipidic, viabilitate ridicată la nivel intestinal. Nu mor în acidul gastric.'],
                ['titlu' => 'Test microbiologic pe fiecare lot', 'text' => 'UFC cuantificat real în laborator, nu doar declarativ pe etichetă. Buletinul de analize public pentru fiecare lot.'],
                ['titlu' => 'Fabricat în UE', 'text' => 'Facilități certificate, aromă naturală de lămâie, fără conservanți sintetici. Vegan, fără zahăr adăugat.'],
            ],
            'faq' => [
                'nume' => 'Microflora+',
                'items' => [
                    ['q' => 'Cum păstrez sticla după deschidere?', 'a' => '<strong>La frigider</strong>, consumate în 30 zile de la deschidere. Probioticele sunt sensibile la căldură. Nu lăsa sticla pe blat sau în mașină vara.'],
                    ['q' => 'Pot să le iau în paralel cu antibiotice?', 'a' => 'Da, dar <strong>la distanță de minim 2 ore</strong>. Antibioticele distrug probioticele dacă le iei împreună. Recomandare: probiotice dimineața, antibiotice la prânz sau seara.'],
                    ['q' => 'Diferența față de iaurturi probiotice?', 'a' => 'Iaurturile au UFC redus (1–2 mld) și <strong>fără microîncapsulare</strong> — multe probiotice nu ajung în intestin. Aici ai 10 mld UFC protejate cu strat lipidic, viabilitate verificată în laborator.'],
                    ['q' => 'Câte zile durează o sticlă?', 'a' => '<strong>33 de zile cu 15 ml/zi.</strong> Aproximativ o lună de cură.'],
                    ['q' => 'Pot să le iau pe termen lung?', 'a' => 'Da. Mulți utilizatori fac <strong>cure de 2–3 luni urmate de pauze de 1 lună</strong>. Pentru utilizare continuă peste 6 luni, consultă medicul.'],
                ],
            ],
        ],

        // -------------------------------------------------------------------
        // Vita Complete+ Vegan Shots
        // -------------------------------------------------------------------
        'vita-complete-vegan-shots-500-ml-50-shots' => [
            'excerpt' => 'Un shot zilnic. <strong>25+ vitamine și minerale</strong>. Plus extracte verzi și aminoacizi pentru energie reală, nu doar pe etichetă.',
            'pdp_eyebrow' => 'Lichid · multivitamine & energie',
            'pdp_subline' => '25+ vitamine și minerale · Green Blend · Amino Blend · 500 ml · 50 doze · vegan, portocale.',
            'ihl' => [
                'eyebrow' => 'Formulă · spus complet',
                'titlu' => '25+ vitamine. <em>Un singur shot.</em>',
                'caption' => 'B-complex · C · D3 · E · zinc · magneziu · seleniu · și încă 18+',
                'prose' => [
                    'În loc să iei <strong>5 pastile separate</strong> dimineața, un shot acoperă tot: B-complex pentru energie, C și D3 pentru imunitate, zinc și seleniu pentru detox celular, magneziu pentru muscular.',
                    'Plus <strong>Green Blend</strong> (extracte vegetale antioxidante) și <strong>Amino Blend</strong> (aminoacizi pentru performanță și refacere). O singură rutină, multe efecte.',
                ],
                'rows' => [
                    ['lbl' => 'Vitamine + minerale', 'val' => '<strong>25+</strong> per doză'],
                    ['lbl' => 'Green Blend', 'val' => 'Extracte vegetale (sprijin antioxidant)'],
                    ['lbl' => 'Amino Blend', 'val' => 'Aminoacizi (sprijin performanță și refacere)'],
                    ['lbl' => 'Format', 'val' => '<strong>Shot lichid 10 ml</strong>'],
                    ['lbl' => 'Aditivi', 'val' => '<strong>0</strong> alergeni · vegan · aromă naturală portocale'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Cum îl folosești',
                'intro' => 'Un shot dimineața. La fel de eficient în zilele solicitante. Stack-uire cu obiceiuri bune. Atât.',
                'steps' => [
                    ['when' => 'Dimineața', 'titlu' => '10 ml (1 shot) la trezire.', 'text' => 'Imediat după trezire sau cu micul dejun. Format lichid = absorbție rapidă pentru un start activ al zilei.'],
                    ['when' => 'În zile solicitante', 'titlu' => 'Rămâne la fel de eficient.', 'text' => 'Când programul e încărcat, când dormi puțin, când treci prin perioade de stres — Vita Complete+ acoperă deficitele.'],
                    ['when' => 'Stack-uire cu obiceiuri', 'titlu' => 'Suplimentele susțin, nu înlocuiesc.', 'text' => 'Vitaminele se absorb mai bine cu hidratare adecvată și mișcare. Restul ține de tine.'],
                ],
            ],
            'pcine' => [
                'da' => [
                    'Persoane cu program încărcat care nu mănâncă echilibrat',
                    'Vegani care vor un aport complet de B12 și D3',
                    'Perioade de stres sau oboseală cronică',
                    'Sportivi care au nevoie de aminoacizi suplimentari',
                ],
                'nu' => [
                    'Minori sub 12 ani — consultă medicul pediatru',
                    'Femei însărcinate — formulă specifică recomandată',
                    'Persoane cu hipervitaminoză diagnosticată',
                    'Cei care iau deja multivitamine concentrate',
                ],
            ],
            'stand' => [
                ['titlu' => 'Format lichid premium', 'text' => 'Absorbție mai bună decât pastilele uscate. Vitaminele intră direct în circuit, fără timp de descompunere.'],
                ['titlu' => 'Test de potență pe fiecare lot', 'text' => 'Vitamine cuantificate real în laborator, nu doar declarativ. Buletinul de analize public pentru fiecare lot.'],
                ['titlu' => 'Fabricat în UE', 'text' => 'Facilități certificate, vegan, fără conservanți sintetici. B12 din metilcobalamină, D3 din lichen.'],
            ],
            'faq' => [
                'nume' => 'Vita Complete+',
                'items' => [
                    ['q' => 'Pot să-l combin cu alte suplimente?', 'a' => 'Da, cu <strong>probiotice, colagen, proteină</strong> — fără probleme. Atenție la dublarea cu alte multivitamine concentrate (poate depăși doza zilnică recomandată).'],
                    ['q' => 'Câte zile durează o sticlă?', 'a' => '<strong>50 de zile cu 10 ml/zi.</strong> Aproximativ 7 săptămâni.'],
                    ['q' => 'Cum păstrez sticla după deschidere?', 'a' => 'La temperatura camerei, ferit de lumină directă. <strong>Consumare în 60 de zile</strong> de la deschidere.'],
                    ['q' => 'E sigur pentru vegani?', 'a' => 'Da, <strong>100% vegan</strong>: B12 din metilcobalamină, D3 din lichen, fără gelatină, fără ingrediente de origine animală.'],
                    ['q' => 'Pot să-l iau pe stomacul gol?', 'a' => 'Da, dar majoritatea preferă cu apă imediat după trezire sau în timpul micului dejun pentru o <strong>experiență mai blândă pe stomac</strong>.'],
                ],
            ],
        ],

    ],
];
