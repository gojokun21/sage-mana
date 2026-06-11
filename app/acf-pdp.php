<?php

/**
 * Grup ACF pentru secțiunile editoriale ale PDP-ului (produse simple).
 *
 * Înregistrat în cod (versionat, chei stabile) ca seed-ul
 * (App\Console\Commands\PdpSeed via `wp acorn natura:pdp-seed`) să scrie pe
 * chei stabile. Valorile rămân editabile în admin. Schema oglindește
 * secțiunile din partials/single-product/*.blade.php (mockup `PDP - *`).
 * Iconițele/SVG rămân statice în markup; doar textul vine de aici.
 * Partial-urile au fallback static — produsele fără valori (ex. pachetele)
 * rămân funcționale.
 */

namespace App;

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    /*
     * NB: grupul `informatie_generala` (din DB, grup „Single Product") nu avea
     * subcâmpul `forma`, deși template-urile îl citesc. NU îl înregistrăm local
     * aici — acf_add_local_field() cu părinte din DB face ca ACF să ignore
     * subcâmpurile din DB (grupul ar rămâne doar cu `forma`). Subcâmpul e creat
     * în DB (acf_update_field) de pasul de seed — vezi App\pdp_ensure_forma_field().
     */

    acf_add_local_field_group([
        'key' => 'group_pdp_editorial',
        'title' => 'PDP — conținut editorial',
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ],
                // Pachetele au grupul lor (group_pachet_editorial, acf-pachet.php).
                [
                    'param' => 'post_taxonomy',
                    'operator' => '!=',
                    'value' => 'product_type:bundle',
                ],
            ],
        ],
        'menu_order' => 5,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
        'fields' => [

            // ---------------------------------------------------------------
            // HERO
            // ---------------------------------------------------------------
            ['key' => 'field_pdp_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pdp_eyebrow', 'name' => 'pdp_eyebrow', 'label' => 'Eyebrow (ex. „Jeleuri · focus & claritate mentală")', 'type' => 'text'],
            ['key' => 'field_pdp_subline', 'name' => 'pdp_subline', 'label' => 'Subline (sub titlu, ex. „Coama Leului + vitamina B6 · 60 jeleuri · 30 zile · vegan.")', 'type' => 'text'],

            // ---------------------------------------------------------------
            // INGREDIENT CHEIE
            // ---------------------------------------------------------------
            ['key' => 'field_pdp_tab_ihl', 'label' => 'Ingredient cheie', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pdp_ihl_eyebrow', 'name' => 'ihl_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Ingredient cheie · spus complet'],
            ['key' => 'field_pdp_ihl_titlu', 'name' => 'ihl_titlu', 'label' => 'Titlu (permite <em>…</em>)', 'type' => 'text'],
            ['key' => 'field_pdp_ihl_caption', 'name' => 'ihl_caption', 'label' => 'Legendă ilustrație', 'type' => 'text'],
            ['key' => 'field_pdp_ihl_prose', 'name' => 'ihl_prose', 'label' => 'Paragrafe (permite <strong>)', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă paragraf', 'sub_fields' => [
                ['key' => 'field_pdp_ihl_prose_text', 'name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 3],
            ]],
            ['key' => 'field_pdp_ihl_rows', 'name' => 'ihl_rows', 'label' => 'Tabel ingredient (etichetă / valoare)', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă rând', 'sub_fields' => [
                ['key' => 'field_pdp_ihl_row_lbl', 'name' => 'lbl', 'label' => 'Etichetă', 'type' => 'text'],
                ['key' => 'field_pdp_ihl_row_val', 'name' => 'val', 'label' => 'Valoare (permite <strong>, <em>)', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // CUM ÎL FOLOSEȘTI
            // ---------------------------------------------------------------
            ['key' => 'field_pdp_tab_how', 'label' => 'Cum îl folosești', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pdp_how_eyebrow', 'name' => 'how_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Cum îl folosești'],
            ['key' => 'field_pdp_how_intro', 'name' => 'how_intro', 'label' => 'Intro (sub titlu)', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_pdp_how_steps', 'name' => 'how_steps', 'label' => 'Pași (3)', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă pas', 'sub_fields' => [
                ['key' => 'field_pdp_how_step_when', 'name' => 'when', 'label' => 'Moment (ex. „Dimineața")', 'type' => 'text'],
                ['key' => 'field_pdp_how_step_titlu', 'name' => 'titlu', 'label' => 'Titlu pas', 'type' => 'text'],
                ['key' => 'field_pdp_how_step_text', 'name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 2],
            ]],

            // ---------------------------------------------------------------
            // PENTRU CINE
            // ---------------------------------------------------------------
            ['key' => 'field_pdp_tab_pcine', 'label' => 'Pentru cine', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pdp_pcine_da', 'name' => 'pcine_da', 'label' => 'Merge bine pentru…', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă rând', 'sub_fields' => [
                ['key' => 'field_pdp_pcine_da_text', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],
            ['key' => 'field_pdp_pcine_nu', 'name' => 'pcine_nu', 'label' => 'Nu merge (încă) pentru…', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă rând', 'sub_fields' => [
                ['key' => 'field_pdp_pcine_nu_text', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // STANDARDE DE PRODUCȚIE
            // ---------------------------------------------------------------
            ['key' => 'field_pdp_tab_stand', 'label' => 'Standarde', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pdp_stand_cards', 'name' => 'stand_cards', 'label' => 'Carduri (3 — iconițele rămân statice, pe poziție)', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă card', 'sub_fields' => [
                ['key' => 'field_pdp_stand_card_titlu', 'name' => 'titlu', 'label' => 'Titlu', 'type' => 'text'],
                ['key' => 'field_pdp_stand_card_text', 'name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 2],
            ]],

            // ---------------------------------------------------------------
            // FAQ
            // ---------------------------------------------------------------
            ['key' => 'field_pdp_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pdp_faq_nume', 'name' => 'faq_nume_produs', 'label' => 'Nume scurt produs (în titlul „Ce ne întrebați despre X")', 'type' => 'text'],
            ['key' => 'field_pdp_faq_items', 'name' => 'faq_items', 'label' => 'Întrebări', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_pdp_faq_item_q', 'name' => 'intrebare', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_pdp_faq_item_a', 'name' => 'raspuns', 'label' => 'Răspuns (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
            ]],
        ],
    ]);
});
