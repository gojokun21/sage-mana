<?php

/**
 * Seed pentru pagina „Suplimente sub 200 lei” — logică partajată + UI în admin.
 *
 * Aceeași logică e folosită de:
 *  - comanda CLI App\Console\Commands\Sub200Seed (`wp acorn natura:sub200-seed`)
 *  - pagina din admin „Unelte → Seed «Sub 200 lei»” (buton protejat prin nonce)
 *
 * Pe live: intri în wp-admin → Unelte → Seed «Sub 200 lei» → apeși butonul.
 * NU există URL public care declanșează seed-ul (ar fi nesigur). Accesul cere
 * `manage_options` (administrator) + nonce valid.
 */

namespace App;

/**
 * Rulează seed-ul. Returnează un jurnal: listă de ['level' => info|warn|error|line, 'msg' => ...].
 *
 * @param array{force?:bool, dry?:bool, skip_products?:bool} $opts
 * @return array<int, array{level:string, msg:string}>
 */
function seed_sub200(array $opts = []): array
{
    $force = ! empty($opts['force']);
    $dry = ! empty($opts['dry']);
    $skipProducts = ! empty($opts['skip_products']);

    $log = [];
    $push = static function (string $level, string $msg) use (&$log): void {
        $log[] = ['level' => $level, 'msg' => $msg];
    };

    foreach (['get_page_by_path', 'wp_insert_post', 'update_field', 'get_field'] as $fn) {
        if (! function_exists($fn)) {
            $push('error', "Funcția {$fn}() lipsește (ACF activ?).");

            return $log;
        }
    }

    $seedFile = get_theme_file_path('database/seeds/sub200.php');
    if (! is_file($seedFile)) {
        $push('error', "Fișierul de seed lipsește: {$seedFile}");

        return $log;
    }

    $data = require $seedFile;
    if (! is_array($data) || empty($data['page'])) {
        $push('error', 'Seed-ul nu a returnat un array valid.');

        return $log;
    }

    sub200_seed_page($data['page'], $dry, $force, $push);

    if (! $skipProducts) {
        sub200_seed_products($data['products_meta'] ?? [], $dry, $force, $push);
    }

    $push('info', $dry ? 'Dry-run complet. Nu s-a scris nimic.' : 'Gata.');

    return $log;
}

/**
 * Găsește (după template) sau creează pagina, apoi scrie ACF-ul editorial.
 */
function sub200_seed_page(array $page, bool $dry, bool $force, callable $push): int
{
    $template = 'template-sub-200.blade.php';

    $existing = get_posts([
        'post_type' => 'page',
        'post_status' => 'any',
        'numberposts' => 1,
        'fields' => 'ids',
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
    ]);
    $id = ! empty($existing) ? (int) $existing[0] : 0;

    if ($dry) {
        $push('line', $id
            ? "Pagina există (ID {$id}) → ".($force ? 'ACF ar fi rescris' : 'ACF păstrat (fără --force)')
            : 'Ar crea pagina „'.($page['title'] ?? 'Sub 200 lei').'” cu template '.$template);

        return $id;
    }

    if (! $id) {
        $id = (int) wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => (string) ($page['title'] ?? 'Suplimente sub 200 lei'),
            'post_name' => (string) ($page['slug'] ?? 'sub-200-lei'),
            'post_content' => '',
            'meta_input' => ['_wp_page_template' => $template],
        ], true);

        if (! $id || $id instanceof \WP_Error) {
            $push('error', 'Eșec la crearea paginii.');

            return 0;
        }
        $push('info', "Creat: {$page['title']} (ID {$id})");
    } else {
        update_post_meta($id, '_wp_page_template', $template);
        $push('line', "Pagina există (ID {$id}).");

        if (! $force) {
            $push('line', 'ACF păstrat — bifează „Rescrie” pentru a suprascrie.');

            return $id;
        }
    }

    $n = sub200_write_page_fields($id, $page);
    $push('line', "ACF scris ({$n} câmpuri).");

    return $id;
}

/**
 * Scrie toate câmpurile ACF editoriale ale paginii. Returnează numărul de câmpuri.
 */
function sub200_write_page_fields(int $id, array $p): int
{
    $hero = $p['hero'] ?? [];
    $explain = $p['explain'] ?? [];
    $products = $p['products'] ?? [];
    $table = $p['table'] ?? [];
    $bridge = $p['bridge'] ?? [];
    $faq = $p['faq'] ?? [];
    $cta = $p['cta'] ?? [];

    $fields = [
        'hero_eyebrow' => $hero['eyebrow'] ?? '',
        'hero_titlu' => $hero['titlu'] ?? '',
        'hero_lede' => $hero['lede'] ?? '',
        'hero_cpd_tagline' => $hero['cpd_tagline'] ?? '',
        'chip_all_label' => $hero['chip_all_label'] ?? '',
        'chip_vegan_label' => $hero['chip_vegan_label'] ?? '',
        'chip_long_label' => $hero['chip_long_label'] ?? '',
        'chip_short_label' => $hero['chip_short_label'] ?? '',

        'explain_eyebrow' => $explain['eyebrow'] ?? '',
        'explain_titlu' => $explain['titlu'] ?? '',
        'explain_cards' => array_map(static fn ($c) => [
            'titlu' => $c['titlu'] ?? '',
            'text' => $c['text'] ?? '',
            'link_text' => $c['link_text'] ?? '',
            'link_url' => $c['link_url'] ?? '',
        ], $explain['cards'] ?? []),

        'products_titlu' => $products['titlu'] ?? '',
        'products_meta' => $products['meta'] ?? '',
        'products_empty' => $products['empty'] ?? '',

        'table_eyebrow' => $table['eyebrow'] ?? '',
        'table_titlu' => $table['titlu'] ?? '',
        'table_intro' => $table['intro'] ?? '',
        'table_note' => $table['note'] ?? '',

        'bridge_eyebrow' => $bridge['eyebrow'] ?? '',
        'bridge_titlu' => $bridge['titlu'] ?? '',
        'bridge_text' => $bridge['text'] ?? '',
        'bridge_link_text' => $bridge['link_text'] ?? '',
        'bridge_link_url' => $bridge['link_url'] ?? '',

        'faq_titlu' => $faq['titlu'] ?? '',
        'faq_items' => array_map(static fn ($f) => [
            'q' => $f['q'] ?? '',
            'a' => $f['a'] ?? '',
        ], $faq['items'] ?? []),

        'cta_titlu' => $cta['titlu'] ?? '',
        'cta_text' => $cta['text'] ?? '',
        'cta_btn_text' => $cta['btn_text'] ?? '',
        'cta_btn_url' => $cta['btn_url'] ?? '',
    ];

    foreach ($fields as $name => $value) {
        update_field($name, $value, $id);
    }

    return count($fields);
}

/**
 * Corectează informatie_generala (forma + protocol_zile) pe produse, fără să
 * atingă restul subcâmpurilor grupului (read-merge-write).
 */
function sub200_seed_products(array $map, bool $dry, bool $force, callable $push): void
{
    if (empty($map)) {
        return;
    }

    $push('line', 'Produse (informatie_generala):');

    foreach ($map as $slug => $vals) {
        $wc = get_page_by_path((string) $slug, OBJECT, 'product');
        if (! $wc instanceof \WP_Post) {
            $push('warn', "Produs negăsit: {$slug}");

            continue;
        }
        $pid = (int) $wc->ID;
        $info = get_field('informatie_generala', $pid);
        $info = is_array($info) ? $info : [];

        $current_days = isset($info['protocol_zile']) ? (int) $info['protocol_zile'] : 0;
        $target_days = (int) ($vals['protocol_zile'] ?? 0);

        if ($dry) {
            $push('line', sprintf('%s: protocol_zile %s → %d', $slug, $current_days ?: '∅', $target_days));

            continue;
        }

        if (! $force && $current_days === $target_days && ! empty($info['forma'])) {
            $push('line', "{$slug}: deja {$target_days} zile (neschimbat)");

            continue;
        }

        $info['protocol_zile'] = $target_days;
        if (! empty($vals['forma'])) {
            $info['forma'] = (string) $vals['forma'];
        }
        update_field('informatie_generala', $info, $pid);

        $push('info', "{$slug}: protocol_zile = {$target_days}".(! empty($vals['forma']) ? ", forma = „{$vals['forma']}”" : ''));
    }
}

/* -------------------------------------------------------------------------
 * UI în admin — Unelte → Seed «Sub 200 lei»
 * ---------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_management_page(
        __('Seed „Sub 200 lei”', 'sage'),
        __('Seed „Sub 200 lei”', 'sage'),
        'manage_options',
        'natura-sub200-seed',
        'App\\render_sub200_seed_page'
    );
});

function render_sub200_seed_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Acces interzis.', 'sage'));
    }

    $log = [];
    $ran = false;

    if (isset($_POST['natura_sub200_mode']) && check_admin_referer('natura_sub200_seed')) {
        $mode = sanitize_text_field(wp_unslash($_POST['natura_sub200_mode']));
        $opts = [
            'dry' => $mode === 'dry',
            'force' => in_array($mode, ['force', 'page'], true),
            'skip_products' => $mode === 'page',
        ];
        $log = seed_sub200($opts);
        $ran = true;
    }

    $colors = ['error' => '#b32d2e', 'warn' => '#bd8600', 'info' => '#1a7a3c', 'line' => '#50575e'];

    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Seed „Suplimente sub 200 lei”', 'sage').'</h1>';
    echo '<p style="max-width:720px">'.esc_html__('Creează/actualizează pagina cu template „Suplimente sub 200 lei”, îi populează conținutul editorial (ACF) și corectează protocol_zile + forma pe produsele afișate. Idempotent — rulează de câte ori vrei.', 'sage').'</p>';

    echo '<form method="post" style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">';
    wp_nonce_field('natura_sub200_seed');
    echo '<button type="submit" name="natura_sub200_mode" value="dry" class="button button-secondary">'.esc_html__('Previzualizare (dry-run)', 'sage').'</button>';
    echo '<button type="submit" name="natura_sub200_mode" value="run" class="button button-primary">'.esc_html__('Rulează (creează / scrie ce lipsește)', 'sage').'</button>';
    echo '<button type="submit" name="natura_sub200_mode" value="force" class="button button-primary" onclick="return confirm(\''.esc_attr__('Rescrie ACF-ul paginii și protocol_zile pe produse. Continui?', 'sage').'\')">'.esc_html__('Rescrie tot (force)', 'sage').'</button>';
    echo '<button type="submit" name="natura_sub200_mode" value="page" class="button" onclick="return confirm(\''.esc_attr__('Rescrie doar ACF-ul paginii (fără produse). Continui?', 'sage').'\')">'.esc_html__('Doar pagina (force, fără produse)', 'sage').'</button>';
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
