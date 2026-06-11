<?php

namespace App;

use Illuminate\Contracts\Console\Kernel;
use Roots\Acorn\Application;

/**
 * Tools → „Seeders": un panou cu buton per seed, pentru rulare fără wp-cli
 * (util mai ales pe live, unde shell-ul nu e disponibil).
 *
 * Rulează comenzile Acorn `natura:*-seed` direct din request-ul WP (WordPress +
 * ACF sunt încărcate), prin kernel-ul de consolă, și afișează output-ul.
 *
 * Securitate: doar `manage_options` (admin), fiecare rulare verificată cu nonce.
 * Acțiunile „force"/„toate" cer confirmare. NU e endpoint public.
 */
defined('ABSPATH') || exit;

/**
 * Comenzile de seed, în ordinea de rulare „toate". `dupa-simptom-seed` e ultimul
 * (leagă cardurile de paginile de detaliu create de `simptom-seed`).
 */
function seed_runner_commands(): array
{
    return [
        'natura:simptom-seed' => 'Pagini simptom (detaliu) + mutare sub /dupa-simptom/',
        'natura:obiectiv-seed' => 'Pagini obiectiv (detaliu) sub /dupa-obiectiv/',
        'natura:dupa-simptom-seed' => 'Hub După simptom — index ACF (rulează DUPĂ simptom-seed)',
        'natura:sub200-seed' => 'Sub 200 lei',
        'natura:pdp-seed' => 'PDP (single product)',
        'natura:pachet-seed' => 'Pachete',
        'natura:bestseller-seed' => 'Cele mai vândute',
        'natura:noutati-seed' => 'Noutăți · În curând',
        'natura:mega-suplimente-seed' => 'Mega meniu Suplimente',
        'natura:home-seed' => 'Home — conținut editorial',
    ];
}

/**
 * Rulează o comandă Acorn prin kernel-ul de consolă și întoarce [exit, output].
 */
function seed_runner_run(string $signature, array $params = []): array
{
    try {
        $kernel = Application::getInstance()->make(Kernel::class);
        $exit = $kernel->call($signature, $params);

        return ['exit' => $exit, 'output' => $kernel->output()];
    } catch (\Throwable $e) {
        return ['exit' => 1, 'output' => 'EROARE: '.$e->getMessage()];
    }
}

add_action('admin_menu', function () {
    add_management_page(
        __('Seeders', 'sage'),
        __('Seeders', 'sage'),
        'manage_options',
        'mn-seeders',
        __NAMESPACE__.'\\seed_runner_page'
    );
});

function seed_runner_page(): void
{
    if (! current_user_can('manage_options')) {
        wp_die(esc_html__('Acces interzis.', 'sage'));
    }

    $commands = seed_runner_commands();
    $base_url = admin_url('tools.php?page=mn-seeders');

    $action_url = function (string $target, string $mode) use ($base_url) {
        return wp_nonce_url(
            add_query_arg(['mn_run' => $target, 'mn_mode' => $mode], $base_url),
            'mn_seeders_run'
        );
    };

    $results = [];

    if (isset($_GET['mn_run'], $_GET['mn_mode'])) {
        check_admin_referer('mn_seeders_run');

        $target = sanitize_text_field(wp_unslash($_GET['mn_run']));
        $mode = sanitize_text_field(wp_unslash($_GET['mn_mode']));

        $params = [];
        if ($mode === 'dry') {
            $params['--dry-run'] = true;
        } elseif ($mode === 'force') {
            $params['--force'] = true;
        }

        $to_run = ($target === '__all__')
            ? array_keys($commands)
            : (isset($commands[$target]) ? [$target] : []);

        $ran_real = false;
        foreach ($to_run as $sig) {
            $results[$sig] = seed_runner_run($sig, $params);
            if ($mode !== 'dry') {
                $ran_real = true;
            }
        }

        // Mutarea/crearea de pagini schimbă ierarhia → reîmprospătează rewrite-ul.
        if ($ran_real && (in_array('natura:simptom-seed', $to_run, true) || in_array('natura:obiectiv-seed', $to_run, true))) {
            flush_rewrite_rules();
            $results['rewrite'] = ['exit' => 0, 'output' => 'flush_rewrite_rules() executat.'];
        }
    }

    echo '<div class="wrap">';
    echo '<h1>'.esc_html__('Seeders', 'sage').'</h1>';
    echo '<p>'.esc_html__('Rulează seed-urile fără wp-cli (merge și pe live). Recomandat: „Dry" întâi (previzualizare), apoi „Rulează". „Force" rescrie conținut existent, inclusiv editări din admin.', 'sage').'</p>';

    echo '<p style="margin:18px 0;display:flex;gap:10px;flex-wrap:wrap">';
    printf('<a class="button" href="%s">%s</a>', esc_url($action_url('__all__', 'dry')), esc_html__('Dry-run TOATE', 'sage'));
    printf('<a class="button button-primary" href="%s" onclick="return confirm(\'%s\')">%s</a>', esc_url($action_url('__all__', 'run')), esc_attr__('Rulezi toate seed-urile, în ordine?', 'sage'), esc_html__('Rulează TOATE', 'sage'));
    printf('<a class="button" href="%s" onclick="return confirm(\'%s\')" style="color:#b32d2e;border-color:#b32d2e">%s</a>', esc_url($action_url('__all__', 'force')), esc_attr__('FORCE rescrie tot conținutul. Sigur?', 'sage'), esc_html__('Rulează TOATE --force', 'sage'));
    echo '</p>';

    if (! empty($results)) {
        echo '<h2>'.esc_html__('Rezultat', 'sage').'</h2>';
        foreach ($results as $sig => $res) {
            $ok = (int) $res['exit'] === 0;
            printf('<h3 style="margin-bottom:4px;color:%s">%s %s</h3>', esc_attr($ok ? '#1a7f37' : '#b32d2e'), $ok ? '✓' : '✗', esc_html($sig));
            echo '<pre style="background:#1e1e1e;color:#e6e6e6;padding:14px;border-radius:8px;overflow:auto;max-height:340px;white-space:pre-wrap">'.esc_html(trim((string) $res['output']) ?: '(fără output)').'</pre>';
        }
    }

    echo '<h2>'.esc_html__('Seed-uri individuale', 'sage').'</h2>';
    echo '<table class="widefat striped" style="max-width:920px"><thead><tr>';
    echo '<th>'.esc_html__('Comandă', 'sage').'</th><th>'.esc_html__('Descriere', 'sage').'</th><th style="width:230px">'.esc_html__('Acțiuni', 'sage').'</th>';
    echo '</tr></thead><tbody>';
    foreach ($commands as $sig => $label) {
        echo '<tr>';
        echo '<td><code>'.esc_html($sig).'</code></td>';
        echo '<td>'.esc_html($label).'</td>';
        echo '<td style="display:flex;gap:6px;flex-wrap:wrap">';
        printf('<a class="button button-small" href="%s">%s</a>', esc_url($action_url($sig, 'dry')), esc_html__('Dry', 'sage'));
        printf('<a class="button button-small button-primary" href="%s">%s</a>', esc_url($action_url($sig, 'run')), esc_html__('Rulează', 'sage'));
        printf('<a class="button button-small" href="%s" onclick="return confirm(\'%s\')">%s</a>', esc_url($action_url($sig, 'force')), esc_attr__('Force rescrie. Sigur?', 'sage'), esc_html__('Force', 'sage'));
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
}
