<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function App\seed_pachet;

/**
 * Seedează conținutul editorial al paginilor de pachet (din mockup-urile
 * `preferinte/Pagina Pachet - *`). Logica trăiește în App\seed_pachet()
 * (app/pachet-seed.php), partajată cu pagina din admin „Unelte → Seed pagini pachet”.
 *
 *   wp acorn natura:pachet-seed
 *   wp acorn natura:pachet-seed --dry-run
 *   wp acorn natura:pachet-seed --force
 */
class PachetSeed extends Command
{
    protected $signature = 'natura:pachet-seed
                            {--force : Rescrie conținutul (ACF + excerpt) chiar dacă există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}';

    protected $description = 'Populează secțiunile editoriale ale paginilor de pachet (ACF) + descrierea scurtă.';

    public function handle(): int
    {
        if (! function_exists('App\\seed_pachet')) {
            $this->error('App\\seed_pachet() lipsește — rulează prin `wp acorn`.');

            return self::FAILURE;
        }

        $log = seed_pachet([
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
