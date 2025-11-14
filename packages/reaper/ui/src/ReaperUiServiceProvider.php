<?php

namespace Reaper\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Reaper\Ui\View\Components\Btn;

class ReaperUiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'reaper');

        Blade::component(Btn::class, 'btn', 'reaper.ui');

        Route::prefix('reaper-ui')
            ->as("reaper-ui")
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });
    }
}
