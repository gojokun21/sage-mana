<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Seedează „index-ul de simptome" în ACF-ul hub-ului `dupa-simptom`
 * (grup `group_dupa_simptom_hub`, vezi app/acf-dupa-simptom.php) din
 * database/seeds/dupa-simptom-grupe.php. Fiecare card cu `slug` e legat la
 * pagina lui de detaliu /dupa-simptom/<slug>/ (câmpul „pagina").
 *
 * Idempotent. Implicit NU rescrie dacă ACF-ul are deja grupe (protejează
 * editările din admin) — folosește --force ca să rescrii.
 *
 *   wp acorn natura:dupa-simptom-seed
 *   wp acorn natura:dupa-simptom-seed --dry-run
 *   wp acorn natura:dupa-simptom-seed --force
 */
class DupaSimptomSeed extends Command
{
    protected $signature = 'natura:dupa-simptom-seed
                            {--force : Rescrie chiar dacă hub-ul are deja grupe în ACF}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}';

    protected $description = 'Populează ACF-ul hub-ului După simptom (grupe + carduri + linkuri la detaliu).';

    private const HUB_SLUG = 'dupa-simptom';

    public function handle(): int
    {
        foreach (['get_page_by_path', 'update_field', 'get_field'] as $fn) {
            if (! function_exists($fn)) {
                $this->error("Funcția {$fn}() lipsește — rulează prin `wp acorn` (WordPress + ACF încărcate).");

                return self::FAILURE;
            }
        }

        $force = (bool) $this->option('force');
        $dry = (bool) $this->option('dry-run');

        $hub = get_page_by_path(self::HUB_SLUG, OBJECT, 'page');
        if (! $hub instanceof \WP_Post) {
            $this->error('Pagina-hub /'.self::HUB_SLUG.'/ nu există. Rulează întâi `wp acorn natura:simptom-seed`.');

            return self::FAILURE;
        }
        $hub_id = (int) $hub->ID;

        $seedFile = dirname(__DIR__, 3).'/database/seeds/dupa-simptom-grupe.php';
        if (! is_file($seedFile)) {
            $this->error("Fișierul de seed lipsește: {$seedFile}");

            return self::FAILURE;
        }
        $seed = require $seedFile;
        $grupe = $seed['grupe'] ?? [];

        if (empty($grupe)) {
            $this->error('Seed-ul nu conține grupe.');

            return self::FAILURE;
        }

        $existing = get_field('simptom_grupe', $hub_id);
        if (! empty($existing) && ! $force && ! $dry) {
            $this->warn('Hub-ul are deja grupe în ACF. Folosește --force ca să rescrii.');

            return self::SUCCESS;
        }

        // Construiește rândurile ACF, rezolvând slug-ul cardului → ID pagină detaliu.
        $rows = [];
        $linked = 0;
        $unlinked = 0;
        foreach ($grupe as $g) {
            $carduri = [];
            foreach (($g['cards'] ?? []) as $c) {
                $page_id = '';
                if (! empty($c['slug'])) {
                    $page = get_page_by_path(self::HUB_SLUG.'/'.$c['slug'], OBJECT, 'page');
                    if ($page instanceof \WP_Post) {
                        $page_id = (int) $page->ID;
                        $linked++;
                    } else {
                        $this->warn('      ! Pagina de detaliu lipsește: /'.self::HUB_SLUG."/{$c['slug']}/ ({$c['name']})");
                        $unlinked++;
                    }
                } else {
                    $unlinked++;
                }
                $carduri[] = [
                    'nume' => $c['name'] ?? '',
                    'descriere' => $c['desc'] ?? '',
                    'chip' => $c['chip'] ?? '',
                    'pagina' => $page_id,
                ];
            }
            $rows[] = [
                'eyebrow' => $g['eyebrow'] ?? '',
                'titlu' => $g['title'] ?? '',
                'titlu_em' => $g['title_em'] ?? '',
                'carduri' => $carduri,
            ];
        }

        if ($dry) {
            $this->line(sprintf('  [dry] ar scrie %d grupe, %d carduri legate, %d fără link, pe hub ID %d.', count($rows), $linked, $unlinked, $hub_id));

            return self::SUCCESS;
        }

        update_field('simptom_grupe', $rows, $hub_id);
        update_field('grupe_footer', (string) ($seed['footer'] ?? ''), $hub_id);

        $this->info(sprintf('Gata. %d grupe scrise pe hub ID %d (%d carduri legate la detaliu, %d fără link).', count($rows), $hub_id, $linked, $unlinked));

        return self::SUCCESS;
    }
}
