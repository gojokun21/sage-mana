<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Seedează paginile „După obiectiv": asigură pagina-hub `dupa-obiectiv`, apoi
 * creează/actualizează câte o pagină COPIL per obiectiv (URL /dupa-obiectiv/<slug>/),
 * îi atribuie `template-obiectiv.blade.php` și îi populează ACF (app/acf-obiectiv.php)
 * din database/seeds/obiective.php (transcris din preferinte/Pagina obiectiv - *).
 *
 * Idempotent. Pe paginile existente nu rescrie ACF decât cu --force.
 *
 *   wp acorn natura:obiectiv-seed
 *   wp acorn natura:obiectiv-seed --dry-run
 *   wp acorn natura:obiectiv-seed --force --only=energie
 */
class ObiectivSeed extends Command
{
    protected $signature = 'natura:obiectiv-seed
                            {--force : Rescrie câmpurile ACF și pe paginile care există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}
                            {--only= : Procesează doar obiectivul cu acest slug}';

    protected $description = 'Creează paginile de obiectiv (copii sub /dupa-obiectiv/) și le populează ACF.';

    private const TEMPLATE = 'template-obiectiv.blade.php';

    private const HUB_SLUG = 'dupa-obiectiv';

    public function handle(): int
    {
        foreach (['get_page_by_path', 'wp_insert_post', 'update_field'] as $fn) {
            if (! function_exists($fn)) {
                $this->error("Funcția {$fn}() lipsește — rulează prin `wp acorn`.");

                return self::FAILURE;
            }
        }

        $force = (bool) $this->option('force');
        $dry = (bool) $this->option('dry-run');
        $only = $this->option('only') ? (string) $this->option('only') : null;

        $themeRoot = dirname(__DIR__, 3);
        $seedFile = $themeRoot.'/database/seeds/obiective.php';

        if (! is_file($seedFile)) {
            $this->error("Fișierul de seed lipsește: {$seedFile}");

            return self::FAILURE;
        }

        $data = require $seedFile;
        if (! is_array($data) || empty($data)) {
            $this->error('Seed-ul nu a returnat un array valid.');

            return self::FAILURE;
        }

        // Hub-ul părinte.
        $hub_id = $this->ensureHub($dry);

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($data as $slug => $obiectiv) {
            if ($only !== null && $only !== $slug) {
                continue;
            }

            $slug = sanitize_title((string) $slug);
            $title = (string) ($obiectiv['title'] ?? ucfirst($slug));

            $page = get_page_by_path(self::HUB_SLUG.'/'.$slug, OBJECT, 'page');
            $exists = $page instanceof \WP_Post;
            $id = $exists ? (int) $page->ID : 0;

            if ($dry) {
                $action = $exists ? ($force ? 'ar actualiza (ACF rescris)' : 'ar actualiza (ACF păstrat)') : 'ar crea';
                $this->line(sprintf('  [dry] %-30s → %s /%s/%s/', $title, $action, self::HUB_SLUG, $slug));

                continue;
            }

            if (! $exists) {
                $id = (int) wp_insert_post([
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_title' => $title,
                    'post_name' => $slug,
                    'post_parent' => $hub_id,
                    'post_content' => '',
                    'meta_input' => ['_wp_page_template' => self::TEMPLATE],
                ], true);

                if (! $id || $id instanceof \WP_Error) {
                    $this->error("  Eșec la crearea paginii /{$slug}/");

                    continue;
                }
                $created++;
                $this->info("  + Creat: {$title} (/".self::HUB_SLUG."/{$slug}/, ID {$id})");
            } else {
                $updated++;
                $this->line("  ~ Există: {$title} (ID {$id})");
            }

            update_post_meta($id, '_wp_page_template', self::TEMPLATE);

            if ($exists && ! $force) {
                $skipped++;
                $this->line('      ↳ ACF păstrat (folosește --force pentru a rescrie).');

                continue;
            }

            $this->writeFields($id, $obiectiv, $slug);
        }

        if ($dry) {
            $this->info('Dry-run complet. Nu s-a scris nimic.');

            return self::SUCCESS;
        }

        $this->info("Gata. Create: {$created}, existente: {$updated}, ACF păstrat: {$skipped}.");

        return self::SUCCESS;
    }

    /**
     * Asigură pagina-hub `dupa-obiectiv` (părintele paginilor de obiectiv).
     */
    private function ensureHub(bool $dry): int
    {
        $hub = get_page_by_path(self::HUB_SLUG, OBJECT, 'page');
        if ($hub instanceof \WP_Post) {
            return (int) $hub->ID;
        }

        if ($dry) {
            $this->line('  [dry] ar crea pagina-hub /'.self::HUB_SLUG.'/');

            return 0;
        }

        $id = (int) wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'După obiectiv',
            'post_name' => self::HUB_SLUG,
            'post_content' => '',
        ], true);

        $this->info('  + Creat hub: După obiectiv (/'.self::HUB_SLUG."/, ID {$id})");

        return $id;
    }

    /**
     * Rezolvă un slug de produs WC la ID (sau '' + avertisment).
     */
    private function resolveProduct(?string $slug, string $ctx): int|string
    {
        if (! $slug) {
            return '';
        }
        $wc = get_page_by_path($slug, OBJECT, 'product');
        if ($wc instanceof \WP_Post) {
            return (int) $wc->ID;
        }
        $this->warn("      ! Produs negăsit în WC: {$slug} ({$ctx}) — fallback nume/preț.");

        return '';
    }

    /**
     * Scrie toate câmpurile ACF pentru o pagină de obiectiv.
     */
    private function writeFields(int $id, array $o, string $slug): void
    {
        $hero = $o['hero'] ?? [];
        $reco = $o['reco'] ?? [];
        $alts = $o['alts'] ?? [];
        $bundle = $o['bundle'] ?? [];
        $how = $o['how'] ?? [];
        $reviews = $o['reviews'] ?? [];
        $edu = $o['edu'] ?? [];
        $faq = $o['faq'] ?? [];

        $alts_items = [];
        foreach (($alts['items'] ?? []) as $it) {
            $alts_items[] = [
                'produs' => $this->resolveProduct($it['produs_slug'] ?? null, $slug.' alt'),
                'nume' => $it['nume'] ?? '',
                'pret' => $it['pret'] ?? '',
                'desc' => $it['desc'] ?? '',
                'cta' => $it['cta'] ?? '',
            ];
        }

        $fields = [
            // Hero
            'hero_eyebrow' => $hero['eyebrow'] ?? '',
            'hero_titlu' => $hero['titlu'] ?? '',
            'hero_lede' => $hero['lede'] ?? '',
            'hero_cta_primary' => $hero['cta_primary'] ?? '',
            'hero_cta_secondary' => $hero['cta_secondary'] ?? '',
            'hero_stats' => array_map(static fn ($t) => ['text' => $t], $hero['stats'] ?? []),

            // Reco
            'reco_eyebrow' => $reco['eyebrow'] ?? '',
            'reco_titlu' => $reco['titlu'] ?? '',
            'reco_subtitlu' => $reco['subtitlu'] ?? '',
            'reco_produs' => $this->resolveProduct($reco['produs_slug'] ?? null, $slug.' reco'),
            'reco_nume' => $reco['nume'] ?? '',
            'reco_pret' => $reco['pret'] ?? '',
            'reco_durata' => $reco['durata'] ?? '',
            'reco_benefits' => array_map(static fn ($t) => ['text' => $t], $reco['benefits'] ?? []),
            'reco_cta' => $reco['cta'] ?? '',

            // Alts
            'alts_titlu' => $alts['titlu'] ?? '',
            'alts_items' => $alts_items,

            // Bundle
            'bundle_eyebrow' => $bundle['eyebrow'] ?? '',
            'bundle_titlu' => $bundle['titlu'] ?? '',
            'bundle_text' => $bundle['text'] ?? '',
            'bundle_cta' => $bundle['cta'] ?? '',
            'bundle_cta_url' => $bundle['cta_url'] ?? '',

            // How
            'how_eyebrow' => $how['eyebrow'] ?? '',
            'how_titlu' => $how['titlu'] ?? '',
            'how_items' => array_map(static fn ($s) => [
                'when' => $s['when'] ?? '',
                'body' => $s['body'] ?? '',
            ], $how['items'] ?? []),

            // Reviews
            'reviews_eyebrow' => $reviews['eyebrow'] ?? '',
            'reviews_titlu' => $reviews['titlu'] ?? '',
            'reviews_items' => array_map(static fn ($r) => [
                'rating' => (string) ($r['rating'] ?? 5),
                'quote' => $r['quote'] ?? '',
                'by' => $r['by'] ?? '',
            ], $reviews['items'] ?? []),
            'reviews_note' => $reviews['note'] ?? '',

            // Edu
            'edu_eyebrow' => $edu['eyebrow'] ?? '',
            'edu_titlu' => $edu['titlu'] ?? '',
            'edu_text' => $edu['text'] ?? '',

            // FAQ
            'faq_eyebrow' => $faq['eyebrow'] ?? '',
            'faq_titlu' => $faq['titlu'] ?? '',
            'faq_items' => array_map(static fn ($f) => [
                'q' => $f['q'] ?? '',
                'a' => $f['a'] ?? '',
            ], $faq['items'] ?? []),
        ];

        foreach ($fields as $name => $value) {
            update_field($name, $value, $id);
        }

        $this->line('      ↳ ACF scris ('.count($fields).' câmpuri).');
    }
}
