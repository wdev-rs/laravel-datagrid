<?php

namespace WdevRs\LaravelDatagrid;

use Illuminate\Support\ServiceProvider;
use WdevRs\LaravelDatagrid\Console\Commands\MakeDataGrid;

class LaravelDatagridServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-datagrid');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-datagrid');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-datagrid.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-datagrid'),
            ], 'views');

            // Publishing the gridjs frontend.
            $this->publishes([
                __DIR__.'/../resources/js/gridjs' => resource_path('js/vendor/laravel-datagrid/gridjs'),
            ], 'gridjs');

            // Publishing the datagrid frontend.
            $this->publishes([
                __DIR__.'/../resources/js/datagrid' => resource_path('js/vendor/laravel-datagrid/datagrid'),
            ], 'datagrid');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-datagrid'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-datagrid'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                MakeDataGrid::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-datagrid');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-datagrid', function () {
            return new LaravelDatagrid();
        });
    }
}
