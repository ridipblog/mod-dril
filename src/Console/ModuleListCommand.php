<?php

namespace ModDril\modularSystem\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleListCommand extends Command
{
    protected $signature = 'module:list';
    protected $description = 'Show all module list';
    public function handle()
    {
        $manifestPath = app_path('Domains/modules.json');
        if (!File::exists($manifestPath)) {
            $this->info('No module found.');
            return Command::FAILURE;
        }

        $modules = json_decode(File::get($manifestPath), true);

        if (empty($modules)) {
            $this->info('No modules found.');
            return Command::SUCCESS;
        }

        $rows = [];

        foreach ($modules as $name => $data) {
            $rows[] = [
                'Module' => $name,
                'Status' => ($data['enabled'] ?? false) ? 'Enabled' : 'Disabled',
            ];
        }

        $this->table(
            ['Module', 'Status'],
            $rows
        );
    }
}
