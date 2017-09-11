<?php

namespace Seekerliu\ViewComposerGenerator;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadConfig();

        $this->loadCommand();
    }

    /**
     * Register the provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge config.
        $this->mergeConfigFrom(
            realpath(__DIR__.'/config.php'), 'view-composer-generator'
        );
    }

    public function loadConfig()
    {
        // Config path.
        $configPath = realpath(__DIR__.'/config.php');

        // Publish config.
        $this->publishes(
            [$configPath => config_path('view-composer-generator.php')],
            'view-composer-generator'
        );
    }

    public function loadCommand()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ViewComposerMakeCommand::class,
            ]);
        }
    }
}
