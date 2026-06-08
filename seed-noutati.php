<?php

/**
 * ⚠️ FIȘIER TEMPORAR — seed „Noutăți · În curând” PRIN LINK. ȘTERGE-L după folosire.
 *
 * Bootstrap-ează WordPress și rulează seed-ul direct, protejat de un token în URL.
 * Nu depinde de loader-ul din functions.php (merge chiar dacă opcache e vechi).
 *
 * URL-uri (înlocuiește domeniul):
 *   Previzualizare:
 *     /wp-content/themes/sage-nature/seed-noutati.php?token=mn7x2k9q-noutati-seed&mode=dry
 *   Aplică tot:
 *     /wp-content/themes/sage-nature/seed-noutati.php?token=mn7x2k9q-noutati-seed&mode=force
 *
 * După ce ai rulat (local + live): ȘTERGE acest fișier.
 */

const SEED_TOKEN = 'mn7x2k9q-noutati-seed';

$wp_load = dirname(__FILE__, 4).'/wp-load.php';
if (! is_file($wp_load)) {
    http_response_code(500);
    exit('wp-load.php negăsit: '.$wp_load);
}
require_once $wp_load;

$token = isset($_GET['token']) ? (string) $_GET['token'] : '';
if (! hash_equals(SEED_TOKEN, $token)) {
    http_response_code(403);
    exit('Token invalid.');
}

if (! function_exists('App\\register_noutati_acf')) {
    require_once __DIR__.'/app/acf-noutati.php';
}
if (! function_exists('App\\seed_noutati')) {
    require_once __DIR__.'/app/noutati-seed.php';
}
if (! function_exists('App\\seed_noutati')) {
    http_response_code(500);
    exit('App\\seed_noutati() indisponibil — verifică app/noutati-seed.php.');
}

// Asigură grupul ACF înregistrat ACUM (altfel repeaterele nu se scriu corect).
if (function_exists('App\\register_noutati_acf')) {
    App\register_noutati_acf();
}

$mode = isset($_GET['mode']) ? preg_replace('/[^a-z]/', '', (string) $_GET['mode']) : 'force';
$log = App\seed_noutati(['dry' => $mode === 'dry', 'force' => $mode === 'force']);

nocache_headers();
header('Content-Type: text/plain; charset=utf-8');
echo "Seed „Noutăți · În curând” — mode: {$mode}\n";
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
echo "Gata. ȘTERGE acest fișier (seed-noutati.php) după folosire.\n";
exit;
