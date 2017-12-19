<?php

namespace RoyScheepens\HexonExport;

use RoyScheepens\HexonExport\Middleware\VerifyIpWhitelist;

use RoyScheepens\HexonExport\Models\Occasion;
use RoyScheepens\HexonExport\Models\OccasionImage;

use RoyScheepens\HexonExport\Observers\OccasionObserver;
use RoyScheepens\HexonExport\Observers\OccasionImageObserver;

use Illuminate\Support\ServiceProvider;

class HexonExportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hexon-export.php' => config_path('hexon-export.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations')
        ], 'migrations');

        $routeConfig = [
            'namespace' => 'RoyScheepens\HexonExport\Controllers',
            'middleware' => [VerifyIpWhitelist::class],
        ];

        // todo: add to api group?

        $this->app['router']->group($routeConfig, function($router)
        {
            $router->post($this->app['config']->get('hexon-export.url_endpoint'), [
                'uses' => 'HandleExportController@handle',
                'as' => 'hexon-export.export_handler'
            ]);
        });

        // Observers
        Occasion::observe(OccasionObserver::class);
        OccasionImage::observe(OccasionImageObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HexonExport::class, function () {
            return new HexonExport();
        });

        $this->app->alias(HexonExport::class, 'hexon-export');

        $this->mergeConfigFrom(
            __DIR__.'/../config/hexon-export.php', 'hexon-export'
        );
    }
}
