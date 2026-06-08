<?php

/**
 * Date de seed pentru paginile „După obiectiv".
 *
 * Sursă: mockup-urile din `preferinte/Pagina obiectiv - *.html`. Fiecare element
 * (indexat pe slug) conține tot conținutul celor 8 secțiuni, transcris fidel.
 * Consumat de App\Console\Commands\ObiectivSeed (`wp acorn natura:obiectiv-seed`).
 *
 * `*_produs_slug` = slug WC presupus; comanda îl rezolvă la ID (câmpul Post Object).
 * `nume`/`pret` rămân fallback de afișare dacă produsul nu există încă în catalog.
 */

return [

    // =====================================================================
    // ENERGIE
    // =====================================================================
    'energie' => [
        'title' => 'Mai multă energie zilnică',
        'hero' => [
            'eyebrow' => 'Obiectiv: Energie',
            'titlu' => '<em>Energia</em> care ține toată ziua. Fără cafea în plus.',
            'lede' => 'B-complex, Q10, magneziu și adaptogeni. Combinația care repornește motorul celular în 2–4 săptămâni.',
            'cta_primary' => 'Vezi recomandarea principală',
            'cta_secondary' => 'Compară pachetele',
            'stats' => ['847 cure vândute', '★ 4,8/5', 'din 312 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => 'Pachet <em>Energie</em>',
            'subtitlu' => 'Revitalizare & Vitalitate Zilnică · Multivitamine + Adaptogeni Vegan.',
            'produs_slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei',
            'durata' => 'ajunge 120 de zile', 'cta' => 'Adaugă în coș — 306 lei',
            'benefits' => [
                'Pentru oboseală persistentă, trezire grea, energie scăzută după-amiaza.',
                'Pentru deficite subclinice de B12, magneziu, vitamina D, zinc.',
                'Pentru ten obosit, cearcăne persistente, treziri la 2–3 noaptea.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru energie</em>',
            'items' => [
                ['produs_slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei', 'cta' => 'Vezi produs', 'desc' => 'Și pentru imunitate, și pentru echilibru metabolic. Suport antiinflamator în paralel cu Pachetul Energie.'],
                ['produs_slug' => 'pachet-vitalitate', 'nume' => 'Pachet Vitalitate', 'pret' => '499 lei', 'cta' => 'Vezi pachet', 'desc' => 'Pentru cei care vor abordare mai largă: energie + frumusețe + echilibru. Cură de 90 zile.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Energie + <em>Imunitate.</em> Pachetul care ține toată toamna.',
            'text' => 'Adaugă și Pachetul Imunitate la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'O capsulă din B-complex cu micul dejun, pentru un start susținut. Nu pe stomacul gol.'],
                ['when' => 'La prânz', 'body' => 'Magneziu înainte de momentul mort de la 14:00. Previne căderea cognitivă post-prandială.'],
                ['when' => 'Seara', 'body' => 'Adaptogenii după cină. Nu interferează cu somnul, dar reglează cortizolul peste noapte.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Maria T.', 'quote' => 'Pentru a putea ține pasul cu nepoțelul meu, am nevoie constant de energie și suplimente de calitate. Așa că mi-am făcut stocul cu produse Vivens Genetica.'],
                ['rating' => 4, 'by' => 'Andrei C.', 'quote' => 'După 3 săptămâni am observat că nu mai am acea cădere de la 14:00. Cafeaua a rămas un ritual, nu o necesitate.'],
                ['rating' => 5, 'by' => 'Cristina M.', 'quote' => 'Am încercat multe multivitamine. Pachetul Energie e primul cu rezultate vizibile în sub o lună.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Oboseala cronică e rar dintr-o singură cauză. <strong>B-complex</strong> repune în funcțiune ciclul Krebs, de unde vine ATP-ul. <strong>Magneziul</strong> deblochează conversia. <strong>Adaptogenii</strong> (rhodiola, ginseng) reglează cortizolul. Când cele trei lucrează împreună, motorul celular repornește în 2–4 săptămâni.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Când o să simt diferența?', 'a' => 'Majoritatea raportează schimbări vizibile între ziua 14 și ziua 28. Primele semne sunt trezirea mai ușoară și absența căderii de la 14:00. Pentru efecte stabile, recomandăm cura completă de 90–120 zile.'],
                ['q' => 'Pot lua împreună cu cafeaua?', 'a' => 'Da, complet compatibil. Cafeaua dă boost imediat, dar nu rezolvă cauza. Pachetul Energie lucrează la nivel celular, în paralel. La 6–8 săptămâni mulți spun că au redus cafeaua de la trei la una pe zi, fără efort.'],
                ['q' => 'Cât timp ar trebui să țină o cură?', 'a' => 'Cura standard e 120 de zile (cât conține pachetul). Apoi recomandăm pauză de 4–6 săptămâni înainte de a relua. Cei cu oboseală cronică severă pot ține 6 luni consecutiv, cu acord medical.'],
            ],
        ],
    ],

    // =====================================================================
    // ANTI-AGING
    // =====================================================================
    'anti-aging' => [
        'title' => 'Anti-aging & longevitate',
        'hero' => [
            'eyebrow' => 'Obiectiv: Longevitate',
            'titlu' => 'Vârsta <em>biologică</em> mai tânără decât cea de pe buletin.',
            'lede' => 'Resveratrol, NAD+, colagen, antioxidanți. Nu încetinim timpul. Îi dăm corpului materialele să se repare mai bine.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['512 cure vândute', '★ 4,9/5', 'din 156 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => 'Pachet <em>Regenerare Celulară</em>',
            'subtitlu' => 'Colagen + Multivitamine + Black Seed · Cură profundă, 120 zile.',
            'produs_slug' => 'pachet-regenerare-celulara', 'nume' => 'Pachet Regenerare Celulară', 'pret' => '524 lei',
            'durata' => 'ajunge 120 de zile', 'cta' => 'Adaugă în coș — 524 lei',
            'benefits' => [
                'Pentru piele lăsată, riduri fine, ten stins, cearcăne persistente. 7,2 g peptide colagen tip 1+2+3, vit. C, vit. E.',
                'Pentru păr care cade abundent, unghii care se rup. Biotină, zinc, seleniu și colagen tip 1+3.',
                'Pentru dureri articulare, rigiditate matinală, recuperare lentă. Colagen tip 2, MSM, glucozamină, condroitină, acid hialuronic, curcumă.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru longevitate</em>',
            'items' => [
                ['produs_slug' => 'pachet-vitalitate', 'nume' => 'Pachet Vitalitate', 'pret' => '499 lei', 'cta' => 'Vezi pachet', 'desc' => 'Frumusețe, regenerare și echilibru, în 50 de zile. Pentru cei care vor abordare mai concentrată.'],
                ['produs_slug' => 'pachet-complex-sanatate', 'nume' => 'Pachet Complex Sănătate 40+', 'pret' => '499 lei', 'cta' => 'Vezi pachet', 'desc' => 'Energie, imunitate și protecție celulară, formulat pentru maturitate (după 40 de ani).'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Regenerare + <em>Vitalitate.</em> Cura lungă pe care ți-o promiți la 50 de ani.',
            'text' => 'Adaugă Pachetul Vitalitate la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Colagen cu vitamina C. Absorbție maximă și sinteza proprie activată simultan.'],
                ['when' => 'La prânz', 'body' => 'Multivitaminele cu masa principală, mai ales în zilele intense fizic sau mental.'],
                ['when' => 'Seara', 'body' => 'Black Seed și antioxidanți cu cina. Reparațiile celulare se fac în somn, când corpul reface ce a fost stresat.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Adriana B.', 'quote' => 'La 52 de ani, oamenii îmi dau 42. Pachet Regenerare Celulară a intrat în rutina mea de 3 ani. Nu fac promisiuni, dar diferența se vede.'],
                ['rating' => 5, 'by' => 'George I.', 'quote' => 'Iau pachetul de 8 luni. Pielea, energia și somnul s-au schimbat clar. Cel mai bun raport preț-rezultate din suplimentele încercate până acum.'],
                ['rating' => 5, 'by' => 'Daniela P.', 'quote' => 'După menopauză, totul s-a accelerat. Pachet Regenerare m-a ajutat să simt că nu pierd controlul. Energie, ten, articulații, toate îmbunătățite.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Longevitatea reală nu e un supliment. E o combinație de mișcare, somn, nutriție și stres redus, susținută de materiile prime de care celula are nevoie. <strong>Colagenul</strong> scade cu 1–1,5% pe an după 25. <strong>Antioxidanții</strong> reduc stresul oxidativ care îmbătrânește ADN-ul. <strong>Vitaminele B</strong> susțin metilarea. Când corpul are materialele, reparațiile vin natural.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Anti-aging chiar există sau e marketing?', 'a' => 'Anti-aging ca să te facă să arăți cu 20 de ani mai tânăr peste noapte, e marketing. Anti-aging ca susținere reală a proceselor de regenerare, da, există și are studii consistente. Colagenul, antioxidanții, omega-3, NAD+, resveratrol — toate au date care arată îmbunătățiri măsurabile în markeri de îmbătrânire. Realismul: nu inversezi timpul, dar îți poți schimba traiectoria.'],
                ['q' => 'De la ce vârstă are sens să încep?', 'a' => 'Producția de colagen începe să scadă la 25, iar la 30–35 majoritatea oamenilor observă primele schimbări (oboseală mai grea, recuperare mai lentă, primele riduri fine). Prevenția e cea mai eficientă: să începi între 30 și 40 cu o cură de 90 zile pe an. După 45–50, devine mai mult întreținere continuă cu pauze.'],
                ['q' => 'Cât timp ține o cură și când o repet?', 'a' => 'Cura standard de Regenerare Celulară e 120 zile. Recomandare practică: 2 cure pe an (primăvara și toamna), cu pauză de 2–3 luni între ele. Unii oameni preferă o cură lungă de 6 luni pe an, urmată de 6 luni pauză. Important: nu lua continuu fără pauză, corpul trebuie să-și mențină capacitatea proprie de sinteză.'],
            ],
        ],
    ],

    // =====================================================================
    // DETOXIFIERE
    // =====================================================================
    'detoxifiere' => [
        'title' => 'Detoxifiere & curățare',
        'hero' => [
            'eyebrow' => 'Obiectiv: Detox',
            'titlu' => '<em>Curățare</em> reală a ficatului și intestinului. Nu sucuri verzi.',
            'lede' => 'Silimarină, clorofilă, fibre, probiotice. Când corpul lucrează 28 de zile, organele de detox își revin la rolul lor.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['1.089 cure vândute', '★ 4,7/5', 'din 312 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => 'Pachet <em>Detox Plus</em>',
            'subtitlu' => 'Curățare Profundă Ficat & Sistem Digestiv · 3 Suplimente, Vegan.',
            'produs_slug' => 'pachet-detox-plus', 'nume' => 'Pachet Detox Plus', 'pret' => '457 lei',
            'durata' => 'ajunge 120 de zile', 'cta' => 'Adaugă în coș — 457 lei',
            'benefits' => [
                'Pentru balonare cronică, gaze, tranzit haotic, intestin sensibil, recuperare post-antibiotice.',
                'Pentru oboseală fără cauză, ten gălbui, treziri la 2–3 noaptea, halenă matinală.',
                'Pentru moleșeală după mese grase, sensibilitate la cofeină și medicamente, slăbit greoi, digestie greoaie.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru detox</em>',
            'items' => [
                ['produs_slug' => 'pachet-detox', 'nume' => 'Pachet Detox', 'pret' => '283 lei', 'cta' => 'Vezi pachet', 'desc' => 'Versiunea light: 2 suplimente, curățare echilibrată. Cură 120 zile, raport calitate-preț.'],
                ['produs_slug' => 'pachet-confort-digestiv', 'nume' => 'Pachet Confort Digestiv', 'pret' => '283 lei', 'cta' => 'Vezi pachet', 'desc' => 'Probiotice + detox ficat. Focus pe digestie zilnică, balonare, tranzit, după-mese grase.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Detox + <em>Energie.</em> Când vrei să curăți și să recapeți vitalitate.',
            'text' => 'Adaugă Pachetul Energie la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Silimarină și clorofilă pe stomacul gol. Hidratare bună toată ziua: 2–2,5 litri de apă.'],
                ['when' => 'Înainte de mese', 'body' => 'Probiotice cu 15 minute înainte de masă, când aciditatea gastrică e mai mică și sunt protejate.'],
                ['when' => 'Seara', 'body' => 'Fibre solubile cu ultima masă. Tranzitul de a doua zi dimineața e mai ușor și consistent.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Maria T.', 'quote' => 'Sunt mamă a doi copilași, mereu eram obosită, balonată și nu eram în apele mele. De când am descoperit capsulele pentru detoxifiere, pot să spun că sunt alt om.'],
                ['rating' => 5, 'by' => 'Daniel B.', 'quote' => 'Post-sărbători, primul lucru: Pachet Detox Plus. În 3 săptămâni mi-a revenit digestia și tenul. Nu mai e oboseala aceea grea de la mese grele.'],
                ['rating' => 5, 'by' => 'Roxana M.', 'quote' => 'După antibiotice pentru o infecție urinară, intestinul era haos. 6 săptămâni cu Confort Digestiv și totul revine la normal.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Detox-ul real nu e fast cu lichide. Ficatul și intestinul deja fac munca; ai nevoie doar să le ajuți. <strong>Silimarina</strong> (din armurariu) susține regenerarea hepatocitelor. <strong>Probioticele</strong> rebalansează flora intestinală. <strong>Fibrele solubile</strong> leagă toxinele și le elimină. Când cele trei lucrează împreună 28 de zile, sistemul își revine fără stres.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Detox-ul real înseamnă cură cu sucuri?', 'a' => 'Nu. Cura cu sucuri de 3–5 zile e mai degrabă marketing. Detox-ul real e un proces care durează 4 săptămâni, în care susții ficatul și intestinul să-și facă munca normal. Sucurile pot fi un bonus de hidratare, dar nu înlocuiesc nutriția echilibrată. Pachetul Detox Plus lucrează la nivel celular, nu lichid.'],
                ['q' => 'Cât timp ține o cură de detox?', 'a' => 'Standardul e 28 de zile pentru un efect vizibil, dar pentru o regenerare hepatică solidă recomandăm 90–120 zile. Mulți raportează că la 4 săptămâni se simt mai energici și au tranzit mai bun, dar refacerea profundă a ficatului are nevoie de timp. Recomandare practică: 1–2 cure pe an, sau una post-sărbători.'],
                ['q' => 'Pot face detox în timpul antibioticelor?', 'a' => 'Probioticele se iau la 2 ore după antibiotic, nu împreună (antibioticul îți distruge probioticele). Restul componentelor (silimarină, fibre) sunt sigure, dar e bine să întrebi medicul tău, mai ales dacă tratamentul e lung. După antibiotice, recomandăm o cură completă de Pachet Confort Digestiv pentru a reconstrui flora.'],
            ],
        ],
    ],

    // =====================================================================
    // SĂNĂTATE INTESTINALĂ
    // =====================================================================
    'sanatate-intestinala' => [
        'title' => 'Sănătate intestinală',
        'hero' => [
            'eyebrow' => 'Obiectiv: Intestin',
            'titlu' => 'Digestie <em>calmă.</em> Fără surprize, fără explicații lungi la mese.',
            'lede' => 'Probiotice microîncapsulate, fibre solubile, enzime digestive. Microbiomul echilibrat schimbă totul, de la digestie la imunitate.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['1.342 cure vândute', '★ 4,8/5', 'din 389 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => 'Pachet <em>Confort Digestiv</em>',
            'subtitlu' => 'Probiotice + Detox Ficat · 2 Suplimente pentru Digestie, Vegan.',
            'produs_slug' => 'pachet-confort-digestiv', 'nume' => 'Pachet Confort Digestiv', 'pret' => '283 lei',
            'durata' => 'ajunge 120 de zile', 'cta' => 'Adaugă în coș — 283 lei',
            'benefits' => [
                'Pentru balonare după mese, gaze, tranzit haotic, intestin sensibil.',
                'Pentru moleșeală după mese grase, greutate sub coaste, digestie greoaie a grăsimilor.',
                'Pentru recuperare după antibiotice, post-sărbători, exces alimentar, alcool ocazional.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru intestin</em>',
            'items' => [
                ['produs_slug' => 'pachet-echilibru', 'nume' => 'Pachet Echilibru', 'pret' => '325 lei', 'cta' => 'Vezi pachet', 'desc' => 'Probiotice + colagen hidrolizat. Pentru intestin sănătos plus suport pe piele, păr, articulații.'],
                ['produs_slug' => 'pachet-detox', 'nume' => 'Pachet Detox', 'pret' => '283 lei', 'cta' => 'Vezi pachet', 'desc' => 'Curățare mai largă: intestin plus ficat în paralel. Cură 120 zile.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Intestin + <em>Detox.</em> Sistemul digestiv lucrează în echipă.',
            'text' => 'Adaugă Pachetul Detox la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Probiotice pe stomacul gol, cu apă călduță. Capsulele microîncapsulate trec de aciditatea gastrică.'],
                ['when' => 'Înainte de mese', 'body' => 'Enzime digestive cu 10 min înainte, mai ales când știi că mănânci gras sau greu.'],
                ['when' => 'Seara', 'body' => 'Fibre solubile la cină. Hrănești microbii buni peste noapte, când fac munca de fond.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Maria T.', 'quote' => 'Microflora+ mă ajută să am o digestie echilibrată și sănătoasă. Probioticele microîncapsulate m-au scăpat de tranzitul haotic după antibiotice.'],
                ['rating' => 5, 'by' => 'Mihai A.', 'quote' => 'Sindrom de intestin iritabil de 5 ani. Pachet Confort Digestiv plus dietă atentă, primul an fără crize majore.'],
                ['rating' => 4, 'by' => 'Anca P.', 'quote' => 'Călătoresc des, mâncare diferită zilnic. Iau probioticele constant și intestinul s-a stabilizat. Nu mai am surprize la prima zi într-o țară nouă.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Intestinul are propriul sistem nervos și găzduiește 70% din imunitate. <strong>Microbiomul</strong> — trilioane de bacterii — digeră, sintetizează vitamine, reglează inflamația. Antibioticele, stresul și mâncarea procesată îl dezechilibrează. <strong>Probioticele microîncapsulate</strong> trec aciditatea gastrică și ajung vii în colon. <strong>Fibrele solubile</strong> le hrănesc. Restul vine de la el.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Cât timp până simt diferența la digestie?', 'a' => 'Primele schimbări apar între ziua 7 și 14: mai puțină balonare după mese, tranzit mai regulat. Stabilizarea reală a microbiomului ia 4–8 săptămâni. Recomandare: cura completă de 120 zile dacă vii după antibiotice sau ai sensibilitate cronică, 60 zile pentru întreținere generală.'],
                ['q' => 'Pot lua probiotice în timpul antibioticelor?', 'a' => 'Da, dar separat: probioticele se iau la cel puțin 2 ore distanță de doza de antibiotic (altfel le distruge). E exact perioada în care ai cel mai mult nevoie de ele. După tratament, continuă cura încă 30–60 zile pentru reconstrucția completă a florei intestinale.'],
                ['q' => 'E ok să iau zilnic, sau în cure?', 'a' => 'Pentru majoritatea oamenilor, recomandarea e în cure: 60–120 zile, apoi pauză 1–2 luni. Microbiomul are nevoie să-și mențină capacitatea proprie, nu să devină dependent. Excepțiile: post-antibiotice, sindrom de intestin iritabil cronic, călătorii frecvente — atunci probioticele zilnic, continuu, e justificat.'],
            ],
        ],
    ],

    // =====================================================================
    // FOCUS
    // =====================================================================
    'focus' => [
        'title' => 'Focus & claritate mentală',
        'hero' => [
            'eyebrow' => 'Obiectiv: Focus',
            'titlu' => 'Concentrare <em>clară.</em> Fără ceață, fără cafele în lanț.',
            'lede' => 'Lion\'s Mane, B12 și omega-3. Hrana reală a creierului, pentru zile lungi de concentrare.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['623 cure vândute', '★ 4,8/5', 'din 198 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => 'Pachet <em>Focus</em>',
            'subtitlu' => 'Claritate Mentală, Concentrare & Energie · Suplimente Vegan Premium.',
            'produs_slug' => 'pachet-focus', 'nume' => 'Pachet Focus', 'pret' => '292 lei',
            'durata' => 'ajunge 50 de zile', 'cta' => 'Adaugă în coș — 292 lei',
            'benefits' => [
                'Pentru ceață mentală, lipsă de claritate, uitări frecvente, pierderea firului în conversații.',
                'Pentru oboseală cognitivă, moleșeală după-amiaza, dependență de cafea ca să funcționezi.',
                'Pentru memorie de lucru slabă, concentrare care se rupe, sarcini lungi neduse la capăt.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru focus</em>',
            'items' => [
                ['produs_slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei', 'cta' => 'Vezi pachet', 'desc' => 'Când ceața e de oboseală, nu de focus. B-complex, magneziu și adaptogeni pentru zilele lungi.'],
                ['produs_slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei', 'cta' => 'Vezi produs', 'desc' => 'Suport antioxidant pentru creier și sistem nervos. Bonus: imunitate și echilibru metabolic.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Focus + <em>Energie.</em> Pentru zile cu multă muncă cognitivă.',
            'text' => 'Adaugă și Pachetul Energie la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Omega-3 cu micul dejun, pentru membrane neuronale flexibile. Cu grăsime se absoarbe mai bine.'],
                ['when' => 'Înainte de muncă concentrată', 'body' => 'Lion\'s Mane cu 30 minute înainte. Efectul se simte în 60–90 minute.'],
                ['when' => 'Seara', 'body' => 'B12 cu masa de seară. Nu deranjează somnul, susține sinteza de neurotransmițători peste noapte.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Ana M.', 'quote' => 'Jeleurile pentru focus și concentrare sunt foarte bune, cu gust delicios de afine, și sunt foarte ușor de luat.'],
                ['rating' => 5, 'by' => 'Vlad P.', 'quote' => 'Lucrez în development, sesiuni de 4–6 ore. După 3 săptămâni cu Pachet Focus, primul lucru observat: nu mai citesc același paragraf de 3 ori.'],
                ['rating' => 4, 'by' => 'Diana S.', 'quote' => 'Studiu pentru un examen mare. Pachet Focus + cafea moderată, nu cafea peste cafea. Diferența la final de zi e enormă.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Ceața mentală are 3 surse comune: inflamație, deficit de B12, sau membrane neuronale rigide. <strong>Omega-3</strong> (EPA + DHA) face membranele flexibile. <strong>B12</strong> susține sinteza de neurotransmițători. <strong>Lion\'s Mane</strong> stimulează NGF (nerve growth factor). Când cele trei lucrează împreună, creierul primește combustibilul real, nu doar adrenalina din cafea.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Lion\'s Mane chiar funcționează?', 'a' => 'Există studii consistente, dar nu e un nootropic instant. Efectul vine din stimularea NGF (nerve growth factor) și se construiește în 2–4 săptămâni. Diferența o simți pe sarcini cognitive lungi, nu pe momente acute. Răbdarea contează aici mai mult decât pe alte suplimente.'],
                ['q' => 'Pot ține cu cafeaua de dimineață?', 'a' => 'Da, complet compatibil. Cafeaua dă boost rapid, Pachetul Focus construiește în paralel. Mulți raportează că după 4–6 săptămâni au redus cafeaua natural, fără efort, pentru că nu mai aveau nevoie de ea pentru a porni.'],
                ['q' => 'Cât timp durează până simt diferența?', 'a' => 'Primele schimbări apar între ziua 14 și 21. Mai puține blank-uri în mijlocul propoziției, mai puțin re-citit. La 6 săptămâni e stabil. Cura de 50 zile e exact intervalul în care creierul își construiește căile noi, susținute de Lion\'s Mane și omega-3.'],
            ],
        ],
    ],

    // =====================================================================
    // PERFORMANȚĂ SPORTIVĂ
    // =====================================================================
    'performanta-sportiva' => [
        'title' => 'Performanță sportivă',
        'hero' => [
            'eyebrow' => 'Obiectiv: Sport',
            'titlu' => '<em>Forță</em> și recuperare. Proteine reale, creatină dovedită.',
            'lede' => 'Combustibilul pentru cei care nu se mulțumesc cu antrenamente pe jumătate. Calitate clinică, nu marketing.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară produsele',
            'stats' => ['2.184 cure vândute', '★ 4,9/5', 'din 612 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => '<em>ChocoProtein</em> 1000g',
            'subtitlu' => 'Proteină din Zer · Ciocolată · Susținere Masă Musculară.',
            'produs_slug' => 'chocoprotein-1000g', 'nume' => 'ChocoProtein 1000g', 'pret' => '219 lei',
            'durata' => '1000g · 33 porții', 'cta' => 'Adaugă în coș — 219 lei',
            'benefits' => [
                'Contribuie la creșterea și menținerea masei musculare.',
                'Sprijină refacerea musculară după antrenamente.',
                'Asigură un aport complet de aminoacizi esențiali.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru sport</em>',
            'items' => [
                ['produs_slug' => 'creatine-monohidrate-pro-1000g', 'nume' => 'Creatine Monohidrate Pro 1000g', 'pret' => '219 lei', 'cta' => 'Vezi produs', 'desc' => '200 porții. Suplimentul cel mai studiat din sport: forță, putere, recuperare. Creapure standardizat.'],
                ['produs_slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei', 'cta' => 'Vezi pachet', 'desc' => 'Pentru zilele când îi trebuie suport sistemic, nu doar pe efort. B-complex, magneziu, adaptogeni.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Proteină + <em>Creatină.</em> Pachetul clasic care funcționează.',
            'text' => 'Adaugă ChocoProtein și Creatine Monohidrate Pro împreună. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Pre-antrenament', 'body' => '5 g creatină cu 30 minute înainte. Apa e cheia: hidratează bine, e nevoie de transport.'],
                ['when' => 'Post-antrenament', 'body' => '30 g proteină în fereastra de 1–2 ore. Combină cu carbohidrați pentru recuperare optimă.'],
                ['when' => 'Zilele fără antrenament', 'body' => 'Continuă creatina (5 g/zi). Saturarea musculară înseamnă consistență, nu peak-uri.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Andrei P.', 'quote' => 'Sala 4 zile pe săptămână, ChocoProtein e proteina mea de bază. Gust bun, nu se simte chimic, se dizolvă perfect.'],
                ['rating' => 5, 'by' => 'Cristian L.', 'quote' => 'Am încercat 5 brand-uri de creatină în 10 ani. Diferența reală e în puritate. Vivens Genetica e cea pe care o iau acum constant.'],
                ['rating' => 5, 'by' => 'Mihai G.', 'quote' => 'După 12 săptămâni cu proteină + creatină zilnic, am adăugat 4 kg musculară vizibilă. Bonus: recuperarea e cu o zi mai rapidă între sesiuni.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Mușchiul crește din stimul + proteine + repaus. <strong>Proteina din zer</strong> are biodisponibilitate maximă (94%) și un profil complet de aminoacizi esențiali. <strong>Creatina monohidrată</strong> are cele mai multe studii din toată industria suplimentelor sportive: crește forța și puterea pe efort scurt. Combinația nu e magică, e fundamentală.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Creatina chiar e sigură pentru rinichi?', 'a' => 'Da, la doze normale (3–5 g/zi). E unul dintre cele mai studiate suplimente din lume: peste 500 de studii peer-reviewed în 30 de ani, fără efecte adverse semnificative la oameni sănătoși. Mitul rinichilor vine din confuzia cu steroizi. Dacă ai afecțiuni renale preexistente, verifică cu medicul tău înainte. Pentru toți ceilalți, e sigură pe termen lung.'],
                ['q' => 'Pot lua proteină și dacă vreau să slăbesc?', 'a' => 'Da, chiar e recomandat. Proteina susține masa musculară în timpul deficitului caloric și crește sațietatea. Un shake post-antrenament cu apă (nu lapte) adaugă 25 g proteină la 110 kcal. E unul dintre cele mai eficiente moduri de a păstra forma, când reduci alte calorii.'],
                ['q' => 'Cât timp până văd rezultate?', 'a' => 'Creatina: forța crește în 7–14 zile (saturare musculară). Proteina: efectul pe masă musculară se vede la 8–12 săptămâni cu antrenament consistent. Recuperarea mai rapidă (mai puțin DOMS, mai puțină oboseală) se simte încă din primele 2 săptămâni de utilizare regulată.'],
            ],
        ],
    ],

    // =====================================================================
    // IMUNITATE
    // =====================================================================
    'imunitate' => [
        'title' => 'Imunitate puternică',
        'hero' => [
            'eyebrow' => 'Obiectiv: Imunitate',
            'titlu' => '<em>Imunitate</em> zilnică. Nu doar când ești deja răcit.',
            'lede' => 'Vitamina C, zinc, D3 și timoquinonă. Blindajul care funcționează înainte să ai nevoie de el.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['1.243 cure vândute', '★ 4,7/5', 'din 487 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => 'Pachet <em>Imunitate</em>',
            'subtitlu' => 'Apărare Naturală & Energie · Vitamina C + Vitamina E · Vegan.',
            'produs_slug' => 'pachet-imunitate', 'nume' => 'Pachet Imunitate', 'pret' => '349 lei',
            'durata' => 'ajunge 120 de zile', 'cta' => 'Adaugă în coș — 349 lei',
            'benefits' => [
                'Pentru răceli frecvente, alergii sezoniere, imunitate slabă. Vit C 250%, zinc, D3 și timoquinonă, blindaj zilnic.',
                'Pentru recuperare lentă după viroze. Vitamina E și antioxidanții susțin refacerea celulară.',
                'Pentru cei care intră iarna fără rezerve. Construiește bariera înainte să ai nevoie de ea.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru imunitate</em>',
            'items' => [
                ['produs_slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei', 'cta' => 'Vezi produs', 'desc' => 'Ulei chimen negru egiptean cu vitamina E. Protecție imunitară și echilibru metabolic, 120 doze.'],
                ['produs_slug' => 'pachet-complex-sanatate', 'nume' => 'Pachet Complex Sănătate 40+', 'pret' => '499 lei', 'cta' => 'Vezi pachet', 'desc' => 'Pentru imunitate după 40 de ani. Protecție celulară susținută și energie pentru întreținere lunară.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Imunitate + <em>Energie.</em> Combinația care ține toată toamna.',
            'text' => 'Adaugă Pachetul Energie la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Vitamina C cu micul dejun. Începe ziua cu absorbție maximă și suport pentru bariere.'],
                ['when' => 'La prânz', 'body' => 'Zinc + D3 cu masa principală, mai bine cu grăsimi. Absorbția crește semnificativ.'],
                ['when' => 'Seara', 'body' => 'Timoquinonă din Black Seed. Nu deranjează somnul, dar reduce inflamația peste noapte.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Maria T.', 'quote' => 'Iarna trecută n-am fost răcită nici măcar o dată. Prima dată în ultimii 5 ani. Continui cu Pachet Imunitate și la toamna.'],
                ['rating' => 5, 'by' => 'Bogdan R.', 'quote' => 'Am 2 copii la grădiniță. Asta înseamnă răceli săptămânale în familie. De când iau Pachet Imunitate, mă prind mai rar și mai ușor.'],
                ['rating' => 4, 'by' => 'Ioana D.', 'quote' => 'Lucrez în open space, oamenii strănută non-stop. Acum nu mai îmi pasă, parcă am scut.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Imunitatea nu se construiește în 3 zile, când deja te-ai răcit. <strong>Vitamina C</strong> și <strong>zincul</strong> susțin barierele și funcția celulelor imune. <strong>D3</strong> reglează răspunsul imun, mai ales iarna. <strong>Timoquinona</strong> din chimen negru are studii pe stres oxidativ și inflamație. Împreună, e blindaj construit înainte, nu reacție după.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Când încep să simt că imunitatea e mai puternică?', 'a' => 'Imunitatea nu funcționează ca o pastilă pentru durere. Primele 2–3 săptămâni construiesc rezerve. Diferența se vede iarna, când cei din jurul tău se îmbolnăvesc și tu nu, sau te recuperezi în 2 zile, nu 7. Recomandăm cura completă de 120 zile pentru un sezon stabil.'],
                ['q' => 'Pot lua împreună cu alte vitamine?', 'a' => 'Da, Pachetul Imunitate e construit ca formulă completă, deci nu ai nevoie să mai adaugi vitamine separat. Dacă iei deja D3 sau alte suplimente, scrie-ne pe WhatsApp să verificăm dozajul total. Mai mult nu înseamnă mai bine.'],
                ['q' => 'Iarna sau tot anul?', 'a' => 'Iarna e momentul cel mai util, dar Vit D3 e justificat tot anul în România (chiar și vara mulți avem deficit subclinic). Recomandare practică: cura de 120 zile în septembrie–decembrie, apoi întreținere dimineață cu Black Seed Elixir între ianuarie și august.'],
            ],
        ],
    ],

    // =====================================================================
    // SĂNĂTATEA INIMII
    // =====================================================================
    'sanatatea-inimii' => [
        'title' => 'Sănătatea inimii',
        'hero' => [
            'eyebrow' => 'Obiectiv: Inimă',
            'titlu' => '<em>Inima,</em> calm și constant. Susținere zilnică, nu panică anuală.',
            'lede' => 'Omega-3, Q10, magneziu și K2. Patru piloni cardiovasculari pe care îți poți baza rutina de viață.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['743 cure vândute', '★ 4,9/5', 'din 198 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => '<em>Black Seed</em> Elixir',
            'subtitlu' => 'Imunitate & Echilibru Metabolic · Ulei Chimen Negru Egiptean + Vit. E, Vegan.',
            'produs_slug' => 'black-seed-elixir', 'nume' => 'Black Seed Elixir', 'pret' => '184 lei',
            'durata' => '240 capsule · 120 zile', 'cta' => 'Adaugă în coș — 184 lei',
            'benefits' => [
                'Protecție imunitară zilnică, susținere antioxidantă pentru sistemul vascular.',
                'Echilibru metabolic natural, suport pe colesterol și inflamație de fond.',
                'Suport cardiovascular prin timoquinonă și vitamina E, studiate pe stres oxidativ.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru inimă</em>',
            'items' => [
                ['produs_slug' => 'pachet-complex-sanatate', 'nume' => 'Pachet Complex Sănătate 40+', 'pret' => '499 lei', 'cta' => 'Vezi pachet', 'desc' => 'Pentru imunitate, energie și protecție celulară după 40 de ani. Complex cu omega-3, Q10 și K2.'],
                ['produs_slug' => 'pachet-energie', 'nume' => 'Pachet Energie', 'pret' => '306 lei', 'cta' => 'Vezi pachet', 'desc' => 'Magneziu și adaptogeni pentru tensiune calmă, recuperare zilnică, ritm cardiac stabil sub efort.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Inimă + <em>Energie.</em> Când vrei susținere cardiovasculară și vitalitate.',
            'text' => 'Adaugă Pachetul Energie la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Omega-3 cu micul dejun. Cu grăsimi, absorbția e maximă. Niciodată pe stomacul gol.'],
                ['when' => 'La prânz', 'body' => 'Q10 cu masa principală, mai ales dacă iei statine. Acestea reduc Q10 natural în organism.'],
                ['when' => 'Seara', 'body' => 'Magneziu și K2 înainte de culcare. Magneziul calmează, K2 dirijează calciul în oase, nu în artere.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Stelian D.', 'quote' => 'La 58 de ani, cardiologul mi-a recomandat Omega-3 și Q10. Vivens Genetica are puritatea pe care o caut. Tensiune mai stabilă, energie mai bună.'],
                ['rating' => 5, 'by' => 'Mariana C.', 'quote' => 'Familie cu istoric cardiovascular. Black Seed Elixir plus analize anuale, asta înseamnă pentru mine prevenție reală. Încă nimic anormal în ultimii 4 ani.'],
                ['rating' => 5, 'by' => 'Tudor V.', 'quote' => 'Joc tenis 3 ori pe săptămână la 52 de ani. Pachet Complex 40+ și Black Seed sunt rutina mea de întreținere. Nu mai am palpitațiile pe care le simțeam ocazional sub efort.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Sistemul cardiovascular cere consistență, nu eroism. <strong>Omega-3</strong> (EPA + DHA) reduc inflamația vasculară și trigliceridele. <strong>Q10</strong> e combustibilul mitocondriilor cardiace, scade natural cu vârsta. <strong>Magneziul</strong> reglează ritmul și tonusul vascular. <strong>Vitamina K2</strong> ține calciul în oase și îl scoate din artere. Când toate patru lucrează zilnic, inima capătă condițiile pe care le merită. Suplimentele nu înlocuiesc tratamentul cardiologic — consultă medicul înainte dacă ai un diagnostic existent.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Pot lua împreună cu medicamente cardiovasculare?', 'a' => 'Pentru majoritatea combinațiilor, da, dar verifică cu medicul tău. Atenție specială la: anticoagulante (omega-3 în doze mari pot prelungi sângerarea), statine (Q10 e recomandat tocmai pentru că statinele îl reduc), antihipertensive (magneziul poate potența efectul, ajustarea dozei poate fi necesară). Niciodată să nu oprești un medicament fără acord medical.'],
                ['q' => 'Omega-3 de pește sau din alge?', 'a' => 'Ambele funcționează, depinde de preferință și absorbție. Cel de pește are concentrații mai mari de EPA și DHA cu un cost mai mic. Cel din alge e potrivit pentru vegani și are de obicei un nivel mai mic de contaminanți. Pentru efect cardiovascular real, ai nevoie de minim 1g EPA+DHA pe zi, indiferent de sursă. Verifică eticheta, nu doar cantitatea totală de ulei.'],
                ['q' => 'Cât timp până văd schimbări în analize?', 'a' => 'Trigliceridele pot scădea în 6–8 săptămâni cu omega-3 (1–2 g/zi). Tensiunea poate răspunde la magneziu în 4–6 săptămâni. Colesterolul HDL crește lent, 3–6 luni. Pentru schimbări vizibile în analize, recomandă verificare după 3 luni de cură consistentă. Stilul de viață contează 70% din rezultat, suplimentele restul.'],
            ],
        ],
    ],

    // =====================================================================
    // FRUMUSEȚE
    // =====================================================================
    'frumusete' => [
        'title' => 'Frumusețe — piele, păr, unghii',
        'hero' => [
            'eyebrow' => 'Obiectiv: Frumusețe',
            'titlu' => '<em>Frumusețea</em> care vine din interior. La propriu.',
            'lede' => 'Colagen, biotină, seleniu, zinc. Cremele țin de suprafață. Foliculul și pielea se hrănesc din sânge.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['1.567 cure vândute', '★ 4,8/5', 'din 423 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => 'Pachet <em>Frumusețe</em>',
            'subtitlu' => 'Piele, Păr & Unghii · Colagen + Antioxidanți pentru Articulații.',
            'produs_slug' => 'pachet-frumusete', 'nume' => 'Pachet Frumusețe', 'pret' => '349 lei',
            'durata' => 'ajunge 50 de zile', 'cta' => 'Adaugă în coș — 349 lei',
            'benefits' => [
                'Pentru riduri fine, pierdere de fermitate a pielii, ten obosit fără strălucire, cearcăne persistente.',
                'Pentru păr fragil, cădere accentuată, unghii care se exfoliază în foi, vârfuri despicate.',
                'Pentru disconfort articular, rigiditate matinală, mobilitate redusă a genunchilor și a articulațiilor mici.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru frumusețe</em>',
            'items' => [
                ['produs_slug' => 'collagen-joint-berry-500-ml', 'nume' => 'Collagen Joint+ Berry', 'pret' => '184 lei', 'cta' => 'Vezi produs', 'desc' => 'Colagen lichid, 33 doze. Bonus pentru articulații și mobilitate. Aromă naturală de fructe de pădure.'],
                ['produs_slug' => 'pachet-regenerare-celulara', 'nume' => 'Pachet Regenerare Celulară', 'pret' => '524 lei', 'cta' => 'Vezi pachet', 'desc' => 'Pentru abordare profundă: colagen + multivitamine + Black Seed. Cură 120 zile.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Frumusețe + <em>Regenerare.</em> Când vrei rezultat vizibil și profund.',
            'text' => 'Adaugă și Pachetul Regenerare Celulară la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Colagen cu vitamina C. Absorbția e maximă și sinteza proprie e activată simultan.'],
                ['when' => 'La prânz', 'body' => 'Biotină, zinc și seleniu cu masa principală. Cofactorii cheratinei lucrează împreună.'],
                ['when' => 'Seara', 'body' => 'Antioxidanți cu cina. Lucrează pe regenerarea nocturnă a pielii, când corpul reface țesut.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Mihaela R.', 'quote' => 'Lucrând în domeniul cosmeticii, știu cât este de important colagenul. Am ales Collagen Joint+ și Vita Complete+. O alegere pe care cu siguranță o voi repeta.'],
                ['rating' => 5, 'by' => 'Elena V.', 'quote' => 'La 45 de ani, primul lucru observat după 8 săptămâni: unghiile nu mai se exfoliază. Apoi părul a început să cadă mai puțin.'],
                ['rating' => 4, 'by' => 'Andreea T.', 'quote' => 'Cremele și serurile au limita lor. Cu Pachet Frumusețe am văzut diferența unde nu intra crema, în textura generală a pielii.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Părul, unghiile și pielea sunt vârfurile unui sistem nutrițional. <strong>Colagenul ingerat</strong> oferă aminoacizii speciali (glicină, prolină, hidroxiprolină) pentru sinteza proprie. <strong>Biotina, zincul și seleniul</strong> sunt cofactori pentru cheratină. <strong>Vitamina C</strong> activează sinteza colagenului. Cremele lucrează pe suprafață. Foliculul și derma se hrănesc din sânge.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Colagenul ingerat chiar ajunge la piele?', 'a' => 'Da, dar nu intact. Colagenul se descompune în peptide și aminoacizi (glicină, prolină, hidroxiprolină) care ajung în sânge și sunt redistribuiți. Studiile arată că prezența acestor peptide stimulează fibroblastele să producă mai mult colagen propriu. Nu e magia colagenul ingerat = colagen pe piele, e mai degrabă materie primă + semnal.'],
                ['q' => 'Când văd primele rezultate?', 'a' => 'Unghiile: 4–6 săptămâni (cresc 3–4 mm pe lună, deci durează să se înlocuiască). Părul: 8–12 săptămâni (foliculul are ciclul lui). Pielea: 6–10 săptămâni pentru hidratare și elasticitate vizibilă. Diferențele dramatice nu apar peste noapte, dar la 3 luni majoritatea oamenilor observă schimbarea când se uită în poze vechi.'],
                ['q' => 'Trebuie să iau colagen tot timpul sau e o cură?', 'a' => 'Recomandare practică: cură de 50–120 zile, apoi întreținere periodică (2–3 cure pe an). Producția proprie de colagen scade cu vârsta (~1% pe an după 25). Pentru suport real, e o investiție pe termen lung, dar nu trebuie luat zilnic fără pauză. Multe persoane intră într-un ritm de 3 luni cură, 1–2 luni pauză.'],
            ],
        ],
    ],

    // =====================================================================
    // OASE & ARTICULAȚII
    // =====================================================================
    'oase-articulatii' => [
        'title' => 'Oase & articulații',
        'hero' => [
            'eyebrow' => 'Obiectiv: Articulații',
            'titlu' => '<em>Mobilitate</em> care nu se înmoaie cu vârsta.',
            'lede' => 'Colagen tip II, vitamina C, vitamina D, calciu. Hrana reală a cartilajului și a osului.',
            'cta_primary' => 'Vezi recomandarea principală', 'cta_secondary' => 'Compară pachetele',
            'stats' => ['892 cure vândute', '★ 4,8/5', 'din 267 recenzii', '90 zile garanție'],
        ],
        'reco' => [
            'eyebrow' => 'Pick-ul principal',
            'titlu' => '<em>Collagen</em> Joint+ Berry',
            'subtitlu' => 'Articulații, Oase & Piele · Colagen Lichid 33 doze.',
            'produs_slug' => 'collagen-joint-berry-500-ml', 'nume' => 'Collagen Joint+ Berry', 'pret' => '184 lei',
            'durata' => '500 ml · 33 zile', 'cta' => 'Adaugă în coș — 184 lei',
            'benefits' => [
                'Sprijină sănătatea articulațiilor și mobilitatea.',
                'Contribuie la menținerea elasticității pielii și a țesuturilor.',
                'Susține sinteza naturală de colagen și refacerea cartilajelor.',
            ],
        ],
        'alts' => [
            'titlu' => 'Alte opțiuni <em>pentru articulații</em>',
            'items' => [
                ['produs_slug' => 'pachet-regenerare-celulara', 'nume' => 'Pachet Regenerare Celulară', 'pret' => '524 lei', 'cta' => 'Vezi pachet', 'desc' => 'Cură profundă: colagen tip 1+2+3, MSM, glucozamină, condroitină, acid hialuronic, curcumă. 120 zile.'],
                ['produs_slug' => 'pachet-frumusete', 'nume' => 'Pachet Frumusețe', 'pret' => '349 lei', 'cta' => 'Vezi pachet', 'desc' => 'Același colagen, bonus pe piele, păr, unghii. Cură de 50 zile.'],
            ],
        ],
        'bundle' => [
            'eyebrow' => 'Combină',
            'titlu' => 'Articulații + <em>Frumusețe.</em> Același colagen, două beneficii.',
            'text' => 'Adaugă și Pachetul Frumusețe la coș. Economisești 15% pe total, fără cod, automat la finalizare.',
            'cta' => 'Vezi combinația',
        ],
        'how' => [
            'items' => [
                ['when' => 'Dimineața', 'body' => 'Colagen lichid pe stomacul gol. Absorbția e maximă, iar vitamina C activează sinteza.'],
                ['when' => 'Înainte de mișcare', 'body' => 'MSM și glucozamină dacă faci sport sau ai muncă fizică. Susțin cartilajul sub stres.'],
                ['when' => 'Seara', 'body' => 'Curcuma cu cina. Antiinflamator în timpul somnului, când se face cea mai mare reparație.'],
            ],
        ],
        'reviews' => [
            'titlu' => 'Ce spun cei care <em>folosesc</em>',
            'note' => 'Recenzii reale și demo pentru mockup design. Datele finale se actualizează la conectarea cu sistemul de review.',
            'items' => [
                ['rating' => 5, 'by' => 'Mihaela R.', 'quote' => 'Merg constant la sală, și când am început să simt disconfort după antrenamente, am ales Collagen Joint+. O alegere pe care cu siguranță o voi repeta.'],
                ['rating' => 5, 'by' => 'Liviu M.', 'quote' => 'La 52 de ani, genunchii începuseră să pocnească și să doară la scări. După 10 săptămâni cu Collagen Joint+, mobilitatea s-a îmbunătățit clar. Ține minte că-i nevoie de consistență, nu pastile minune.'],
                ['rating' => 4, 'by' => 'Carmen S.', 'quote' => 'Hipermobilitate articulară. Specialistul mi-a recomandat colagen tip II + vitamina C. Vivens Genetica are exact ce trebuie, gust ok, ușor de luat.'],
            ],
        ],
        'edu' => [
            'titlu' => 'De ce funcționează <em>combinația asta</em>',
            'text' => 'Cartilajul nu are vase de sânge. Se hrănește prin difuziune din lichidul sinovial, activat de mișcare. <strong>Colagenul tip II</strong> e materia primă a cartilajului. <strong>Vitamina C</strong> activează sinteza colagenului. <strong>Vitamina D și calciul</strong> țin osul dens. Când cele patru lucrează împreună, plus mișcare zilnică blândă, sistemul primește materialele și semnalul să le folosească.',
        ],
        'faq' => [
            'titlu' => 'Ce ne <em>întrebați</em>',
            'items' => [
                ['q' => 'Colagenul tip II e mai bun ca cel tip I?', 'a' => 'Depinde de obiectiv. Colagenul tip II e materia primă a cartilajului (articulații), iar tip I e dominant în piele, oase, tendoane. Pentru mobilitate și disconfort articular, tip II e mai țintit. Pentru frumusețe (piele/păr/unghii), tip I funcționează mai bine. Pachetul Regenerare Celulară conține ambele, dacă vrei beneficii pe ambele direcții.'],
                ['q' => 'Pot lua împreună cu medicamente pentru articulații?', 'a' => 'În general da, colagenul și vitaminele sunt nutrienți, nu medicamente. Dar dacă iei antiinflamatoare cronic sau ai un tratament specific (corticosteroizi, anticoagulante), verifică cu medicul tău. Pentru cei mai mulți, suplimentele susțin terapia, nu o înlocuiesc.'],
                ['q' => 'Cât timp până simt diferența?', 'a' => 'Articulațiile răspund lent: 6–12 săptămâni pentru schimbări palpabile (mai puțin disconfort dimineața, mai puține pocnituri, mobilitate mai bună). Cartilajul se regenerează încet — e structura cea mai lentă din corp. Recomandare practică: cură de 90–120 zile, apoi întreținere lunară. Mișcarea regulată e jumătate din rezultat.'],
            ],
        ],
    ],

];
