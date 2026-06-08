<?php

/**
 * Grup ACF pentru pagina filtru „Cele mai vândute”
 * (template `template-cele-mai-vandute.blade.php`).
 *
 * Tot conținutul editorial e editabil din admin; cele 5 „best-seller” sunt
 * PRODUSE REALE alese din WooCommerce (repeater cu post_object). Numele,
 * imaginea, prețul, beneficiile (ACF informatie_generala) și durata/cost-zi
 * vin LIVE din produs; doar textul „de ce e best-seller” + rating-ul de tabel
 * sunt editoriale.
 *
 * Seed: App\seed_bestseller() (app/bestseller-seed.php) — `wp acorn natura:bestseller-seed`
 * sau link/Unelte. Token în URL pentru link.
 */

namespace App;

/**
 * Valoarea ACF a câmpului (pe pagina curentă) sau fallback. Mirror al sub200_field().
 */
function bestseller_field(string $name, $default = null)
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
 * Output sigur pentru câmpuri cu accente inline (<em>/<strong>/<br>/<a>).
 */
function bestseller_kses(string $html): string
{
    return wp_kses($html, [
        'em' => [],
        'strong' => [],
        'br' => [],
        'a' => ['href' => [], 'target' => [], 'rel' => [], 'class' => []],
    ]);
}

add_action('acf/init', 'App\\register_bestseller_acf');

/**
 * Înregistrează grupul ACF. Funcție numită (nu closure) ca să poată fi apelată
 * explicit de scriptul de seed prin link (seed-bestseller.php).
 */
function register_bestseller_acf(): void
{
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_bestseller_filtru',
        'title' => 'Pagină „Cele mai vândute” — conținut',
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-cele-mai-vandute.blade.php',
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
            ['key' => 'field_bs_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_bs_hero_eyebrow', 'name' => 'hero_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_bs_hero_titlu', 'name' => 'hero_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_bs_hero_lede', 'name' => 'hero_lede', 'label' => 'Lede (permite <strong>)', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_bs_honest_label', 'name' => 'honest_label', 'label' => 'Notă onestitate — etichetă', 'type' => 'text', 'default_value' => 'Notă de onestitate'],
            ['key' => 'field_bs_honest_body', 'name' => 'honest_body', 'label' => 'Notă onestitate — text', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_bs_honest_line', 'name' => 'honest_line', 'label' => 'Notă onestitate — linie subliniată', 'type' => 'text'],

            // ---------------------------------------------------------------
            // EXPLAIN
            // ---------------------------------------------------------------
            ['key' => 'field_bs_tab_explain', 'label' => 'Cum am ales', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_bs_explain_eyebrow', 'name' => 'explain_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_bs_explain_titlu', 'name' => 'explain_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_bs_explain_cards', 'name' => 'explain_cards', 'label' => 'Criterii (3)', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă criteriu', 'sub_fields' => [
                ['key' => 'field_bs_explain_h3', 'name' => 'titlu', 'label' => 'Titlu', 'type' => 'text'],
                ['key' => 'field_bs_explain_text', 'name' => 'text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
            ]],

            // ---------------------------------------------------------------
            // PRODUSE (repeater cu produse reale)
            // ---------------------------------------------------------------
            ['key' => 'field_bs_tab_products', 'label' => 'Produse (top)', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_bs_products_titlu', 'name' => 'products_titlu', 'label' => 'Titlu secțiune (permite <em>)', 'type' => 'text'],
            ['key' => 'field_bs_products_meta', 'name' => 'products_meta', 'label' => 'Linie meta (dreapta)', 'type' => 'text', 'default_value' => 'Ordonate după reorder rate'],
            ['key' => 'field_bs_items', 'name' => 'bestsellers', 'label' => 'Top produse (în ordine)', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă produs', 'sub_fields' => [
                ['key' => 'field_bs_item_produs', 'name' => 'produs', 'label' => 'Produs WooCommerce', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 0, 'required' => 1, 'instructions' => 'Numele, imaginea, prețul, beneficiile și durata vin din produs.'],
                ['key' => 'field_bs_item_cat', 'name' => 'cat_label', 'label' => 'Etichetă categorie (override; gol = categoria produsului)', 'type' => 'text'],
                ['key' => 'field_bs_item_sub', 'name' => 'sub_override', 'label' => 'Linie sub-titlu (override; gol = formă · durată)', 'type' => 'text'],
                ['key' => 'field_bs_item_why', 'name' => 'why', 'label' => 'De ce e best-seller (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_bs_item_cta', 'name' => 'cta_label', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Vezi produsul'],
                ['key' => 'field_bs_item_rating', 'name' => 'rating', 'label' => 'Reorder rate (stele, pentru tabel)', 'type' => 'select', 'choices' => [5 => '★★★★★', 4 => '★★★★', 3 => '★★★', 2 => '★★', 1 => '★'], 'default_value' => 5, 'return_format' => 'value'],
                ['key' => 'field_bs_item_rating_lbl', 'name' => 'rating_label', 'label' => 'Reorder rate — etichetă (ex. „Foarte ridicat”)', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // TABEL
            // ---------------------------------------------------------------
            ['key' => 'field_bs_tab_table', 'label' => 'Tabel', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_bs_table_eyebrow', 'name' => 'table_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Top · cifre transparente'],
            ['key' => 'field_bs_table_titlu', 'name' => 'table_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_bs_table_intro', 'name' => 'table_intro', 'label' => 'Intro', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_bs_table_note', 'name' => 'table_note', 'label' => 'Notă subsol (permite <strong>)', 'type' => 'textarea', 'rows' => 2],

            // ---------------------------------------------------------------
            // QUIZ (bridge)
            // ---------------------------------------------------------------
            ['key' => 'field_bs_tab_quiz', 'label' => 'Quiz', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_bs_quiz_eyebrow', 'name' => 'quiz_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Ghid onest · 60 secunde'],
            ['key' => 'field_bs_quiz_titlu', 'name' => 'quiz_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_bs_quiz_text', 'name' => 'quiz_text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_bs_quiz_cta_t', 'name' => 'quiz_cta_text', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Începe testul'],
            ['key' => 'field_bs_quiz_cta_u', 'name' => 'quiz_cta_url', 'label' => 'URL buton (gol = /test/)', 'type' => 'text'],
            ['key' => 'field_bs_quiz_micro', 'name' => 'quiz_micro', 'label' => 'Micro (sub buton)', 'type' => 'text', 'default_value' => '7 întrebări · fără email · anonim'],

            // ---------------------------------------------------------------
            // FAQ
            // ---------------------------------------------------------------
            ['key' => 'field_bs_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_bs_faq_titlu', 'name' => 'faq_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_bs_faq_items', 'name' => 'faq_items', 'label' => 'Întrebări', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_bs_faq_q', 'name' => 'q', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_bs_faq_a', 'name' => 'a', 'label' => 'Răspuns (permite <strong>)', 'type' => 'textarea', 'rows' => 4],
            ]],

            // ---------------------------------------------------------------
            // CTA FINAL
            // ---------------------------------------------------------------
            ['key' => 'field_bs_tab_cta', 'label' => 'CTA final', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_bs_cta_titlu', 'name' => 'cta_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_bs_cta_text', 'name' => 'cta_text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_bs_cta_btn_t', 'name' => 'cta_btn_text', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Vezi toate suplimentele'],
            ['key' => 'field_bs_cta_btn_u', 'name' => 'cta_btn_url', 'label' => 'URL buton (gol = catalog)', 'type' => 'text'],
        ],
    ]);
}
