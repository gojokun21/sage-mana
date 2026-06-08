<?php

/**
 * ⚠️ FIȘIER TEMPORAR — seed „Cele mai vândute” PRIN LINK. ȘTERGE-L după folosire.
 *
 * Bootstrap-ează WordPress și rulează seed-ul direct, protejat de un token în URL.
 * Nu depinde de loader-ul din functions.php (merge chiar dacă opcache e vechi).
 *
 * URL-uri (înlocuiește domeniul):
 *   Previzualizare:
 *     /wp-content/themes/sage-nature/seed-bestseller.php?token=mn7x2k9q-bestseller-seed&mode=dry
 *   Aplică tot (creează pagina + ACF + leagă produsele reale):
 *     /wp-content/themes/sage-nature/seed-bestseller.php?token=mn7x2k9q-bestseller-seed&mode=force
 *
 * După ce ai rulat (local + live): ȘTERGE acest fișier.
 */

const SEED_TOKEN = 'mn7x2k9q-bestseller-seed';

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

// 3. Încarcă logica + grupul ACF (chiar dacă functions.php e stale/opcache).
if (! function_exists('App\\register_bestseller_acf')) {
    require_once __DIR__.'/app/acf-bestseller.php';
}
if (! function_exists('App\\seed_bestseller')) {
    require_once __DIR__.'/app/bestseller-seed.php';
}
if (! function_exists('App\\seed_bestseller')) {
    http_response_code(500);
    exit('App\\seed_bestseller() indisponibil — verifică app/bestseller-seed.php.');
}

// Asigură grupul ACF înregistrat ACUM (altfel repeaterele nu se scriu corect).
if (function_exists('App\\register_bestseller_acf')) {
    App\register_bestseller_acf();
}

// 4. Rulează.
$mode = isset($_GET['mode']) ? preg_replace('/[^a-z]/', '', (string) $_GET['mode']) : 'force';
$log = App\seed_bestseller([
    'dry' => $mode === 'dry',
    'force' => $mode === 'force',
]);

// 5. Afișează jurnalul.
nocache_headers();
header('Content-Type: text/plain; charset=utf-8');
echo "Seed „Cele mai vândute” — mode: {$mode}\n";
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
echo "Gata. ȘTERGE acest fișier (seed-bestseller.php) după folosire.\n";
exit;
