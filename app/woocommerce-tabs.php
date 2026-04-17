<?php

/**
 * WooCommerce custom product tabs (ported from mana-naturii).
 *
 * Adds: Utilizare, Ingrediente, Precauții — populated via ACF fields on each product.
 * Reorders: Description → Utilizare → Ingrediente → Precauții → Reviews.
 */

namespace App;

add_filter('woocommerce_product_tabs', function ($tabs) {
    $tabs['utilizare_tab'] = [
        'title'    => 'Utilizare',
        'priority' => 50,
        'callback' => __NAMESPACE__ . '\\render_utilizare_tab',
    ];

    $tabs['ingrediente_tab'] = [
        'title'    => 'Ingrediente',
        'priority' => 60,
        'callback' => __NAMESPACE__ . '\\render_ingrediente_tab',
    ];

    $tabs['precautii_tab'] = [
        'title'    => 'Precauții',
        'priority' => 70,
        'callback' => __NAMESPACE__ . '\\render_precautii_tab',
    ];

    return $tabs;
});

add_filter('woocommerce_product_tabs', function ($tabs) {
    $new_tabs = [];

    if (isset($tabs['description'])) {
        $new_tabs['description'] = $tabs['description'];
        unset($tabs['description']);
    }

    foreach (['utilizare_tab', 'ingrediente_tab', 'precautii_tab'] as $key) {
        if (isset($tabs[$key])) {
            $new_tabs[$key] = $tabs[$key];
            unset($tabs[$key]);
        }
    }

    if (isset($tabs['reviews'])) {
        $new_tabs['reviews'] = $tabs['reviews'];
        unset($tabs['reviews']);
    }

    return $new_tabs + $tabs;
}, 999);

function render_utilizare_tab(): void
{
    if (!function_exists('get_field')) {
        return;
    }

    $content = get_field('utilizare');

    if ($content) {
        echo wp_kses_post($content);
    }
}

function render_ingrediente_tab(): void
{
    if (!function_exists('get_field')) {
        return;
    }

    $content = get_field('ingrediente');
    $doza_header = '2 capsule';

    if (have_rows('informatie_generala')) {
        while (have_rows('informatie_generala')) {
            the_row();
            $doza_zilnica = get_sub_field('doza_zilnica');

            if (!empty($doza_zilnica['cantitatea']) && !empty($doza_zilnica['tipul_dozei'])) {
                $doza_header = esc_html($doza_zilnica['cantitatea']) . ' ' . esc_html($doza_zilnica['tipul_dozei']);
            }
        }
    }

    echo '<div class="ingrediente-content">';

    if (have_rows('tabel_ingrediente')) {
        while (have_rows('tabel_ingrediente')) {
            the_row();

            $cantitatea = get_sub_field('cantitatea');
            $cantitatea_tabel = !empty($cantitatea) ? $cantitatea : $doza_header;
            ?>
            <div class="ingrediente-content">
                <h3><?php echo esc_html(get_sub_field('denumirea_produsului')); ?></h3>

                <div class="table-responsive">
                    <table class="ingrediente-table">
                        <thead>
                            <tr>
                                <th>Ingredient</th>
                                <th><?php echo esc_html($cantitatea_tabel); ?><br><small>(doza zilnică recomandată)</small></th>
                                <th>% VNR*</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (have_rows('items')) : ?>
                                <?php while (have_rows('items')) : the_row(); ?>
                                    <tr class="<?php echo get_sub_field('sub_item') ? 'sub-row' : ''; ?>">
                                        <td>
                                            <?php
                                            echo get_sub_field('sub_item')
                                                ? '&nbsp;- ' . esc_html(get_sub_field('ingredient'))
                                                : esc_html(get_sub_field('ingredient'));
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo esc_html(get_sub_field('doza')); ?></td>
                                        <td class="text-center"><?php echo esc_html(get_sub_field('vnr')); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
    }

    if ($content) {
        echo wp_kses_post($content);
    }

    echo '</div>';
}

function render_precautii_tab(): void
{
    if (!function_exists('get_field')) {
        return;
    }

    $content = get_field('precauții');

    if ($content) {
        echo wp_kses_post($content);
    }
}
