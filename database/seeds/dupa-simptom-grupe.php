<?php

/**
 * Conținutul „index simptome" de pe hub-ul /dupa-simptom/ (cele 4 grupe × carduri).
 *
 * Sursă unică de adevăr:
 *   - App\Console\Commands\DupaSimptomSeed populează ACF-ul hub-ului din acest array
 *     (rezolvă `slug` → pagina de detaliu /dupa-simptom/<slug>/ în câmpul „pagina").
 *   - partials/symptom/groups.blade.php îl folosește ca FALLBACK când ACF-ul e gol,
 *     ca pagina să arate identic chiar înainte de seed.
 *
 * `slug` e opțional per card: setat doar unde EXISTĂ pagina de detaliu (vezi
 * database/seeds/simptome.php). Restul cardurilor rămân fără link (admin le poate
 * lega ulterior din ACF). `chip` e opțional.
 */
defined('ABSPATH') || exit;

return [
    'footer' => __('Mai sunt 12 simptome în index, distribuite în grupe minore (piele, păr, hormoni, ciclu menstrual).', 'sage'),

    'grupe' => [
        [
            'eyebrow' => __('Grupa 01 · Digestiv', 'sage'),
            'title' => __('Când ceva nu e', 'sage'),
            'title_em' => __('în ordine cu digestia.', 'sage'),
            'cards' => [
                ['name' => __('Balonare', 'sage'), 'chip' => '60%', 'desc' => __('Abdomen plin, presiune în pântec, gaze.', 'sage'), 'slug' => 'balonare'],
                ['name' => __('Constipație', 'sage'), 'desc' => __('Mai puțin de 3 tranzite pe săptămână, scaun tare, efort mare.', 'sage')],
                ['name' => __('Reflux și arsuri', 'sage'), 'desc' => __('Senzație de arsură în piept sau în gât, mai des după mese mari sau seara.', 'sage')],
                ['name' => __('Diaree cronică', 'sage'), 'desc' => __('Tranzit accelerat repetat, mai mult de 2 săptămâni.', 'sage')],
                ['name' => __('Intoleranțe alimentare', 'sage'), 'desc' => __('Reacții repetate la aceeași grupă de alimente: lactate, gluten, FODMAP.', 'sage')],
            ],
        ],
        [
            'eyebrow' => __('Grupa 02 · Energie & somn', 'sage'),
            'title' => __('Când corpul refuză', 'sage'),
            'title_em' => __('să mai dea randament.', 'sage'),
            'cards' => [
                ['name' => __('Oboseală cronică', 'sage'), 'desc' => __('Epuizare care nu trece nici după weekend lung. Te trezești deja obosit dimineața.', 'sage'), 'slug' => 'oboseala-cronica'],
                ['name' => __('Ceața mentală', 'sage'), 'desc' => __('Greutate în concentrare, memorie de scurt termen încetinită, lucrezi prin ceață.', 'sage'), 'slug' => 'ceata-mentala'],
                ['name' => __('Somn agitat', 'sage'), 'desc' => __('Treziri nocturne, somn neodihnitor, dimineți grele.', 'sage')],
                ['name' => __('Insomnie de adormire', 'sage'), 'desc' => __('S-au scurs 30+ minute în fiecare seară până te ia somnul.', 'sage')],
                ['name' => __('Energy crash după-amiezii', 'sage'), 'desc' => __('Ora 14–15 te găsește golit, cauți cafea ca să termini ziua.', 'sage')],
            ],
        ],
        [
            'eyebrow' => __('Grupa 03 · Imunitate & inflamație', 'sage'),
            'title' => __('Când organismul luptă', 'sage'),
            'title_em' => __('mai des decât trebuie.', 'sage'),
            'cards' => [
                ['name' => __('Răceli repetate', 'sage'), 'desc' => __('Mai mult de 4 răceli pe an, durează și 10+ zile fiecare.', 'sage'), 'slug' => 'raceli-frecvente'],
                ['name' => __('Inflamație articulară', 'sage'), 'desc' => __('Durere în genunchi, șolduri sau încheieturi, accentuată dimineața.', 'sage'), 'slug' => 'articulatii'],
                ['name' => __('Alergii sezoniere puternice', 'sage'), 'desc' => __('Polen, praf, păr de animale, simptome care țin săptămâni.', 'sage')],
                ['name' => __('Piele inflamată', 'sage'), 'desc' => __('Eczeme, roșeață persistentă, mâncărimi recurente.', 'sage')],
                ['name' => __('Recuperare lentă după boală', 'sage'), 'desc' => __('După viroză, energia revine în 3–4 săptămâni, nu într-una.', 'sage')],
            ],
        ],
        [
            'eyebrow' => __('Grupa 04 · Performanță & focus', 'sage'),
            'title' => __('Când vrei mai mult', 'sage'),
            'title_em' => __('de la ce ești deja.', 'sage'),
            'cards' => [
                ['name' => __('Recuperare musculară lentă', 'sage'), 'desc' => __('Durere musculară care ține 3+ zile după antrenament intens.', 'sage'), 'slug' => 'recuperare-antrenament'],
                ['name' => __('Lipsă de focus la antrenament', 'sage'), 'desc' => __('Corpul e ok, dar mintea rătăcește în sală.', 'sage')],
                ['name' => __('Plateau de forță', 'sage'), 'desc' => __('Câteva săptămâni cu aceleași greutăți, niciun progres.', 'sage')],
                ['name' => __('Tonus muscular scăzut', 'sage'), 'desc' => __('Mușchi care arată mai puțin definit, chiar dacă te antrenezi.', 'sage')],
                ['name' => __('Energie sportivă inconsistentă', 'sage'), 'desc' => __('Zi de zi cu variații mari de putere și rezistență.', 'sage')],
            ],
        ],
    ],
];
