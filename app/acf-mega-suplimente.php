<?php

/**
 * Grup ACF pentru mega-meniul „Suplimente” (clasa `mega-produse` pe itemul de meniu).
 * Conținut global → legat de pagina de opțiuni ACF „Meniu”.
 *
 * Coloana „Pe categorie” + cardul featured trag date REALE din WooCommerce
 * (categorii product_cat + produse). Restul (format, quick links, bandă jos) e
 * editorial, editabil aici, cu fallback pe valorile din mockup în partial.
 *
 * Seed: App\seed_mega_suplimente() (app/mega-suplimente-seed.php).
 */

namespace App;

/** Valoarea ACF a unei opțiuni (post_id 'option') sau fallback. */
function msup_field(string $name, $default = null)
{
    if (! function_exists('get_field')) {
        return $default;
    }
    $value = get_field($name, 'option');
    if ($value === null || $value === false || $value === '' || $value === []) {
        return $default;
    }

    return $value;
}

function msup_kses(string $html): string
{
    return wp_kses($html, ['em' => [], 'strong' => [], 'br' => []]);
}

add_action('acf/init', 'App\\register_msup_acf');

function register_msup_acf(): void
{
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_mega_suplimente',
        'title' => 'Mega-meniu „Suplimente”',
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'Meniu']]],
        'menu_order' => 5,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
        'fields' => [

            // Coloana 1 — categorii (titlu + link footer; lista e dinamică din WC)
            ['key' => 'field_msup_cat_title', 'name' => 'msup_cat_title', 'label' => 'Coloana 1 — titlu', 'type' => 'text', 'default_value' => 'Pe categorie'],
            ['key' => 'field_msup_cat_foot', 'name' => 'msup_cat_foot', 'label' => 'Coloana 1 — link footer ({count} = nr. produse)', 'type' => 'text', 'default_value' => '→ Vezi toate cele {count} de produse'],

            // Coloana 2 — format
            ['key' => 'field_msup_fmt_title', 'name' => 'msup_format_title', 'label' => 'Coloana 2 — titlu', 'type' => 'text', 'default_value' => 'Pe format'],
            ['key' => 'field_msup_fmt', 'name' => 'msup_formate', 'label' => 'Coloana 2 — formate', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă format', 'sub_fields' => [
                ['key' => 'field_msup_fmt_label', 'name' => 'label', 'label' => 'Etichetă', 'type' => 'text'],
                ['key' => 'field_msup_fmt_count', 'name' => 'count', 'label' => 'Count (ex. „(3)”)', 'type' => 'text'],
                ['key' => 'field_msup_fmt_link', 'name' => 'link', 'label' => 'Link (opțional)', 'type' => 'text'],
            ]],
            ['key' => 'field_msup_fmt_disc', 'name' => 'msup_format_disclaimer', 'label' => 'Coloana 2 — disclaimer', 'type' => 'text', 'default_value' => 'Filtrele se combină în catalog.'],

            // Coloana 3 — quick links
            ['key' => 'field_msup_q_title', 'name' => 'msup_quick_title', 'label' => 'Coloana 3 — titlu', 'type' => 'text', 'default_value' => 'Quick links'],
            ['key' => 'field_msup_q', 'name' => 'msup_quick', 'label' => 'Coloana 3 — linkuri', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă link', 'sub_fields' => [
                ['key' => 'field_msup_q_label', 'name' => 'label', 'label' => 'Etichetă', 'type' => 'text'],
                ['key' => 'field_msup_q_link', 'name' => 'link', 'label' => 'Link', 'type' => 'text'],
                ['key' => 'field_msup_q_badge', 'name' => 'badge', 'label' => 'Badge (ex. „Nou”, opțional)', 'type' => 'text'],
            ]],

            // Featured (produse reale)
            ['key' => 'field_msup_f_title', 'name' => 'msup_featured_title', 'label' => 'Featured — titlu', 'type' => 'text', 'default_value' => 'Recomandate de echipa noastră'],
            ['key' => 'field_msup_f', 'name' => 'msup_featured', 'label' => 'Featured — produse', 'type' => 'repeater', 'min' => 0, 'max' => 4, 'layout' => 'block', 'button_label' => 'Adaugă produs', 'sub_fields' => [
                ['key' => 'field_msup_f_produs', 'name' => 'produs', 'label' => 'Produs WooCommerce', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 0],
                ['key' => 'field_msup_f_why', 'name' => 'why', 'label' => 'Descriere scurtă (gol = scurtă descriere produs)', 'type' => 'text'],
            ]],

            // Bandă jos
            ['key' => 'field_msup_b_info', 'name' => 'msup_bottom_info', 'label' => 'Bandă jos — info (separator cu „•”)', 'type' => 'text', 'default_value' => 'Transport gratuit peste 299 lei • 90 zile garanție • Plata ramburs'],
            ['key' => 'field_msup_b_cta_t', 'name' => 'msup_bottom_cta_text', 'label' => 'Bandă jos — buton text', 'type' => 'text', 'default_value' => 'Vezi catalogul complet'],
            ['key' => 'field_msup_b_cta_u', 'name' => 'msup_bottom_cta_url', 'label' => 'Bandă jos — buton URL (gol = catalog)', 'type' => 'text'],
        ],
    ]);
}
