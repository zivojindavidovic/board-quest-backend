<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
        $this->configureModels();
    }

    /**
     * Properties set to unguarded
     * Prevents lazy loading
     */
    private function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict();
    }
}
