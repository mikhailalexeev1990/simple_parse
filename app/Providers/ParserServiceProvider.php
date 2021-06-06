<?php

namespace App\Providers;

use App\Console\Commands\ParseRbkNews;
use App\Services\Parser\ParseNews;
use App\Services\Parser\ParserInterface;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ParserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(ParseRbkNews::class)
            ->needs(ParserInterface::class)
            ->give(ParseNews::class);
        ;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
