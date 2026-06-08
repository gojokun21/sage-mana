<?php

/**
 * ⚠️ FIȘIER TEMPORAR — seed mega-meniu „Suplimente” PRIN LINK. ȘTERGE-L după folosire.
 *
 * URL-uri (înlocuiește domeniul):
 *   Previzualizare:
 *     /wp-content/themes/sage-nature/seed-mega-suplimente.php?token=mn7x2k9q-megasup-seed&mode=dry
 *   Aplică tot:
 *     /wp-content/themes/sage-nature/seed-mega-suplimente.php?token=mn7x2k9q-megasup-seed&mode=force
 */

const SEED_TOKEN = 'mn7x2k9q-megasup-seed';

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

if (! function_exists('App\\register_msup_acf')) {
    require_once __DIR__.'/app/acf-mega-suplimente.php';
}
if (! function_exists('App\\seed_mega_suplimente')) {
    require_once __DIR__.'/app/mega-suplimente-seed.php';
}
if (! function_exists('App\\seed_mega_suplimente')) {
    http_response_code(500);
    exit('App\\seed_mega_suplimente() indisponibil.');
}

if (function_exists('App\\register_msup_acf')) {
    App\register_msup_acf();
}

$mode = isset($_GET['mode']) ? preg_replace('/[^a-z]/', '', (string) $_GET['mode']) : 'force';
$log = App\seed_mega_suplimente(['dry' => $mode === 'dry', 'force' => $mode === 'force']);

nocache_headers();
header('Content-Type: text/plain; charset=utf-8');
echo "Seed „Mega-meniu Suplimente” — mode: {$mode}\n";
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
echo "Gata. ȘTERGE acest fișier (seed-mega-suplimente.php) după folosire.\n";
exit;
