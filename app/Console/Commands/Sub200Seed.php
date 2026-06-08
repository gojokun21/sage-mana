<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Seedează pagina filtru „Suplimente sub 200 lei”:
 *  1. Asigură o pagină cu template `template-sub-200.blade.php` (o creează dacă
 *     nu există) și îi populează conținutul EDITORIAL în ACF (app/acf-sub200.php)
 *     din database/seeds/sub200.php.
 *  2. Corectează `informatie_generala` (protocol_zile = ZILE cură + forma) pe
 *     produsele afișate, ca să iasă corect costul/zi din grilă și tabel.
 *
 * Idempotent. Pe pagina/produsele existente NU rescrie decât cu --force.
 *
 *   wp acorn natura:sub200-seed
 *   wp acorn natura:sub200-seed --dry-run
 *   wp acorn natura:sub200-seed --force
 *   wp acorn natura:sub200-seed --force --skip-products   (doar ACF-ul paginii)
 */
class Sub200Seed extends Command
{
    protected $signature = 'natura:sub200-seed
                            {--force : Rescrie ACF-ul paginii și meta produselor chiar dacă există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}
                            {--skip-products : Nu atinge informatie_generala pe produse}';

    protected $description = 'Creează/populează pagina „Sub 200 lei” (ACF) și corectează protocol_zile pe produse.';

    private const TEMPLATE = 'template-sub-200.blade.php';

    private const DEFAULT_SLUG = 'sub-200-lei';

    public function handle(): int
    {
        foreach (['get_page_by_path', 'wp_insert_post', 'update_field', 'get_field'] as $fn) {
            if (! function_exists($fn)) {
                $this->error("Funcția {$fn}() lipsește — rulează prin `wp acorn`.");

                return self::FAILURE;
            }
        }

        $force = (bool) $this->option('force');
        $dry = (bool) $this->option('dry-run');
        $skipProducts = (bool) $this->option('skip-products');

        $seedFile = dirname(__DIR__, 3).'/database/seeds/sub200.php';
        if (! is_file($seedFile)) {
            $this->error("Fișierul de seed lipsește: {$seedFile}");

            return self::FAILURE;
        }

        $data = require $seedFile;
        if (! is_array($data) || empty($data['page'])) {
            $this->error('Seed-ul nu a returnat un array valid.');

            return self::FAILURE;
        }

        // --- 1. Pagina ---
        $page_id = $this->ensurePage($data['page'], $dry, $force);

        // --- 2. Produse (informatie_generala) ---
        if (! $skipProducts) {
            $this->seedProducts($data['products_meta'] ?? [], $dry, $force);
        }

        if ($dry) {
            $this->info('Dry-run complet. Nu s-a scris nimic.');

            return self::SUCCESS;
        }

        $this->info('Gata.'.($page_id ? " Pagina ID {$page_id}." : ''));

        return self::SUCCESS;
    }

    /**
     * Găsește (după template) sau creează pagina, apoi scrie ACF-ul editorial.
     */
    private function ensurePage(array $page, bool $dry, bool $force): int
    {
        $existing = get_posts([
            'post_type' => 'page',
            'post_status' => 'any',
            'numberposts' => 1,
            'fields' => 'ids',
            'meta_key' => '_wp_page_template',
            'meta_value' => self::TEMPLATE,
        ]);
        $id = ! empty($existing) ? (int) $existing[0] : 0;

        if ($dry) {
            $this->line($id
                ? "  [dry] pagina există (ID {$id}) → ".($force ? 'ACF ar fi rescris' : 'ACF păstrat')
                : '  [dry] ar crea pagina „'.($page['title'] ?? 'Sub 200 lei').'” cu template '.self::TEMPLATE);

            return $id;
        }

        if (! $id) {
            $id = (int) wp_insert_post([
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_title' => (string) ($page['title'] ?? 'Suplimente sub 200 lei'),
                'post_name' => (string) ($page['slug'] ?? self::DEFAULT_SLUG),
                'post_content' => '',
                'meta_input' => ['_wp_page_template' => self::TEMPLATE],
            ], true);

            if (! $id || $id instanceof \WP_Error) {
                $this->error('  Eșec la crearea paginii.');

                return 0;
            }
            $this->info("  + Creat: {$page['title']} (ID {$id})");
        } else {
            update_post_meta($id, '_wp_page_template', self::TEMPLATE);
            $this->line("  ~ Pagina există (ID {$id}).");

            if (! $force) {
                $this->line('      ↳ ACF păstrat (folosește --force pentru a rescrie).');

                return $id;
            }
        }

        $this->writePageFields($id, $page);

        return $id;
    }

    /**
     * Scrie toate câmpurile ACF editoriale ale paginii.
     */
    private function writePageFields(int $id, array $p): void
    {
        $hero = $p['hero'] ?? [];
        $explain = $p['explain'] ?? [];
        $products = $p['products'] ?? [];
        $table = $p['table'] ?? [];
        $bridge = $p['bridge'] ?? [];
        $faq = $p['faq'] ?? [];
        $cta = $p['cta'] ?? [];

        $fields = [
            // Hero
            'hero_eyebrow' => $hero['eyebrow'] ?? '',
            'hero_titlu' => $hero['titlu'] ?? '',
            'hero_lede' => $hero['lede'] ?? '',
            'hero_cpd_tagline' => $hero['cpd_tagline'] ?? '',
            'chip_all_label' => $hero['chip_all_label'] ?? '',
            'chip_vegan_label' => $hero['chip_vegan_label'] ?? '',
            'chip_long_label' => $hero['chip_long_label'] ?? '',
            'chip_short_label' => $hero['chip_short_label'] ?? '',

            // Explain
            'explain_eyebrow' => $explain['eyebrow'] ?? '',
            'explain_titlu' => $explain['titlu'] ?? '',
            'explain_cards' => array_map(static fn ($c) => [
                'titlu' => $c['titlu'] ?? '',
                'text' => $c['text'] ?? '',
                'link_text' => $c['link_text'] ?? '',
                'link_url' => $c['link_url'] ?? '',
            ], $explain['cards'] ?? []),

            // Grilă produse
            'products_titlu' => $products['titlu'] ?? '',
            'products_meta' => $products['meta'] ?? '',
            'products_empty' => $products['empty'] ?? '',

            // Tabel
            'table_eyebrow' => $table['eyebrow'] ?? '',
            'table_titlu' => $table['titlu'] ?? '',
            'table_intro' => $table['intro'] ?? '',
            'table_note' => $table['note'] ?? '',

            // Bridge
            'bridge_eyebrow' => $bridge['eyebrow'] ?? '',
            'bridge_titlu' => $bridge['titlu'] ?? '',
            'bridge_text' => $bridge['text'] ?? '',
            'bridge_link_text' => $bridge['link_text'] ?? '',
            'bridge_link_url' => $bridge['link_url'] ?? '',

            // FAQ
            'faq_titlu' => $faq['titlu'] ?? '',
            'faq_items' => array_map(static fn ($f) => [
                'q' => $f['q'] ?? '',
                'a' => $f['a'] ?? '',
            ], $faq['items'] ?? []),

            // CTA
            'cta_titlu' => $cta['titlu'] ?? '',
            'cta_text' => $cta['text'] ?? '',
            'cta_btn_text' => $cta['btn_text'] ?? '',
            'cta_btn_url' => $cta['btn_url'] ?? '',
        ];

        foreach ($fields as $name => $value) {
            update_field($name, $value, $id);
        }

        $this->line('      ↳ ACF scris ('.count($fields).' câmpuri).');
    }

    /**
     * Corectează informatie_generala (forma + protocol_zile) pe produse, fără să
     * atingă restul subcâmpurilor grupului (read-merge-write).
     */
    private function seedProducts(array $map, bool $dry, bool $force): void
    {
        if (empty($map)) {
            return;
        }

        $this->line('  Produse (informatie_generala):');

        foreach ($map as $slug => $vals) {
            $wc = get_page_by_path((string) $slug, OBJECT, 'product');
            if (! $wc instanceof \WP_Post) {
                $this->warn("    ! Produs negăsit: {$slug}");

                continue;
            }
            $pid = (int) $wc->ID;
            $info = get_field('informatie_generala', $pid);
            $info = is_array($info) ? $info : [];

            $current_days = isset($info['protocol_zile']) ? (int) $info['protocol_zile'] : 0;
            $target_days = (int) ($vals['protocol_zile'] ?? 0);

            if ($dry) {
                $this->line(sprintf('    [dry] %-45s protocol_zile %s → %d', $slug, $current_days ?: '∅', $target_days));

                continue;
            }

            // Fără --force nu suprascriem o valoare deja corectă (egală cu ținta).
            if (! $force && $current_days === $target_days && ! empty($info['forma'])) {
                $this->line("    = {$slug} (deja {$target_days} zile)");

                continue;
            }

            $info['protocol_zile'] = $target_days;
            if (! empty($vals['forma'])) {
                $info['forma'] = (string) $vals['forma'];
            }
            update_field('informatie_generala', $info, $pid);

            $this->info("    ~ {$slug}: protocol_zile = {$target_days}".(! empty($vals['forma']) ? ", forma = „{$vals['forma']}”" : ''));
        }
    }
}
