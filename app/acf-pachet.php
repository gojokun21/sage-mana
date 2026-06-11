<?php

/**
 * Grup ACF pentru secțiunile editoriale ale paginii de PACHET (produse bundle).
 *
 * Înregistrat în cod (versionat, chei stabile) ca seed-ul
 * (App\Console\Commands\PachetSeed via `wp acorn natura:pachet-seed`) să scrie
 * pe chei stabile. Schema oglindește secțiunile din
 * partials/single-pachet/*.blade.php (mockup `Pagina Pachet - *`).
 * Câmpurile au prefix `pk_` ca să nu se ciocnească cu grupul PDP al produselor
 * simple (pcine_da, faq_items etc.). Partial-urile au fallback static.
 * Prețuri, produse componente, rating — rămân WooCommerce nativ.
 */

namespace App;

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_pachet_editorial',
        'title' => 'Pachet — conținut editorial',
        'location' => [
            [
                [
                    'param' => 'post_taxonomy',
                    'operator' => '==',
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
            ['key' => 'field_pk_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pk_eyebrow', 'name' => 'pk_eyebrow', 'label' => 'Eyebrow (ex. „PACHET FOCUS · CLARITATE & CONCENTRARE")', 'type' => 'text'],
            ['key' => 'field_pk_tagline', 'name' => 'pk_tagline', 'label' => 'Tagline (italic, sub titlu — ex. „Memorie, concentrare, echilibru neuronal.")', 'type' => 'text'],

            // ---------------------------------------------------------------
            // CUM LUCREAZĂ ÎMPREUNĂ
            // ---------------------------------------------------------------
            ['key' => 'field_pk_tab_why', 'label' => 'Cum lucrează', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pk_why_kicker', 'name' => 'pk_why_kicker', 'label' => 'Kicker', 'type' => 'text', 'default_value' => 'Cum lucrează împreună'],
            ['key' => 'field_pk_why_titlu', 'name' => 'pk_why_titlu', 'label' => 'Titlu (permite <em>…</em>)', 'type' => 'text'],
            ['key' => 'field_pk_why_prose', 'name' => 'pk_why_prose', 'label' => 'Paragrafe (permite <strong>)', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă paragraf', 'sub_fields' => [
                ['key' => 'field_pk_why_prose_text', 'name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 3],
            ]],
            ['key' => 'field_pk_why_cards', 'name' => 'pk_why_cards', 'label' => 'Carduri produse (rol + titlu + text)', 'type' => 'repeater', 'min' => 0, 'max' => 4, 'layout' => 'block', 'button_label' => 'Adaugă card', 'sub_fields' => [
                ['key' => 'field_pk_why_card_rol', 'name' => 'rol', 'label' => 'Rol (ex. „Ținta · LionFocus B6")', 'type' => 'text'],
                ['key' => 'field_pk_why_card_titlu', 'name' => 'titlu', 'label' => 'Titlu', 'type' => 'text'],
                ['key' => 'field_pk_why_card_text', 'name' => 'text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
            ]],

            // ---------------------------------------------------------------
            // BENEFICII
            // ---------------------------------------------------------------
            ['key' => 'field_pk_tab_benefits', 'label' => 'Beneficii', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pk_benefits_titlu', 'name' => 'pk_benefits_titlu', 'label' => 'Titlu (permite <em>, ex. „Ce se schimbă <em>în 50 de zile.</em>")', 'type' => 'text'],
            ['key' => 'field_pk_benefits_items', 'name' => 'pk_benefits_items', 'label' => 'Beneficii (listă)', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă beneficiu', 'sub_fields' => [
                ['key' => 'field_pk_benefits_item_text', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // CUM SE FOLOSEȘTE (TIMELINE)
            // ---------------------------------------------------------------
            ['key' => 'field_pk_tab_tl', 'label' => 'Cum se folosește', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pk_tl_titlu', 'name' => 'pk_tl_titlu', 'label' => 'Titlu (permite <em>, ex. „Două produse, <em>două momente ale zilei.</em>")', 'type' => 'text'],
            ['key' => 'field_pk_tl_steps', 'name' => 'pk_tl_steps', 'label' => 'Pași (3–4)', 'type' => 'repeater', 'min' => 0, 'max' => 4, 'layout' => 'block', 'button_label' => 'Adaugă pas', 'sub_fields' => [
                ['key' => 'field_pk_tl_step_when', 'name' => 'when', 'label' => 'Moment (ex. „Dimineața, pe stomacul gol")', 'type' => 'text'],
                ['key' => 'field_pk_tl_step_titlu', 'name' => 'titlu', 'label' => 'Titlu pas', 'type' => 'text'],
                ['key' => 'field_pk_tl_step_text', 'name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 2],
            ]],

            // ---------------------------------------------------------------
            // PENTRU CINE
            // ---------------------------------------------------------------
            ['key' => 'field_pk_tab_pcine', 'label' => 'Pentru cine', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pk_pcine_da', 'name' => 'pk_pcine_da', 'label' => 'Merge bine pentru…', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă rând', 'sub_fields' => [
                ['key' => 'field_pk_pcine_da_text', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],
            ['key' => 'field_pk_pcine_nu', 'name' => 'pk_pcine_nu', 'label' => 'Nu merge (încă) pentru…', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă rând', 'sub_fields' => [
                ['key' => 'field_pk_pcine_nu_text', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // FAQ
            // ---------------------------------------------------------------
            ['key' => 'field_pk_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_pk_faq_items', 'name' => 'pk_faq_items', 'label' => 'Întrebări', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_pk_faq_item_q', 'name' => 'intrebare', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_pk_faq_item_a', 'name' => 'raspuns', 'label' => 'Răspuns (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
            ]],
        ],
    ]);
});
