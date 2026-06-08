<?php

namespace App\Providers;

use App\Console\Commands\AddressImport;
use App\Console\Commands\ObiectivSeed;
use App\Console\Commands\SimptomSeed;
use App\Console\Commands\Sub200Seed;
use App\Console\Commands\BestsellerSeed;
use App\Console\Commands\NoutatiSeed;
use App\Console\Commands\MegaSuplimenteSeed;
use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddressImport::class,
                SimptomSeed::class,
                ObiectivSeed::class,
                Sub200Seed::class,
                BestsellerSeed::class,
                NoutatiSeed::class,
                MegaSuplimenteSeed::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
