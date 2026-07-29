<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class ReleasePackageCommand extends Command
{
    protected $signature = 'reaper-ui:release {--dev : Push to master without creating a new tag}';

    protected $description = 'Sync packages/reaper/ui to the reaper-ui repo and cut a new tagged release';

    private const REMOTE = 'reaper-ui-origin';

    private const PREFIX = 'packages/reaper/ui';

    public function handle(): int
    {
        if ($this->option('dev')) {
            return $this->releaseDev();
        }

        $lastTag = $this->lastTag();
        $this->info('Last tag: '.($lastTag ?? '(none yet)'));

        $tag = $this->ask('New tag (e.g. v1.2.3)');

        if (! $tag) {
            $this->error('A tag is required.');

            return self::FAILURE;
        }

        if (! preg_match('/^v\d+\.\d+\.\d+$/', $tag)) {
            $this->error('Tag must look like vMAJOR.MINOR.PATCH, e.g. v1.2.3.');

            return self::FAILURE;
        }

        if ($lastTag === $tag) {
            $this->error("Tag {$tag} is the same as the last tag.");

            return self::FAILURE;
        }

        if (! $this->confirm("Sync package code and push tag {$tag} to ".self::REMOTE.'?', true)) {
            $this->warn('Aborted.');

            return self::SUCCESS;
        }

        $this->info('Syncing latest package code...');
        if (! $this->runGit(['git', 'subtree', 'push', '--prefix='.self::PREFIX, self::REMOTE, 'master'])) {
            return self::FAILURE;
        }

        $this->info('Fetching synced commit...');
        if (! $this->runGit(['git', 'fetch', self::REMOTE, 'master'])) {
            return self::FAILURE;
        }

        $this->info("Tagging {$tag}...");
        if (! $this->runGit(['git', 'tag', '-a', $tag, self::REMOTE.'/master', '-m', $tag])) {
            return self::FAILURE;
        }

        $this->info("Pushing tag {$tag}...");
        if (! $this->runGit(['git', 'push', self::REMOTE, $tag])) {
            return self::FAILURE;
        }

        $this->info("Released {$tag}.");

        return self::SUCCESS;
    }

    private function releaseDev(): int
    {
        if (! $this->confirm('Sync package code and push to master on '.self::REMOTE.'?', true)) {
            $this->warn('Aborted.');

            return self::SUCCESS;
        }

        $this->info('Syncing latest package code...');
        if (! $this->runGit(['git', 'subtree', 'push', '--prefix='.self::PREFIX, self::REMOTE, 'master'])) {
            return self::FAILURE;
        }

        $this->info('Pushed to master (dev-master).');

        return self::SUCCESS;
    }

    private function lastTag(): ?string
    {
        $result = Process::run(['git', 'ls-remote', '--tags', '--refs', self::REMOTE]);

        if ($result->failed()) {
            return null;
        }

        $tags = [];

        foreach (explode("\n", trim($result->output())) as $line) {
            if (preg_match('#refs/tags/(v\d+\.\d+\.\d+)$#', $line, $m)) {
                $tags[] = $m[1];
            }
        }

        if (empty($tags)) {
            return null;
        }

        usort($tags, fn ($a, $b) => version_compare($a, $b));

        return end($tags);
    }

    private function runGit(array $command): bool
    {
        $result = Process::run($command, function (string $type, string $output) {
            $this->output->write($output);
        });

        if ($result->failed()) {
            $this->error('Command failed: '.implode(' ', $command));

            return false;
        }

        return true;
    }
}
