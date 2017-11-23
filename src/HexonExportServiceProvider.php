<?php

namespace RoyScheepens\HexonExport;

use RoyScheepens\HexonExport\Middleware\VerifyHexonIps;

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

        $routeConfig = [
            'namespace' => 'RoyScheepens\HexonExport\Controllers',
            'middleware' => [VerifyIpWhitelist::class],
        ];

        $this->app['router']->group($routeConfig, function($router)
        {
            $router->post($this->app['config']->get('hexon-export.url_endpoint'), [
                'uses' => 'HandleExportController@handle',
                'as' => 'hexon-export.export_handler'
            ]);
        });
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
