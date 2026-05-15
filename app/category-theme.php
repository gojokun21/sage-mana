<?php

/**
 * Tema vizuala per categorie de produs.
 *
 * Un singur Color Picker ACF pe taxonomy product_cat. Valoarea e citita in
 * taxonomy-product_cat.blade.php si injectata ca `--cat-accent` pe wrapper.
 * Restul paletei (hover/soft/border/text) e derivata in CSS prin color-mix(),
 * deci admin-ul seteaza o singura culoare.
 */

namespace App;

add_action('acf/init', function () {
    if (! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_category_theme',
        'title' => 'Tema vizuala categorie',
        'fields' => [
            [
                'key' => 'field_category_theme_color',
                'label' => 'Culoare accent',
                'name' => 'category_theme_color',
                'type' => 'color_picker',
                'instructions' => 'Culoarea principala folosita pentru butoane, ribbons si accente pe pagina acestei categorii. Lasa gol pentru paleta verde default.',
                'default_value' => '',
                'return_format' => 'string',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'product_cat',
                ],
            ],
        ],
        'menu_order' => 5,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);
});

/**
 * Returneaza culoarea de accent pentru un term, sanitizata ca hex.
 * Fallback la null daca nu e setata sau invalida.
 */
function category_theme_color($term): ?string
{
    if (! function_exists('get_field') || ! $term) {
        return null;
    }

    $value = get_field('category_theme_color', $term);

    if (! is_string($value) || $value === '') {
        return null;
    }

    $value = trim($value);

    return preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', $value) ? $value : null;
}

/**
 * Culoarea de accent pentru categoria curenta (sau null daca nu e o categorie
 * sau nu are ACF setat). Centralizeaza logica `is_product_category()` ca sa nu
 * o duplicam in fiecare hook.
 */
function current_category_theme_color(): ?string
{
    if (! function_exists('is_product_category') || ! is_product_category()) {
        return null;
    }

    return category_theme_color(get_queried_object());
}

/**
 * Adauga `has-cat-theme` pe <body> cand suntem pe o categorie cu accent setat.
 * Folosit pentru a targeta CSS overrides pentru body bg / topbar / footer in
 * category-bundle.css fara a corupe alte pagini.
 */
add_filter('body_class', function ($classes) {
    if (current_category_theme_color() !== null) {
        $classes[] = 'has-cat-theme';
    }

    return $classes;
});

/**
 * Printeaza --cat-accent pe <body> in <head>. Restul paletei (dark/soft/border/
 * text) e derivata in CSS prin color-mix(), deci nu trebuie injectata aici.
 * Asezat in <head> ca sa fie disponibila inainte de primul paint.
 */
add_action('wp_head', function () {
    $color = current_category_theme_color();

    if ($color === null) {
        return;
    }

    printf(
        "<style id=\"cat-theme-inline\">body.has-cat-theme{--cat-accent:%s}</style>\n",
        esc_attr($color)
    );
}, 5);
