<?php

/**
 * Grup ACF pentru paginile de simptom (template `template-simptom.blade.php`).
 *
 * Înregistrat în cod (nu în DB) ca să fie versionat și ca seed-ul
 * (App\Console\Commands\SimptomSeed via `wp acorn natura:simptom-seed`) să
 * scrie pe chei stabile. Valorile rămân editabile în admin. Schema oglindește
 * exact cele 8 secțiuni din partials/simptom/*.blade.php; iconițele/SVG-urile
 * rămân statice în markup, doar textul vine de aici.
 */

namespace App;

/**
 * Returnează valoarea ACF a câmpului dacă e setată (non-empty), altfel
 * fallback-ul. Folosit de partials/simptom/* ca să randeze conținutul din ACF
 * cu cădere pe textul hardcodat („Sindrom metabolic") când câmpul e gol.
 */
function simptom_field(string $name, $default = null)
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

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_simptom_detaliu',
        'title' => 'Pagină simptom — conținut',
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-simptom.blade.php',
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
            ['key' => 'field_simptom_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_hero_eyebrow', 'name' => 'hero_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'După simptom'],
            ['key' => 'field_simptom_hero_titlu', 'name' => 'hero_titlu', 'label' => 'Titlu (permite <em>…</em> pentru accent italic)', 'type' => 'text'],
            ['key' => 'field_simptom_hero_lede', 'name' => 'hero_lede', 'label' => 'Lede', 'type' => 'textarea', 'rows' => 4],
            ['key' => 'field_simptom_hero_chips', 'name' => 'hero_chips', 'label' => 'Meta-chips', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'table', 'button_label' => 'Adaugă chip', 'sub_fields' => [
                ['key' => 'field_simptom_hero_chip_text', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],

            // ---------------------------------------------------------------
            // DEFINIȚIE
            // ---------------------------------------------------------------
            ['key' => 'field_simptom_tab_def', 'label' => 'Definiție', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_def_eyebrow', 'name' => 'def_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Definiție simplă'],
            ['key' => 'field_simptom_def_titlu', 'name' => 'def_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_simptom_def_cells', 'name' => 'def_cells', 'label' => 'Celule', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă celulă', 'sub_fields' => [
                ['key' => 'field_simptom_def_cell_titlu', 'name' => 'titlu', 'label' => 'Titlu', 'type' => 'text'],
                ['key' => 'field_simptom_def_cell_text', 'name' => 'text', 'label' => 'Text', 'type' => 'textarea', 'rows' => 3],
            ]],

            // ---------------------------------------------------------------
            // SEMNE (cauze)
            // ---------------------------------------------------------------
            ['key' => 'field_simptom_tab_semne', 'label' => 'Semne', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_semne_eyebrow', 'name' => 'semne_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Cum se simte de obicei'],
            ['key' => 'field_simptom_semne_titlu', 'name' => 'semne_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_simptom_semne_items', 'name' => 'semne_items', 'label' => 'Semne', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă semn', 'sub_fields' => [
                ['key' => 'field_simptom_semne_titlu_item', 'name' => 'titlu', 'label' => 'Titlu', 'type' => 'text'],
                ['key' => 'field_simptom_semne_desc', 'name' => 'desc', 'label' => 'Descriere', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_simptom_semne_ajuta', 'name' => 'ajuta', 'label' => 'Ce ajută de obicei', 'type' => 'textarea', 'rows' => 2],
            ]],

            // ---------------------------------------------------------------
            // AUTOTEST
            // ---------------------------------------------------------------
            ['key' => 'field_simptom_tab_autotest', 'label' => 'Autotest', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_autotest_eyebrow', 'name' => 'autotest_eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'default_value' => 'Verificare rapidă'],
            ['key' => 'field_simptom_autotest_titlu', 'name' => 'autotest_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_simptom_autotest_intrebari', 'name' => 'autotest_intrebari', 'label' => 'Întrebări', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_simptom_autotest_q', 'name' => 'q', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_simptom_autotest_default', 'name' => 'default', 'label' => 'Răspuns implicit (din mockup)', 'type' => 'select', 'choices' => [0 => 'Da', 1 => 'Uneori', 2 => 'Nu'], 'default_value' => 0, 'return_format' => 'value'],
            ]],
            ['key' => 'field_simptom_autotest_rez_strong', 'name' => 'autotest_rezultat_strong', 'label' => 'Rezultat — frază bold', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_simptom_autotest_rez_text', 'name' => 'autotest_rezultat_text', 'label' => 'Rezultat — restul textului', 'type' => 'textarea', 'rows' => 3],

            // ---------------------------------------------------------------
            // CÂND LA MEDIC
            // ---------------------------------------------------------------
            ['key' => 'field_simptom_tab_medic', 'label' => 'Când la medic', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_medic_titlu', 'name' => 'medic_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_simptom_medic_lede', 'name' => 'medic_lede', 'label' => 'Lede', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_simptom_medic_semnale', 'name' => 'medic_semnale', 'label' => 'Semnale de alarmă', 'type' => 'repeater', 'min' => 0, 'layout' => 'table', 'button_label' => 'Adaugă semnal', 'sub_fields' => [
                ['key' => 'field_simptom_medic_semnal', 'name' => 'text', 'label' => 'Text', 'type' => 'text'],
            ]],
            ['key' => 'field_simptom_medic_foot', 'name' => 'medic_foot', 'label' => 'Notă subsol', 'type' => 'textarea', 'rows' => 3],

            // ---------------------------------------------------------------
            // PRODUSE
            // ---------------------------------------------------------------
            ['key' => 'field_simptom_tab_produse', 'label' => 'Produse', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_produse_eyebrow', 'name' => 'produse_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_simptom_produse_titlu', 'name' => 'produse_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_simptom_produse_intro', 'name' => 'produse_intro', 'label' => 'Intro', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_simptom_produse_items', 'name' => 'produse_items', 'label' => 'Produse (max 3, în ordine)', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă produs', 'sub_fields' => [
                ['key' => 'field_simptom_produse_produs', 'name' => 'produs', 'label' => 'Produs WooCommerce', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 1],
                ['key' => 'field_simptom_produse_nume', 'name' => 'nume', 'label' => 'Nume afișat (fallback dacă nu legi produs WC)', 'type' => 'text'],
                ['key' => 'field_simptom_produse_pret', 'name' => 'pret', 'label' => 'Preț afișat (fallback, ex. „119 lei")', 'type' => 'text'],
                ['key' => 'field_simptom_produse_opt', 'name' => 'opt', 'label' => 'Etichetă opțiune (ex. Opțiune 01)', 'type' => 'text'],
                ['key' => 'field_simptom_produse_category', 'name' => 'category', 'label' => 'Categorie (eyebrow mini)', 'type' => 'text'],
                ['key' => 'field_simptom_produse_why', 'name' => 'why', 'label' => 'De ce (copy)', 'type' => 'textarea', 'rows' => 3],
                ['key' => 'field_simptom_produse_cta', 'name' => 'cta', 'label' => 'Text buton', 'type' => 'text', 'default_value' => 'Vezi produsul'],
                ['key' => 'field_simptom_produse_cta_class', 'name' => 'cta_class', 'label' => 'Stil buton', 'type' => 'select', 'choices' => ['btn-terra' => 'Terra (primar)', 'btn-secondary-g' => 'Secundar verde'], 'default_value' => 'btn-terra', 'return_format' => 'value'],
            ]],

            // ---------------------------------------------------------------
            // MITURI
            // ---------------------------------------------------------------
            ['key' => 'field_simptom_tab_mituri', 'label' => 'Mituri', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_mituri_eyebrow', 'name' => 'mituri_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_simptom_mituri_titlu', 'name' => 'mituri_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_simptom_mituri_items', 'name' => 'mituri_items', 'label' => 'Mituri', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă mit', 'sub_fields' => [
                ['key' => 'field_simptom_mit', 'name' => 'mit', 'label' => 'Mit', 'type' => 'text'],
                ['key' => 'field_simptom_real', 'name' => 'real', 'label' => 'Realitate', 'type' => 'textarea', 'rows' => 3],
            ]],

            // ---------------------------------------------------------------
            // FAQ
            // ---------------------------------------------------------------
            ['key' => 'field_simptom_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_simptom_faq_eyebrow', 'name' => 'faq_eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ['key' => 'field_simptom_faq_titlu', 'name' => 'faq_titlu', 'label' => 'Titlu (permite <em>)', 'type' => 'text'],
            ['key' => 'field_simptom_faq_items', 'name' => 'faq_items', 'label' => 'Întrebări frecvente', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă întrebare', 'sub_fields' => [
                ['key' => 'field_simptom_faq_q', 'name' => 'q', 'label' => 'Întrebare', 'type' => 'text'],
                ['key' => 'field_simptom_faq_a', 'name' => 'a', 'label' => 'Răspuns (permite <strong>)', 'type' => 'textarea', 'rows' => 4],
            ]],
        ],
    ]);
});
