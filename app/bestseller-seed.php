<?php

/**
 * Seed pentru pagina „Cele mai vândute” — logică partajată + UI în admin.
 *
 * Folosit de:
 *  - comanda CLI App\Console\Commands\BestsellerSeed (`wp acorn natura:bestseller-seed`)
 *  - pagina din admin „Unelte → Seed «Cele mai vândute»”
 *  - scriptul prin link seed-bestseller.php (temporar)
 *
 * Creează/găsește pagina cu template-ul, scrie conținutul editorial în ACF și
 * populează repeater-ul de produse rezolvând slug-urile WC la ID-uri reale.
 */

namespace App;

/**
 * @param array{force?:bool, dry?:bool} $opts
 * @return array<int, array{level:string, msg:string}>
 */
function seed_bestseller(array $opts = []): array
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

    $seedFile = get_theme_file_path('database/seeds/bestseller.php');
    if (! is_file($seedFile)) {
        $push('error', "Fișierul de seed lipsește: {$seedFile}");

        return $log;
    }

    $data = require $seedFile;
    if (! is_array($data) || empty($data['page'])) {
        $push('error', 'Seed-ul nu a returnat un array valid.');

        return $log;
    }

    bestseller_seed_page($data['page'], $dry, $force, $push);

    $push('info', $dry ? 'Dry-run complet. Nu s-a scris nimic.' : 'Gata.');

    return $log;
}

function bestseller_seed_page(array $page, bool $dry, bool $force, callable $push): int
{
    $template = 'template-cele-mai-vandute.blade.php';

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
            : 'Ar crea pagina „'.($page['title'] ?? 'Cele mai vândute').'” cu template '.$template);
        bestseller_preview_products($page['bestsellers'] ?? [], $push);

        return $id;
    }

    if (! $id) {
        $id = (int) wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => (string) ($page['title'] ?? 'Cele mai vândute'),
            'post_name' => (string) ($page['slug'] ?? 'cele-mai-vandute'),
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

    $n = bestseller_write_page_fields($id, $page, $push);
    $push('line', "ACF scris ({$n} câmpuri).");

    return $id;
}

/**
 * Rezolvă slug-urile de produs la ID-uri și scrie toate câmpurile ACF.
 */
function bestseller_write_page_fields(int $id, array $p, callable $push): int
{
    $hero = $p['hero'] ?? [];
    $explain = $p['explain'] ?? [];
    $table = $p['table'] ?? [];
    $quiz = $p['quiz'] ?? [];
    $faq = $p['faq'] ?? [];
    $cta = $p['cta'] ?? [];

    // Repeater de produse: slug → ID WC.
    $items = [];
    foreach (($p['bestsellers'] ?? []) as $row) {
        $slug = (string) ($row['produs_slug'] ?? '');
        $wc = $slug !== '' ? get_page_by_path($slug, OBJECT, 'product') : null;
        if (! $wc instanceof \WP_Post) {
            $push('warn', "Produs negăsit (sărit): {$slug}");

            continue;
        }
        $items[] = [
            'produs' => (int) $wc->ID,
            'cat_label' => $row['cat_label'] ?? '',
            'sub_override' => $row['sub_override'] ?? '',
            'why' => $row['why'] ?? '',
            'cta_label' => $row['cta_label'] ?? '',
            'rating' => (string) ($row['rating'] ?? 5),
            'rating_label' => $row['rating_label'] ?? '',
        ];
        $push('info', "Produs legat: {$wc->post_title} (#".count($items).')');
    }

    $fields = [
        'hero_eyebrow' => $hero['eyebrow'] ?? '',
        'hero_titlu' => $hero['titlu'] ?? '',
        'hero_lede' => $hero['lede'] ?? '',
        'honest_label' => $hero['honest_label'] ?? '',
        'honest_body' => $hero['honest_body'] ?? '',
        'honest_line' => $hero['honest_line'] ?? '',

        'explain_eyebrow' => $explain['eyebrow'] ?? '',
        'explain_titlu' => $explain['titlu'] ?? '',
        'explain_cards' => array_map(static fn ($c) => [
            'titlu' => $c['titlu'] ?? '',
            'text' => $c['text'] ?? '',
        ], $explain['cards'] ?? []),

        'products_titlu' => $p['products_titlu'] ?? '',
        'products_meta' => $p['products_meta'] ?? '',
        'bestsellers' => $items,

        'table_eyebrow' => $table['eyebrow'] ?? '',
        'table_titlu' => $table['titlu'] ?? '',
        'table_intro' => $table['intro'] ?? '',
        'table_note' => $table['note'] ?? '',

        'quiz_eyebrow' => $quiz['eyebrow'] ?? '',
        'quiz_titlu' => $quiz['titlu'] ?? '',
        'quiz_text' => $quiz['text'] ?? '',
        'quiz_cta_text' => $quiz['cta_text'] ?? '',
        'quiz_cta_url' => $quiz['cta_url'] ?? '',
        'quiz_micro' => $quiz['micro'] ?? '',

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
 * Pentru dry-run: doar verifică ce produse s-ar lega.
 */
function bestseller_preview_products(array $rows, callable $push): void
{
    foreach ($rows as $row) {
        $slug = (string) ($row['produs_slug'] ?? '');
        $wc = $slug !== '' ? get_page_by_path($slug, OBJECT, 'product') : null;
        $push($wc instanceof \WP_Post ? 'line' : 'warn',
            $wc instanceof \WP_Post ? "OK produs: {$slug}" : "Produs negăsit: {$slug}");
    }
}

/* -------------------------------------------------------------------------
 * UI în admin — Unelte → Seed «Cele mai vândute»
 * ---------------------------------------------------------------------- */

add_action('admin_menu', function () {
    add_management_page(
        __('Seed „Cele mai vândute”', 'sage'),
        __('Seed „Cele mai vândute”', 'sage'),
        'manage_options',
        'natura-bestseller-seed',
        'App\\render_bestseller_seed_page'
    );
});

function render_bestseller_seed_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Acces interzis.', 'sage'));
    }

    $log = [];
    $ran = false;

    if (isset($_POST['natura_bs_mode']) && check_admin_referer('natura_bestseller_seed')) {
        $mode = sanitize_text_field(wp_unslash($_POST['natura_bs_mode']));
        $log = seed_bestseller([
            'dry' => $mode === 'dry',
            'force' => $mode === 'force',
        ]);
        $ran = true;
    }

    $colors = ['error' => '#b32d2e', 'warn' => '#bd8600', 'info' => '#1a7a3c', 'line' => '#50575e'];

    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Seed „Cele mai vândute”', 'sage').'</h1>';
    echo '<p style="max-width:720px">'.esc_html__('Creează/actualizează pagina cu template „Cele mai vândute”, îi populează conținutul editorial (ACF) și leagă produsele reale din catalog (după slug). Idempotent.', 'sage').'</p>';

    echo '<form method="post" style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">';
    wp_nonce_field('natura_bestseller_seed');
    echo '<button type="submit" name="natura_bs_mode" value="dry" class="button button-secondary">'.esc_html__('Previzualizare (dry-run)', 'sage').'</button>';
    echo '<button type="submit" name="natura_bs_mode" value="run" class="button button-primary">'.esc_html__('Rulează (creează / scrie ce lipsește)', 'sage').'</button>';
    echo '<button type="submit" name="natura_bs_mode" value="force" class="button button-primary" onclick="return confirm(\''.esc_attr__('Rescrie tot conținutul ACF al paginii. Continui?', 'sage').'\')">'.esc_html__('Rescrie tot (force)', 'sage').'</button>';
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
