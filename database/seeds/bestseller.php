<?php

/**
 * Seed pentru pagina filtru „Cele mai vândute” (template-cele-mai-vandute.blade.php).
 * Conținut editorial + lista de produse REALE (după slug WC) pentru repeater-ul ACF.
 *
 * Produsele de mai jos folosesc slug-uri confirmate în catalog. Le poți reordona /
 * înlocui / adăuga oricând din ACF (tab „Produse (top)”) — inclusiv un pachet (bundle).
 *
 * Folosit de App\seed_bestseller (`wp acorn natura:bestseller-seed`, link sau Unelte).
 */

return [
    'page' => [
        'title' => 'Cele mai vândute',
        'slug' => 'cele-mai-vandute',

        'hero' => [
            'eyebrow' => 'Top · Quick link',
            'titlu' => 'Cele mai <em>vândute.</em>',
            'lede' => '<strong>{count} produse</strong> pe care clienții noștri le cumpără constant și le recomandă mai departe.',
            'honest_label' => 'Notă de onestitate',
            'honest_body' => 'Nu publicăm cifre de vânzări fabricate. Mâna Naturii este un brand mic — nu vindem milioane de unități. În schimb, urmărim reorder rate, recenzii și retenția clienților.',
            'honest_line' => '→ Aceste produse sunt cele cu cea mai mare loialitate măsurată în 12 luni.',
        ],

        'explain' => [
            'eyebrow' => 'Cum am ales',
            'titlu' => 'Trei criterii reale, <em>fără cifre fabricate.</em>',
            'cards' => [
                ['titlu' => 'Reorder rate, nu volum brut.', 'text' => 'Pentru un brand mic, contează <strong>câți clienți repetă cumpărarea</strong>. Aceste produse au cele mai multe re-cumpărări în 12 luni.'],
                ['titlu' => 'Recomandare către prieteni.', 'text' => 'Toate au scor mare la întrebarea <strong>„ai recomanda acest produs?”</strong> în sondajele post-cură. Word-of-mouth, nu publicitate plătită.'],
                ['titlu' => 'Acoperire pe nevoi diferite.', 'text' => 'Nu sunt produse din aceeași categorie. Acoperă <strong>digestie, detox, imunitate, energie, articulații</strong>. Probabil unul e exact pentru tine.'],
            ],
        ],

        'products_titlu' => '{count} produse, <em>{count} motive diferite.</em>',
        'products_meta' => 'Ordonate după reorder rate',

        // PRODUSE REALE (slug WC). Reordonează/înlocuiește din ACF după nevoie.
        'bestsellers' => [
            [
                'produs_slug' => 'microflora-lemon-shots-500-ml-33-shots',
                'cat_label' => 'Sănătate Intestinală',
                'sub_override' => '',
                'why' => 'Reorder rate cel mai mare din catalog. Clienții îl iau preventiv toamnă-iarnă și post-antibiotice. Gustul de lămâie face cura sustenabilă.',
                'cta_label' => 'Vezi produsul',
                'rating' => 5,
                'rating_label' => 'Foarte ridicat',
            ],
            [
                'produs_slug' => 'd-tox-ficat',
                'cat_label' => 'Detoxifiere',
                'sub_override' => '',
                'why' => 'Cost/zi excelent pentru o cură lungă de 120 de zile. Clienții îl repetă sezonier (post-sărbători, primăvara) pentru susținere hepatică.',
                'cta_label' => 'Vezi produsul',
                'rating' => 5,
                'rating_label' => 'Foarte ridicat',
            ],
            [
                'produs_slug' => 'black-seed-elixir',
                'cat_label' => 'Imunitate',
                'sub_override' => '',
                'why' => 'Produs nișat dar cu fani loiali — clienții care îl încep continuă 2–3 cure consecutive. Cost/zi excelent pentru 4 luni de protecție. Recomandat de medici colaboratori.',
                'cta_label' => 'Vezi produsul',
                'rating' => 4,
                'rating_label' => 'Ridicat · fani loiali',
            ],
            [
                'produs_slug' => 'vita-complete-vegan-shots-500-ml-50-shots',
                'cat_label' => 'Stare de bine generală',
                'sub_override' => '',
                'why' => 'Multivitamina lichidă cu cea mai bună retenție — clienții o iau ca ritual zilnic și revin lunar. Gust bun, doză completă, fără pastile.',
                'cta_label' => 'Vezi produsul',
                'rating' => 4,
                'rating_label' => 'Ridicat · uz zilnic',
            ],
            [
                'produs_slug' => 'collagen-joint-berry-500-ml',
                'cat_label' => 'Articulații & Frumusețe',
                'sub_override' => '',
                'why' => 'Cel mai cumpărat în segmentul beauty/articulații. Clienții peste 35 ani revin lunar. Forma lichidă cu gust de fructe de pădure face cura plăcută — retention rate mare.',
                'cta_label' => 'Vezi produsul',
                'rating' => 4,
                'rating_label' => 'Ridicat · 35+',
            ],
        ],

        'table' => [
            'eyebrow' => 'Top · cifre transparente',
            'titlu' => 'Reorder rate, <em>nu vânzări fabricate.</em>',
            'intro' => 'Coloana „Reorder rate” folosește semne calitative, nu cifre exacte. Rating-ul intern e calculat pe re-cumpărări în 12 luni — nu îl publicăm pe cifre rotunde manipulative.',
            'note' => 'Aceste rate sunt calculate intern pe baza re-cumpărărilor în 12 luni. <strong>Nu sunt cifre publicabile exact</strong>, dar le folosim pentru a alege ce promovăm.',
        ],

        'quiz' => [
            'eyebrow' => 'Ghid onest · 60 secunde',
            'titlu' => 'Niciunul nu ți se <em>potrivește exact?</em>',
            'text' => 'Aceste produse sunt cele mai cerute, dar nu sunt singurele potrivite pentru tine. Testul de 60 secunde recomandă exact ce ai nevoie pe baza simptomelor și stilului tău de viață.',
            'cta_text' => 'Începe testul',
            'cta_url' => '', // gol = /test/
            'micro' => '7 întrebări · fără email · anonim',
        ],

        'faq' => [
            'titlu' => 'Întrebări <em>frecvente.</em>',
            'items' => [
                ['q' => 'Cum știți că acestea sunt best-seller?', 'a' => '<strong>Reorder rate calculat la 12 luni</strong> + sondaje post-cură („ai recomanda acest produs?”). Nu cifre fake gen „1234 vânduri” sau „top 1 luna aceasta”. Sunt produsele cu cea mai mare loialitate măsurată intern.'],
                ['q' => 'Pot lua 2 best-seller-uri simultan?', 'a' => 'Da, dar verifică întâi dacă un <strong>pachet acoperă deja ambele probleme</strong>. Pachetele integrate sunt adesea mai economice decât produsele luate separat.'],
                ['q' => 'De ce nu publicați cifre exacte?', 'a' => 'Pentru că am văzut competitori cu cifre suspect de rotunde. <strong>Onestitatea contează mai mult</strong>. Suntem un brand mic — reorder rate-ul real spune mai mult decât un volum brut umflat.'],
                ['q' => 'Lista se actualizează?', 'a' => 'Da, o <strong>revizuim trimestrial</strong> pe baza datelor reale. Lista nu e statică — reflectă comportamentul real al clienților, nu marketingul nostru.'],
            ],
        ],

        'cta' => [
            'titlu' => 'Vezi catalogul <em>complet.</em>',
            'text' => 'Aceste produse sunt doar punctul de plecare — dacă nu te regăsești aici, restul catalogului are altă soluție pentru tine.',
            'btn_text' => 'Vezi toate suplimentele',
            'btn_url' => '', // gol = catalog
        ],
    ],
];
