<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function App\seed_mega_suplimente;

/**
 * Seedează mega-meniul „Suplimente” (ACF options). Logica trăiește în
 * App\seed_mega_suplimente() (app/mega-suplimente-seed.php).
 *
 *   wp acorn natura:mega-suplimente-seed --dry-run
 *   wp acorn natura:mega-suplimente-seed
 *   wp acorn natura:mega-suplimente-seed --force
 */
class MegaSuplimenteSeed extends Command
{
    protected $signature = 'natura:mega-suplimente-seed
                            {--force : Rescrie ACF options chiar dacă există deja}
                            {--dry-run : Afișează ce ar face, fără să scrie nimic}';

    protected $description = 'Populează mega-meniul „Suplimente” (ACF options „Meniu”).';

    public function handle(): int
    {
        if (! function_exists('App\\seed_mega_suplimente')) {
            $this->error('App\\seed_mega_suplimente() lipsește — rulează prin `wp acorn`.');

            return self::FAILURE;
        }

        $log = seed_mega_suplimente([
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
