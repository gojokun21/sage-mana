<?php

namespace App;

/**
 * „Date personale" (endpoint edit-account) — persistă câmpurile suplimentare din
 * formularul reskin care NU sunt native în WC `save_account_details`:
 *   - telefon → billing_phone (sursa de adevăr WC, folosită la checkout/comenzi)
 *   - data nașterii + gen → user meta (mn_birthday / mn_gender)
 * Numele, emailul și parola rămân gestionate nativ de WooCommerce.
 *
 * @see resources/views/woocommerce/myaccount/form-edit-account.blade.php
 */
defined('ABSPATH') || exit;

/**
 * Genuri permise (slug => folosit la validare). Etichetele se afișează în Blade.
 */
function mn_account_genders(): array
{
    return ['feminin', 'masculin', 'nespecificat'];
}

add_action('woocommerce_save_account_details', function ($user_id) {
    // Telefon → billing_phone (WC îl citește la checkout și pe comenzi).
    if (isset($_POST['account_phone'])) {
        $phone = wc_clean(wp_unslash($_POST['account_phone']));
        $customer = new \WC_Customer($user_id);
        $customer->set_billing_phone($phone);
        $customer->save();
    }

    // Data nașterii (opțional) → user meta. Acceptă gol sau YYYY-MM-DD.
    if (isset($_POST['account_bday'])) {
        $bday = sanitize_text_field(wp_unslash($_POST['account_bday']));
        if ($bday === '' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $bday)) {
            update_user_meta($user_id, 'mn_birthday', $bday);

            // Oglindește în meta-urile plugin-ului mn-loyalty ca bonusul „zi de
            // naștere" să funcționeze din acest unic câmp nativ (câmpul duplicat
            // injectat de plugin e dezactivat mai jos). `_mn_loyalty_bday_md` =
            // m-d (folosit de cron-ul de zi de naștere).
            if ($bday === '') {
                delete_user_meta($user_id, '_mn_loyalty_birthday');
                delete_user_meta($user_id, '_mn_loyalty_bday_md');
            } else {
                update_user_meta($user_id, '_mn_loyalty_birthday', $bday);
                update_user_meta($user_id, '_mn_loyalty_bday_md', substr($bday, 5));
            }
        }
    }

    // Gen (opțional) → user meta, doar din lista permisă.
    if (isset($_POST['account_gender'])) {
        $gender = sanitize_text_field(wp_unslash($_POST['account_gender']));
        update_user_meta($user_id, 'mn_gender', in_array($gender, mn_account_genders(), true) ? $gender : '');
    }
}, 10, 1);

/**
 * Dezactivează câmpul duplicat „Zi de naștere (pentru bonus Constant)" pe care
 * plugin-ul mn-loyalty îl injectează în formularul de cont prin
 * `woocommerce_edit_account_form`. Era nestilizat (clase WC default, nu modelul
 * `.field-row` din reskin) și dubla câmpul nativ „Data nașterii". Bonusul de zi
 * de naștere folosește acum câmpul nativ (vezi oglindirea în `_mn_loyalty_bday_md`
 * de mai sus). Rulează pe `wp_loaded`, după ce plugin-ul și-a înregistrat hook-ul.
 */
add_action('wp_loaded', function () {
    if (class_exists('MN_Loyalty_Earning')) {
        remove_action('woocommerce_edit_account_form', ['MN_Loyalty_Earning', 'render_birthday_field']);
    }
});
