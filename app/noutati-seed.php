<?php

/**
 * Seed pentru pagina „Noutăți · În curând” — logică partajată + UI în admin.
 *
 * Folosit de comanda CLI (App\Console\Commands\NoutatiSeed), pagina din admin
 * „Unelte → Seed «Noutăți»” și scriptul prin link seed-noutati.php (temporar).
 */

namespace App;

/**
 * @param array{force?:bool, dry?:bool} $opts
 * @return array<int, array{level:string, msg:string}>
 */
function seed_noutati(array $opts = []): array
{
    $force = ! empty($opts['force']);
    $dry = ! empty($opts['dry']);

    $log = [];
    $push = static function (string $level, string $msg) use (&$log): void {
        $log[] = ['level' => $level, 'msg' => $msg];
    };

    foreach (['get_page_by_path', 'wp_insert_post', 'update_field'] as $fn) {
        if (! function_exists($fn)) {
            $push('error', "Funcția {$fn}() lipsește (ACF activ?).");

            return $log;
        }
    }

    $seedFile = get_theme_file_path('database/seeds/noutati.php');
    if (! is_file($seedFile)) {
        $push('error', "Fișierul de seed lipsește: {$seedFile}");

        return $log;
    }

    $data = require $seedFile;
    if (! is_array($data) || empty($data['page'])) {
        $push('error', 'Seed-ul nu a returnat un array valid.');

        return $log;
    }

    noutati_seed_page($data['page'], $dry, $force, $push);

    $push('info', $dry ? 'Dry-run complet. Nu s-a scris nimic.' : 'Gata.');

    return $log;
}

function noutati_seed_page(array $page, bool $dry, bool $force, callable $push): int
{
    $template = 'template-noutati.blade.php';

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
            ? "Pagina există (ID {$id}) → ".($force ? 'ACF ar fi rescris' : 'ACF păstrat (fără force)')
            : 'Ar crea pagina „'.($page['title'] ?? 'Noutăți · În curând').'” cu template '.$template);
        $push('line', count($page['tinctures'] ?? []).' tincturi în seed.');

        return $id;
    }

    if (! $id) {
        $id = (int) wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => (string) ($page['title'] ?? 'Noutăți · În curând'),
            'post_name' => (string) ($page['slug'] ?? 'noutati-in-curand'),
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
            $push('line', 'ACF păstrat — folosește „force” pentru a rescrie.');

            return $id;
        }
    }

    $n = noutati_write_page_fields($id, $page);
    $push('info', count($page['tinctures'] ?? []).' tincturi scrise.');
    $push('line', "ACF scris ({$n} câmpuri).");

    return $id;
}

function noutati_write_page_fields(int $id, array $p): int
{
    $hero = $p['hero'] ?? [];
    $explain = $p['explain'] ?? [];
    $why = $p['why'] ?? [];
    $notify = $p['notify'] ?? [];
    $faq = $p['faq'] ?? [];
    $cta = $p['cta'] ?? [];

    // Tincturi (repeater cu repeatere imbricate: ingredients/benefits/status_rows).
    $tinctures = array_map(static function ($t) {
        return [
            'produs' => $t['produs'] ?? '',
            'theme' => $t['theme'] ?? '',
            'pending_badge' => $t['pending_badge'] ?? '',
            'bottle_label' => $t['bottle_label'] ?? '',
            'cat_chip' => $t['cat_chip'] ?? '',
            'name' => $t['name'] ?? '',
            'brand_line' => $t['brand_line'] ?? '',
            'role' => $t['role'] ?? '',
            'specs' => $t['specs'] ?? '',
            'usage' => $t['usage'] ?? '',
            'ingredients_summary' => $t['ingredients_summary'] ?? '',
            'ingredients' => array_map(static fn ($i) => [
                'plant' => $i['plant'] ?? '',
                'latin' => $i['latin'] ?? '',
                'pct' => $i['pct'] ?? '',
            ], $t['ingredients'] ?? []),
            'benefits' => array_map(static fn ($b) => ['text' => $b['text'] ?? ''], $t['benefits'] ?? []),
            'contraindic_label' => $t['contraindic_label'] ?? '',
            'contraindic_text' => $t['contraindic_text'] ?? '',
            'contraindic_extra_label' => $t['contraindic_extra_label'] ?? '',
            'contraindic_extra_text' => $t['contraindic_extra_text'] ?? '',
            'status_label' => $t['status_label'] ?? '',
            'status_rows' => array_map(static fn ($r) => [
                'k' => $r['k'] ?? '',
                'v' => $r['v'] ?? '',
                'type' => $r['type'] ?? 'normal',
            ], $t['status_rows'] ?? []),
            'notify_btn' => $t['notify_btn'] ?? '',
        ];
    }, $p['tinctures'] ?? []);

    $fields = [
        'hero_eyebrow' => $hero['eyebrow'] ?? '',
        'hero_titlu' => $hero['titlu'] ?? '',
        'hero_brand_by' => $hero['brand_by'] ?? '',
        'hero_lede' => $hero['lede'] ?? '',
        'disclaimer_label' => $hero['disclaimer_label'] ?? '',
        'disclaimer_text' => $hero['disclaimer_text'] ?? '',
        'hero_cta_text' => $hero['cta_text'] ?? '',
        'hero_cta_url' => $hero['cta_url'] ?? '',

        'explain_eyebrow' => $explain['eyebrow'] ?? '',
        'explain_titlu' => $explain['titlu'] ?? '',
        'explain_cards' => array_map(static fn ($c) => ['titlu' => $c['titlu'] ?? '', 'text' => $c['text'] ?? ''], $explain['cards'] ?? []),

        'tinctures_titlu' => $p['tinctures_titlu'] ?? '',
        'tinctures_sub' => $p['tinctures_sub'] ?? '',
        'tinctures' => $tinctures,

        'why_eyebrow' => $why['eyebrow'] ?? '',
        'why_titlu' => $why['titlu'] ?? '',
        'why_cards' => array_map(static fn ($c) => ['titlu' => $c['titlu'] ?? '', 'text' => $c['text'] ?? ''], $why['cards'] ?? []),

        'notify_eyebrow' => $notify['eyebrow'] ?? '',
        'notify_titlu' => $notify['titlu'] ?? '',
        'notify_lede' => $notify['lede'] ?? '',
        'notify_email_label' => $notify['email_label'] ?? '',
        'notify_email_placeholder' => $notify['email_placeholder'] ?? '',
        'notify_which_label' => $notify['which_label'] ?? '',
        'notify_consent' => $notify['consent'] ?? '',
        'notify_submit' => $notify['submit'] ?? '',
        'notify_post_line' => $notify['post_line'] ?? '',

        'faq_titlu' => $faq['titlu'] ?? '',
        'faq_items' => array_map(static fn ($f) => ['q' => $f['q'] ?? '', 'a' => $f['a'] ?? ''], $faq['items'] ?? []),

        'cta_titlu' => $cta['titlu'] ?? '',
        'cta_text' => $cta['text'] ?? '',
        'cta_primary_text' => $cta['primary_text'] ?? '',
        'cta_primary_url' => $cta['primary_url'] ?? '',
        'cta_outline_text' => $cta['outline_text'] ?? '',
        'cta_outline_url' => $cta['outline_url'] ?? '',
    ];

    foreach ($fields as $name => $value) {
        update_field($name, $value, $id);
    }

    return count($fields);
}

/* -------------------------------------------------------------------------
 * UI în admin — Unelte → Seed «Noutăți»
 * ---------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_management_page(
        __('Seed „Noutăți”', 'sage'),
        __('Seed „Noutăți”', 'sage'),
        'manage_options',
        'natura-noutati-seed',
        'App\\render_noutati_seed_page'
    );
});

function render_noutati_seed_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Acces interzis.', 'sage'));
    }

    $log = [];
    $ran = false;

    if (isset($_POST['natura_nt_mode']) && check_admin_referer('natura_noutati_seed')) {
        $mode = sanitize_text_field(wp_unslash($_POST['natura_nt_mode']));
        $log = seed_noutati(['dry' => $mode === 'dry', 'force' => $mode === 'force']);
        $ran = true;
    }

    $colors = ['error' => '#b32d2e', 'warn' => '#bd8600', 'info' => '#1a7a3c', 'line' => '#50575e'];

    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Seed „Noutăți · În curând”', 'sage').'</h1>';
    echo '<p style="max-width:720px">'.esc_html__('Creează/actualizează pagina cu template „Noutăți · În curând” și îi populează tot conținutul editorial (inclusiv cele 3 tincturi) în ACF. Idempotent.', 'sage').'</p>';

    echo '<form method="post" style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">';
    wp_nonce_field('natura_noutati_seed');
    echo '<button type="submit" name="natura_nt_mode" value="dry" class="button button-secondary">'.esc_html__('Previzualizare (dry-run)', 'sage').'</button>';
    echo '<button type="submit" name="natura_nt_mode" value="run" class="button button-primary">'.esc_html__('Rulează (creează / scrie ce lipsește)', 'sage').'</button>';
    echo '<button type="submit" name="natura_nt_mode" value="force" class="button button-primary" onclick="return confirm(\''.esc_attr__('Rescrie tot conținutul ACF al paginii. Continui?', 'sage').'\')">'.esc_html__('Rescrie tot (force)', 'sage').'</button>';
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
