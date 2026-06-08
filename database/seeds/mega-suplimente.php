<?php

/**
 * Seed pentru mega-meniul „Suplimente” (ACF options, pagina „Meniu”).
 * Coloana de categorii + lista featured trag date reale din WC; aici sunt
 * doar etichetele editoriale (format, quick links, bandă) + slug-urile featured.
 *
 * Folosit de App\seed_mega_suplimente (`wp acorn natura:mega-suplimente-seed`).
 */

return [
    'cat_title' => 'Pe categorie',
    'cat_foot' => '→ Vezi toate cele {count} de produse',

    'format_title' => 'Pe format',
    'formate' => [
        ['label' => 'Capsule', 'count' => '(3)', 'link' => ''],
        ['label' => 'Lichid · Shots', 'count' => '(4)', 'link' => ''],
        ['label' => 'Pudră · Proteine', 'count' => '(3)', 'link' => ''],
        ['label' => 'Jeleuri', 'count' => '(1)', 'link' => ''],
        ['label' => 'Pachete', 'count' => '(9)', 'link' => ''],
    ],
    'format_disclaimer' => 'Filtrele se combină în catalog.',

    'quick_title' => 'Quick links',
    // Link-urile pe filtre se rezolvă la slug-ul paginii (creat de celelalte seed-uri).
    'quick' => [
        ['label' => 'Cele mai vândute', 'page_slug' => 'cele-mai-vandute', 'badge' => ''],
        ['label' => 'Noutăți', 'page_slug' => 'noutati-in-curand', 'badge' => 'Nou'],
        ['label' => 'Sub 200 lei', 'page_slug' => 'sub-200-lei', 'badge' => ''],
        ['label' => 'Pachete sub 400 lei', 'url' => '/pachete/', 'badge' => ''],
        ['label' => 'Cum aleg suplimentul potrivit?', 'url' => '/test/', 'badge' => ''],
    ],

    'featured_title' => 'Recomandate de echipa noastră',
    'featured' => [
        ['produs_slug' => 'black-seed-elixir', 'why' => 'Imunitate & echilibru metabolic'],
        ['produs_slug' => 'vita-complete-vegan-shots-500-ml-50-shots', 'why' => 'Multivitamine + energie zilnică'],
        ['produs_slug' => 'microflora-lemon-shots-500-ml-33-shots', 'why' => 'Probiotice lichide, confort digestiv'],
    ],

    'bottom_info' => 'Transport gratuit peste 299 lei • 90 zile garanție • Plata ramburs',
    'bottom_cta_text' => 'Vezi catalogul complet',
    'bottom_cta_url' => '', // gol = catalog
];
