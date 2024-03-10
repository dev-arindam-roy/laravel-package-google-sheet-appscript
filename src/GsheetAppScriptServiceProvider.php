<?php

namespace Arindam\GsheetAppScript;

use Illuminate\Support\ServiceProvider;
use Arindam\GsheetAppScript\Gsheet\GsheetAppScriptClass;

class GsheetAppScriptServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind('gsheetappscriptclass', function() {
            return new GsheetAppScriptClass();
        });

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'gsheet-appscript');

        $this->mergeConfigFrom(
            __DIR__ . '/config/gsheet-appscript.php', 'gsheet-appscript'
        );

        $this->publishes([
            __DIR__ . '/config/gsheet-appscript.php' => config_path('gsheet-appscript.php')
        ], 'gsheet-appscript:config');

        //php artisan vendor:publish --provider="Arindam\GsheetAppScript\GsheetAppScriptServiceProvider" --force
        //php artisan vendor:publish --tag="gsheet-appscript:config"
    }
}