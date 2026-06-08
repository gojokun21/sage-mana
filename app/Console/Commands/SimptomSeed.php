<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Seedează paginile de simptom: creează (sau actualizează) câte o pagină
 * WordPress per simptom, îi atribuie template-ul `template-simptom.blade.php`
 * și îi populează câmpurile ACF (vezi app/acf-simptom.php) cu conținutul din
 * database/seeds/simptome.php (transcris din preferinte/Pagina simptom - *.html).
 *
 * Idempotent: re-rulabil. Implicit, pe paginile care există deja NU rescrie
 * ACF-ul (protejează editările din admin) — folosește --force ca să rescrii.
 *
 *   wp acorn natura:simptom-seed
 *   wp acorn natura:simptom-seed --dry-run
 *   wp acorn natura:simptom-seed --force --only=balonare
 */
class SimptomSeed extends Command
{
    protected $signature = 'natura:simptom-seed
                            {--force : Rescrie câmpurile ACF și pe paginile care există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}
                            {--only= : Procesează doar simptomul cu acest slug}';

    protected $description = 'Creează paginile de simptom și le populează ACF din database/seeds/simptome.php.';

    private const TEMPLATE = 'template-simptom.blade.php';

    public function handle(): int
    {
        foreach (['get_page_by_path', 'wp_insert_post', 'update_field'] as $fn) {
            if (! function_exists($fn)) {
                $this->error("Funcția {$fn}() lipsește — rulează prin `wp acorn` (WordPress + ACF încărcate).");

                return self::FAILURE;
            }
        }

        $force = (bool) $this->option('force');
        $dry = (bool) $this->option('dry-run');
        $only = $this->option('only') ? (string) $this->option('only') : null;

        $themeRoot = dirname(__DIR__, 3);
        $seedFile = $themeRoot.'/database/seeds/simptome.php';

        if (! is_file($seedFile)) {
            $this->error("Fișierul de seed lipsește: {$seedFile}");

            return self::FAILURE;
        }

        $data = require $seedFile;

        if (! is_array($data) || empty($data)) {
            $this->error('Seed-ul nu a returnat un array valid.');

            return self::FAILURE;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($data as $slug => $simptom) {
            if ($only !== null && $only !== $slug) {
                continue;
            }

            $slug = sanitize_title((string) $slug);
            $title = (string) ($simptom['title'] ?? ucfirst($slug));

            $page = get_page_by_path($slug, OBJECT, 'page');
            $exists = $page instanceof \WP_Post;
            $id = $exists ? (int) $page->ID : 0;

            if ($dry) {
                $action = $exists ? ($force ? 'ar actualiza (ACF rescris)' : 'ar actualiza (ACF păstrat)') : 'ar crea';
                $this->line(sprintf('  [dry] %-22s → %s /%s/', $title, $action, $slug));

                continue;
            }

            if (! $exists) {
                $id = (int) wp_insert_post([
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_title' => $title,
                    'post_name' => $slug,
                    'post_content' => '',
                    'meta_input' => ['_wp_page_template' => self::TEMPLATE],
                ], true);

                if (! $id || $id instanceof \WP_Error) {
                    $this->error("  Eșec la crearea paginii /{$slug}/");

                    continue;
                }
                $created++;
                $this->info("  + Creat: {$title} (/{$slug}/, ID {$id})");
            } else {
                $updated++;
                $this->line("  ~ Există: {$title} (/{$slug}/, ID {$id})");
            }

            // Asigură template-ul corect pe orice rulare.
            update_post_meta($id, '_wp_page_template', self::TEMPLATE);

            // Pe pagini existente nu rescriem ACF decât cu --force.
            if ($exists && ! $force) {
                $skipped++;
                $this->line('      ↳ ACF păstrat (folosește --force pentru a rescrie).');

                continue;
            }

            $this->writeFields($id, $simptom, $slug);
        }

        if ($dry) {
            $this->info('Dry-run complet. Nu s-a scris nimic.');

            return self::SUCCESS;
        }

        $this->info("Gata. Create: {$created}, existente: {$updated}, ACF păstrat: {$skipped}.");

        return self::SUCCESS;
    }

    /**
     * Scrie toate câmpurile ACF pentru o pagină de simptom.
     */
    private function writeFields(int $id, array $s, string $slug): void
    {
        $hero = $s['hero'] ?? [];
        $def = $s['definitie'] ?? [];
        $semne = $s['semne'] ?? [];
        $auto = $s['autotest'] ?? [];
        $medic = $s['medic'] ?? [];
        $produse = $s['produse'] ?? [];
        $mituri = $s['mituri'] ?? [];
        $faq = $s['faq'] ?? [];

        // Produse: rezolvă slug-urile WC la ID-uri pentru câmpul Post Object.
        $produse_items = [];
        foreach (($produse['items'] ?? []) as $it) {
            $pid = '';
            if (! empty($it['slug'])) {
                $wc = get_page_by_path($it['slug'], OBJECT, 'product');
                if ($wc instanceof \WP_Post) {
                    $pid = (int) $wc->ID;
                } else {
                    $this->warn("      ! Produs negăsit în WC: {$it['slug']} ({$slug}) — rămâne fallback nume/preț.");
                }
            }
            $produse_items[] = [
                'produs' => $pid,
                'nume' => $it['nume'] ?? '',
                'pret' => $it['pret'] ?? '',
                'opt' => $it['opt'] ?? '',
                'category' => $it['category'] ?? '',
                'why' => $it['why'] ?? '',
                'cta' => $it['cta'] ?? '',
                'cta_class' => $it['cta_class'] ?? 'btn-terra',
            ];
        }

        $fields = [
            // Hero
            'hero_eyebrow' => $hero['eyebrow'] ?? '',
            'hero_titlu' => $hero['titlu'] ?? '',
            'hero_lede' => $hero['lede'] ?? '',
            'hero_chips' => array_map(static fn ($t) => ['text' => $t], $hero['chips'] ?? []),

            // Definiție
            'def_eyebrow' => $def['eyebrow'] ?? '',
            'def_titlu' => $def['titlu'] ?? '',
            'def_cells' => $def['cells'] ?? [],

            // Semne
            'semne_eyebrow' => $semne['eyebrow'] ?? '',
            'semne_titlu' => $semne['titlu'] ?? '',
            'semne_items' => $semne['items'] ?? [],

            // Autotest
            'autotest_eyebrow' => $auto['eyebrow'] ?? '',
            'autotest_titlu' => $auto['titlu'] ?? '',
            'autotest_intrebari' => array_map(static fn ($q) => [
                'q' => $q['q'] ?? '',
                'default' => (string) ($q['default'] ?? 0),
            ], $auto['intrebari'] ?? []),
            'autotest_rezultat_strong' => $auto['rezultat_strong'] ?? '',
            'autotest_rezultat_text' => $auto['rezultat_text'] ?? '',

            // Medic
            'medic_titlu' => $medic['titlu'] ?? '',
            'medic_lede' => $medic['lede'] ?? '',
            'medic_semnale' => array_map(static fn ($t) => ['text' => $t], $medic['semnale'] ?? []),
            'medic_foot' => $medic['foot'] ?? '',

            // Produse
            'produse_eyebrow' => $produse['eyebrow'] ?? '',
            'produse_titlu' => $produse['titlu'] ?? '',
            'produse_intro' => $produse['intro'] ?? '',
            'produse_items' => $produse_items,

            // Mituri
            'mituri_eyebrow' => $mituri['eyebrow'] ?? '',
            'mituri_titlu' => $mituri['titlu'] ?? '',
            'mituri_items' => $mituri['items'] ?? [],

            // FAQ
            'faq_eyebrow' => $faq['eyebrow'] ?? '',
            'faq_titlu' => $faq['titlu'] ?? '',
            'faq_items' => $faq['items'] ?? [],
        ];

        foreach ($fields as $name => $value) {
            update_field($name, $value, $id);
        }

        $this->line('      ↳ ACF scris ('.count($fields).' câmpuri).');
    }
}
