<?php

/**
 * Grup ACF pentru paginile „După obiectiv" (template `template-obiectiv.blade.php`).
 *
 * Înregistrat în cod (versionat, chei stabile) ca seed-ul
 * (App\Console\Commands\ObiectivSeed via `wp acorn natura:obiectiv-seed`) să
 * scrie pe chei stabile. Valorile rămân editabile în admin. Schema oglindește
 * cele 8 secțiuni din partials/obiectiv/*.blade.php (mockup `Pagina obiectiv - *`).
 * Iconițele/SVG rămân statice în markup; doar textul + produsele vin de aici.
 */

namespace App;

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_obiectiv_detaliu',
        'title' => 'Pagină obiectiv — conținut',
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-obiectiv.blade.php',
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
            ['key' => 'field_obiectiv_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_hero_eyebrow', 'name' => 'hero_eyebrow', 'label' => 'Eyebrow (ex. „Obiectiv: Energie")', 'type' => 'text'],
            ['key' => 'field_obiectiv_hero_titlu', 'name' => 'hero_titlu', 'label' => 'Titlu (permite <em>…</em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_hero_lede', 'name' => 'hero_lede', 'label' => 'Lede', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_obiectiv_hero_cta1', 'name' => 'hero_cta_primary', 'label' => 'CTA principal (text)', 'type' => 'text', 'default_value' => 'Vezi recomandarea principală'],
            ['key' => 'field_obiectiv_hero_cta2', 'name' => 'hero_cta_secondary', 'label' => 'CTA secundar (text)', 'type' => 'text', 'default_value' => 'Compară pachetele'],
            ['key' => 'field_obiectiv_hero_stats', 'name' => 'hero_stats', 'label' => 'Stats (linie)', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă stat', 'sub_fields' => [
                ['key' => 'field_obiectiv_hero_stat', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // RECOMANDARE PRINCIPALĂ
            // ---------------------------------------------------------------
            ['key' => 'field_obiectiv_tab_reco', 'label' => 'Recomandare', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_reco_eyebrow', 'name' => 'reco_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Pick-ul principal'],
            ['key' => 'field_obiectiv_reco_titlu', 'name' => 'reco_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_reco_subtitlu', 'name' => 'reco_subtitlu', 'label' => 'Subtitlu', 'type' => 'text'],
            ['key' => 'field_obiectiv_reco_produs', 'name' => 'reco_produs', 'label' => 'Produs WooCommerce', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 1],
            ['key' => 'field_obiectiv_reco_nume', 'name' => 'reco_nume', 'label' => 'Nume afișat (fallback)', 'type' => 'text'],
            ['key' => 'field_obiectiv_reco_pret', 'name' => 'reco_pret', 'label' => 'Preț afișat (fallback)', 'type' => 'text'],
            ['key' => 'field_obiectiv_reco_durata', 'name' => 'reco_durata', 'label' => 'Durată (ex. „ajunge 120 de zile")', 'type' => 'text'],
            ['key' => 'field_obiectiv_reco_benefits', 'name' => 'reco_benefits', 'label' => 'Beneficii', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă beneficiu', 'sub_fields' => [
                ['key' => 'field_obiectiv_reco_benefit', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],
            ['key' => 'field_obiectiv_reco_cta', 'name' => 'reco_cta', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Adaugă în coș'],

            // ---------------------------------------------------------------
            // ALTE OPȚIUNI
            // ---------------------------------------------------------------
            ['key' => 'field_obiectiv_tab_alts', 'label' => 'Alte opțiuni', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_alts_titlu', 'name' => 'alts_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_alts_items', 'name' => 'alts_items', 'label' => 'Produse alternative', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă opțiune', 'sub_fields' => [
                ['key' => 'field_obiectiv_alts_produs', 'name' => 'produs', 'label' => 'Produs WooCommerce', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 1],
                ['key' => 'field_obiectiv_alts_nume', 'name' => 'nume', 'label' => 'Nume afișat (fallback)', 'type' => 'text'],
                ['key' => 'field_obiectiv_alts_pret', 'name' => 'pret', 'label' => 'Preț afișat (fallback)', 'type' => 'text'],
                ['key' => 'field_obiectiv_alts_desc', 'name' => 'desc', 'label' => 'Descriere', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_obiectiv_alts_cta', 'name' => 'cta', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Vezi produs'],
            ]],

            // ---------------------------------------------------------------
            // BUNDLE BAND
            // ---------------------------------------------------------------
            ['key' => 'field_obiectiv_tab_bundle', 'label' => 'Combină', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_bundle_eyebrow', 'name' => 'bundle_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Combină'],
            ['key' => 'field_obiectiv_bundle_titlu', 'name' => 'bundle_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_bundle_text', 'name' => 'bundle_text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_obiectiv_bundle_cta', 'name' => 'bundle_cta', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Vezi combinația'],
            ['key' => 'field_obiectiv_bundle_url', 'name' => 'bundle_cta_url', 'label' => 'Link buton (opțional)', 'type' => 'text'],

            // ---------------------------------------------------------------
            // CUM SE FOLOSEȘTE
            // ---------------------------------------------------------------
            ['key' => 'field_obiectiv_tab_how', 'label' => 'Cum se folosește', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_how_eyebrow', 'name' => 'how_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Cum se folosește'],
            ['key' => 'field_obiectiv_how_titlu', 'name' => 'how_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_how_items', 'name' => 'how_items', 'label' => 'Pași', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă pas', 'sub_fields' => [
                ['key' => 'field_obiectiv_how_when', 'name' => 'when', 'label' => 'Când (ex. Dimineața)', 'type' => 'text'],
                ['key' => 'field_obiectiv_how_body', 'name' => 'body', 'label' => 'Descriere', 'type' => 'textarea', 'rows' => 2],
            ]],

            // ---------------------------------------------------------------
            // RECENZII
            // ---------------------------------------------------------------
            ['key' => 'field_obiectiv_tab_reviews', 'label' => 'Recenzii', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_reviews_eyebrow', 'name' => 'reviews_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Recenzii'],
            ['key' => 'field_obiectiv_reviews_titlu', 'name' => 'reviews_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_reviews_items', 'name' => 'reviews_items', 'label' => 'Recenzii', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă recenzie', 'sub_fields' => [
                ['key' => 'field_obiectiv_review_rating', 'name' => 'rating', 'label' => 'Stele', 'type' => 'select', 'choices' => [5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1'], 'default_value' => 5, 'return_format' => 'value'],
                ['key' => 'field_obiectiv_review_quote', 'name' => 'quote', 'label' => 'Citat', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_obiectiv_review_by', 'name' => 'by', 'label' => 'Autor', 'type' => 'text'],
            ]],
            ['key' => 'field_obiectiv_reviews_note', 'name' => 'reviews_note', 'label' => 'Notă subsol', 'type' => 'text'],

            // ---------------------------------------------------------------
            // EDU
            // ---------------------------------------------------------------
            ['key' => 'field_obiectiv_tab_edu', 'label' => 'De ce funcționează', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_edu_eyebrow', 'name' => 'edu_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'De ce funcționează'],
            ['key' => 'field_obiectiv_edu_titlu', 'name' => 'edu_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_edu_text', 'name' => 'edu_text', 'label' => 'Text (permite <strong>)', 'type' => 'textarea', 'rows' => 5],

            // ---------------------------------------------------------------
            // FAQ
            // ---------------------------------------------------------------
            ['key' => 'field_obiectiv_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_obiectiv_faq_eyebrow', 'name' => 'faq_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Întrebări frecvente'],
            ['key' => 'field_obiectiv_faq_titlu', 'name' => 'faq_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_obiectiv_faq_items', 'name' => 'faq_items', 'label' => 'Întrebări', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_obiectiv_faq_q', 'name' => 'q', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_obiectiv_faq_a', 'name' => 'a', 'label' => 'Răspuns (permite <strong>)', 'type' => 'textarea', 'rows' => 4],
            ]],
        ],
    ]);
});
