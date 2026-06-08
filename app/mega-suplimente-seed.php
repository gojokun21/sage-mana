<?php

/**
 * Seed pentru mega-meniul „Suplimente” — logică partajată + UI în admin.
 * Scrie în ACF options (post_id 'option'). Coloana de categorii + featured
 * trag date reale din WC; aici populăm doar etichetele editoriale + featured.
 *
 * Folosit de comanda CLI, pagina din admin „Unelte → Seed «Mega Suplimente»”
 * și scriptul prin link seed-mega-suplimente.php.
 */

namespace App;

/**
 * @param array{force?:bool, dry?:bool} $opts
 * @return array<int, array{level:string, msg:string}>
 */
function seed_mega_suplimente(array $opts = []): array
{
    $force = ! empty($opts['force']);
    $dry = ! empty($opts['dry']);

    $log = [];
    $push = static function (string $level, string $msg) use (&$log): void {
        $log[] = ['level' => $level, 'msg' => $msg];
    };

    if (! function_exists('update_field') || ! function_exists('get_field')) {
        $push('error', 'ACF (update_field) lipsește.');

        return $log;
    }

    $seedFile = get_theme_file_path('database/seeds/mega-suplimente.php');
    if (! is_file($seedFile)) {
        $push('error', "Fișierul de seed lipsește: {$seedFile}");

        return $log;
    }
    $d = require $seedFile;

    // Nu rescrie dacă există deja conținut (fără --force).
    if (! $force) {
        $existing = get_field('msup_featured', 'option');
        if (! empty($existing)) {
            $push('line', 'Există deja conținut în ACF — folosește „force” pentru a rescrie.');
            if (! $dry) {
                return $log;
            }
        }
    }

    // Quick links: rezolvă slug pagină → permalink (fallback pe url/shop).
    $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
    $quick = array_map(static function ($q) use ($shop, $push) {
        $link = '';
        if (! empty($q['page_slug'])) {
            $p = get_page_by_path($q['page_slug'], OBJECT, 'page');
            $link = $p ? get_permalink($p) : '';
            if (! $p) {
                $push('warn', "Pagină negăsită pentru quick link: {$q['page_slug']} (folosesc catalogul)");
            }
        }
        if ($link === '' && ! empty($q['url'])) {
            $link = home_url($q['url']);
        }
        if ($link === '') {
            $link = $shop;
        }

        return ['label' => $q['label'] ?? '', 'link' => $link, 'badge' => $q['badge'] ?? ''];
    }, $d['quick'] ?? []);

    // Featured: slug → ID produs.
    $featured = [];
    foreach ($d['featured'] ?? [] as $f) {
        $slug = (string) ($f['produs_slug'] ?? '');
        $wc = $slug !== '' ? get_page_by_path($slug, OBJECT, 'product') : null;
        if (! $wc instanceof \WP_Post) {
            $push('warn', "Produs featured negăsit (sărit): {$slug}");

            continue;
        }
        $featured[] = ['produs' => (int) $wc->ID, 'why' => $f['why'] ?? ''];
        $push('info', "Featured legat: {$wc->post_title}");
    }

    $fields = [
        'msup_cat_title' => $d['cat_title'] ?? '',
        'msup_cat_foot' => $d['cat_foot'] ?? '',
        'msup_format_title' => $d['format_title'] ?? '',
        'msup_formate' => array_map(static fn ($x) => ['label' => $x['label'] ?? '', 'count' => $x['count'] ?? '', 'link' => $x['link'] ?? ''], $d['formate'] ?? []),
        'msup_format_disclaimer' => $d['format_disclaimer'] ?? '',
        'msup_quick_title' => $d['quick_title'] ?? '',
        'msup_quick' => $quick,
        'msup_featured_title' => $d['featured_title'] ?? '',
        'msup_featured' => $featured,
        'msup_bottom_info' => $d['bottom_info'] ?? '',
        'msup_bottom_cta_text' => $d['bottom_cta_text'] ?? '',
        'msup_bottom_cta_url' => $d['bottom_cta_url'] ?? '',
    ];

    if ($dry) {
        $push('line', 'Dry-run: ar scrie '.count($fields).' câmpuri în ACF options „Meniu”.');
        $push('info', 'Dry-run complet. Nu s-a scris nimic.');

        return $log;
    }

    if (function_exists('register_msup_acf')) {
        register_msup_acf(); // asigură grupul înregistrat înainte de scriere
    }
    foreach ($fields as $name => $value) {
        update_field($name, $value, 'option');
    }

    $push('info', 'Gata. ACF options scrise ('.count($fields).' câmpuri).');

    return $log;
}

/* -------------------------------------------------------------------------
 * UI în admin — Unelte → Seed «Mega Suplimente»
 * ---------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_management_page(
        __('Seed „Mega Suplimente”', 'sage'),
        __('Seed „Mega Suplimente”', 'sage'),
        'manage_options',
        'natura-mega-suplimente-seed',
        'App\\render_mega_suplimente_seed_page'
    );
});

function render_mega_suplimente_seed_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Acces interzis.', 'sage'));
    }

    $log = [];
    $ran = false;

    if (isset($_POST['natura_msup_mode']) && check_admin_referer('natura_mega_suplimente_seed')) {
        $mode = sanitize_text_field(wp_unslash($_POST['natura_msup_mode']));
        $log = seed_mega_suplimente(['dry' => $mode === 'dry', 'force' => $mode === 'force']);
        $ran = true;
    }

    $colors = ['error' => '#b32d2e', 'warn' => '#bd8600', 'info' => '#1a7a3c', 'line' => '#50575e'];

    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Seed „Mega-meniu Suplimente”', 'sage').'</h1>';
    echo '<p style="max-width:720px">'.esc_html__('Populează etichetele editoriale ale mega-meniului „Suplimente” în ACF options „Meniu” (format, quick links, featured, bandă jos). Categoriile și prețurile vin live din WooCommerce. Idempotent.', 'sage').'</p>';
    echo '<p style="max-width:720px"><strong>'.esc_html__('Important:', 'sage').'</strong> '.esc_html__('itemul de meniu „Suplimente” trebuie să aibă clasa CSS „mega-produse” (Aspect → Meniuri → Opțiuni ecran → Clase CSS).', 'sage').'</p>';

    echo '<form method="post" style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">';
    wp_nonce_field('natura_mega_suplimente_seed');
    echo '<button type="submit" name="natura_msup_mode" value="dry" class="button button-secondary">'.esc_html__('Previzualizare (dry-run)', 'sage').'</button>';
    echo '<button type="submit" name="natura_msup_mode" value="run" class="button button-primary">'.esc_html__('Rulează (scrie ce lipsește)', 'sage').'</button>';
    echo '<button type="submit" name="natura_msup_mode" value="force" class="button button-primary" onclick="return confirm(\''.esc_attr__('Rescrie conținutul ACF al mega-meniului. Continui?', 'sage').'\')">'.esc_html__('Rescrie tot (force)', 'sage').'</button>';
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
