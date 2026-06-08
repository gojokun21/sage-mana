<?php

/**
 * Seed pentru pagina filtru „Suplimente sub 200 lei” (template-sub-200.blade.php)
 * și pentru corectarea `informatie_generala` (protocol_zile / forma) pe produsele
 * afișate. Transcris din preferinte/Pagina filtru - Sub 200 lei.html.
 *
 * Folosit de App\Console\Commands\Sub200Seed (`wp acorn natura:sub200-seed`).
 * `protocol_zile` = numărul de ZILE de cură (nu de doze) — așa devine cost/zi corect.
 */

return [

    // Pagina + conținutul editorial (ACF group_sub200_filtru).
    'page' => [
        'title' => 'Suplimente sub 200 lei',
        'slug' => 'sub-200-lei',

        'hero' => [
            'eyebrow' => 'Filtru preț · Quick link',
            'titlu' => 'Suplimente <em>sub 200 lei.</em>',
            'lede' => '{count} pentru o singură problemă. Cure de 30–120 zile, fără compromisuri pe formulare.',
            'cpd_tagline' => 'Compari cura completă, nu cutia.',
            'chip_all_label' => 'Toate',
            'chip_vegan_label' => 'Vegan',
            'chip_long_label' => 'Cură lungă · 120+ zile',
            'chip_short_label' => 'Cură scurtă · 30–50 zile',
        ],

        'explain' => [
            'eyebrow' => 'De ce sub 200 lei',
            'titlu' => 'Nu înseamnă suplimente <em>mai slabe.</em>',
            'cards' => [
                [
                    'titlu' => 'Formulare complete într-un singur produs.',
                    'text' => 'Sub 200 lei se cumpără monoproduse. Pentru o problemă specifică (ficat, intestin, imunitate) <strong>un singur supliment cu formulare densă</strong> rezolvă mai bine decât 3 produse subdozate.',
                    'link_text' => '',
                    'link_url' => '',
                ],
                [
                    'titlu' => 'Cura înseamnă 30–120 zile, nu o cutie.',
                    'text' => 'Prețul afișat e <strong>prețul întregii cure</strong>. Black Seed Elixir 184 lei = 4 luni de protecție. Calcul real pe zi, nu pe cutie — vezi tabelul comparativ.',
                    'link_text' => '',
                    'link_url' => '',
                ],
                [
                    'titlu' => 'Când merită să treci la pachet.',
                    'text' => 'Dacă ai <strong>2–3 probleme simultan</strong> (ficat + digestie + imunitate slabă), un pachet de 280–330 lei e mai economic decât 3 produse separate.',
                    'link_text' => 'Vezi pachetele sub 400 lei',
                    'link_url' => '', // gol = /pachete/
                ],
            ],
        ],

        'products' => [
            'titlu' => '{count} produse, fiecare <em>cu rol clar.</em>',
            'meta' => 'Ordonat după preț crescător · cură completă inclusă',
            'empty' => 'Momentan nu avem produse sub 200 lei disponibile. Revino curând — catalogul se actualizează.',
        ],

        'table' => [
            'eyebrow' => 'Tabel comparativ',
            'titlu' => 'Cost real <em>pe zi.</em>',
            'intro' => 'Sortat crescător după cost/zi. Cele mai eficiente cure sunt cele cu cea mai mică valoare pe zi — durata mai lungă „diluează” prețul cutiei.',
            'note' => 'Nu cumperi cutia, <strong>cumperi cura</strong>. Compară întotdeauna costul pe zi.',
        ],

        'bridge' => [
            'eyebrow' => 'Mai multe probleme simultan?',
            'titlu' => 'Pachetele pot fi <em>mai eficiente.</em>',
            'text' => 'Dacă te confrunți cu <strong>2–3 simptome diferite</strong> (de exemplu digestie + imunitate + ten obosit), un pachet la 280–400 lei poate fi mai economic decât 3 produse separate — și produsele lucrează sinergic, nu doar alăturat.',
            'link_text' => 'Vezi toate pachetele sub 400 lei',
            'link_url' => '', // gol = /pachete/
        ],

        'faq' => [
            'titlu' => 'Întrebări <em>frecvente.</em>',
            'items' => [
                [
                    'q' => 'Cum aleg între produse?',
                    'a' => 'Cel mai rapid: <strong>fă testul de 60 secunde</strong> — îți spunem onest care ți se potrivește. Sau, dacă știi clar ce problemă vrei să rezolvi (ficat, intestin, imunitate, focus), citește beneficiile fiecărui produs și compară costul pe zi din tabel.',
                ],
                [
                    'q' => 'Cura înseamnă o cutie sau mai multe?',
                    'a' => 'Depinde de produs. Numărul de zile al curei (durata) e afișat pe fiecare card și în tabel — de la cure scurte de 30–50 zile până la cure lungi de 120 zile. <strong>Prețul afișat e prețul întregii cure</strong>, nu al unei singure cutii.',
                ],
                [
                    'q' => 'Pot combina 2 produse sub 200 lei?',
                    'a' => 'Da, dar verifică întâi pachetele — sunt mai economice. <strong>Microflora+ + D-Tox Ficat individual</strong> = 159 + 139 = 298 lei. <strong>Pachet Confort Digestiv</strong> (aceleași două produse) = 283 lei. Diferența de 15 lei vine din pachet, plus livrare consolidată.',
                ],
                [
                    'q' => 'Când văd rezultate?',
                    'a' => 'Variabil: <strong>2–4 săptămâni</strong> pentru digestie, energie și focus. <strong>4–6 săptămâni</strong> pentru ficat (silimarină) și imunitate. <strong>6–12 săptămâni</strong> pentru piele, păr, unghii și articulații (peptide colagen). Cure lungi (120 zile) sunt formulate pentru menținere și prevenție, nu doar pentru efect rapid.',
                ],
            ],
        ],

        'cta' => [
            'titlu' => 'Încă nu știi <em>ce ți se potrivește?</em>',
            'text' => 'Testul de 60 secunde îți recomandă onest care din cele {count} produse e cel mai potrivit — sau dacă ai nevoie de un pachet în loc.',
            'btn_text' => 'Începe testul',
            'btn_url' => '', // gol = /test/
        ],
    ],

    // Corecție date produse — informatie_generala (protocol_zile = zile cură).
    // Cheile sunt slug-urile WC. forma = ambalaj/doze; protocol_zile = ZILE.
    'products_meta' => [
        'lionfocus-b6-jeleuri' => ['forma' => '60 jeleuri', 'protocol_zile' => 30],
        'd-tox-ficat' => ['forma' => '120 capsule', 'protocol_zile' => 120],
        'microflora-lemon-shots-500-ml-33-shots' => ['forma' => '500 ml · 33 doze', 'protocol_zile' => 33],
        'black-seed-elixir' => ['forma' => '240 capsule', 'protocol_zile' => 120],
        'collagen-joint-berry-500-ml' => ['forma' => '500 ml · 33 doze', 'protocol_zile' => 33],
        'vita-complete-vegan-shots-500-ml-50-shots' => ['forma' => '500 ml · 50 doze', 'protocol_zile' => 50],
    ],
];
