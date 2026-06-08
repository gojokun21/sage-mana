<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function App\seed_noutati;

/**
 * Seedează pagina „Noutăți · În curând” (ACF + cele 3 tincturi). Logica trăiește
 * în App\seed_noutati() (app/noutati-seed.php), partajată cu admin + link.
 *
 *   wp acorn natura:noutati-seed --dry-run
 *   wp acorn natura:noutati-seed
 *   wp acorn natura:noutati-seed --force
 */
class NoutatiSeed extends Command
{
    protected $signature = 'natura:noutati-seed
                            {--force : Rescrie ACF-ul paginii chiar dacă există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}';

    protected $description = 'Creează/populează pagina „Noutăți · În curând” (ACF + tincturi).';

    public function handle(): int
    {
        if (! function_exists('App\\seed_noutati')) {
            $this->error('App\\seed_noutati() lipsește — rulează prin `wp acorn`.');

            return self::FAILURE;
        }

        $log = seed_noutati([
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
