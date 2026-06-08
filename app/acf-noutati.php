<?php

/**
 * Grup ACF pentru pagina filtru „Noutăți · În curând”
 * (template `template-noutati.blade.php`).
 *
 * Tot conținutul e editabil din admin. Cele 3 tincturi sunt produse VIITOARE
 * (în curs de aprobare ANSVSA) — datele lor sunt EDITORIALE într-un repeater
 * (compoziție pe plante, beneficii, contraindicații, status). Opțional poți
 * lega un produs WC real per tinctură (când apare un draft), pentru imagine/link.
 *
 * Seed: App\seed_noutati() (app/noutati-seed.php).
 */

namespace App;

function noutati_field(string $name, $default = null)
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

function noutati_kses(string $html): string
{
    return wp_kses($html, [
        'em' => [], 'strong' => [], 'br' => [],
        'a' => ['href' => [], 'target' => [], 'rel' => [], 'class' => []],
    ]);
}

add_action('acf/init', 'App\\register_noutati_acf');

function register_noutati_acf(): void
{
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    $theme_choices = [
        '' => 'Auriu (implicit)',
        't-vas' => 'Mov (Dreno VAS)',
        't-colon' => 'Verde (Colon)',
        't-neuro' => 'Albastru (Neuro)',
        't-immune' => 'Chihlimbar (Imunitate)',
        't-sleep' => 'Indigo (Somn)',
    ];

    acf_add_local_field_group([
        'key' => 'group_noutati_filtru',
        'title' => 'Pagină „Noutăți · În curând” — conținut',
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'template-noutati.blade.php']]],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
        'fields' => [

            // HERO
            ['key' => 'field_nt_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_nt_hero_eyebrow', 'name' => 'hero_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_nt_hero_titlu', 'name' => 'hero_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_nt_hero_brand', 'name' => 'hero_brand_by', 'label' => 'Linie brand (ex. „by Vivens Genetica”)', 'type' => 'text'],
            ['key' => 'field_nt_hero_lede', 'name' => 'hero_lede', 'label' => 'Lede (permite <strong>; {count} = nr. tincturi)', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_nt_disc_label', 'name' => 'disclaimer_label', 'label' => 'Disclaimer — etichetă', 'type' => 'text', 'default_value' => 'Important · înainte să citești mai departe'],
            ['key' => 'field_nt_disc_text', 'name' => 'disclaimer_text', 'label' => 'Disclaimer — text (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_nt_hero_cta_t', 'name' => 'hero_cta_text', 'label' => 'CTA — text', 'type' => 'text', 'default_value' => 'Anunță-mă la lansare'],
            ['key' => 'field_nt_hero_cta_u', 'name' => 'hero_cta_url', 'label' => 'CTA — URL (gol = #notify)', 'type' => 'text'],

            // EXPLAIN
            ['key' => 'field_nt_tab_explain', 'label' => 'De ce durează', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_nt_explain_eyebrow', 'name' => 'explain_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_nt_explain_titlu', 'name' => 'explain_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_nt_explain_cards', 'name' => 'explain_cards', 'label' => 'Carduri (3)', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă card', 'sub_fields' => [
                ['key' => 'field_nt_explain_h3', 'name' => 'titlu', 'label' => 'Titlu', 'type' => 'text'],
                ['key' => 'field_nt_explain_text', 'name' => 'text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 3],
            ]],

            // TINCTURI
            ['key' => 'field_nt_tab_tinc', 'label' => 'Tincturi', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_nt_tinc_titlu', 'name' => 'tinctures_titlu', 'label' => 'Titlu secțiune (permite <em>; {count})', 'type' => 'text'],
            ['key' => 'field_nt_tinc_sub', 'name' => 'tinctures_sub', 'label' => 'Subtitlu', 'type' => 'text'],
            ['key' => 'field_nt_tinc_items', 'name' => 'tinctures', 'label' => 'Tincturi', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă tinctură', 'sub_fields' => [
                ['key' => 'field_nt_t_produs', 'name' => 'produs', 'label' => 'Produs WC (opțional — pentru imagine/link când există)', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 1],
                ['key' => 'field_nt_t_theme', 'name' => 'theme', 'label' => 'Culoare sticlă', 'type' => 'select', 'choices' => $theme_choices, 'default_value' => '', 'return_format' => 'value'],
                ['key' => 'field_nt_t_badge', 'name' => 'pending_badge', 'label' => 'Badge status', 'type' => 'text', 'default_value' => 'În așteptarea aprobării ANSVSA'],
                ['key' => 'field_nt_t_bottle', 'name' => 'bottle_label', 'label' => 'Etichetă sticlă (folosește | pentru linie nouă)', 'type' => 'text'],
                ['key' => 'field_nt_t_cat', 'name' => 'cat_chip', 'label' => 'Categorie (chip)', 'type' => 'text'],
                ['key' => 'field_nt_t_name', 'name' => 'name', 'label' => 'Nume (permite <em>)', 'type' => 'text'],
                ['key' => 'field_nt_t_brand', 'name' => 'brand_line', 'label' => 'Linie brand', 'type' => 'text', 'default_value' => 'by Vivens Genetica'],
                ['key' => 'field_nt_t_role', 'name' => 'role', 'label' => 'Rol (descriere scurtă)', 'type' => 'textarea', 'rows' => 2],
                ['key' => 'field_nt_t_specs', 'name' => 'specs', 'label' => 'Specificații (ex. „Extract 1:3 · 30 ml · …”)', 'type' => 'text'],
                ['key' => 'field_nt_t_usage', 'name' => 'usage', 'label' => 'Mod de utilizare (permite <strong>)', 'type' => 'text'],
                ['key' => 'field_nt_t_ing_sum', 'name' => 'ingredients_summary', 'label' => 'Compoziție — titlu (ex. „Compoziție · 7 plante”)', 'type' => 'text'],
                ['key' => 'field_nt_t_ing', 'name' => 'ingredients', 'label' => 'Compoziție (plante)', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă plantă', 'sub_fields' => [
                    ['key' => 'field_nt_t_ing_plant', 'name' => 'plant', 'label' => 'Plantă', 'type' => 'text'],
                    ['key' => 'field_nt_t_ing_latin', 'name' => 'latin', 'label' => 'Denumire latină · parte', 'type' => 'text'],
                    ['key' => 'field_nt_t_ing_pct', 'name' => 'pct', 'label' => '%', 'type' => 'text'],
                ]],
                ['key' => 'field_nt_t_bens', 'name' => 'benefits', 'label' => 'Beneficii', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă beneficiu', 'sub_fields' => [
                    ['key' => 'field_nt_t_ben', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
                ]],
                ['key' => 'field_nt_t_ci_label', 'name' => 'contraindic_label', 'label' => 'Contraindicații — etichetă', 'type' => 'text', 'default_value' => 'Contraindicații'],
                ['key' => 'field_nt_t_ci_text', 'name' => 'contraindic_text', 'label' => 'Contraindicații — text', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_nt_t_ci2_label', 'name' => 'contraindic_extra_label', 'label' => 'Atenție specială — etichetă (opțional)', 'type' => 'text'],
                ['key' => 'field_nt_t_ci2_text', 'name' => 'contraindic_extra_text', 'label' => 'Atenție specială — text (opțional)', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_nt_t_status_label', 'name' => 'status_label', 'label' => 'Status & preț — etichetă', 'type' => 'text', 'default_value' => 'Status & preț'],
                ['key' => 'field_nt_t_status', 'name' => 'status_rows', 'label' => 'Rânduri status', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă rând', 'sub_fields' => [
                    ['key' => 'field_nt_t_status_k', 'name' => 'k', 'label' => 'Cheie', 'type' => 'text'],
                    ['key' => 'field_nt_t_status_v', 'name' => 'v', 'label' => 'Valoare', 'type' => 'text'],
                    ['key' => 'field_nt_t_status_type', 'name' => 'type', 'label' => 'Stil', 'type' => 'select', 'choices' => ['normal' => 'Normal', 'estimate' => 'Verde (estimare)', 'tba' => 'Gri (TBA)'], 'default_value' => 'normal', 'return_format' => 'value'],
                ]],
                ['key' => 'field_nt_t_notify', 'name' => 'notify_btn', 'label' => 'Text buton „Anunță-mă”', 'type' => 'text', 'default_value' => 'Anunță-mă când e gata'],
            ]],

            // WHY
            ['key' => 'field_nt_tab_why', 'label' => 'De ce tincturi', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_nt_why_eyebrow', 'name' => 'why_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Despre formă'],
            ['key' => 'field_nt_why_titlu', 'name' => 'why_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_nt_why_cards', 'name' => 'why_cards', 'label' => 'Carduri', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă card', 'sub_fields' => [
                ['key' => 'field_nt_why_h3', 'name' => 'titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
                ['key' => 'field_nt_why_text', 'name' => 'text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 4],
            ]],

            // NOTIFY (vizual)
            ['key' => 'field_nt_tab_notify', 'label' => 'Formular notify', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_nt_n_eyebrow', 'name' => 'notify_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Listă de lansare'],
            ['key' => 'field_nt_n_titlu', 'name' => 'notify_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_nt_n_lede', 'name' => 'notify_lede', 'label' => 'Lede', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_nt_n_email_label', 'name' => 'notify_email_label', 'label' => 'Etichetă câmp email', 'type' => 'text', 'default_value' => 'Adresa ta de email'],
            ['key' => 'field_nt_n_email_ph', 'name' => 'notify_email_placeholder', 'label' => 'Placeholder email', 'type' => 'text', 'default_value' => 'email@exemplu.ro'],
            ['key' => 'field_nt_n_which_label', 'name' => 'notify_which_label', 'label' => 'Etichetă „pentru care tincturi”', 'type' => 'text', 'default_value' => 'Pentru care tincturi vrei să fii anunțat'],
            ['key' => 'field_nt_n_consent', 'name' => 'notify_consent', 'label' => 'Text consimțământ (permite <strong>)', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_nt_n_submit', 'name' => 'notify_submit', 'label' => 'Text buton submit', 'type' => 'text', 'default_value' => 'Înscrie-mă pe listă'],
            ['key' => 'field_nt_n_post', 'name' => 'notify_post_line', 'label' => 'Linie sub formular', 'type' => 'text'],

            // FAQ
            ['key' => 'field_nt_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_nt_faq_titlu', 'name' => 'faq_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_nt_faq_items', 'name' => 'faq_items', 'label' => 'Întrebări', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_nt_faq_q', 'name' => 'q', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_nt_faq_a', 'name' => 'a', 'label' => 'Răspuns (permite <strong>)', 'type' => 'textarea', 'rows' => 4],
            ]],

            // CTA
            ['key' => 'field_nt_tab_cta', 'label' => 'CTA final', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_nt_cta_titlu', 'name' => 'cta_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_nt_cta_text', 'name' => 'cta_text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_nt_cta_b1_t', 'name' => 'cta_primary_text', 'label' => 'Buton principal — text', 'type' => 'text', 'default_value' => 'Vezi catalogul'],
            ['key' => 'field_nt_cta_b1_u', 'name' => 'cta_primary_url', 'label' => 'Buton principal — URL (gol = catalog)', 'type' => 'text'],
            ['key' => 'field_nt_cta_b2_t', 'name' => 'cta_outline_text', 'label' => 'Buton secundar — text', 'type' => 'text', 'default_value' => 'Fă testul de 60 sec'],
            ['key' => 'field_nt_cta_b2_u', 'name' => 'cta_outline_url', 'label' => 'Buton secundar — URL (gol = /test/)', 'type' => 'text'],
        ],
    ]);
}
