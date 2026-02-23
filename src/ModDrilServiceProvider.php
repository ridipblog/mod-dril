<?php


namespace ModDril\modularSystem;

use Illuminate\Support\ServiceProvider;

class ModDrilServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeModuleCommand::class,
            ]);
        }
    }
}
