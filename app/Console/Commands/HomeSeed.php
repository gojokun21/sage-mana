<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Populează ACF-ul paginii Home (grup `group_home`, vezi app/acf-home.php) cu
 * textul editorial din database/seeds/home.php. Pagina Home = pagina cu template
 * `template-home.blade.php` (fallback: pagina setată ca front page).
 *
 * Idempotent. Implicit NU rescrie dacă pagina are deja conținut ACF (protejează
 * editările din admin) — folosește --force ca să rescrii.
 *
 *   wp acorn natura:home-seed
 *   wp acorn natura:home-seed --dry-run
 *   wp acorn natura:home-seed --force
 */
class HomeSeed extends Command
{
    protected $signature = 'natura:home-seed
                            {--force : Rescrie chiar dacă pagina are deja conținut ACF}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}';

    protected $description = 'Populează ACF-ul paginii Home cu textul editorial din database/seeds/home.php.';

    private const TEMPLATE = 'template-home.blade.php';

    public function handle(): int
    {
        foreach (['update_field', 'get_field', 'get_posts'] as $fn) {
            if (! function_exists($fn)) {
                $this->error("Funcția {$fn}() lipsește — rulează prin `wp acorn`.");

                return self::FAILURE;
            }
        }

        $force = (bool) $this->option('force');
        $dry = (bool) $this->option('dry-run');

        $home_id = $this->resolveHomeId();
        if (! $home_id) {
            $this->error('Pagina Home nu a fost găsită (niciun page cu template '.self::TEMPLATE.' și nicio front page).');

            return self::FAILURE;
        }

        $seedFile = dirname(__DIR__, 3).'/database/seeds/home.php';
        if (! is_file($seedFile)) {
            $this->error("Fișierul de seed lipsește: {$seedFile}");

            return self::FAILURE;
        }
        $fields = require $seedFile;

        if (! is_array($fields) || empty($fields)) {
            $this->error('Seed-ul nu a returnat un array valid.');

            return self::FAILURE;
        }

        // „Are deja conținut" = oricare câmp scalar de bază e setat.
        $has_content = (bool) get_field('hero_titlu', $home_id);
        if ($has_content && ! $force && ! $dry) {
            $this->warn("Pagina Home (ID {$home_id}) are deja conținut ACF. Folosește --force ca să rescrii.");

            return self::SUCCESS;
        }

        if ($dry) {
            $this->line(sprintf('  [dry] ar scrie %d câmpuri pe pagina Home (ID %d).', count($fields), $home_id));

            return self::SUCCESS;
        }

        foreach ($fields as $name => $value) {
            update_field($name, $value, $home_id);
        }

        $this->info(sprintf('Gata. %d câmpuri scrise pe pagina Home (ID %d).', count($fields), $home_id));

        return self::SUCCESS;
    }

    /**
     * ID-ul paginii Home: întâi pagina cu template-home, altfel front page-ul.
     */
    private function resolveHomeId(): int
    {
        $pages = get_posts([
            'post_type' => 'page',
            'post_status' => 'publish',
            'numberposts' => 1,
            'fields' => 'ids',
            'meta_key' => '_wp_page_template',
            'meta_value' => self::TEMPLATE,
        ]);
        if (! empty($pages)) {
            return (int) $pages[0];
        }

        return (int) get_option('page_on_front');
    }
}
