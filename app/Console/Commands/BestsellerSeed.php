<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function App\seed_bestseller;

/**
 * Seedează pagina „Cele mai vândute” (ACF + produse reale). Logica trăiește în
 * App\seed_bestseller() (app/bestseller-seed.php), partajată cu pagina din admin
 * și cu scriptul prin link.
 *
 *   wp acorn natura:bestseller-seed --dry-run
 *   wp acorn natura:bestseller-seed
 *   wp acorn natura:bestseller-seed --force
 */
class BestsellerSeed extends Command
{
    protected $signature = 'natura:bestseller-seed
                            {--force : Rescrie ACF-ul paginii chiar dacă există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}';

    protected $description = 'Creează/populează pagina „Cele mai vândute” (ACF) și leagă produsele reale.';

    public function handle(): int
    {
        if (! function_exists('App\\seed_bestseller')) {
            $this->error('App\\seed_bestseller() lipsește — rulează prin `wp acorn`.');

            return self::FAILURE;
        }

        $log = seed_bestseller([
            'force' => (bool) $this->option('force'),
            'dry' => (bool) $this->option('dry-run'),
        ]);

        foreach ($log as $entry) {
            match ($entry['level']) {
                'error' => $this->error('  '.$entry['msg']),
                'warn' => $this->warn('  '.$entry['msg']),
                'info' => $this->info('  '.$entry['msg']),
                default => $this->line('  '.$entry['msg']),
            };
        }

        return array_filter($log, static fn ($e) => $e['level'] === 'error') ? self::FAILURE : self::SUCCESS;
    }
}
