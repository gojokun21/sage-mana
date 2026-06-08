<?php

/**
 * Grup ACF pentru pagina filtru „Suplimente sub 200 lei”
 * (template `template-sub-200.blade.php`).
 *
 * Înregistrat în cod (versionat, chei stabile) ca seed-ul
 * (App\Console\Commands\Sub200Seed via `wp acorn natura:sub200-seed`) să scrie
 * pe chei stabile. Valorile rămân editabile în admin. Schema oglindește
 * secțiunile EDITORIALE din partials/sub-200/*.blade.php — grila de produse +
 * tabelul cost/zi rămân LIVE din WooCommerce (vezi template-sub-200.blade.php).
 * Iconițele/SVG-urile rămân statice în markup; doar textul vine de aici.
 *
 * Token suportat în lede/CTA: {count} → numărul de produse afișate (live).
 */

namespace App;

/**
 * Returnează valoarea ACF a câmpului (pe pagina curentă) dacă e setată,
 * altfel fallback-ul. Mirror al `simptom_field()` — folosit de partials/sub-200/*.
 */
function sub200_field(string $name, $default = null)
{
    if (! function_exists('get_field')) {
        return $default;
    }

    $value = get_field($name);

    if ($value === null || $value === false || $value === '' || $value === []) {
        return $default;
    }

    return $value;
}

/**
 * Output sigur pentru câmpuri care permit accente inline (<em>/<strong>/<br>/<a>).
 */
function sub200_kses(string $html): string
{
    return wp_kses($html, [
        'em' => [],
        'strong' => [],
        'br' => [],
        'a' => ['href' => [], 'target' => [], 'rel' => [], 'class' => []],
    ]);
}

add_action('acf/init', 'App\\register_sub200_acf');

/**
 * Înregistrează grupul ACF al paginii. Funcție numită (nu closure) ca să poată fi
 * apelată explicit de scriptul de seed prin link (seed-sub200.php), când acf/init
 * a fost deja declanșat și functions.php n-a apucat să încarce acest fișier.
 */
function register_sub200_acf(): void
{
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_sub200_filtru',
        'title' => 'Pagină „Sub 200 lei” — conținut',
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-sub-200.blade.php',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
        'fields' => [

            // ---------------------------------------------------------------
            // HERO
            // ---------------------------------------------------------------
            ['key' => 'field_sub200_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_sub200_hero_eyebrow', 'name' => 'hero_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_sub200_hero_titlu', 'name' => 'hero_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_sub200_hero_lede', 'name' => 'hero_lede', 'label' => 'Lede (permite <strong>; {count} = nr. produse)', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_sub200_hero_cpd_tag', 'name' => 'hero_cpd_tagline', 'label' => 'Tagline sub linia cost/zi', 'type' => 'text', 'default_value' => 'Compari cura completă, nu cutia.'],
            ['key' => 'field_sub200_chip_all', 'name' => 'chip_all_label', 'label' => 'Chip „Toate” (etichetă)', 'type' => 'text', 'default_value' => 'Toate'],
            ['key' => 'field_sub200_chip_vegan', 'name' => 'chip_vegan_label', 'label' => 'Chip „Vegan” (etichetă)', 'type' => 'text', 'default_value' => 'Vegan'],
            ['key' => 'field_sub200_chip_long', 'name' => 'chip_long_label', 'label' => 'Chip cură lungă (etichetă)', 'type' => 'text', 'default_value' => 'Cură lungă · 120+ zile'],
            ['key' => 'field_sub200_chip_short', 'name' => 'chip_short_label', 'label' => 'Chip cură scurtă (etichetă)', 'type' => 'text', 'default_value' => 'Cură scurtă · 30–50 zile'],

            // ---------------------------------------------------------------
            // EXPLAIN
            // ---------------------------------------------------------------
            ['key' => 'field_sub200_tab_explain', 'label' => 'Explică', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_sub200_explain_eyebrow', 'name' => 'explain_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_sub200_explain_titlu', 'name' => 'explain_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_sub200_explain_cards', 'name' => 'explain_cards', 'label' => 'Carduri (3)', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă card', 'sub_fields' => [
                ['key' => 'field_sub200_explain_h3', 'name' => 'titlu', 'label' => 'Titlu card', 'type' => 'text'],
                ['key' => 'field_sub200_explain_text', 'name' => 'text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_sub200_explain_link_t', 'name' => 'link_text', 'label' => 'Text link (opțional)', 'type' => 'text'],
                ['key' => 'field_sub200_explain_link_u', 'name' => 'link_url', 'label' => 'URL link (opțional)', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // GRILA PRODUSE (doar titluri — produsele vin din WooCommerce)
            // ---------------------------------------------------------------
            ['key' => 'field_sub200_tab_products', 'label' => 'Grilă produse', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_sub200_products_titlu', 'name' => 'products_titlu', 'label' => 'Titlu (permite <em>; {count} = nr. produse)', 'type' => 'text'],
            ['key' => 'field_sub200_products_meta', 'name' => 'products_meta', 'label' => 'Linie meta (dreapta)', 'type' => 'text', 'default_value' => 'Ordonat după preț crescător · cură completă inclusă'],
            ['key' => 'field_sub200_products_empty', 'name' => 'products_empty', 'label' => 'Text „niciun produs”', 'type' => 'textarea', 'rows' => 2],

            // ---------------------------------------------------------------
            // TABEL COST/ZI
            // ---------------------------------------------------------------
            ['key' => 'field_sub200_tab_table', 'label' => 'Tabel cost/zi', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_sub200_table_eyebrow', 'name' => 'table_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Tabel comparativ'],
            ['key' => 'field_sub200_table_titlu', 'name' => 'table_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_sub200_table_intro', 'name' => 'table_intro', 'label' => 'Intro', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_sub200_table_note', 'name' => 'table_note', 'label' => 'Notă subsol (permite <strong>)', 'type' => 'text'],

            // ---------------------------------------------------------------
            // BRIDGE
            // ---------------------------------------------------------------
            ['key' => 'field_sub200_tab_bridge', 'label' => 'Bridge pachete', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_sub200_bridge_eyebrow', 'name' => 'bridge_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_sub200_bridge_titlu', 'name' => 'bridge_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_sub200_bridge_text', 'name' => 'bridge_text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 4],
            ['key' => 'field_sub200_bridge_link_t', 'name' => 'bridge_link_text', 'label' => 'Text link', 'type' => 'text', 'default_value' => 'Vezi toate pachetele sub 400 lei'],
            ['key' => 'field_sub200_bridge_link_u', 'name' => 'bridge_link_url', 'label' => 'URL link (gol = /pachete/)', 'type' => 'text'],

            // ---------------------------------------------------------------
            // FAQ
            // ---------------------------------------------------------------
            ['key' => 'field_sub200_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_sub200_faq_titlu', 'name' => 'faq_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_sub200_faq_items', 'name' => 'faq_items', 'label' => 'Întrebări', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_sub200_faq_q', 'name' => 'q', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_sub200_faq_a', 'name' => 'a', 'label' => 'Răspuns (permite <strong>)', 'type' => 'textarea', 'rows' => 4],
            ]],

            // ---------------------------------------------------------------
            // CTA FINAL
            // ---------------------------------------------------------------
            ['key' => 'field_sub200_tab_cta', 'label' => 'CTA final', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_sub200_cta_titlu', 'name' => 'cta_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_sub200_cta_text', 'name' => 'cta_text', 'label' => 'Text ({count} = nr. produse)', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_sub200_cta_btn_t', 'name' => 'cta_btn_text', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Începe testul'],
            ['key' => 'field_sub200_cta_btn_u', 'name' => 'cta_btn_url', 'label' => 'URL buton (gol = /test/)', 'type' => 'text'],
        ],
    ]);
}
