<?php

/**
 * ⚠️ FIȘIER TEMPORAR — seed „Hub După simptom (index ACF)” PRIN LINK. ȘTERGE-L după folosire.
 *
 * Populează ACF-ul hub-ului /dupa-simptom/ (grupe + carduri) și leagă cardurile de
 * paginile de detaliu (comanda Acorn `natura:dupa-simptom-seed`). Rulează DUPĂ
 * `seed-simptom.php` (are nevoie ca paginile de detaliu să existe deja).
 *
 * URL-uri (înlocuiește domeniul):
 *   Previzualizare:
 *     /wp-content/themes/sage-nature/seed-dupa-simptom.php?token=mn7x2k9q-dupasimptom-seed&mode=dry
 *   Aplică (scrie ACF doar dacă e gol):
 *     /wp-content/themes/sage-nature/seed-dupa-simptom.php?token=mn7x2k9q-dupasimptom-seed&mode=run
 *   Rescrie ACF-ul (inclusiv editări din admin):
 *     /wp-content/themes/sage-nature/seed-dupa-simptom.php?token=mn7x2k9q-dupasimptom-seed&mode=force
 *
 * După ce ai rulat (local + live): ȘTERGE acest fișier.
 */

const SEED_TOKEN = 'mn7x2k9q-dupasimptom-seed';
const SEED_COMMAND = 'natura:dupa-simptom-seed';
const SEED_FLUSH_REWRITE = false; // nu mută pagini, doar scrie ACF

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

echo "ȘTERGE acest fișier (seed-dupa-simptom.php) după folosire.\n";
exit;
