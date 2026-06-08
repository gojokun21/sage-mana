<?php

/**
 * ⚠️ FIȘIER TEMPORAR — seed „Sub 200 lei” PRIN LINK. ȘTERGE-L după folosire.
 *
 * Bootstrap-ează WordPress și rulează seed-ul direct, fără login, protejat de un
 * token în URL. Nu depinde de loader-ul din functions.php (deci merge chiar dacă
 * opcache servește o versiune veche a functions.php).
 *
 * URL-uri (înlocuiește domeniul):
 *   Previzualizare (nu scrie nimic):
 *     /wp-content/themes/sage-nature/seed-sub200.php?token=mn7x2k9q-sub200-seed&mode=dry
 *   Aplică tot (creează pagina + ACF + corectează produsele):
 *     /wp-content/themes/sage-nature/seed-sub200.php?token=mn7x2k9q-sub200-seed&mode=force
 *   Doar pagina (fără produse):
 *     /wp-content/themes/sage-nature/seed-sub200.php?token=mn7x2k9q-sub200-seed&mode=page
 *
 * După ce ai rulat pe local ȘI pe live: ȘTERGE acest fișier. (Opțional poți șterge
 * și app/sub200-seed.php + intrarea „sub200-seed” din functions.php dacă nu mai
 * vrei pagina din Unelte / comanda wp-cli.)
 */

// Schimbă token-ul dacă vrei; trebuie să fie identic în URL.
const SEED_TOKEN = 'mn7x2k9q-sub200-seed';

// 1. Bootstrap WordPress (wp-load.php e în rădăcina publică, 4 niveluri mai sus).
$wp_load = dirname(__FILE__, 4).'/wp-load.php';
if (! is_file($wp_load)) {
    http_response_code(500);
    exit('wp-load.php negăsit: '.$wp_load);
}
require_once $wp_load;

// 2. Verifică token-ul.
$token = isset($_GET['token']) ? (string) $_GET['token'] : '';
if (! hash_equals(SEED_TOKEN, $token)) {
    http_response_code(403);
    exit('Token invalid.');
}

// 3. Încarcă logica de seed + grupul ACF (chiar dacă functions.php e stale/opcache).
if (! function_exists('App\\register_sub200_acf')) {
    require_once __DIR__.'/app/acf-sub200.php';
}
if (! function_exists('App\\seed_sub200')) {
    require_once __DIR__.'/app/sub200-seed.php';
}
if (! function_exists('App\\seed_sub200')) {
    http_response_code(500);
    exit('App\\seed_sub200() indisponibil — verifică app/sub200-seed.php.');
}

// Asigură grupul ACF înregistrat ACUM (acf/init a trecut deja) — altfel ACF nu
// poate scrie corect repeaterele (explain_cards, faq_items).
if (function_exists('App\\register_sub200_acf')) {
    App\register_sub200_acf();
}

// 4. Rulează.
$mode = isset($_GET['mode']) ? preg_replace('/[^a-z]/', '', (string) $_GET['mode']) : 'force';
$log = App\seed_sub200([
    'dry' => $mode === 'dry',
    'force' => in_array($mode, ['force', 'page'], true),
    'skip_products' => $mode === 'page',
]);

// 5. Afișează jurnalul ca text simplu.
nocache_headers();
header('Content-Type: text/plain; charset=utf-8');
echo "Seed „Sub 200 lei” — mode: {$mode}\n";
echo str_repeat('=', 52)."\n";
foreach ($log as $entry) {
    $prefix = match ($entry['level']) {
        'error' => '[EROARE]  ',
        'warn' => '[ATENTIE] ',
        'info' => '[OK]      ',
        default => '          ',
    };
    echo $prefix.$entry['msg']."\n";
}
echo str_repeat('=', 52)."\n";
echo "Gata. ȘTERGE acest fișier (seed-sub200.php) după folosire.\n";
exit;
