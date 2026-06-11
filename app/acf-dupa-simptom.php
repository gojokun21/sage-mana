<?php

/**
 * Grup ACF pentru HUB-ul „După simptom" (template `template-dupa-simptom.blade.php`).
 *
 * Doar „index-ul de simptome" e editabil (cele 4 grupe + cardurile lor), conform
 * scope-ului agreat — hero/medic/quiz/blog rămân text static în partials. Fiecare
 * card are un câmp „pagina" (relație la pagina lui de detaliu /dupa-simptom/<slug>/),
 * astfel încât linkurile pleacă din admin, nu hardcodate.
 *
 * Înregistrat în cod (versionat). Seed: App\Console\Commands\DupaSimptomSeed
 * (`wp acorn natura:dupa-simptom-seed`) populează cheile din
 * database/seeds/dupa-simptom-grupe.php. Schema oglindește groups.blade.php.
 */

namespace App;

/**
 * Returnează grupele normalizate pentru groups.blade.php:
 *   [ ['eyebrow','title','title_em','cards'=>[['name','desc','chip','link']]] , ... ]
 *
 * Sursă: ACF-ul hub-ului dacă e populat; altfel fallback pe array-ul static din
 * database/seeds/dupa-simptom-grupe.php (link rezolvat din `slug`). În ambele
 * cazuri linkul gol devine '#'.
 */
function dupa_simptom_grupe(): array
{
    $rows = function_exists('get_field') ? get_field('simptom_grupe') : null;

    if (is_array($rows) && ! empty($rows)) {
        return array_map(function ($g) {
            $cards = is_array($g['carduri'] ?? null) ? $g['carduri'] : [];

            return [
                'eyebrow' => (string) ($g['eyebrow'] ?? ''),
                'title' => (string) ($g['titlu'] ?? ''),
                'title_em' => (string) ($g['titlu_em'] ?? ''),
                'cards' => array_map(function ($c) {
                    $page_id = $c['pagina'] ?? 0;
                    $link = $page_id ? get_permalink((int) $page_id) : '';

                    return [
                        'name' => (string) ($c['nume'] ?? ''),
                        'desc' => (string) ($c['descriere'] ?? ''),
                        'chip' => (string) ($c['chip'] ?? ''),
                        'link' => $link ?: '#',
                    ];
                }, $cards),
            ];
        }, $rows);
    }

    // Fallback static — pagina arată identic chiar înainte de seed.
    $seed = require dirname(__DIR__).'/database/seeds/dupa-simptom-grupe.php';
    $grupe = $seed['grupe'] ?? [];

    return array_map(function ($g) {
        return [
            'eyebrow' => $g['eyebrow'] ?? '',
            'title' => $g['title'] ?? '',
            'title_em' => $g['title_em'] ?? '',
            'cards' => array_map(function ($c) {
                $link = '';
                if (! empty($c['slug'])) {
                    $page = get_page_by_path('dupa-simptom/'.$c['slug'], OBJECT, 'page');
                    if ($page instanceof \WP_Post) {
                        $link = get_permalink($page->ID);
                    }
                }

                return [
                    'name' => $c['name'] ?? '',
                    'desc' => $c['desc'] ?? '',
                    'chip' => $c['chip'] ?? '',
                    'link' => $link ?: '#',
                ];
            }, $g['cards'] ?? []),
        ];
    }, $grupe);
}

/**
 * Linia de subsol a index-ului (sub ultima grupă). ACF → fallback static.
 */
function dupa_simptom_footer(): string
{
    $val = function_exists('get_field') ? get_field('grupe_footer') : null;
    if (is_string($val) && trim($val) !== '') {
        return $val;
    }

    $seed = require dirname(__DIR__).'/database/seeds/dupa-simptom-grupe.php';

    return (string) ($seed['footer'] ?? '');
}

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_dupa_simptom_hub',
        'title' => 'Hub După simptom — index simptome',
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-dupa-simptom.blade.php',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
        'description' => 'Cele 4 grupe și cardurile lor. Hero, „la medic", quiz și blog rămân statice în temă.',
        'fields' => [
            [
                'key' => 'field_ds_grupe',
                'name' => 'simptom_grupe',
                'label' => 'Grupe de simptome',
                'type' => 'repeater',
                'min' => 0,
                'layout' => 'block',
                'button_label' => 'Adaugă grupă',
                'sub_fields' => [
                    ['key' => 'field_ds_grupa_eyebrow', 'name' => 'eyebrow', 'label' => 'Eyebrow (ex. „Grupa 01 · Digestiv")', 'type' => 'text'],
                    ['key' => 'field_ds_grupa_titlu', 'name' => 'titlu', 'label' => 'Titlu', 'type' => 'text'],
                    ['key' => 'field_ds_grupa_titlu_em', 'name' => 'titlu_em', 'label' => 'Titlu — accent italic verde', 'type' => 'text'],
                    [
                        'key' => 'field_ds_grupa_carduri',
                        'name' => 'carduri',
                        'label' => 'Carduri simptom',
                        'type' => 'repeater',
                        'min' => 0,
                        'layout' => 'block',
                        'button_label' => 'Adaugă card',
                        'sub_fields' => [
                            ['key' => 'field_ds_card_nume', 'name' => 'nume', 'label' => 'Nume simptom', 'type' => 'text'],
                            ['key' => 'field_ds_card_desc', 'name' => 'descriere', 'label' => 'Descriere scurtă', 'type' => 'textarea', 'rows' => 2],
                            ['key' => 'field_ds_card_chip', 'name' => 'chip', 'label' => 'Chip (opțional, ex. „60%")', 'type' => 'text'],
                            [
                                'key' => 'field_ds_card_pagina',
                                'name' => 'pagina',
                                'label' => 'Pagina de detaliu',
                                'instructions' => 'Pagina /dupa-simptom/<slug>/. Lasă gol dacă încă nu există (cardul nu va avea link).',
                                'type' => 'post_object',
                                'post_type' => ['page'],
                                'return_format' => 'id',
                                'ui' => 1,
                                'allow_null' => 1,
                            ],
                        ],
                    ],
                ],
            ],
            ['key' => 'field_ds_grupe_footer', 'name' => 'grupe_footer', 'label' => 'Linie subsol index', 'type' => 'textarea', 'rows' => 2],
        ],
    ]);
});
