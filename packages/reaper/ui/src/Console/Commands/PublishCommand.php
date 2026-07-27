<?php

namespace Reaper\Ui\Console\Commands;

use Illuminate\Console\Command;
use Reaper\Ui\ReaperUiServiceProvider;

class PublishCommand extends Command
{
    protected $signature = 'reaper-ui:publish
        {--tag=* : Publish only the given tag(s): reaper-ui-config, reaper-ui-assets. Defaults to all.}
        {--force : Overwrite any existing files at the destination}';

    protected $description = 'Publish the reaper/ui package config and assets';

    public function handle(): int
    {
        $params = [
            '--provider' => ReaperUiServiceProvider::class,
            '--force' => (bool) $this->option('force'),
        ];

        if (! empty($this->option('tag'))) {
            $params['--tag'] = $this->option('tag');
        }

        $this->call('vendor:publish', $params);

        return self::SUCCESS;
    }
}
