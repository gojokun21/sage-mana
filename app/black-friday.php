<?php

/**
 * Black Friday — reducere temporară pe pachete (produse de tip `bundle`).
 *
 * Aplică dinamic o reducere de BF_PERCENT% pe TOATE pachetele care NU au deja
 * o reducere proprie. Pachetele aflate deja la reducere (au sale price propriu
 * sau preț activ sub prețul întreg, ex. componente la ofertă) rămân neatinse —
 * NU se cumulează cei 10%.
 *
 * 100% dinamic, prin filtre WooCommerce, fără nicio modificare în baza de date:
 *   - se aprinde singur peste tot: card listare, pagină produs, coș, checkout;
 *   - se OPREȘTE complet fie punând BF_ENABLED pe `false`, fie ștergând acest
 *     fișier ȘI scoțând 'black-friday' din lista din functions.php. Prețurile
 *     revin instant la normal.
 *
 * Comenzile deja plasate NU sunt afectate (au totalurile lor deja salvate).
 *
 * Pentru oprire mai târziu: vezi BF_ENABLED mai jos.
 */

namespace App;

/** Pune pe `false` ca să oprești reducerea fără a șterge fișierul. */
const BF_ENABLED = true;

/** Procentul de reducere de Black Friday. */
const BF_PERCENT = 10;

/**
 * Prețul redus de Black Friday pentru un pachet, sau `null` dacă nu se aplică.
 *
 * Citește prețurile în context 'edit' (valori brute din DB, fără filtre) ca să
 * evite recursivitatea cu propriile noastre filtre și interferența altor
 * plugin-uri care ar filtra prețul în context 'view'.
 *
 * @param  mixed  $product
 */
function bf_bundle_sale_price($product): ?float
{
    if (! BF_ENABLED) {
        return null;
    }

    // În ecranele de admin (listă/editare produs) lăsăm prețurile reale, ca să
    // nu deruteze. `is_admin() && ! wp_doing_ajax()` exclude adminul, dar
    // PĂSTREAZĂ AJAX-ul de frontend (add-to-cart rulează prin admin-ajax.php).
    if (is_admin() && ! wp_doing_ajax()) {
        return null;
    }

    if (! $product instanceof \WC_Product || ! $product->is_type('bundle')) {
        return null;
    }

    $regular = (float) $product->get_regular_price('edit');
    if ($regular <= 0) {
        return null;
    }

    // Are deja un sale price propriu setat? → nu aplicăm 10% peste.
    if ((string) $product->get_sale_price('edit') !== '') {
        return null;
    }

    // Pachet cu preț calculat din componente, deja sub prețul întreg
    // (componente aflate la reducere)? → e deja redus, îl lăsăm neatins.
    $active = (float) $product->get_price('edit');
    if ($active > 0 && $active < $regular - 0.00001) {
        return null;
    }

    return round($regular * (1 - BF_PERCENT / 100), wc_get_price_decimals());
}

/**
 * Suprascrie sale price-ul. Declanșează automat `is_on_sale()`, badge-ul
 * "-10%" și prețul tăiat din temă (content-product / single / sticky bar).
 */
add_filter('woocommerce_product_get_sale_price', function ($sale_price, $product) {
    $bf = bf_bundle_sale_price($product);

    return $bf === null ? $sale_price : $bf;
}, 99, 2);

/**
 * Suprascrie prețul activ. Folosit la calculul totalurilor în coș și checkout
 * (containerul pachetului ține tot prețul la bundle-urile cu preț static).
 */
add_filter('woocommerce_product_get_price', function ($price, $product) {
    $bf = bf_bundle_sale_price($product);

    return $bf === null ? $price : $bf;
}, 99, 2);
