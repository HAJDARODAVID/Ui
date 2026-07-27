<?php

namespace Reaper\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Reaper\Ui\Console\Commands\PublishCommand;
use Reaper\Ui\Livewire\GlobalModal;
use Reaper\Ui\View\Components\Btn;

class ReaperUiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/global-modal.php', 'global-modal');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'reaper');

        Blade::component(Btn::class, 'btn', 'reaper.ui');

        Livewire::component('global-modal', GlobalModal::class);

        Route::prefix('reaper-ui')
            ->as("reaper-ui")
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });

        $this->publishes([
            __DIR__ . '/../config/global-modal.php' => config_path('global-modal.php'),
        ], 'reaper-ui-config');

        $this->publishes([
            __DIR__ . '/../resources/js/global-modal.js' => resource_path('js/vendor/reaper-ui/global-modal.js'),
        ], 'reaper-ui-assets');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishCommand::class,
            ]);
        }
    }
}
