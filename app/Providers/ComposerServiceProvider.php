<?php

namespace Parking\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Parking\Repositories\IssueRepository;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view)
        {
            $view->with('issuesCount', (new IssueRepository())->openIssueCount());
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
