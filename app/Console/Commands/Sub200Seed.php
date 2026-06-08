<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function App\seed_sub200;

/**
 * Seedează pagina filtru „Suplimente sub 200 lei” + corectează protocol_zile/forma
 * pe produse. Logica trăiește în App\seed_sub200() (app/sub200-seed.php), partajată
 * cu pagina din admin „Unelte → Seed «Sub 200 lei»”.
 *
 *   wp acorn natura:sub200-seed
 *   wp acorn natura:sub200-seed --dry-run
 *   wp acorn natura:sub200-seed --force
 *   wp acorn natura:sub200-seed --force --skip-products
 */
class Sub200Seed extends Command
{
    protected $signature = 'natura:sub200-seed
                            {--force : Rescrie ACF-ul paginii și meta produselor chiar dacă există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}
                            {--skip-products : Nu atinge informatie_generala pe produse}';

    protected $description = 'Creează/populează pagina „Sub 200 lei” (ACF) și corectează protocol_zile pe produse.';

    public function handle(): int
    {
        if (! function_exists('App\\seed_sub200')) {
            $this->error('App\\seed_sub200() lipsește — rulează prin `wp acorn`.');

            return self::FAILURE;
        }

        $log = seed_sub200([
            'force' => (bool) $this->option('force'),
            'dry' => (bool) $this->option('dry-run'),
            'skip_products' => (bool) $this->option('skip-products'),
        ]);

        foreach ($log as $entry) {
            match ($entry['level']) {
                'error' => $this->error('  '.$entry['msg']),
                'warn' => $this->warn('  '.$entry['msg']),
                'info' => $this->info('  '.$entry['msg']),
                default => $this->line('  '.$entry['msg']),
            };
        }

        $hasError = (bool) array_filter($log, static fn ($e) => $e['level'] === 'error');

        return $hasError ? self::FAILURE : self::SUCCESS;
    }
}
