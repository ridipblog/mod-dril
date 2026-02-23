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
        $name = $this->argument('name');
        $basePath = app_path("Domains/{$name}");

        if (File::exists($basePath)) {
            $this->error("Module already exists.");
            return;
        }

        File::makeDirectory($basePath . '/Providers', 0755, true);
        File::makeDirectory($basePath . '/Http/Controllers', 0755, true);
        File::makeDirectory($basePath . '/Routes', 0755, true);

        // Create ServiceProvider file
        File::put(
            $basePath . "/Providers/{$name}ServiceProvider.php",
            $this->getServiceProviderStub($name)
        );

        $this->info("Module {$name} created successfully.");
    }

    protected function getServiceProviderStub($name)
    {
        return "<?php

namespace App\\Domains\\{$name}\\Providers;

use Illuminate\Support\ServiceProvider;

class {$name}ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        //
    }
}
";
    }
}
