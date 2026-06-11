<?php

/**
 * Date seed pentru secțiunile editoriale ale paginilor de PACHET — transcrise
 * din mockup-urile `preferinte/Pagina Pachet - *.html`. Cheia este slug-ul
 * produsului WooCommerce (tip bundle).
 *
 * Consumat de App\seed_pachet() (app/pachet-seed.php). Câmpurile ACF sunt
 * definite în app/acf-pachet.php (grup `group_pachet_editorial`, prefix pk_).
 * `excerpt` suprascrie post_excerpt (afișat în hero sub tagline).
 *
 * Note:
 *  - Mockup-ul „Imunitate Toamna" nu are produs corespondent în DB — neinclus.
 *  - La Detox și Confort Digestiv, mockup-urile aveau răspunsuri doar la prima
 *    întrebare FAQ; restul au fost completate concis — de revizuit editorial.
 *  - La Vitalitate, titlul secțiunii „Cum se folosește" din mockup spunea
 *    „Două produse" deși pachetul are trei — corectat la „Trei produse".
 */

return [

    // -------------------------------------------------------------------
    // Pachet Focus (LionFocus B6 + Vita Complete+)
    // -------------------------------------------------------------------
    'pachet-focus' => [
        'excerpt' => 'Două produse care lucrează pe două straturi: <strong>LionFocus B6</strong> țintește neuronii cu extract concentrat de Coama Leului (Hericium erinaceus) și vitamina B6, <strong>Vita Complete+</strong> aduce fundația nutrițională cu 25+ vitamine și minerale pentru energie celulară reală. Cură completă pentru <strong>50 de zile</strong>.',
        'pk_eyebrow' => 'PACHET FOCUS · CLARITATE & CONCENTRARE',
        'pk_tagline' => 'Memorie, concentrare, echilibru neuronal.',
        'why' => [
            'kicker' => 'Cum lucrează împreună',
            'titlu' => 'Axa <em>bază-țintă, ținută în echilibru.</em>',
            'prose' => [
                'Creierul consumă <strong>20% din energia totală</strong> a corpului deși cântărește doar 2% din greutate. Are nevoie permanentă de cofactori specifici: <strong>B-complex</strong> pentru neurotransmițători, <strong>magneziu</strong> pentru sinapse calme, <strong>Coenzima Q10</strong> pentru mitocondriile neuronale.',
                'Pachetul Focus lucrează pe două straturi: Vita Complete+ acoperă fundația nutrițională completă, LionFocus B6 aduce extract concentrat de Hericium pentru susținerea funcției cognitive. Două straturi, o singură axă.',
            ],
            'cards' => [
                ['rol' => 'Ținta · LionFocus B6', 'titlu' => 'Coama Leului 200 mg din corp de fructificație + Vit. B6 100% VNR.', 'text' => 'Extract concentrat de <strong>Hericium erinaceus</strong> cu 56 mg polizaharide și 8 mg beta-glucani. Vitamina B6 susține funcția psihologică normală și reducerea oboselii. Gust de afine, 2 jeleuri pe zi.'],
                ['rol' => 'Baza · Vita Complete+', 'titlu' => '25+ vitamine și minerale lichide + Green Blend & Amino Blend.', 'text' => 'B-complex complet (B1 1515%, B2 1191%, B6 476%, B12 10667%), Coenzima Q10, luteina și zeaxantina pentru ochi obosiți de ecrane. Combustibilul mitocondrial pentru claritate mentală reală.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 50 de zile.</em>',
            'items' => [
                'Claritate mentală pe parcursul zilei — Hericium + B-complex + Q10 susțin funcția neuronală',
                'Reducerea oboselii cu claim EFSA — Vit. C, B6, B12, B-complex, magneziu',
                'Memorie de lucru susținută — colina (precursor acetilcolină) + Hericium + B12 metilcobalamină',
                'Concentrare pe sarcini lungi — efectul cumulativ al Hericium (6–12 săptămâni)',
                'Echilibru psihologic și mai puțină iritabilitate — B6 + magneziu pe axa stres-neurotransmițători',
                'Energie celulară reală fără cafea — Coenzima Q10 + B-complex repornesc mitocondriile',
                'Dependență redusă de cafea — funcționezi clar dimineața și după-amiaza',
                'Protecție celulară împotriva stresului oxidativ (claim EFSA) — Vit. C, E naturală, seleniu, Q10',
                'Susținere a ochilor în era ecranelor — luteina și zeaxantina se acumulează în retină',
                'Sistem nervos echilibrat (claim EFSA) — magneziu, B6, B12, colina',
                'Imunitate susținută în paralel — Vit. C 250%, D3 400%, zinc, Vit. A',
                'Cură ușor de respectat zilnic — 1 shot dimineața + 2 jeleuri în pauza de cafea, 30 secunde',
            ],
        ],
        'tl' => [
            'titlu' => 'Două produse, <em>două momente ale zilei.</em>',
            'steps' => [
                ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '1 shot Vita Complete+.', 'text' => '10 ml la trezire, cu 15–30 min înainte de mic dejun. Absorbția optimă a vitaminelor B-complex pentru claritate de la primele ore. Gust de portocale.'],
                ['when' => 'În pauza de cafea', 'titlu' => '2 jeleuri LionFocus B6.', 'text' => 'În jurul orei 10–11 sau când simți prima moleșeală cognitivă. Hericium se absoarbe bine indiferent de masă. Gust de afine.'],
                ['when' => 'Durată recomandată', 'titlu' => 'Minimum 3 luni pentru efect complet.', 'text' => 'Primele schimbări (energie, claritate) apar în 1–2 săptămâni. Efectul Hericium pe memoria de lucru se construiește cumulativ în 6–12 săptămâni.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Persoane cu ceață mentală cronică, uitări frecvente, pierdere de claritate',
                'Cei cu oboseală cognitivă, moleșeală după-amiaza, dependență de cafea',
                'Persoane care lucrează pe sarcini lungi și cognitive (writing, coding, analiză)',
                'Studenți în sesiune, persoane în pregătirea examenelor',
                'Stil de viață cu ecrane 8+ ore pe zi, ochi obosiți',
                'Cei peste 30 cu primele semne de declin cognitiv subclinic',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Persoane cu tulburări psihice în tratament — verifică cu medicul',
                'Alergie la ciuperci sau familia Hericium',
                'Cei sub tratament cu anticoagulante — Hericium poate influența coagularea',
                'Cei cu boli autoimune severe — verifică cu medicul (beta-glucani modulează imunitatea)',
            ],
        ],
        'faq' => [
            ['q' => 'Când încep să simt diferența?', 'a' => 'Energia și claritatea apar în <strong>1–2 săptămâni</strong>. Efectul Hericium pe memoria de lucru se construiește cumulativ în 6–12 săptămâni de utilizare zilnică.'],
            ['q' => 'Hericium este sigur?', 'a' => 'Da. <strong>Coama Leului</strong> are istorie de utilizare alimentară de secole în Asia. Studii moderne arată profil de siguranță excelent. Folosim extract din <strong>corp de fructificație</strong>, nu miceliu pe cereale.'],
            ['q' => 'Pot lua cu cafea?', 'a' => 'Da. De fapt, mulți utilizatori observă că <strong>reduc treptat numărul de cești</strong> pe zi pentru că nu mai au nevoie de boost-uri repetate.'],
            ['q' => 'Conține alergeni?', 'a' => 'Nu conține <strong>gluten, lactoză, soia sau OMG</strong>. Atenție dacă ești alergic la ciuperci.'],
            ['q' => 'Jeleurile au zahăr?', 'a' => 'Conțin <strong>îndulcitori naturali</strong> (eritritol, stevia) în cantități mici. Fără zahăr adăugat, fără coloranți artificiali.'],
            ['q' => 'Garanția de 14 zile cum funcționează?', 'a' => 'Dacă după 14 zile produsul nu te convinge, primești banii înapoi pentru sticla deschisă. Returul se face din cont, <strong>fără întrebări</strong>.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Detox (D-Tox Ficat + Microflora+)
    // -------------------------------------------------------------------
    'pachet-detox' => [
        'excerpt' => 'Două produse, două axe, sub 30 secunde zilnic. <strong>D-Tox Ficat</strong> repornește filtrul hepatic, <strong>Microflora+ Lemon Shots</strong> reface flora intestinală.',
        'pk_eyebrow' => 'PACHET DETOX · axa intestin-ficat',
        'pk_tagline' => 'Reset esențial al axei intestin-ficat.',
        'why' => [
            'kicker' => 'Cum acționează pachetul',
            'titlu' => 'Curățare <em>și</em> refacere — pe aceeași axă.',
            'prose' => [
                'Detoxifierea reală nu e o cură-bici de 7 zile. Este <strong>echilibrul axei intestin-ficat</strong>: ficatul filtrează, intestinul absoarbe. Dacă ficatul e obosit, toxinele se acumulează. Dacă flora intestinală e dezechilibrată, ficatul lucrează în plus.',
                'Pachetul Detox tratează ambele capete simultan: <strong>D-Tox Ficat</strong> repornește filtrul hepatic, <strong>Microflora+ Lemon Shots</strong> reface flora. Două intervenții complementare, o singură rutină.',
            ],
            'cards' => [
                ['rol' => 'Curățare · D-Tox Ficat', 'titlu' => 'Silimarină 200 mg, anghinare, păpădie.', 'text' => 'Hepatoprotecție prin silimarină standardizată. Stimularea producției de bilă prin cinarină. Regenerare hepatocite și protecție antioxidantă.'],
                ['rol' => 'Refacere · Microflora+', 'titlu' => '4 tulpini probiotice microîncapsulate, 10 mld UFC.', 'text' => 'B. lactis, L. acidophilus, L. plantarum, L. salivarius + L-glutamină + Vit. C. Refacerea florei, susținerea peretelui intestinal.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 120 de zile.</em>',
            'items' => [
                'Reducerea balonării și a gazelor după mese',
                'Tranzit intestinal regulat și predictibil',
                'Reducerea senzației de oboseală (Vit. C, claim EFSA)',
                'Repornirea filtrului hepatic prin silimarină standardizată',
                'Digestia mai ușoară a grăsimilor prin cinarină (anghinare)',
                'Ten mai luminos prin echilibrarea axei intestin-ficat',
                'Halenă mai curată odată cu echilibrarea florei',
                'Somn mai stabil, mai puține treziri nocturne',
                'Reducerea poftei de zahăr după reechilibrarea microbiomului',
                'Susținere imunitară (intestinul găzduiește 70–80% din celulele imune)',
                'Sprijin pentru obiective de slăbit (digestie eficientă, mai puțină inflamație)',
                'Rutină zilnică simplă, sub 30 de secunde',
            ],
        ],
        'tl' => [
            'titlu' => 'Două produse, <em>o rutină de 30 de secunde.</em>',
            'steps' => [
                ['when' => 'Dimineața, cu micul dejun', 'titlu' => '1 capsulă D-Tox Ficat.', 'text' => 'Cu apă, în timpul mesei. Silimarina se absoarbe mai bine în prezența lipidelor alimentare.'],
                ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '1 shot Microflora+ Lemon.', 'text' => '15 ml la trezire, cu 15–30 min înainte de mic dejun. Probioticele ajung viabile în intestin prin microîncapsulare.'],
                ['when' => 'Durată recomandată', 'titlu' => 'Minimum 3 luni, ideal 120 zile.', 'text' => 'Curățarea hepatică și refacerea florei se construiesc treptat. Diferențele se simt clar după 4–6 săptămâni consecutive.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Cure sezoniere de primăvară și toamnă',
                'După sărbători sau perioade cu mese grele și alcool',
                'Post-antibiotice sau post-tratamente cronice',
                'Persoane cu balonare frecventă și tranzit lent',
                'Cei care simt greutate hepatică și oboseală metabolică',
                'Sprijin în programe de slăbit sau dietă reset',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Persoane imunocompromise sever — consultă medicul',
                'Alergie la armurariu sau familia Asteraceae',
                'Tratament cronic metabolizat hepatic — verifică interacțiunile',
            ],
        ],
        'faq' => [
            ['q' => 'Pot lua și alte suplimente în același timp?', 'a' => 'Da. Pachetul se combină ușor cu <strong>vitamine, omega-3, colagen</strong>. Pentru tratamente prescrise (mai ales metabolizate hepatic) consultă medicul. Evită dublarea cu alte produse care conțin silimarină sau probiotice.'],
            ['q' => 'Cât timp țin cura?', 'a' => 'Minimum 3 luni, ideal <strong>120 de zile</strong>. D-Tox Ficat acoperă toată perioada; pentru Microflora+ (33 de doze) recomandăm o sticlă pe lună sau cure intermitente (lunile 1 și 3).'],
            ['q' => 'Pot lua în sarcină sau alăptare?', 'a' => '<strong>Nu fără avizul medicului.</strong> Femeile însărcinate sau care alăptează trebuie să consulte medicul înainte de orice supliment, inclusiv plante hepatoprotectoare.'],
            ['q' => 'Ce fac dacă unul dintre produse nu mi se potrivește?', 'a' => 'Ne scrii și găsim soluția: poți <strong>returna produsul în 14 zile</strong>, chiar deschis, și primești banii înapoi. Restul curei continuă cu produsul care ți se potrivește.'],
            ['q' => 'Garanția de 14 zile cum funcționează?', 'a' => 'Dacă după 14 zile produsul nu te convinge, primești banii înapoi pentru sticla deschisă. Returul se face din cont, <strong>fără întrebări</strong>.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Imunitate (Black Seed Elixir + Vita Complete+)
    // -------------------------------------------------------------------
    'pachet-imunitate' => [
        'excerpt' => 'Două produse care lucrează împreună pe axa scut-combustibil: <strong>Black Seed Elixir</strong> construiește scutul antiinflamator și antioxidant, <strong>Vita Complete+ Vegan Shots</strong> oferă combustibilul celular cu 25+ nutrienți esențiali. Cură completă pentru <strong>120 de zile</strong>.',
        'pk_eyebrow' => 'PACHET IMUNITATE · APĂRARE & ENERGIE',
        'pk_tagline' => 'Apărare imună, antioxidanți, energie celulară.',
        'why' => [
            'kicker' => 'Cum lucrează împreună',
            'titlu' => 'Axa <em>scut-combustibil, ținută în echilibru.</em>',
            'prose' => [
                'Imunitatea puternică nu vine dintr-o singură pastilă. Vine din echilibrul între <strong>nutrienții esențiali</strong> (vitamina C, D3, zinc, vitamina A), <strong>inflamația cronică de fond</strong> ținută sub control și <strong>stresul oxidativ</strong> neutralizat zilnic.',
                'Pachetul Imunitate lucrează simultan pe ambele capete: Black Seed Elixir construiește scutul, Vita Complete+ alimentează fundația celulară. Două intervenții, o singură axă.',
            ],
            'cards' => [
                ['rol' => 'Scutul · Black Seed Elixir', 'titlu' => 'Ulei chimen negru egiptean 1000 mg + Vit. E naturală.', 'text' => 'Presat la rece, bogat în timoquinonă — compus rar studiat în peste 800 de studii pentru rolul antiinflamator și antioxidant. Vitamina E 67% VNR pentru protecție celulară.'],
                ['rol' => 'Combustibilul · Vita Complete+', 'titlu' => '25+ vitamine și minerale lichide + Green Blend & Amino Blend.', 'text' => 'Vit. C 250%, D3 400%, B12 10667%, Biotina 400%, B6 476% — un complex complet de micronutrienți pentru energie, imunitate și reducerea oboselii.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 120 de zile.</em>',
            'items' => [
                'Imunitate zilnică întărită cu Vit. C 250% VNR, D3 400%, zinc și Vit. A',
                'Reducerea frecvenței și duratei răcelilor prin aport constant de nutrienți cheie',
                'Inflamația cronică de fond redusă prin timoquinonă + Vit. E + omega 6/9',
                'Protecție antioxidantă puternică împotriva stresului oxidativ celular',
                'Energie celulară susținută prin B-complex complet + Coenzima Q10 + magneziu',
                'Reducerea oboselii persistente cu vitamine cu rol confirmat EFSA',
                'Compensarea deficitelor subclinice de Vit. D, B12, zinc, magneziu',
                'Susținere cardiovasculară prin acizi grași nesaturați și Vit. E naturală',
                'Echilibru metabolic și susținerea nivelului normal al grăsimilor din sânge',
                'Păr, unghii și piele susținute prin biotină, zinc, seleniu și Vit. E',
                'Sistem nervos echilibrat cu magneziu, potasiu, B6, B12',
                'Claritate mentală și concentrare prin B-complex complet + iod',
            ],
        ],
        'tl' => [
            'titlu' => 'Două produse, <em>două momente ale zilei.</em>',
            'steps' => [
                ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '1 shot Vita Complete+.', 'text' => '10 ml la trezire, cu 15–30 min înainte de mic dejun. Absorbția optimă a vitaminelor liposolubile prin formatul lichid. Gust de portocale.'],
                ['when' => 'La micul dejun', 'titlu' => '2 capsule Black Seed Elixir.', 'text' => 'Cu apă, în timpul mesei sau imediat după. Uleiul de chimen negru se absoarbe mai bine în prezența lipidelor alimentare.'],
                ['when' => 'Durată recomandată', 'titlu' => 'Minimum 3 luni, ideal 120 zile.', 'text' => 'Schimbările pe axa imună se construiesc treptat. Diferențele se simt clar după 4–6 săptămâni de utilizare consecventă.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Persoane cu răceli frecvente, alergii sezoniere sau imunitate slabă',
                'Persoane care vor un blindaj zilnic în perioada toamnă-iarnă',
                'Cei care simt oboseală cronică și dependență de cafea pentru a funcționa',
                'Persoane peste 30 ani cu deficite subclinice de vitamine și minerale',
                'Stil de viață cu stres, mese neregulate, somn insuficient',
                'Cei care vor o soluție completă într-un singur protocol zilnic',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Persoane imunocompromise sever — consultă medicul',
                'Alergie la chimen negru sau familia Asteraceae',
                'Cei sub tratament cu anticoagulante — verifică cu medicul',
                'Cei cu hipertiroidism — verifică cu medicul',
            ],
        ],
        'faq' => [
            ['q' => 'Când încep să simt diferența?', 'a' => 'Primele schimbări (<strong>energie, somn</strong>) apar în 2–4 săptămâni. Imunitatea crește treptat — diferențele în frecvența răcelilor se văd după 6–8 săptămâni de utilizare zilnică.'],
            ['q' => 'Pot lua amândouă produsele în aceeași zi?', 'a' => 'Da, sunt formulate să se completeze. Recomandăm <strong>Vita Complete+</strong> dimineața pe stomacul gol și <strong>Black Seed Elixir</strong> la micul dejun.'],
            ['q' => 'Este sigur pentru utilizare pe termen lung?', 'a' => 'Da. Ambele produse sunt formulate pentru utilizare zilnică de lungă durată. Pentru cure peste <strong>6 luni</strong>, recomandăm o pauză de 2–4 săptămâni.'],
            ['q' => 'Conține alergeni?', 'a' => 'Nu conține <strong>gluten, lactoză, soia sau OMG</strong>. Atenție dacă ești alergic la chimen negru sau familia Asteraceae (anghinare, păpădie).'],
            ['q' => 'Pot lua împreună cu alte suplimente?', 'a' => 'Da, în majoritatea cazurilor. Dacă iei medicamente pentru <strong>tensiune, glicemie sau anticoagulante</strong>, verifică cu medicul.'],
            ['q' => 'Garanția de 14 zile cum funcționează?', 'a' => 'Dacă după 14 zile produsul nu te convinge, primești banii înapoi pentru sticla deschisă. Returul se face din cont, <strong>fără întrebări</strong>.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Frumusețe (Collagen Joint+ Berry + Vita Complete+)
    // -------------------------------------------------------------------
    'pachet-frumusete' => [
        'excerpt' => 'Două produse care lucrează simultan: <strong>Collagen Joint+ Berry</strong> aduce materia primă cu 7,2 g peptide colagen tip 1+2+3 + acid hialuronic + MSM + glucozamină + condroitină, <strong>Vita Complete+</strong> aduce fundația vitaminică cu 25+ nutrienți, inclusiv Vit. C (cofactor obligatoriu pentru sinteza colagenului). Cură completă pentru <strong>50 de zile</strong>.',
        'pk_eyebrow' => 'PACHET FRUMUSEȚE · PIELE, PĂR & UNGHII',
        'pk_tagline' => 'Colagen tip 1+2+3, cofactori și protecție anti-aging.',
        'why' => [
            'kicker' => 'Cum lucrează împreună',
            'titlu' => 'Axa <em>cărămizi-ciment, ținută în echilibru.</em>',
            'prose' => [
                'Producția naturală de colagen scade cu <strong>1% pe an după 25 de ani</strong>. La 40 ani, fabrica ta lucrează la 70% capacitate. Suplimentele de colagen singure sunt jumătate de soluție — <strong>Vit. C este cofactor obligatoriu</strong> pentru sinteza colagenului (claim EFSA). Plus zinc, biotină, Vit. A, seleniu — toate cu claim EFSA pentru păr, unghii și piele normală.',
                'Pachetul Frumusețe combină cărămizile (peptide colagen) cu cimentul (cofactorii). Două niveluri, un singur ritual.',
            ],
            'cards' => [
                ['rol' => 'Cărămizile · Collagen Joint+ Berry', 'titlu' => '7,2 g colagen hidrolizat tip 1+2+3 + acid hialuronic + MSM + glucozamină + condroitină.', 'text' => 'Peptide colagen de la animale crescute pe pășune. Acid hialuronic 18 mg pentru hidratare dermică. Curcuma pentru micro-inflamația tăcută. Gust de fructe de pădure.'],
                ['rol' => 'Cimentul · Vita Complete+', 'titlu' => '25+ vitamine și minerale lichide + Green Blend & Amino Blend.', 'text' => 'Vit. C 250% (cofactor obligatoriu pentru sinteza colagenului), Biotina 400%, Zinc, Seleniu — toate cu claim EFSA pentru păr/unghii/piele. Plus Coenzima Q10 pentru fibroblastele care fabrică colagenul.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 50 de zile.</em>',
            'items' => [
                'Piele mai fermă, mai elastică, cu strălucire vizibilă — 7,2 g peptide tip 1+3 + acid hialuronic + Vit. C în 6–8 săptămâni',
                'Riduri fine atenuate, contur facial mai bine definit — sinteza de colagen susținută redă structura dermică',
                'Cearcăne reduse, privire mai vie — Q10 + Vit. C + Vit. E susțin microcirculația periorbitală',
                'Păr vizibil mai puternic, mai puține fire în pieptan — Biotina 400% + zinc + seleniu (claim EFSA păr) + peptide tip 1',
                'Unghii care nu se mai exfoliază în foi, cresc uniform — Biotina + zinc (claim EFSA unghii) + peptide colagen',
                'Hidratare a pielii ameliorată — acid hialuronic + peptide colagen mențin apa în matricea dermică',
                'Articulații mai mobile, mai puțină rigiditate matinală — peptide tip 2 + glucozamină + condroitină + MSM',
                'Genunchii nu mai „raportează" pe scări — curcuma 36 mg curcuminoide aduce dimensiunea anti-inflamatorie',
                'Energie celulară reală — Q10 + B-complex + magneziu repornesc mitocondriile, inclusiv din fibroblaste',
                'Reducerea oboselii (claim EFSA) — Vit. C, B-complex, magneziu',
                'Protecție celulară împotriva stresului oxidativ (claim EFSA) — Vit. C 250%, Vit. E naturală, seleniu',
                'Susținere pentru sistemul osos — Vit. D3 400% + K2 + calciu + colagen tip 1',
            ],
        ],
        'tl' => [
            'titlu' => 'Două produse, <em>două momente ale zilei.</em>',
            'steps' => [
                ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '1 shot Vita Complete+.', 'text' => '10 ml la trezire, cu 15–30 min înainte de mic dejun. Vit. C se absoarbe optim și activează sinteza de colagen pentru toată ziua. Gust de portocale.'],
                ['when' => 'La prânz sau seara', 'titlu' => '15 ml Collagen Joint+ Berry.', 'text' => '15 ml pur sau diluat cu apă, în timpul mesei sau după. Peptidele se absorb bine în prezența proteinelor alimentare. Gust de fructe de pădure.'],
                ['when' => 'Durată recomandată', 'titlu' => 'Minimum 3 luni pentru efect complet.', 'text' => 'Primele schimbări (păr, unghii) apar în 4–6 săptămâni. Pielea și articulațiile se îmbunătățesc în 6–12 săptămâni prin ciclul biologic complet.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Persoane peste 30 cu primele semne de îmbătrânire vizibilă (riduri fine, păr fragil)',
                'Femei în peri/post-menopauză cu scăderea sintezei de colagen',
                'Persoane cu disconfort articular, rigiditate matinală, genunchi sensibili',
                'Cei cu păr care cade abundent, unghii care se exfoliază în foi',
                'Stil de viață cu ecrane, stres, mese procesate care taie aportul de cofactori',
                'Cei care vor o soluție completă într-un singur ritual zilnic',
            ],
            'nu' => [
                'Minori sub 18 ani — consultă medicul',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Vegani strict — colagenul este de origine animală (pășune)',
                'Alergie la pește, crustacee sau ingredientele din formulă',
                'Cei cu boli autoimune severe — verifică cu medicul',
                'Cei sub tratament cu anticoagulante — curcuma poate influența coagularea',
            ],
        ],
        'faq' => [
            ['q' => 'Când încep să văd rezultate?', 'a' => 'Părul și unghiile în <strong>4–6 săptămâni</strong>. Pielea (fermitate, hidratare) în 6–8 săptămâni. Articulațiile în 8–12 săptămâni. Ciclul biologic complet de regenerare necesită 3 luni.'],
            ['q' => 'Colagenul este vegan?', 'a' => 'Nu. <strong>Collagen Joint+ Berry</strong> conține colagen hidrolizat de origine animală (de la animale crescute pe pășune). <strong>Vita Complete+</strong> este vegan.'],
            ['q' => 'De ce 7,2 g și nu mai mult?', 'a' => '<strong>7,2 g peptide hidrolizate</strong> este doza optimă susținută de studii pentru efect cumulativ pe piele și articulații. Mai mult colagen fără cofactori adecvați nu se transformă în colagen propriu.'],
            ['q' => 'Conține alergeni?', 'a' => 'Nu conține <strong>gluten, lactoză, soia sau OMG</strong>. Atenție dacă ești alergic la pește sau crustacee. Verifică eticheta integrală dacă ai alergii specifice.'],
            ['q' => 'Pot lua cu alte suplimente?', 'a' => 'Da, în majoritatea cazurilor. Dacă iei <strong>anticoagulante</strong>, verifică cu medicul (curcuma poate influența coagularea).'],
            ['q' => 'Garanția de 14 zile cum funcționează?', 'a' => 'Dacă după 14 zile produsul nu te convinge, primești banii înapoi pentru sticla deschisă. Returul se face din cont, <strong>fără întrebări</strong>.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Echilibru (Microflora+ + Collagen Joint+ Berry)
    // -------------------------------------------------------------------
    'pachet-echilibru' => [
        'excerpt' => 'Două produse care lucrează pe axa intestin-piele: <strong>Microflora+ Lemon Shots</strong> reechilibrează flora intestinală cu 10 miliarde UFC și 4 tulpini active, <strong>Collagen Joint+ Berry</strong> aduce 7,2 g peptide colagen tip 1+2+3 + acid hialuronic. O digestie sănătoasă = absorbție optimă a nutrienților = piele luminoasă de la rădăcină. Cură completă pentru <strong>33 de zile</strong>.',
        'pk_eyebrow' => 'PACHET ECHILIBRU · DIGESTIE & FRUMUSEȚE',
        'pk_tagline' => 'Probiotice, colagen și echilibru interior.',
        'why' => [
            'kicker' => 'Cum lucrează împreună',
            'titlu' => 'Axa <em>intestin-piele, ținută în echilibru.</em>',
            'prose' => [
                '<strong>70–80% din celulele imune</strong> se află în jurul intestinului. Floră intestinală dezechilibrată = absorbție slabă a nutrienților + inflamație de fond + ten obosit + păr fragil. Studiile moderne confirmă <strong>axa intestin-piele</strong>: ce nu absorbi corect în intestin, nu poate construi colagen în piele.',
                'Pachetul Echilibru lucrează pe ambele capete simultan: Microflora+ repune fundația intestinală, Collagen Joint+ Berry aduce cărămizile de regenerare. Două intervenții complementare.',
            ],
            'cards' => [
                ['rol' => 'Fundația · Microflora+ Lemon Shots', 'titlu' => '10 miliarde UFC — 4 tulpini microîncapsulate + Vit. C + L-glutamină.', 'text' => 'Probiotice microîncapsulate care ajung viabile în intestin: <strong>B. lactis, L. acidophilus, L. plantarum, L. salivarius</strong>. L-glutamina 50 mg susține integritatea peretelui intestinal. Gust de lămâie.'],
                ['rol' => 'Regenerarea · Collagen Joint+ Berry', 'titlu' => '7,2 g colagen hidrolizat tip 1+2+3 + acid hialuronic + MSM + glucozamină + condroitină.', 'text' => 'Peptide colagen <strong>tip 1+3 pentru piele, păr, unghii</strong>. Tip 2 + MSM + glucozamină + condroitină pentru articulații. Acid hialuronic 18 mg pentru hidratare dermică. Gust de fructe de pădure.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 33 de zile.</em>',
            'items' => [
                'Reechilibrarea florei intestinale după antibiotice sau dietă dezordonată',
                'Reducerea balonării și a gazelor după mese',
                'Tranzit normalizat — frecvență și consistență optimă',
                'Absorbție îmbunătățită a nutrienților din alimentație',
                'Susținerea integrității peretelui intestinal prin L-glutamină',
                'Imunitate întărită (70–80% din celulele imune sunt în jurul intestinului)',
                'Piele mai fermă și elastică — 7,2 g peptide tip 1+3 + acid hialuronic',
                'Păr vizibil mai puternic, mai puține fire în pieptan — peptide colagen + Vit. C',
                'Unghii care nu se mai exfoliază — peptide colagen + Vit. C cofactor',
                'Articulații mai mobile — peptide tip 2 + glucozamină + condroitină + MSM',
                'Hidratare a pielii ameliorată — acid hialuronic menține apa în derm',
                'Protecție celulară antioxidantă — Vit. C 145% cumulat + seleniu + curcuma',
            ],
        ],
        'tl' => [
            'titlu' => 'Două produse, <em>două momente ale zilei.</em>',
            'steps' => [
                ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '15 ml Microflora+ Lemon Shots.', 'text' => '15 ml la trezire, cu 15–30 min înainte de mic dejun. Probioticele microîncapsulate ajung viabile în intestin. Gust de lămâie.'],
                ['when' => 'La prânz sau seara', 'titlu' => '15 ml Collagen Joint+ Berry.', 'text' => '15 ml pur sau diluat cu apă, în timpul mesei. Peptidele se absorb optim în prezența proteinelor alimentare. Gust de fructe de pădure.'],
                ['when' => 'Durată recomandată', 'titlu' => 'Minimum 3 luni, ideal 33 zile pe cură.', 'text' => 'Flora intestinală se rebalansează în 4–6 săptămâni. Pielea și părul se îmbunătățesc în 6–12 săptămâni prin ciclul biologic complet.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Persoane cu balonare, gaze, tranzit haotic, intestin sensibil',
                'Post-antibiotice, când flora intestinală cere refacere',
                'Femei peste 30 cu scăderea sintezei de colagen și probleme digestive în paralel',
                'Stil de viață cu mese procesate, alcool ocazional, stres cronic',
                'Cei cu piele obosită și digestie greoaie simultan',
                'Femei în peri/post-menopauză cu schimbări hormonale și digestive',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Vegani strict — colagenul este de origine animală',
                'Alergie la pește, crustacee sau ingredientele din formulă',
                'Cei imunocompromiși sever — consultă medicul înainte de probiotice',
                'Cei sub tratament cu anticoagulante — curcuma poate influența coagularea',
            ],
        ],
        'faq' => [
            ['q' => 'Când încep să văd rezultate?', 'a' => 'Digestia se îmbunătățește în <strong>2–4 săptămâni</strong>. Părul și unghiile în 4–6 săptămâni. Pielea (fermitate, hidratare) în 6–8 săptămâni. Ciclul complet de regenerare necesită 3 luni.'],
            ['q' => 'Probioticele rezistă la aciditatea gastrică?', 'a' => 'Da. Folosim <strong>microîncapsulare</strong> pentru a proteja cele 10 miliarde UFC până ajung viabile în intestin, unde se multiplică și colonizează.'],
            ['q' => 'Pot lua amândouă în aceeași zi?', 'a' => 'Da. <strong>Microflora+</strong> dimineața pe stomac gol pentru absorbție optimă, <strong>Collagen Joint+ Berry</strong> la prânz sau seara cu o masă.'],
            ['q' => 'Conține alergeni?', 'a' => 'Nu conține <strong>gluten, lactoză, soia sau OMG</strong>. Atenție dacă ești alergic la pește sau crustacee (colagenul are origine animală).'],
            ['q' => 'Pot lua cu alte suplimente?', 'a' => 'Da, în majoritatea cazurilor. Dacă iei <strong>anticoagulante sau imunosupresoare</strong>, verifică cu medicul înainte.'],
            ['q' => 'Garanția de 14 zile cum funcționează?', 'a' => 'Dacă după 14 zile produsul nu te convinge, primești banii înapoi pentru sticla deschisă. Returul se face din cont, <strong>fără întrebări</strong>.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Energie (Vita Complete+ + D-Tox Ficat)
    // -------------------------------------------------------------------
    'pachet-energie' => [
        'excerpt' => 'Două produse care lucrează simultan pe două axe: <strong>Vita Complete+</strong> aduce combustibilul vitaminic cu 25+ nutrienți pentru energie zilnică, <strong>D-Tox Ficat</strong> susține filtrul hepatic care activează corect vitaminele liposolubile A, D, E, K. Cură completă pentru <strong>120 de zile</strong>.',
        'pk_eyebrow' => 'PACHET ENERGIE · REVITALIZARE & VITALITATE',
        'pk_tagline' => 'Energie celulară, detoxifiere, metabolism activ.',
        'why' => [
            'kicker' => 'Cum lucrează împreună',
            'titlu' => 'Axa <em>combustibil-filtru, ținută în echilibru.</em>',
            'prose' => [
                'Energia reală se construiește din două procese suprapuse: <strong>ce primește celula</strong> (micronutrienții) și <strong>ce procesează ficatul</strong> (activarea acelor micronutrienți). Mâncarea procesată, stresul cronic și solul agricol sărac au creat deficite subclinice de B12, magneziu, vitamina D la jumătate din populația adultă.',
                'În paralel, ficatul filtrează 1,5 litri de sânge pe minut — vitaminele liposolubile A, D, E, K se activează în ficat. Vita Complete+ aduce fundația, D-Tox Ficat repune filtrul în funcțiune.',
            ],
            'cards' => [
                ['rol' => 'Combustibilul · Vita Complete+', 'titlu' => '25+ vitamine și minerale lichide + Green Blend & Amino Blend.', 'text' => 'Vit. C 250%, D3 400%, B12 10667%, B-complex complet, Coenzima Q10 + magneziu. Combustibilul mitocondrial pentru energie zilnică și reducerea oboselii.'],
                ['rol' => 'Filtrul · D-Tox Ficat', 'titlu' => 'Armurariu 200 mg silimarină + Anghinare + Păpădie.', 'text' => 'Silimarina protejează celulele hepatice și susține regenerarea. Cinarina din anghinare stimulează producția de bilă și digestia grăsimilor. Inulina din păpădie susține flora intestinală și eliminarea naturală.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 120 de zile.</em>',
            'items' => [
                'Energie reală fără cafea obligatorie — B-complex + Q10 + magneziu repornesc mitocondriile',
                'Reducerea oboselii cu vitamine cu claim EFSA confirmat (C, B1, B2, B3, B5, B6, B12, magneziu)',
                'Trezire mai ușoară dimineața — echilibru B-complex + magneziu',
                'Metabolism energetic eficient (claim EFSA) — B-complex + crom + magneziu',
                'Filtrul hepatic repornit — silimarina susține regenerarea celulelor hepatice',
                'Activarea corectă a vitaminelor liposolubile A, D, E, K prin ficat funcțional',
                'Digestie ușoară a meselor grase — cinarina stimulează producția de bilă',
                'Ten mai luminos, fără ton gălbui — ficat sănătos se reflectă în calitatea tenului',
                'Halenă matinală mai curată — fără senzația „de mahmureală fără alcool"',
                'Somn mai stabil, fără treziri la 2–3 noaptea',
                'Sprijin în slăbit — ficat susținut + crom 333% + B-complex = metabolism eficient',
                'Protecție celulară împotriva stresului oxidativ (claim EFSA) — Vit. C, E naturală, seleniu + silimarină',
            ],
        ],
        'tl' => [
            'titlu' => 'Două produse, <em>două momente ale zilei.</em>',
            'steps' => [
                ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '1 shot Vita Complete+.', 'text' => '10 ml la trezire, cu 15–30 min înainte de mic dejun. Absorbția optimă a vitaminelor liposolubile prin formatul lichid. Gust de portocale.'],
                ['when' => 'La micul dejun', 'titlu' => '1 capsulă D-Tox Ficat.', 'text' => 'Cu apă, în timpul mesei. Silimarina se absoarbe mai bine în prezența grăsimilor alimentare.'],
                ['when' => 'Durată recomandată', 'titlu' => 'Minimum 3 luni, ideal 120 zile.', 'text' => 'Primele schimbări (energie, somn) apar în 2–4 săptămâni. Funcția hepatică se rebalansează treptat în 6–10 săptămâni.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Persoane cu oboseală persistentă, trezire grea, dependență de cafea',
                'Cei cu „lipsa de energie" la 14:00, prăbușire după prânz',
                'Persoane peste 30 ani cu deficite subclinice de B12, magneziu, vitamina D',
                'Cei care simt moleșeală după mese grase, halenă matinală, ten gălbui',
                'Stil de viață cu mese procesate, alcool ocazional, stres cronic',
                'Cei care „stau" pe cântar și bănuiesc că metabolismul e încetinit',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Persoane cu boli hepatice în tratament — verifică cu medicul',
                'Alergie la armurariu, anghinare, păpădie sau familia Asteraceae',
                'Cei cu obstrucție biliară sau pietre la fiere — verifică cu medicul',
                'Cei sub tratament cu medicamente metabolizate hepatic — verifică cu medicul',
            ],
        ],
        'faq' => [
            ['q' => 'Când încep să simt diferența?', 'a' => 'Energia se simte în <strong>1–2 săptămâni</strong>. Filtrul hepatic se rebalansează în 4–8 săptămâni. Tenul și halena matinală se îmbunătățesc în 6–10 săptămâni.'],
            ['q' => 'Pot lua amândouă produsele în aceeași zi?', 'a' => 'Da, sunt formulate să se completeze. <strong>Vita Complete+</strong> dimineața pe stomacul gol, <strong>D-Tox Ficat</strong> la micul dejun pentru absorbție optimă cu grăsimi alimentare.'],
            ['q' => 'Este sigur pentru utilizare pe termen lung?', 'a' => 'Da. Recomandăm cure de <strong>3 luni cu pauze de 2–4 săptămâni</strong> între. D-Tox Ficat poate fi luat continuu pentru susținere hepatică de fond.'],
            ['q' => 'Conține alergeni?', 'a' => 'Nu conține <strong>gluten, lactoză, soia sau OMG</strong>. Atenție dacă ești alergic la familia Asteraceae (anghinare, păpădie, armurariu).'],
            ['q' => 'Pot lua cu medicamente?', 'a' => '<strong>Silimarina</strong> influențează metabolismul hepatic al unor medicamente. Dacă iei tratament cronic, verifică cu medicul înainte.'],
            ['q' => 'Garanția de 14 zile cum funcționează?', 'a' => 'Dacă după 14 zile produsul nu te convinge, primești banii înapoi pentru sticla deschisă. Returul se face din cont, <strong>fără întrebări</strong>.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Complex Sănătate 40+ (Vita Complete+ + Microflora+ + Black Seed)
    // -------------------------------------------------------------------
    'pachet-complex-sanatate' => [
        'excerpt' => 'Trei produse care răspund schimbărilor biologice ce apar simultan după 40 de ani: <strong>Vita Complete+ Vegan Shots</strong> aduce 25 de nutrienți esențiali (vitamine, minerale, Q10) pentru mitocondrii care încetinesc; <strong>Microflora+ Lemon Shots</strong> reechilibrează flora intestinală erodată de ani de antibiotice și stres; <strong>Black Seed Elixir</strong> oferă scutul antioxidant cu timoquinonă și vitamina E naturală pentru uzura cumulată. Cură completă de <strong>50 de zile</strong>.',
        'pk_eyebrow' => 'PACHET COMPLEX SĂNĂTATE 40+ · VITALITATE COMPLETĂ',
        'pk_tagline' => 'Fundația vitală completă, pentru momentul biologic după 40.',
        'why' => [
            'kicker' => 'Proiectat pentru momentul biologic după 40',
            'titlu' => 'Fundația, flora și scutul — <em>într-o singură rutină.</em>',
            'prose' => [
                'După 40, schimbările biologice apar simultan, nu izolat: <strong>Coenzima Q10 scade</strong>, deficitele de B12, D și magneziu devin frecvente, sinteza de colagen scade, microbiomul pierde diversitate, stresul oxidativ se cumulează.',
                'Cele trei produse ating cele trei nivele necesare: <strong>ce primește celula</strong> (Vita Complete+), <strong>cum absorbi</strong> (Microflora+) și <strong>cum te protejezi</strong> de uzura cumulată (Black Seed Elixir). Trei verigi, o singură rutină zilnică.',
            ],
            'cards' => [
                ['rol' => '1. Fundația · Vita Complete+', 'titlu' => '25+ vitamine și minerale lichide + Coenzima Q10.', 'text' => 'B-complex complet, Vit. C 250%, D3 400%, K2, plus magneziu, zinc, fier, iod, seleniu. <strong>Coenzima Q10</strong> esențială pentru mitocondriile care încetinesc după 30. Format lichid pentru absorbție superioară.'],
                ['rol' => '2. Flora · Microflora+ Lemon Shots', 'titlu' => '10 miliarde UFC — 4 tulpini microîncapsulate + Vit. C + L-glutamină.', 'text' => 'L. acidophilus, L. rhamnosus, B. lactis, B. longum — reconstruiesc flora erodată de ani de antibiotice, stres și dietă procesată. L-glutamină 50 mg pentru peretele intestinal. Gust de lămâie.'],
                ['rol' => '3. Scutul · Black Seed Elixir', 'titlu' => 'Ulei chimen negru egiptean 1000 mg + Vit. E naturală.', 'text' => 'Presat la rece, bogat în <strong>timoquinonă</strong> — compus studiat pentru rolul antiinflamator și antioxidant pe uzura cumulată. Vit. E 67% VNR pentru protecție celulară.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 50 de zile.</em>',
            'items' => [
                'Energie susținută de la mitocondrii — Q10 + B-complex + magneziu',
                'Sistem imunitar mai puternic pe termen lung — Vit. C, D3, zinc',
                'Reducerea oboselii cronice „fără cauză" (claim EFSA) — B-complex, fier, magneziu',
                'Floră intestinală reechilibrată — 10 miliarde UFC, 4 tulpini active',
                'Absorbția vitaminelor și mineralelor crescută',
                'Inflamație cronică de fond redusă — timoquinonă + Vit. E + omega 6/9',
                'Protecție antioxidantă completă (claim EFSA) — Vit. C, E naturală, seleniu, zinc',
                'Sănătatea cardiovasculară susținută — acizi grași nesaturați + Q10',
                'Compensarea deficitelor subclinice de B12, D, magneziu (frecvente la 60–80% adulți 40+)',
                'Metabolism energetic mai bun — mai ușor în dat jos kilograme',
                'Recuperare mai rapidă după stres sau efort — magneziu, B6, Q10',
                'Bază solidă de prevenție pentru momentul biologic 40+',
            ],
        ],
        'tl' => [
            'titlu' => 'Trei produse, <em>ritmul zilei tale.</em>',
            'steps' => [
                ['when' => 'Dimineața sau după micul dejun', 'titlu' => '1 doză Vita Complete+ (10 ml).', 'text' => 'Cele 25 de vitamine și minerale + Q10 se absorb optim dimineața. Gust de portocale.'],
                ['when' => 'Dimineața, pe stomac gol — luna 1', 'titlu' => '1 doză Microflora+ (15 ml).', 'text' => 'La trezire, cu 15–30 min înainte de mic dejun. Cură intensivă în prima lună pentru reechilibrare rapidă. Gust de lămâie.'],
                ['when' => 'Dimineața și seara, cu masă', 'titlu' => '2 capsule Black Seed Elixir.', 'text' => 'O capsulă dimineața și una seara, cu apă, în timpul mesei. Uleiul de chimen negru se absoarbe optim cu lipide alimentare.'],
                ['when' => 'Durată recomandată', 'titlu' => '50 zile, ideal minim 3 luni.', 'text' => 'Energie în 2–3 săptămâni. Imunitate în 4–6 săptămâni. Schimbări profunde (oboseală, inflamație, recuperare) în 8–12 săptămâni.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Bărbați și femei 40+ care simt simultan oboseală, imunitate slabă, recuperare lentă',
                'Oboseală cronică fără cauză medicală, prăbușire după-amiaza',
                'Deficite subclinice de B12, D, magneziu — frecvente la 60–80% adulți 40+',
                'Imunitate slabă cu anii, infecții frecvente sezoniere',
                'Slăbit greoi după 40, metabolism încetinit',
                'Sensibilitate la mese grase, treziri nocturne, ceață mentală ușoară',
            ],
            'nu' => [
                'Copii și tineri sub 30 — suficient cu o alimentație echilibrată',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Alergie la chimen negru sau ingrediente specifice',
                'Condiții medicale severe diagnosticate — consultă medicul',
                'Medicație cronică (anticoagulante, hepatice, hormonale) — verifică cu medicul',
                'Cei imunocompromiși sever — consultă medicul înainte de probiotice',
            ],
        ],
        'faq' => [
            ['q' => 'De ce specific pentru 40+?', 'a' => 'După 40, schimbările biologice apar <strong>simultan</strong>, nu izolat: Q10 scade, B12/D scad, microbiomul pierde diversitate, stresul oxidativ se cumulează. Pachetul atinge toate trei axele în același timp.'],
            ['q' => 'Cât durează până văd rezultate?', 'a' => 'Energia în <strong>2–3 săptămâni</strong>. Imunitatea în 4–6 săptămâni. Schimbări profunde (oboseală cronică, inflamație de fond, recuperare) în 8–12 săptămâni.'],
            ['q' => 'Pot lua toate trei în aceeași zi?', 'a' => 'Da. Sunt formulate să se completeze: <strong>Vita Complete+</strong> dimineața, <strong>Microflora+</strong> pe stomac gol (luna 1 intensiv), <strong>Black Seed Elixir</strong> dimineața și seara cu mese.'],
            ['q' => 'Black Seed ține 120 zile, restul 50 zile — cum continui?', 'a' => 'Recomandare: cură repetată <strong>2–3 ori pe an</strong> sau ca abonament cu prețul redus. Black Seed Elixir poate continua singur pentru susținerea de fond.'],
            ['q' => 'Sunt vegane?', 'a' => 'Da. <strong>Microflora+</strong> și <strong>Vita Complete+</strong> sunt vegane. <strong>Black Seed Elixir</strong> conține ulei vegetal de chimen negru — vegan.'],
            ['q' => 'Cum opresc abonamentul?', 'a' => 'Din contul tău, oricând, <strong>fără întrebări</strong>. Sau primești banii înapoi în 14 zile pentru produsele desfăcute, dacă nu te conving.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Detox Plus (D-Tox Ficat + Microflora+ + Black Seed)
    // -------------------------------------------------------------------
    'pachet-detox-plus' => [
        'excerpt' => 'Trei produse care acționează simultan pe cele trei axe ale detoxifierii: <strong>D-Tox Ficat</strong> susține filtrul hepatic cu armurariu (200 mg silimarină/doză), anghinare și păpădie; <strong>Microflora+ Lemon Shots</strong> reechilibrează flora intestinală cu 10 miliarde UFC; <strong>Black Seed Elixir</strong> aduce protecția antioxidantă cu ulei de chimen negru egiptean și vitamina E naturală. Cură completă de <strong>120 de zile</strong>.',
        'pk_eyebrow' => 'PACHET DETOX PLUS · CURĂȚARE PROFUNDĂ',
        'pk_tagline' => 'Ficat, intestin și scut antioxidant, într-o singură cură.',
        'why' => [
            'kicker' => 'De ce aceste trei produse',
            'titlu' => 'Ficat, intestin și scut antioxidant — <em>într-o singură cură.</em>',
            'prose' => [
                'Lumea modernă ne încarcă zilnic cu microplastice, pesticide, metale grele, aditivi, alcool ocazional și medicamente. Cele trei produse ating cele trei sisteme cheie care fac diferența: <strong>ficatul</strong> (filtrul tăcut), <strong>flora intestinală</strong> (prima barieră) și <strong>apărarea antioxidantă</strong>.',
                'Fără ficat funcțional, toxinele recirculează; fără floră echilibrată, peretele intestinal devine permeabil; fără antioxidanți, stresul oxidativ erodează celulele. Trei verigi, o singură cură lungă.',
            ],
            'cards' => [
                ['rol' => '1. Filtrul · D-Tox Ficat', 'titlu' => 'Armurariu 200 mg silimarină + anghinare + păpădie.', 'text' => 'Silimarina protejează și regenerează celulele hepatice. Cinarina din anghinare stimulează producția de bilă. Păpădia susține eliminarea naturală. Format în capsulă vegetală.'],
                ['rol' => '2. Bariera · Microflora+ Lemon Shots', 'titlu' => '10 miliarde UFC — 4 tulpini microîncapsulate + Vit. C + L-glutamină.', 'text' => 'L. acidophilus, L. rhamnosus, B. lactis, B. longum — ajung viabile în intestin. Vit. C 80 mg + L-glutamină 50 mg pentru integritatea peretelui intestinal. Gust de lămâie.'],
                ['rol' => '3. Scutul · Black Seed Elixir', 'titlu' => 'Ulei chimen negru egiptean 1000 mg + Vit. E naturală.', 'text' => 'Presat la rece, bogat în <strong>timoquinonă</strong> — compus studiat în peste 800 de studii pentru rolul antiinflamator și antioxidant. Vitamina E 67% VNR pentru protecție celulară.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 120 de zile.</em>',
            'items' => [
                'Detoxifiere profundă hepatică — silimarina protejează și regenerează celulele ficatului',
                'Floră intestinală reechilibrată după antibiotice și stres',
                'Digestie îmbunătățită, mai puțin disconfort și balonare',
                'Tranzit intestinal normalizat — frecvență și consistență optimă',
                'Absorbția vitaminelor și mineralelor crescută',
                'Inflamație cronică de fond redusă prin timoquinonă + Vit. E + omega 6/9',
                'Protecție antioxidantă puternică (claim EFSA) — Vit. E naturală + timoquinonă',
                'Sistem imunitar mai puternic — intestin sănătos = imunitate bună',
                'Eliminarea naturală a toxinelor acumulate prin bilă și tranzit',
                'Energie naturală refăcută — ficat curat = mai multă energie reală',
                'Echilibru metabolic restabilit — ficat funcțional reglează grăsimile din sânge',
                'Resetare completă după perioade dificile (stres, sărbători, exces)',
            ],
        ],
        'tl' => [
            'titlu' => 'Trei produse, <em>ritmul zilei tale.</em>',
            'steps' => [
                ['when' => 'Dimineața și seara', 'titlu' => '2 capsule D-Tox Ficat.', 'text' => 'O capsulă dimineața și una seara, înainte de masă. Silimarina se absoarbe mai bine în prezența grăsimilor alimentare ale mesei următoare.'],
                ['when' => 'Dimineața, pe stomac gol — luna 1', 'titlu' => '1 doză Microflora+ (15 ml).', 'text' => 'La trezire, cu 15–30 min înainte de mic dejun. Cură intensivă în prima lună pentru reechilibrare rapidă. Gust de lămâie.'],
                ['when' => 'Dimineața și seara, cu masă', 'titlu' => '2 capsule Black Seed Elixir.', 'text' => 'O capsulă dimineața și una seara, cu apă, în timpul mesei. Uleiul de chimen negru se absoarbe optim cu lipide alimentare.'],
                ['when' => 'Durată recomandată', 'titlu' => '120 zile pentru detoxifiere profundă.', 'text' => 'Digestie în 1–2 săptămâni. Energie în 4–6 săptămâni. Detoxifiere profundă și rezultate durabile în 8–12 săptămâni.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Balonare cronică, tranzit haotic, oboseală fără cauză clară',
                'Ten gălbui, treziri la 2–3 noaptea, halenă matinală',
                'Sensibilitate la cofeină, medicamente sau alcool',
                'Post-antibiotice, când flora intestinală cere refacere',
                'Post-sărbători, după perioade de exces alimentar',
                'Stil de viață cu stres cronic, mese procesate, alcool ocazional',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Boli hepatice severe diagnosticate — consultă medicul',
                'Alergie la chimen negru sau familia Asteraceae (armurariu, anghinare, păpădie)',
                'Medicație cronică — verifică cu medicul pentru interacțiuni hepatice',
                'Cei imunocompromiși sever — consultă medicul înainte de probiotice',
            ],
        ],
        'faq' => [
            ['q' => 'Pot lua toate trei în aceeași zi?', 'a' => 'Da. Sunt formulate să se completeze: <strong>D-Tox Ficat</strong> dimineața și seara înainte de mese, <strong>Microflora+</strong> dimineața pe stomac gol în luna 1, <strong>Black Seed Elixir</strong> dimineața și seara cu mese.'],
            ['q' => 'Cât durează până văd rezultate?', 'a' => 'Digestia se îmbunătățește în <strong>1–2 săptămâni</strong>. Energia și tenul în 4–6 săptămâni. Detoxifierea profundă (filtru hepatic + scut antioxidant) în 8–12 săptămâni.'],
            ['q' => 'Microflora+ ține 33 zile, restul 120 zile — cum continui?', 'a' => 'Recomandare: <strong>o cutie de Microflora+ pe lună</strong> pentru cură intensivă în toate cele 4 luni, sau cure intermitente (lunile 1, 3) dacă simptomele digestive sunt deja ușoare.'],
            ['q' => 'Se pot lua împreună cu medicamente?', 'a' => 'Lasă <strong>2 ore</strong> între supliment și medicament. Pentru medicație cronică (anticoagulante, imunosupresoare, medicamente metabolizate hepatic), verifică cu medicul.'],
            ['q' => 'Sunt sigure pentru ficat?', 'a' => 'Da. <strong>Armurariul</strong> este folosit tradițional pentru regenerarea hepatică — susține ficatul, nu îl suprasolicită. Produsele sunt formulate în doze sigure pentru utilizare pe termen lung.'],
            ['q' => 'Cum opresc abonamentul?', 'a' => 'Din contul tău, oricând, <strong>fără întrebări</strong>. Sau primești banii înapoi în 14 zile pentru produsele desfăcute, dacă nu te conving.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Vitalitate (Microflora+ + Vita Complete+ + Collagen Joint+)
    // -------------------------------------------------------------------
    'pachet-vitalitate' => [
        'excerpt' => 'Trei produse care acoperă axa digestie-absorbție-regenerare: <strong>Microflora+ Lemon Shots</strong> reechilibrează flora intestinală cu 10 miliarde UFC, <strong>Vita Complete+ Vegan Shots</strong> aduce 25 de nutrienți esențiali, iar <strong>Collagen Joint+ Berry</strong> susține articulațiile și pielea cu 7,2 g peptide colagen tip 1+2+3. Cură completă de <strong>33 de zile</strong>.',
        'pk_eyebrow' => 'PACHET VITALITATE · ENERGIE & REGENERARE',
        'pk_tagline' => 'Colagen, vitamine și probiotice care lucrează împreună.',
        'why' => [
            'kicker' => 'De ce aceste trei produse',
            'titlu' => 'Digestie, absorbție, regenerare — <em>într-un singur ritual.</em>',
            'prose' => [
                'Cele trei produse acoperă lanțul complet: <strong>Microflora+</strong> pregătește intestinul — acolo se absorb nutrienții. <strong>Vita Complete+</strong> furnizează cei 25 de nutrienți esențiali deficitari în dieta modernă (Vit. D3, B12, magneziu, zinc, fier). <strong>Collagen Joint+ Berry</strong> aduce materia primă pentru articulații, piele și țesut conjunctiv.',
                'Fără floră echilibrată nu absorbi corect; fără nutrienți nu fabrici colagen; fără cărămizi nu regenerezi. Trei verigi, un singur ritual zilnic.',
            ],
            'cards' => [
                ['rol' => '1. Digestia · Microflora+ Lemon Shots', 'titlu' => '10 miliarde UFC — 4 tulpini microîncapsulate + Vit. C + L-glutamină.', 'text' => 'L. acidophilus, L. rhamnosus, B. lactis, B. longum — ajung viabile în intestin și colonizează. Vit. C 80 mg (100% VNR) + L-glutamină 50 mg pentru integritatea peretelui intestinal. Gust de lămâie.'],
                ['rol' => '2. Absorbția · Vita Complete+', 'titlu' => '25+ vitamine și minerale lichide — Vit. D3 400%, B12, Vit. C 250%.', 'text' => 'B-complex complet (B1, B2, B3, B5, B6, B7, B9, B12), Vit. A, D3, E, K2, plus magneziu, zinc, fier, iod, seleniu — acoperă deficitele subclinice tipice ale dietei procesate. Format lichid pentru absorbție superioară.'],
                ['rol' => '3. Regenerarea · Collagen Joint+ Berry', 'titlu' => '7,2 g colagen tip 1+2+3 + acid hialuronic + MSM + glucozamină + condroitină.', 'text' => 'Peptide colagen pentru piele, păr, unghii și articulații. Acid hialuronic 18 mg pentru hidratare dermică. Curcuma pentru micro-inflamația tăcută. Gust de fructe de pădure.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 33 de zile.</em>',
            'items' => [
                'Energie susținută prin B-complex complet (B1–B12, claim EFSA reducerea oboselii)',
                'Sistem imunitar întărit — Vit. C 350% cumulat + D3 400% + zinc',
                'Floră intestinală echilibrată — 10 miliarde UFC, 4 tulpini active',
                'Absorbție optimizată a nutrienților din mese',
                'Articulații flexibile și confortabile — colagen tip 2 + glucozamină + condroitină + MSM',
                'Piele fermă și hidratată — peptide tip 1+3 + acid hialuronic 18 mg',
                'Păr mai puternic, unghii mai rezistente — biotină, zinc, peptide colagen',
                'Recuperare mai rapidă după efort — magneziu, B6, peptide colagen, MSM',
                'Oase mai puternice — Vit. D3 400% + K2 100% + magneziu + calciu',
                'Echilibru hormonal — B6, B12, magneziu, iod cu rol confirmat EFSA',
                'Reducerea oboselii cronice (claim EFSA) — Vit. C, B-complex, magneziu, fier',
                'Susținere completă pentru femei active 30+ — trei produse, un singur ritual',
            ],
        ],
        'tl' => [
            'titlu' => 'Trei produse, <em>ritmul zilei tale.</em>',
            'steps' => [
                ['when' => 'Dimineața, pe stomac gol', 'titlu' => '1 doză Microflora+ (15 ml).', 'text' => 'La trezire, cu 15–30 min înainte de mic dejun. Probioticele microîncapsulate ajung viabile în intestin. Gust de lămâie.'],
                ['when' => 'La prânz, după masă', 'titlu' => '1 doză Vita Complete+ (10 ml).', 'text' => 'Cele 25 de vitamine și minerale se absorb optim în prezența alimentelor. Gust de portocale.'],
                ['when' => 'Seara, cu sau după cină', 'titlu' => '1 doză Collagen Joint+ (15 ml).', 'text' => 'Peptidele colagen se absorb optim în prezența proteinelor alimentare. Gust de fructe de pădure.'],
                ['when' => 'Durată recomandată', 'titlu' => '33 zile complete pe cură.', 'text' => 'Energie în 1–2 săptămâni. Articulații în 4–6 săptămâni. Piele și păr în 6–8 săptămâni. Recomandat: minim 3 luni.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Femei active 30+ care vor un protocol complet într-un singur ritual',
                'Persoane cu oboseală cronică, dependență de cafea, lipsă de energie',
                'Deficite subclinice de vitamine B, D, magneziu, zinc, fier',
                'Articulații sensibile, rigiditate matinală, recuperare lentă după efort',
                'Piele obosită, păr fragil, unghii care se exfoliază',
                'Stil de viață cu stres, mese procesate, alcool ocazional',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Vegani strict — Collagen Joint+ Berry are origine animală',
                'Alergie la oricare dintre ingrediente — verifică eticheta integrală',
                'Cei imunocompromiși sever — consultă medicul înainte de probiotice',
                'Cei sub tratament cu anticoagulante — verifică cu medicul (curcuma, Vit. K2)',
            ],
        ],
        'faq' => [
            ['q' => 'Pot lua toate trei în aceeași zi?', 'a' => 'Da. Sunt formulate pentru a se completa: <strong>Microflora+</strong> dimineața pe stomac gol, <strong>Vita Complete+</strong> la prânz după masă, <strong>Collagen Joint+</strong> seara cu cina.'],
            ['q' => 'Cât durează până văd rezultate?', 'a' => 'Energie în <strong>1–2 săptămâni</strong>. Articulații (flexibilitate, mai puțină rigiditate matinală) în 4–6 săptămâni. Piele și păr în 6–8 săptămâni.'],
            ['q' => 'Pot continua după cele 33 zile?', 'a' => 'Da — recomandăm <strong>minim 3 luni</strong> pentru regenerare profundă. La abonament cu livrare automată la 30 de zile, prețul scade cu încă 5%.'],
            ['q' => 'Se pot lua împreună cu alte suplimente?', 'a' => 'Verifică suprapunerile pe vitamine și minerale ca să nu depășești VNR. Dacă iei medicamente cronice (anticoagulante, imunosupresoare), verifică cu medicul.'],
            ['q' => 'Sunt vegane?', 'a' => '<strong>Microflora+</strong> și <strong>Vita Complete+</strong> sunt vegane. <strong>Collagen Joint+ Berry</strong> conține peptide de origine animală (animale crescute pe pășune).'],
            ['q' => 'Cum opresc abonamentul?', 'a' => 'Din contul tău, oricând, <strong>fără întrebări</strong>. Sau primești banii înapoi în 14 zile pentru produsele desfăcute, dacă nu te conving.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Confort Digestiv (Microflora+ + D-Tox Ficat)
    // -------------------------------------------------------------------
    'pachet-confort-digestiv' => [
        'excerpt' => 'Două produse care lucrează împreună pe axa intestin-ficat: <strong>Microflora+ Lemon Shots</strong> reechilibrează flora, <strong>D-Tox Ficat</strong> susține detoxifierea naturală. Cură completă pentru <strong>120 de zile</strong>.',
        'pk_eyebrow' => 'PACHET DIGESTIE · intestin-ficat',
        'pk_tagline' => 'Digestie ușoară, detoxifiere & echilibru intern.',
        'why' => [
            'kicker' => 'Cum lucrează pachetul',
            'titlu' => 'Axa <em>intestin-ficat</em>, ținută în echilibru.',
            'prose' => [
                'Intestinul și ficatul comunică zilnic prin sistemul porto-hepatic. Tot ce absorbi în intestin trece prin ficat înainte de a ajunge în circulație. Dacă <strong>flora intestinală e dezechilibrată</strong>, ficatul are mai mult de filtrat. Dacă <strong>ficatul e obosit</strong>, digestia suferă indirect.',
                'Pachetul Confort Digestiv lucrează simultan pe ambele capete: probiotice viabile pentru intestin, plante hepatoprotectoare pentru ficat. Două intervenții, o singură axă.',
            ],
            'cards' => [
                ['rol' => 'Intestinul · Microflora+', 'titlu' => 'Probiotice microîncapsulate, 10 mld UFC.', 'text' => '4 tulpini active (B. lactis, L. acidophilus, L. plantarum, L. salivarius) + L-glutamină + Vit. C. Refacerea florei, reducerea balonării, susținerea peretelui intestinal.'],
                ['rol' => 'Ficatul · D-Tox Ficat', 'titlu' => 'Armurariu 200 mg silimarină, anghinare, păpădie.', 'text' => 'Hepatoprotecție prin silimarină standardizată. Stimulare bilă prin cinarină din anghinare. Detox natural prin inulină din păpădie.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 120 de zile.</em>',
            'items' => [
                'Reechilibrarea florei intestinale după antibiotice sau dietă dezordonată',
                'Reducerea balonării și a gazelor după mese',
                'Normalizarea ritmului tranzitului intestinal',
                'Susținerea integrității peretelui intestinal prin L-glutamină',
                'Detox hepatic natural prin silimarina standardizată',
                'Regenerare hepatocite și protecție împotriva stresului oxidativ',
                'Digestia mai ușoară a grăsimilor prin cinarină (anghinare)',
                'Reducerea poftei de zahăr odată cu echilibrarea florei',
                'Absorbție mai bună a nutrienților din alimentație',
                'Susținere imunitară (70–80% din celulele imune sunt în intestin)',
                'Recuperare mai rapidă post-antibiotice sau post-tratamente',
                'Ușurarea semnelor de ficat obosit (greutate după mese grele)',
            ],
        ],
        'tl' => [
            'titlu' => 'Două produse, <em>două momente ale zilei.</em>',
            'steps' => [
                ['when' => 'Dimineața, pe stomacul gol', 'titlu' => '1 shot Microflora+ Lemon.', 'text' => '15 ml la trezire, cu 15–30 min înainte de mic dejun. Probioticele ajung viabile în intestin prin microîncapsulare.'],
                ['when' => 'La micul dejun', 'titlu' => '1 capsulă D-Tox Ficat.', 'text' => 'Cu apă, în timpul mesei sau imediat după. Silimarina se absoarbe mai bine în prezența lipidelor alimentare.'],
                ['when' => 'Durată recomandată', 'titlu' => 'Minimum 3 luni, ideal 120 zile.', 'text' => 'Schimbările pe axa intestin-ficat se construiesc treptat. Diferențele se simt clar după 4–6 săptămâni de utilizare consecventă.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Persoane cu balonare sau disconfort digestiv recurent',
                'Post-antibiotice, când flora intestinală cere refacere',
                'Stil de viață cu mese grele, alcool ocazional, perioade de stres',
                'Persoane care vor o cură sezonieră de primăvară sau toamnă',
                'Cei care simt greutate hepatică după mese bogate',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Persoane imunocompromise sever — consultă medicul',
                'Alergie la lămâie, la armurariu sau familia Asteraceae',
                'Cei sub tratament cronic metabolizat hepatic — verifică cu medicul',
            ],
        ],
        'faq' => [
            ['q' => 'Pot lua și alte suplimente în același timp?', 'a' => 'Da. Pachetul se combină ușor cu <strong>vitamine, omega-3 sau colagen</strong>. Dacă iei deja un probiotic sau un alt produs cu silimarină, evită dublarea. Pentru tratamente prescrise, consultă medicul.'],
            ['q' => 'Cât timp țin cura?', 'a' => 'Minimum 3 luni, ideal <strong>120 de zile</strong>. D-Tox Ficat acoperă toată perioada; pentru Microflora+ (33 de doze) recomandăm o sticlă pe lună sau cure intermitente.'],
            ['q' => 'Pot lua în sarcină sau alăptare?', 'a' => '<strong>Nu fără avizul medicului.</strong> Femeile însărcinate sau care alăptează trebuie să consulte medicul înainte de orice supliment, inclusiv plante hepatoprotectoare.'],
            ['q' => 'Ce fac dacă unul dintre produse nu mi se potrivește?', 'a' => 'Ne scrii și găsim soluția: poți <strong>returna produsul în 14 zile</strong>, chiar deschis, și primești banii înapoi. Restul curei continuă cu produsul care ți se potrivește.'],
            ['q' => 'Garanția de 14 zile cum funcționează?', 'a' => 'Dacă după 14 zile produsul nu te convinge, primești banii înapoi pentru sticla deschisă. Returul se face din cont, <strong>fără întrebări</strong>.'],
        ],
    ],

    // -------------------------------------------------------------------
    // Pachet Regenerare Celulară (Collagen Joint+ + Vita Complete+ + Black Seed)
    // -------------------------------------------------------------------
    'pachet-regenerare-celulara' => [
        'excerpt' => 'Trei produse care acționează ca un circuit complet de anti-aging funcțional: <strong>Collagen Joint+ Berry</strong> aduce 7,2 g peptide colagen tip 1+2+3 pentru piele, păr, unghii și articulații; <strong>Vita Complete+ Vegan Shots</strong> furnizează fundația de 25 de nutrienți activi (vitamine, minerale, Q10) pentru energia celulară; <strong>Black Seed Elixir</strong> oferă protecția antioxidantă cu timoquinonă și vitamina E naturală. Cură intensivă de <strong>33 de zile</strong>.',
        'pk_eyebrow' => 'PACHET REGENERARE CELULARĂ · ANTI-AGING FUNCȚIONAL',
        'pk_tagline' => 'Colagen, multivitamine și scut antioxidant, pentru vitalitate din interior.',
        'why' => [
            'kicker' => 'Anti-aging funcțional, nu doar estetic',
            'titlu' => 'Fundația, reconstrucția și scutul — <em>într-un singur ritm.</em>',
            'prose' => [
                'După 25–30 ani, sinteza naturală de colagen încetinește: <strong>la 40 ani lucrezi la 70%</strong>, la 50 ani la 50%. În paralel, Q10 scade, deficitele se acumulează, iar stresul oxidativ macină celulele.',
                'Cele trei produse ating cele trei nivele: <strong>Vita Complete+</strong> aduce fundația de 25 nutrienți + Q10, <strong>Black Seed Elixir</strong> aduce protecția antioxidantă cu timoquinonă, <strong>Collagen Joint+ Berry</strong> aduce reconstrucția concretă cu 7,2 g peptide colagen + cofactori. Anti-aging funcțional.',
            ],
            'cards' => [
                ['rol' => '1. Fundația · Vita Complete+', 'titlu' => '25+ vitamine și minerale lichide + Coenzima Q10.', 'text' => 'B-complex complet, Vit. C 250%, D3 400%, K2, plus magneziu, zinc, fier, iod, seleniu. <strong>Coenzima Q10</strong> pentru energia celulară și susținerea sintezei naturale de colagen. Format lichid pentru absorbție superioară.'],
                ['rol' => '2. Scutul · Black Seed Elixir', 'titlu' => 'Ulei chimen negru egiptean 1000 mg + Vit. E naturală.', 'text' => 'Presat la rece, bogat în <strong>timoquinonă</strong> — compus studiat pentru rolul antiinflamator și antioxidant. Vit. E 67% VNR + omega 6 și 9 pentru protecție celulară și încetinirea îmbătrânirii biologice.'],
                ['rol' => '3. Reconstrucția · Collagen Joint+ Berry', 'titlu' => '7,2 g colagen tip 1+2+3 + acid hialuronic + MSM + glucozamină + condroitină.', 'text' => 'Peptide colagen pentru piele, păr, unghii și articulații. Acid hialuronic 18 mg pentru hidratare dermică. Curcuma pentru micro-inflamația tăcută. Gust de fructe de pădure.'],
            ],
        ],
        'benefits' => [
            'titlu' => 'Ce se schimbă <em>în 33 de zile.</em>',
            'items' => [
                'Piele mai fermă și mai hidratată — colagen tip 1+3 + Vit. C + acid hialuronic',
                'Riduri fine diminuate în 6–8 săptămâni',
                'Păr mai puternic, mai des, mai strălucitor — biotină + zinc + colagen',
                'Unghii mai rezistente, fără exfolieri — biotină + colagen + Vit. C',
                'Articulații flexibile — colagen tip 2 + MSM + glucozamină + condroitină + acid hialuronic + curcuma',
                'Energie celulară crescută — Q10 + B-complex + magneziu',
                'Reducerea oboselii cronice (claim EFSA) — vitamine cu rol confirmat',
                'Inflamație cronică de fond redusă — timoquinonă + curcuma + Vit. E',
                'Protecție antioxidantă completă (claim EFSA) — Vit. C, E, seleniu, zinc, timoquinonă',
                'Compensarea deficitelor subclinice de D, B12, biotină, zinc, magneziu',
                'Susținerea sintezei naturale de colagen prin Vit. C — cofactor obligatoriu',
                'Recuperare mai rapidă după efort fizic sau stres',
            ],
        ],
        'tl' => [
            'titlu' => 'Trei produse, <em>ritmul zilei tale.</em>',
            'steps' => [
                ['when' => 'Dimineața sau seara', 'titlu' => '1 doză Collagen Joint+ Berry (15 ml).', 'text' => '15 ml pur sau diluat cu apă, în timpul mesei. Peptidele se absorb optim în prezența proteinelor alimentare. Gust de fructe de pădure.'],
                ['when' => 'Dimineața sau după micul dejun', 'titlu' => '1 doză Vita Complete+ (10 ml).', 'text' => 'Cele 25 de vitamine și minerale + Q10 se absorb optim dimineața. Gust de portocale.'],
                ['when' => 'Dimineața și seara, cu masă', 'titlu' => '2 capsule Black Seed Elixir.', 'text' => 'O capsulă dimineața și una seara, cu apă, în timpul mesei. Uleiul de chimen negru se absoarbe optim cu lipide alimentare.'],
                ['when' => 'Durată recomandată', 'titlu' => '33 zile intensiv, ideal minim 3 luni.', 'text' => 'Energie în 2–3 săptămâni. Articulații în 4–6 săptămâni. Piele în 6–8 săptămâni. Păr și unghii în 8–12 săptămâni.'],
            ],
        ],
        'pcine' => [
            'da' => [
                'Bărbați și femei 30+ cu primele semne de îmbătrânire vizibilă sau internă',
                'Piele lăsată, riduri fine, ten obosit, cearcăne persistente',
                'Păr fragil sau care cade, unghii care se rup ușor',
                'Dureri articulare, rigiditate matinală, recuperare lentă după sport',
                'Oboseală cronică, energie scăzută, dependență de cafea',
                'Stil de viață cu stres, mese procesate, ecrane multe ore zilnic',
            ],
            'nu' => [
                'Minori sub 12 ani — consultă medicul pediatru',
                'Femei însărcinate sau care alăptează — consultă medicul',
                'Vegani strict — Collagen Joint+ Berry are origine animală',
                'Alergie la pește, crustacee sau chimen negru — verifică eticheta integrală',
                'Condiții medicale severe diagnosticate — consultă medicul',
                'Cei sub tratament cu anticoagulante — curcuma poate influența coagularea',
            ],
        ],
        'faq' => [
            ['q' => 'Cât durează până văd rezultate?', 'a' => 'Energie în <strong>2–3 săptămâni</strong>. Articulații în 4–6 săptămâni. Piele în 6–8 săptămâni. Păr și unghii în 8–12 săptămâni.'],
            ['q' => 'Pot lua toate trei în aceeași zi?', 'a' => 'Da, în momente diferite ale zilei: <strong>Vita Complete+</strong> dimineața, <strong>Black Seed Elixir</strong> dimineața și seara cu mese, <strong>Collagen Joint+ Berry</strong> dimineața sau seara cu o masă.'],
            ['q' => 'Colagenul este vegan?', 'a' => 'Nu. <strong>Collagen Joint+ Berry</strong> conține peptide de la animale crescute pe pășune. <strong>Vita Complete+</strong> și <strong>Black Seed Elixir</strong> sunt vegane.'],
            ['q' => 'Pot continua după cele 33 zile?', 'a' => 'Da — recomandăm <strong>minim 3 luni</strong> pentru regenerare profundă. La abonament, prețul scade automat și livrările se programează pe 30 de zile.'],
            ['q' => 'Black Seed ține 120 zile, restul 33–50 — cum continui?', 'a' => 'Recomandare: <strong>Collagen + Vita în cure repetate</strong> la 2–3 luni, Black Seed Elixir pe perioada extinsă pentru susținere antioxidantă de fond.'],
            ['q' => 'Cum opresc abonamentul?', 'a' => 'Din contul tău, oricând, <strong>fără întrebări</strong>. Sau primești banii înapoi în 14 zile pentru produsele desfăcute, dacă nu te conving.'],
        ],
    ],
];
