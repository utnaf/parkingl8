<?php

namespace Parking\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Parking\Entry;
use Parking\Events\Models\EntryObserver;

/** @codeCoverageIgnore */
class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(191);
        Entry::observe(EntryObserver::class);
        date_default_timezone_set(config('app.timezone'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
