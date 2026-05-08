<?php

namespace App\Console\Commands;

use App\Services\AddressDatasetBuilder;
use Illuminate\Console\Command;

/**
 * Import a Romanian postcode CSV into the sharded JSON dataset consumed by
 * the checkout address cascade. Thin wrapper around AddressDatasetBuilder.
 */
class AddressImport extends Command
{
    protected $signature = 'natura:address-import
                            {source : Path to the source CSV file}
                            {--out=public/data/postcodes : Output directory relative to theme root}
                            {--shard-bytes=800000 : Max bytes per shard before alphabetic split}';

    protected $description = 'Import Romanian postcodes CSV into sharded JSON files for client-side address cascade.';

    public function handle(): int
    {
        @ini_set('memory_limit', '1G');

        $source = (string) $this->argument('source');
        $outRel = (string) $this->option('out');
        $shardBytes = (int) $this->option('shard-bytes');

        $themeRoot = dirname(__DIR__, 3);
        $outAbs = rtrim($themeRoot, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.ltrim($outRel, '/\\');

        $this->info("Source: {$source}");
        $this->info("Output: {$outAbs}");
        $this->info("Shard limit: {$shardBytes} bytes");

        $builder = new AddressDatasetBuilder($shardBytes, fn ($line) => $this->line($line));

        try {
            $stats = $builder->build($source, $outAbs);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Done: '.json_encode($stats));

        return self::SUCCESS;
    }
}
