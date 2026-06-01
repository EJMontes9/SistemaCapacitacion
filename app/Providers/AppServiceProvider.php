<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Ui\Button;
use App\View\Components\Ui\Alert;
use App\View\Components\Ui\Badge;
use App\View\Components\Ui\Card;
use App\View\Components\Ui\Table;
use App\View\Components\Ui\EmptyState;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('ui-button', Button::class);
        Blade::component('ui-alert', Alert::class);
        Blade::component('ui-badge', Badge::class);
        Blade::component('ui-card', Card::class);
        Blade::component('ui-table', Table::class);
        Blade::component('ui-empty-state', EmptyState::class);

        if (!file_exists(public_path('storage'))) {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
        }
    }
}
