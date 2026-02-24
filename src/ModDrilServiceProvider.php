<?php


namespace ModDril\modularSystem;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ModDrilServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/config/moddril.php', 'moddril');

        $this->registerDomains();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/moddril.php' => config_path('moddril.php')
        ], 'moddril-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MakeModuleCommand::class,
            ]);
            $this->commands([
                Console\ChangeStatusCommand::class,
            ]);
            $this->commands([
                Console\ModuleListCommand::class,
            ]);
        }
    }

    // *** Register all enabled domain ***
    protected function registerDomains(): void
    {
        $modulesPath = app_path('Domains');
        if (!is_dir($modulesPath)) {
            return;
        }

        $manifestPath = app_path('Domains/modules.json');

        if (!File::exists($manifestPath)) {
            return;
        }

        $modules = json_decode(File::get($manifestPath), true);

        foreach ($modules as $moduleName => $moduleData) {

            if (!($moduleData['enabled'] ?? false)) {
                continue;
            }

            $providerClass = "App\\Domains\\$moduleName\\Providers\\{$moduleName}ServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }
}
