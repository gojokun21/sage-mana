<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function App\seed_pdp;

/**
 * Seedează conținutul editorial PDP pe produse (din mockup-urile `preferinte/PDP - *`).
 * Logica trăiește în App\seed_pdp() (app/pdp-seed.php), partajată cu pagina din
 * admin „Unelte → Seed PDP produse”.
 *
 *   wp acorn natura:pdp-seed
 *   wp acorn natura:pdp-seed --dry-run
 *   wp acorn natura:pdp-seed --force
 */
class PdpSeed extends Command
{
    protected $signature = 'natura:pdp-seed
                            {--force : Rescrie conținutul PDP (ACF + excerpt) chiar dacă există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}';

    protected $description = 'Populează secțiunile editoriale PDP (ACF) + descrierea scurtă pe produse.';

    public function handle(): int
    {
        if (! function_exists('App\\seed_pdp')) {
            $this->error('App\\seed_pdp() lipsește — rulează prin `wp acorn`.');

            return self::FAILURE;
        }

        $log = seed_pdp([
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

        $hasError = (bool) array_filter($log, static fn ($e) => $e['level'] === 'error');

        return $hasError ? self::FAILURE : self::SUCCESS;
    }
}
