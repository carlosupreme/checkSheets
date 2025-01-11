<?php

namespace App\Providers;

use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\Alignment;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void {
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
          
        Notifications::alignment(Alignment::End);
    }
}
