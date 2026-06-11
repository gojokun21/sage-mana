<?php

/**
 * ⚠️ FIȘIER TEMPORAR — seed „După simptom (pagini detaliu)” PRIN LINK. ȘTERGE-L după folosire.
 *
 * Creează paginile de simptom și le mută sub /dupa-simptom/<slug>/ (comanda Acorn
 * `natura:simptom-seed`). Bootstrap-ează WordPress și rulează prin kernel-ul de
 * consolă, protejat de un token în URL.
 *
 * URL-uri (înlocuiește domeniul):
 *   Previzualizare:
 *     /wp-content/themes/sage-nature/seed-simptom.php?token=mn7x2k9q-simptom-seed&mode=dry
 *   Aplică (creează + mută sub hub, păstrează ACF existent):
 *     /wp-content/themes/sage-nature/seed-simptom.php?token=mn7x2k9q-simptom-seed&mode=run
 *   Rescrie tot ACF-ul:
 *     /wp-content/themes/sage-nature/seed-simptom.php?token=mn7x2k9q-simptom-seed&mode=force
 *
 * După ce ai rulat (local + live): ȘTERGE acest fișier.
 */

const SEED_TOKEN = 'mn7x2k9q-simptom-seed';
const SEED_COMMAND = 'natura:simptom-seed';
const SEED_FLUSH_REWRITE = true; // mutarea paginilor schimbă ierarhia paginilor

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

// 3. Mod → opțiuni comandă: dry=--dry-run, force=--force, run=fără flag.
$mode = isset($_GET['mode']) ? preg_replace('/[^a-z]/', '', (string) $_GET['mode']) : 'dry';
$params = [];
if ($mode === 'dry') {
    $params['--dry-run'] = true;
} elseif ($mode === 'force') {
    $params['--force'] = true;
}

nocache_headers();
header('Content-Type: text/plain; charset=utf-8');
echo 'Seed '.SEED_COMMAND." — mode: {$mode}\n".str_repeat('=', 52)."\n";

// 4. Rulează comanda Acorn prin kernel-ul de consolă (Acorn e booted de functions.php).
try {
    $app = \Roots\Acorn\Application::getInstance();
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $exit = $kernel->call(SEED_COMMAND, $params);
    echo $kernel->output();
    echo str_repeat('=', 52)."\n";

    if (SEED_FLUSH_REWRITE && $mode !== 'dry') {
        flush_rewrite_rules();
        echo "flush_rewrite_rules() executat.\n";
    }
    echo $exit === 0 ? "OK (exit 0).\n" : "Eroare (exit {$exit}).\n";
} catch (\Throwable $e) {
    http_response_code(500);
    echo 'EROARE: '.$e->getMessage()."\n";
}

echo "ȘTERGE acest fișier (seed-simptom.php) după folosire.\n";
exit;
