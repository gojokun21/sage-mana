<?php

/**
 * Seed pentru conținutul editorial PDP (produse) — logică partajată + UI în admin.
 *
 * Datele vin din database/seeds/pdp-products.php (transcrise din mockup-urile
 * `preferinte/PDP - *.html`), câmpurile ACF din app/acf-pdp.php.
 *
 * Aceeași logică e folosită de:
 *  - comanda CLI App\Console\Commands\PdpSeed (`wp acorn natura:pdp-seed`)
 *  - pagina din admin „Unelte → Seed PDP produse” (buton protejat prin nonce)
 *
 * Fără --force: produsele care au deja `ihl_titlu` completat sunt sărite, iar
 * post_excerpt e scris doar dacă e gol. Cu --force se rescrie tot (inclusiv
 * excerpt-ul existent).
 */

namespace App;

/**
 * Rulează seed-ul. Returnează un jurnal: listă de ['level' => info|warn|error|line, 'msg' => ...].
 *
 * @param  array{force?:bool, dry?:bool}  $opts
 * @return array<int, array{level:string, msg:string}>
 */
function seed_pdp(array $opts = []): array
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

    $seedFile = get_theme_file_path('database/seeds/pdp-products.php');
    if (! is_file($seedFile)) {
        $push('error', "Fișierul de seed lipsește: {$seedFile}");

        return $log;
    }

    $data = require $seedFile;
    if (! is_array($data) || empty($data['products'])) {
        $push('error', 'Seed-ul nu a returnat un array valid.');

        return $log;
    }

    foreach ($data['products'] as $slug => $vals) {
        $wc = get_page_by_path((string) $slug, OBJECT, 'product');
        if (! $wc instanceof \WP_Post) {
            $push('warn', "Produs negăsit: {$slug}");

            continue;
        }
        $pid = (int) $wc->ID;

        $already = (string) get_field('ihl_titlu', $pid) !== '';

        if ($dry) {
            $push('line', sprintf('%s (ID %d): %s', $slug, $pid, $already
                ? ($force ? 'are conținut → ar fi rescris (force)' : 'are conținut → ar fi sărit')
                : 'ar fi scris'));

            continue;
        }

        if ($already && ! $force) {
            $push('line', "{$slug}: are deja conținut PDP (neschimbat — folosește force pentru rescriere)");

            continue;
        }

        $n = pdp_write_product_fields($pid, $vals);

        $excerptMsg = pdp_write_excerpt($pid, (string) ($vals['excerpt'] ?? ''), $force);

        $push('info', "{$slug} (ID {$pid}): ACF scris ({$n} câmpuri){$excerptMsg}");
    }

    pdp_seed_info($data['informatie_generala'] ?? [], $dry, $force, $push);

    $push('info', $dry ? 'Dry-run complet. Nu s-a scris nimic.' : 'Gata.');

    return $log;
}

/**
 * Se asigură că grupul `informatie_generala` (definit în DB, grup „Single
 * Product") are subcâmpul `forma`. Creat prin acf_update_field() — adică în DB,
 * exact ca din admin. NU prin acf_add_local_field(): un subcâmp local cu
 * părinte din DB face ca ACF să ignore subcâmpurile din DB.
 */
function pdp_ensure_forma_field(callable $push, bool $dry): bool
{
    // Interogări brute pe posturile acf-field — NU acf_get_field(), ca să nu
    // punem în cache definiția grupului fără `forma` înainte de a-l crea
    // (update_field-urile din același request ar pierde subcâmpul).
    $group = get_posts([
        'post_type' => 'acf-field',
        'post_status' => 'publish',
        'name' => 'field_6951a285c0158', // informatie_generala (group)
        'numberposts' => 1,
    ]);
    if (empty($group)) {
        $push('error', 'Grupul ACF `informatie_generala` nu a fost găsit.');

        return false;
    }
    $groupId = (int) $group[0]->ID;

    $subs = get_posts([
        'post_type' => 'acf-field',
        'post_status' => 'publish',
        'post_parent' => $groupId,
        'numberposts' => -1,
    ]);
    foreach ($subs as $sub) {
        if ($sub->post_excerpt === 'forma') {
            return true; // există deja
        }
    }

    if ($dry) {
        $push('line', 'Subcâmpul `forma` lipsește din informatie_generala → ar fi creat.');

        return false;
    }

    acf_update_field([
        'key' => 'field_mn_info_forma',
        'label' => 'Formă / ambalaj (ex. „1000 g · 33 porții", „240 capsule")',
        'name' => 'forma',
        'type' => 'text',
        'parent' => $groupId,
    ]);

    $push('info', 'Subcâmp `forma` creat în grupul informatie_generala.');

    return true;
}

/**
 * Corectează `informatie_generala` (forma + protocol_zile NUMERIC) pe produse,
 * fără să atingă restul subcâmpurilor grupului (read-merge-write, ca în
 * sub200_seed_products). protocol_zile numeric → cost/zi corect în coș,
 * template-sub-200 și template-cele-mai-vandute.
 */
function pdp_seed_info(array $map, bool $dry, bool $force, callable $push): void
{
    if (empty($map)) {
        return;
    }

    pdp_ensure_forma_field($push, $dry);

    $push('line', 'Produse (informatie_generala — forma + protocol_zile):');

    foreach ($map as $slug => $vals) {
        $wc = get_page_by_path((string) $slug, OBJECT, 'product');
        if (! $wc instanceof \WP_Post) {
            $push('warn', "Produs negăsit: {$slug}");

            continue;
        }
        $pid = (int) $wc->ID;

        $info = get_field('informatie_generala', $pid);
        $info = is_array($info) ? $info : [];

        $current_raw = isset($info['protocol_zile']) ? trim((string) $info['protocol_zile']) : '';
        $current_forma = isset($info['forma']) ? trim((string) $info['forma']) : '';
        $target_days = (int) ($vals['protocol_zile'] ?? 0);
        $target_forma = trim((string) ($vals['forma'] ?? ''));

        if ($dry) {
            $push('line', sprintf('%s: protocol_zile „%s” → %d · forma „%s” → „%s”', $slug, $current_raw ?: '∅', $target_days, $current_forma ?: '∅', $target_forma ?: '∅'));

            continue;
        }

        if (! $force && $current_raw === (string) $target_days && $current_forma === $target_forma) {
            $push('line', "{$slug}: deja corect (neschimbat)");

            continue;
        }

        $info['protocol_zile'] = $target_days;
        $info['forma'] = $target_forma;
        update_field('informatie_generala', $info, $pid);

        $push('info', sprintf('%s: protocol_zile = %d, forma = „%s”', $slug, $target_days, $target_forma));
    }
}

/**
 * Scrie toate câmpurile ACF editoriale ale produsului (pe chei stabile).
 * Returnează numărul de câmpuri.
 */
function pdp_write_product_fields(int $pid, array $p): int
{
    $ihl = $p['ihl'] ?? [];
    $how = $p['how'] ?? [];
    $pcine = $p['pcine'] ?? [];
    $stand = $p['stand'] ?? [];
    $faq = $p['faq'] ?? [];

    $fields = [
        'field_pdp_eyebrow' => $p['pdp_eyebrow'] ?? '',
        'field_pdp_subline' => $p['pdp_subline'] ?? '',

        'field_pdp_ihl_eyebrow' => $ihl['eyebrow'] ?? '',
        'field_pdp_ihl_titlu' => $ihl['titlu'] ?? '',
        'field_pdp_ihl_caption' => $ihl['caption'] ?? '',
        'field_pdp_ihl_prose' => array_map(static fn ($t) => ['text' => $t], $ihl['prose'] ?? []),
        'field_pdp_ihl_rows' => array_map(static fn ($r) => [
            'lbl' => $r['lbl'] ?? '',
            'val' => $r['val'] ?? '',
        ], $ihl['rows'] ?? []),

        'field_pdp_how_eyebrow' => $how['eyebrow'] ?? '',
        'field_pdp_how_intro' => $how['intro'] ?? '',
        'field_pdp_how_steps' => array_map(static fn ($s) => [
            'when' => $s['when'] ?? '',
            'titlu' => $s['titlu'] ?? '',
            'text' => $s['text'] ?? '',
        ], $how['steps'] ?? []),

        'field_pdp_pcine_da' => array_map(static fn ($t) => ['text' => $t], $pcine['da'] ?? []),
        'field_pdp_pcine_nu' => array_map(static fn ($t) => ['text' => $t], $pcine['nu'] ?? []),

        'field_pdp_stand_cards' => array_map(static fn ($c) => [
            'titlu' => $c['titlu'] ?? '',
            'text' => $c['text'] ?? '',
        ], $stand),

        'field_pdp_faq_nume' => $faq['nume'] ?? '',
        'field_pdp_faq_items' => array_map(static fn ($f) => [
            'intrebare' => $f['q'] ?? '',
            'raspuns' => $f['a'] ?? '',
        ], $faq['items'] ?? []),
    ];

    foreach ($fields as $key => $value) {
        update_field($key, $value, $pid);
    }

    return count($fields);
}

/**
 * Scrie post_excerpt (descrierea scurtă din hero). Fără force scrie doar dacă
 * excerpt-ul e gol. Returnează un sufix de mesaj pentru jurnal.
 */
function pdp_write_excerpt(int $pid, string $excerpt, bool $force): string
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
 * UI în admin — Unelte → Seed PDP produse
 * ---------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_management_page(
        __('Seed PDP produse', 'sage'),
        __('Seed PDP produse', 'sage'),
        'manage_options',
        'natura-pdp-seed',
        'App\\render_pdp_seed_page'
    );
});

function render_pdp_seed_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Acces interzis.', 'sage'));
    }

    $log = [];
    $ran = false;

    if (isset($_POST['natura_pdp_mode']) && check_admin_referer('natura_pdp_seed')) {
        $mode = sanitize_text_field(wp_unslash($_POST['natura_pdp_mode']));
        $log = seed_pdp([
            'dry' => $mode === 'dry',
            'force' => $mode === 'force',
        ]);
        $ran = true;
    }

    $colors = ['error' => '#b32d2e', 'warn' => '#bd8600', 'info' => '#1a7a3c', 'line' => '#50575e'];

    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Seed conținut PDP produse', 'sage').'</h1>';
    echo '<p style="max-width:720px">'.esc_html__('Populează secțiunile editoriale ale paginilor de produs (eyebrow, subline, descriere scurtă, ingredient cheie, mod de folosire, pentru cine, standarde, FAQ) cu textele din mockup-urile PDP. Idempotent — rulează de câte ori vrei.', 'sage').'</p>';

    echo '<form method="post" style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">';
    wp_nonce_field('natura_pdp_seed');
    echo '<button type="submit" name="natura_pdp_mode" value="dry" class="button button-secondary">'.esc_html__('Previzualizare (dry-run)', 'sage').'</button>';
    echo '<button type="submit" name="natura_pdp_mode" value="run" class="button button-primary">'.esc_html__('Rulează (scrie ce lipsește)', 'sage').'</button>';
    echo '<button type="submit" name="natura_pdp_mode" value="force" class="button button-primary" onclick="return confirm(\''.esc_attr__('Rescrie conținutul PDP (ACF + descriere scurtă) pe toate produsele din seed. Continui?', 'sage').'\')">'.esc_html__('Rescrie tot (force)', 'sage').'</button>';
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
