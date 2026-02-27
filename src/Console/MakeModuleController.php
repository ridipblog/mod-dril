<?php

namespace ModDril\modularSystem\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module-controller {domain} {controller}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domain = ucfirst($this->argument('domain'));
        $controllerInput = $this->argument('controller');

        $controllerInput = str_replace('\\', '/', $controllerInput);

        $controllerName = basename($controllerInput);

        $subPath = dirname($controllerInput) !== '.'
            ? dirname($controllerInput)
            : '';

        $basePath = app_path("Domains/{$domain}/Http/Controllers");

        $directoryPath = $subPath
            ? $basePath . '/' . $subPath
            : $basePath;

        $filePath = $directoryPath . '/' . ucfirst($controllerName) . '.php';

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        // Check if file already exists
        if (File::exists($filePath)) {
            $this->error("Controller already exists!");
            return;
            return Command::FAILURE;
        }

        // Controller template content
        $content = "<?php

namespace App\\Domains\\{$domain}\\Http\\Controllers;

use App\\Http\\Controllers\\Controller;

class {$controllerName} extends Controller
{
    public function index()
    {
        return view('{$domain}::index');
    }
}
";

        // Create file with content
        File::put($filePath, $content);

        $this->info("Controller {$controllerName} created successfully in Domain {$domain}.");
    }
}
