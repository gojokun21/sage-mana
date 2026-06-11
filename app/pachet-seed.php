<?php

/**
 * Seed pentru conținutul editorial al paginilor de PACHET — logică partajată + UI în admin.
 *
 * Datele vin din database/seeds/pachet-products.php (transcrise din mockup-urile
 * `preferinte/Pagina Pachet - *.html`), câmpurile ACF din app/acf-pachet.php.
 *
 * Aceeași logică e folosită de:
 *  - comanda CLI App\Console\Commands\PachetSeed (`wp acorn natura:pachet-seed`)
 *  - pagina din admin „Unelte → Seed pagini pachet” (buton protejat prin nonce)
 *
 * Fără --force: pachetele care au deja `pk_why_titlu` completat sunt sărite,
 * iar post_excerpt e scris doar dacă e gol. Cu --force se rescrie tot.
 */

namespace App;

/**
 * Rulează seed-ul. Returnează un jurnal: listă de ['level' => info|warn|error|line, 'msg' => ...].
 *
 * @param  array{force?:bool, dry?:bool}  $opts
 * @return array<int, array{level:string, msg:string}>
 */
function seed_pachet(array $opts = []): array
{
    $force = ! empty($opts['force']);
    $dry = ! empty($opts['dry']);

    $log = [];
    $push = static function (string $level, string $msg) use (&$log): void {
        $log[] = ['level' => $level, 'msg' => $msg];
    };

    foreach (['get_page_by_path', 'update_field', 'get_field'] as $fn) {
        if (! function_exists($fn)) {
            $push('error', "Funcția {$fn}() lipsește (ACF activ?).");

            return $log;
        }
    }

    $seedFile = get_theme_file_path('database/seeds/pachet-products.php');
    if (! is_file($seedFile)) {
        $push('error', "Fișierul de seed lipsește: {$seedFile}");

        return $log;
    }

    $data = require $seedFile;
    if (! is_array($data) || empty($data)) {
        $push('error', 'Seed-ul nu a returnat un array valid.');

        return $log;
    }

    foreach ($data as $slug => $vals) {
        $wc = get_page_by_path((string) $slug, OBJECT, 'product');
        if (! $wc instanceof \WP_Post) {
            $push('warn', "Pachet negăsit: {$slug}");

            continue;
        }
        $pid = (int) $wc->ID;

        $already = (string) get_field('pk_why_titlu', $pid) !== '';

        if ($dry) {
            $push('line', sprintf('%s (ID %d): %s', $slug, $pid, $already
                ? ($force ? 'are conținut → ar fi rescris (force)' : 'are conținut → ar fi sărit')
                : 'ar fi scris'));

            continue;
        }

        if ($already && ! $force) {
            $push('line', "{$slug}: are deja conținut (neschimbat — folosește force pentru rescriere)");

            continue;
        }

        $n = pachet_write_fields($pid, $vals);

        $excerptMsg = pachet_write_excerpt($pid, (string) ($vals['excerpt'] ?? ''), $force);

        $push('info', "{$slug} (ID {$pid}): ACF scris ({$n} câmpuri){$excerptMsg}");
    }

    $push('info', $dry ? 'Dry-run complet. Nu s-a scris nimic.' : 'Gata.');

    return $log;
}

/**
 * Scrie toate câmpurile ACF editoriale ale pachetului (pe chei stabile).
 * Returnează numărul de câmpuri.
 */
function pachet_write_fields(int $pid, array $p): int
{
    $why = $p['why'] ?? [];
    $benefits = $p['benefits'] ?? [];
    $tl = $p['tl'] ?? [];
    $pcine = $p['pcine'] ?? [];
    $faq = $p['faq'] ?? [];

    $fields = [
        'field_pk_eyebrow' => $p['pk_eyebrow'] ?? '',
        'field_pk_tagline' => $p['pk_tagline'] ?? '',

        'field_pk_why_kicker' => $why['kicker'] ?? '',
        'field_pk_why_titlu' => $why['titlu'] ?? '',
        'field_pk_why_prose' => array_map(static fn ($t) => ['text' => $t], $why['prose'] ?? []),
        'field_pk_why_cards' => array_map(static fn ($c) => [
            'rol' => $c['rol'] ?? '',
            'titlu' => $c['titlu'] ?? '',
            'text' => $c['text'] ?? '',
        ], $why['cards'] ?? []),

        'field_pk_benefits_titlu' => $benefits['titlu'] ?? '',
        'field_pk_benefits_items' => array_map(static fn ($t) => ['text' => $t], $benefits['items'] ?? []),

        'field_pk_tl_titlu' => $tl['titlu'] ?? '',
        'field_pk_tl_steps' => array_map(static fn ($s) => [
            'when' => $s['when'] ?? '',
            'titlu' => $s['titlu'] ?? '',
            'text' => $s['text'] ?? '',
        ], $tl['steps'] ?? []),

        'field_pk_pcine_da' => array_map(static fn ($t) => ['text' => $t], $pcine['da'] ?? []),
        'field_pk_pcine_nu' => array_map(static fn ($t) => ['text' => $t], $pcine['nu'] ?? []),

        'field_pk_faq_items' => array_map(static fn ($f) => [
            'intrebare' => $f['q'] ?? '',
            'raspuns' => $f['a'] ?? '',
        ], $faq),
    ];

    foreach ($fields as $key => $value) {
        update_field($key, $value, $pid);
    }

    return count($fields);
}

/**
 * Scrie post_excerpt (descrierea din hero). Fără force scrie doar dacă
 * excerpt-ul e gol. Returnează un sufix de mesaj pentru jurnal.
 */
function pachet_write_excerpt(int $pid, string $excerpt, bool $force): string
{
    if ($excerpt === '') {
        return '';
    }

    $current = (string) get_post_field('post_excerpt', $pid);
    if ($current !== '' && ! $force) {
        return ', excerpt păstrat (există deja)';
    }

    wp_update_post([
        'ID' => $pid,
        'post_excerpt' => $excerpt,
    ]);

    return ', excerpt scris';
}

/* -------------------------------------------------------------------------
 * UI în admin — Unelte → Seed pagini pachet
 * ---------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_management_page(
        __('Seed pagini pachet', 'sage'),
        __('Seed pagini pachet', 'sage'),
        'manage_options',
        'natura-pachet-seed',
        'App\\render_pachet_seed_page'
    );
});

function render_pachet_seed_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Acces interzis.', 'sage'));
    }

    $log = [];
    $ran = false;

    if (isset($_POST['natura_pachet_mode']) && check_admin_referer('natura_pachet_seed')) {
        $mode = sanitize_text_field(wp_unslash($_POST['natura_pachet_mode']));
        $log = seed_pachet([
            'dry' => $mode === 'dry',
            'force' => $mode === 'force',
        ]);
        $ran = true;
    }

    $colors = ['error' => '#b32d2e', 'warn' => '#bd8600', 'info' => '#1a7a3c', 'line' => '#50575e'];

    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Seed conținut pagini pachet', 'sage').'</h1>';
    echo '<p style="max-width:720px">'.esc_html__('Populează secțiunile editoriale ale paginilor de pachet (eyebrow, tagline, descriere, cum lucrează împreună, beneficii, cum se folosește, pentru cine, FAQ) cu textele din mockup-urile „Pagina Pachet". Idempotent — rulează de câte ori vrei.', 'sage').'</p>';

    echo '<form method="post" style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">';
    wp_nonce_field('natura_pachet_seed');
    echo '<button type="submit" name="natura_pachet_mode" value="dry" class="button button-secondary">'.esc_html__('Previzualizare (dry-run)', 'sage').'</button>';
    echo '<button type="submit" name="natura_pachet_mode" value="run" class="button button-primary">'.esc_html__('Rulează (scrie ce lipsește)', 'sage').'</button>';
    echo '<button type="submit" name="natura_pachet_mode" value="force" class="button button-primary" onclick="return confirm(\''.esc_attr__('Rescrie conținutul (ACF + descriere) pe toate pachetele din seed. Continui?', 'sage').'\')">'.esc_html__('Rescrie tot (force)', 'sage').'</button>';
    echo '</form>';

    if ($ran) {
        echo '<h2>'.esc_html__('Rezultat', 'sage').'</h2>';
        echo '<div style="background:#1e1e1e;color:#e6e6e6;border-radius:8px;padding:14px 18px;font-family:Menlo,Consolas,monospace;font-size:13px;line-height:1.7;max-width:920px">';
        foreach ($log as $entry) {
            $c = $colors[$entry['level']] ?? '#e6e6e6';
            $prefix = $entry['level'] === 'error' ? '✕ ' : ($entry['level'] === 'warn' ? '! ' : ($entry['level'] === 'info' ? '✓ ' : '· '));
            echo '<div style="color:'.esc_attr($c).'">'.esc_html($prefix.$entry['msg']).'</div>';
        }
        echo '</div>';
    }

    echo '</div>';
}
