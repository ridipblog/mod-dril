<?php

namespace ModDril\modularSystem\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ChangeStatusCommand extends Command
{
    protected $signature = 'module:status {domain} {status}';
    protected $description = 'Chnage module status';
    public function handle()
    {
        $domain = ucfirst($this->argument('domain'));
        $statusInput = strtolower($this->argument('status'));

        if (!in_array($statusInput, ['enabled', 'disabled'], true)) {
            $this->error('Status must be either true or false.');
            return Command::FAILURE;
        }

        $status = false;
        if ($statusInput === 'enabled') {
            $status = true;
        }
        $this->chnageStatus($domain, $status);
    }

    protected function chnageStatus(string $domain, bool $status)
    {
        $manifestPath = app_path('Domains/modules.json');
        if (!File::exists($manifestPath)) {
            $this->error('No module found.');
            return Command::FAILURE;
        }

        $modules = json_decode(File::get($manifestPath), true);

        if (!isset($modules[$domain])) {
            $this->error("Module {$domain} not found.");
            return Command::FAILURE;
        }

        $modules[$domain] = [
            'enabled' => $status
        ];
        File::put(
            $manifestPath,
            json_encode($modules, JSON_PRETTY_PRINT)
        );

        $this->info("Module {$domain} status updated.");
    }
}
