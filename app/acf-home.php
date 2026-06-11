<?php

/**
 * Grup ACF pentru Home page (template `template-home.blade.php`).
 *
 * Tot textul editorial din partials/home/* devine editabil din admin, organizat
 * pe taburi per secțiune. Produsele (flagship) și articolele (blog) rămân dinamice
 * din WooCommerce/WP. Iconițele/SVG-urile rămân statice în markup.
 *
 * Înregistrat în cod (versionat). Seed: App\Console\Commands\HomeSeed
 * (`wp acorn natura:home-seed`) populează cheile din database/seeds/home.php.
 * Partials citesc prin \App\home_field() (ACF → fallback pe seed).
 */

namespace App;

defined('ABSPATH') || exit;

/**
 * Defaults editoriale (single source) din database/seeds/home.php.
 */
function home_defaults(): array
{
    static $defaults = null;
    if ($defaults === null) {
        $defaults = require dirname(__DIR__).'/database/seeds/home.php';
    }

    return is_array($defaults) ? $defaults : [];
}

/**
 * Valoarea ACF a câmpului de pe Home (non-empty), altfel fallback pe seed default.
 * Repeater-ele întorc array de rânduri cu aceleași sub-câmpuri ca în seed.
 */
function home_field(string $name)
{
    $value = function_exists('get_field') ? get_field($name) : null;

    if ($value === null || $value === false || $value === '' || $value === []) {
        return home_defaults()[$name] ?? null;
    }

    return $value;
}

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    $tab = static fn (string $key, string $label) => ['key' => $key, 'label' => $label, 'type' => 'tab', 'placement' => 'top'];
    $text = static fn (string $key, string $name, string $label) => ['key' => $key, 'name' => $name, 'label' => $label, 'type' => 'text'];
    $area = static fn (string $key, string $name, string $label, int $rows = 3) => ['key' => $key, 'name' => $name, 'label' => $label, 'type' => 'textarea', 'rows' => $rows];

    acf_add_local_field_group([
        'key' => 'group_home',
        'title' => 'Home — conținut',
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'template-home.blade.php']]],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'active' => true,
        'description' => 'Textul editorial al paginii Home. Produsele și articolele sunt dinamice; aici doar titluri, descrieri, CTA-uri și cardurile statice.',
        'fields' => [

            // HERO
            $tab('field_home_tab_hero', 'Hero'),
            $text('field_home_hero_eyebrow', 'hero_eyebrow', 'Eyebrow'),
            $text('field_home_hero_titlu', 'hero_titlu', 'Titlu'),
            $text('field_home_hero_titlu_em', 'hero_titlu_em', 'Titlu — accent italic'),
            $area('field_home_hero_lede', 'hero_lede', 'Lede (permite <strong>)', 4),
            $text('field_home_hero_cta1', 'hero_cta_primary', 'CTA principal (text) → magazin'),
            $text('field_home_hero_cta2', 'hero_cta_secondary', 'CTA secundar (text)'),
            $text('field_home_hero_cta2_url', 'hero_cta_secondary_url', 'CTA secundar (link) — implicit „#test" (scroll la secțiunea de test din pagină); poți pune „/test/"'),
            ['key' => 'field_home_hero_trust', 'name' => 'hero_trust', 'label' => 'Chips încredere', 'type' => 'repeater', 'min' => 0, 'max' => 4, 'layout' => 'table', 'button_label' => 'Adaugă chip', 'sub_fields' => [
                $text('field_home_hero_trust_text', 'text', 'Text'),
            ]],
            ['key' => 'field_home_hero_image', 'name' => 'hero_image', 'label' => 'Imagine hero (opțional — înlocuiește imaginea implicită)', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'],

            // FILOSOFIE
            $tab('field_home_tab_philo', 'Filosofie'),
            $text('field_home_philo_eyebrow', 'philo_eyebrow', 'Eyebrow'),
            $text('field_home_philo_titlu', 'philo_titlu', 'Titlu'),
            $text('field_home_philo_titlu_em', 'philo_titlu_em', 'Titlu — accent italic'),
            $area('field_home_philo_text', 'philo_text', 'Text'),

            // ENTRY POINTS
            $tab('field_home_tab_entry', 'De unde începi'),
            $text('field_home_entry_eyebrow', 'entry_eyebrow', 'Eyebrow'),
            $text('field_home_entry_titlu', 'entry_titlu', 'Titlu'),
            $text('field_home_entry_titlu_em', 'entry_titlu_em', 'Titlu — accent italic'),
            ['key' => 'field_home_entry_cards', 'name' => 'entry_cards', 'label' => 'Carduri', 'type' => 'repeater', 'min' => 0, 'max' => 4, 'layout' => 'block', 'button_label' => 'Adaugă card', 'sub_fields' => [
                $text('field_home_entry_card_titlu', 'titlu', 'Titlu'),
                $area('field_home_entry_card_text', 'text', 'Text', 2),
                $text('field_home_entry_card_link', 'link_text', 'Text link'),
                $text('field_home_entry_card_url', 'url', 'URL'),
                $text('field_home_entry_card_chip', 'chip', 'Chip auriu (opțional — ultimul card)'),
            ]],

            // QUIZ STRIP
            $tab('field_home_tab_quiz', 'Test 60s'),
            $text('field_home_quiz_eyebrow', 'quiz_eyebrow', 'Eyebrow'),
            $text('field_home_quiz_titlu', 'quiz_titlu', 'Titlu'),
            $text('field_home_quiz_titlu_em', 'quiz_titlu_em', 'Titlu — accent italic'),
            $area('field_home_quiz_text', 'quiz_text', 'Text'),
            $text('field_home_quiz_cta', 'quiz_cta', 'Text buton'),
            $text('field_home_quiz_cta_url', 'quiz_cta_url', 'URL buton'),
            $text('field_home_quiz_micro', 'quiz_micro', 'Micro-text'),

            // FLAGSHIP
            $tab('field_home_tab_flagship', 'Produse recomandate'),
            $text('field_home_flagship_eyebrow', 'flagship_eyebrow', 'Eyebrow'),
            $text('field_home_flagship_titlu', 'flagship_titlu', 'Titlu'),
            $text('field_home_flagship_titlu_em', 'flagship_titlu_em', 'Titlu — accent italic'),
            ['key' => 'field_home_flagship_slots', 'name' => 'flagship_slots', 'label' => 'Etichete sloturi (3) — produsele vin din ACF „eticheta_produs=pachete"', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'table', 'button_label' => 'Adaugă slot', 'sub_fields' => [
                ['key' => 'field_home_flagship_slot_class', 'name' => 'eyebrow_class', 'label' => 'Culoare', 'type' => 'select', 'choices' => ['gold' => 'Auriu', 'green' => 'Verde'], 'default_value' => 'green', 'return_format' => 'value'],
                $text('field_home_flagship_slot_text', 'eyebrow_text', 'Eyebrow slot'),
            ]],
            $text('field_home_flagship_foot', 'flagship_foot', 'Text link subsol'),

            // TRUST
            $tab('field_home_tab_trust', 'Încredere'),
            $text('field_home_trust_eyebrow', 'trust_eyebrow', 'Eyebrow'),
            $text('field_home_trust_titlu', 'trust_titlu', 'Titlu'),
            $text('field_home_trust_titlu_em', 'trust_titlu_em', 'Titlu — accent italic'),
            ['key' => 'field_home_trust_cells', 'name' => 'trust_cells', 'label' => 'Celule (3)', 'type' => 'repeater', 'min' => 0, 'max' => 3, 'layout' => 'block', 'button_label' => 'Adaugă celulă', 'sub_fields' => [
                $text('field_home_trust_cell_titlu', 'titlu', 'Titlu'),
                $area('field_home_trust_cell_text', 'text', 'Text'),
                $text('field_home_trust_cell_link', 'link_text', 'Text link (opțional)'),
                $text('field_home_trust_cell_url', 'link_url', 'URL link (opțional)'),
            ]],

            // BLOG
            $tab('field_home_tab_blog', 'Blog'),
            $text('field_home_blog_eyebrow', 'blog_eyebrow', 'Eyebrow'),
            $text('field_home_blog_titlu', 'blog_titlu', 'Titlu'),
            $text('field_home_blog_titlu_em', 'blog_titlu_em', 'Titlu — accent italic'),
            $text('field_home_blog_foot', 'blog_foot', 'Text link subsol'),

            // TESTIMONIALE
            $tab('field_home_tab_testi', 'Testimoniale'),
            $text('field_home_testi_eyebrow', 'testi_eyebrow', 'Eyebrow'),
            $text('field_home_testi_titlu', 'testi_titlu', 'Titlu'),
            $text('field_home_testi_titlu_em', 'testi_titlu_em', 'Titlu — accent italic'),
            ['key' => 'field_home_testi_cards', 'name' => 'testi_cards', 'label' => 'Recenzii (3)', 'type' => 'repeater', 'min' => 0, 'layout' => 'block', 'button_label' => 'Adaugă recenzie', 'sub_fields' => [
                ['key' => 'field_home_testi_rating', 'name' => 'rating', 'label' => 'Stele (1–5)', 'type' => 'number', 'min' => 1, 'max' => 5, 'default_value' => 5],
                $area('field_home_testi_quote', 'quote', 'Recenzie', 3),
                $text('field_home_testi_nume', 'nume', 'Nume'),
                $text('field_home_testi_rol', 'rol', 'Vârstă · oraș'),
                $text('field_home_testi_produs', 'produs', 'Produs'),
                $text('field_home_testi_verificat', 'verificat', 'Etichetă „verificat"'),
            ]],

            // NEWSLETTER
            $tab('field_home_tab_news', 'Newsletter'),
            $text('field_home_news_eyebrow', 'news_eyebrow', 'Eyebrow'),
            $text('field_home_news_titlu', 'news_titlu', 'Titlu'),
            $text('field_home_news_titlu_em', 'news_titlu_em', 'Titlu — accent italic'),
            $area('field_home_news_text', 'news_text', 'Text'),
            $text('field_home_news_placeholder', 'news_placeholder', 'Placeholder email'),
            $text('field_home_news_button', 'news_button', 'Text buton'),
            $text('field_home_news_micro', 'news_micro', 'Micro-text'),
        ],
    ]);
});
