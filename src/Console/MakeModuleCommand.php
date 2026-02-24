<?php

namespace ModDril\modularSystem\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a new domain module';

    public function handle()
    {

        $domain = ucfirst($this->argument('name'));
        $basePath = app_path("Domains/{$domain}");

        if (File::exists($basePath) && File::isDirectory($basePath)) {
            $this->error("Domain already exists.");
            return Command::FAILURE;
        }

        $folders = [
            'Http/Controllers',
            'Http/Requests',
            'Services',
            'Repositories',
            'Providers',
            'Routes',
            'Views',
        ];

        foreach ($folders as $folder) {
            File::makeDirectory("{$basePath}/{$folder}", 0755, true, true);
        }

        File::put(
            $basePath . "/Routes/web.php",
            $this->generateRouteStub()
        );

        // Create ServiceProvider file
        File::put(
            $basePath . "/Providers/{$domain}ServiceProvider.php",
            $this->getServiceProviderStub($domain)
        );
        $this->updateModuleManifest($domain);

        $this->info("Module {$domain} created successfully.");
    }

    // *** Generate main route file ***
    protected function generateRouteStub(): string
    {
        return "<?php
use Illuminate\Support\Facades\Route;
";
    }

    protected function getServiceProviderStub(string $domain): string
    {
        $viewName = strtolower($domain);
        return <<<PHP
<?php

namespace App\\Domains\\{$domain}\\Providers;

use Illuminate\Support\ServiceProvider;

class {$domain}ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes automatically
        \$this->loadRoutesFrom(
            app_path('Domains/{$domain}/Routes/web.php')
        );

         // Load views automatically
        \$this->loadViewsFrom(
            app_path('Domains/{$domain}/Views'),
            '{$viewName}'
        );
    }

    public function register()
    {
        //
    }
}
PHP;
    }

    // *** Update modules ***
    protected function updateModuleManifest(string $domain): void
    {
        $manifestPath = app_path('Domains/modules.json');

        if (!File::exists($manifestPath)) {
            File::put($manifestPath, json_encode([], JSON_PRETTY_PRINT));
        }

        $modules = json_decode(File::get($manifestPath), true);

        if (!isset($modules[$domain])) {
            $modules[$domain] = [
                'enabled' => true
            ];

            File::put(
                $manifestPath,
                json_encode($modules, JSON_PRETTY_PRINT)
            );
        }
    }
}
