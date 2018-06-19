<?php

namespace Parking\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Parking\Repositories\IssueRepository;
use Parking\Service\ConfigurationService;

/** @codeCoverageIgnore */
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

            $view->with('config', app()->get(ConfigurationService::class)->get());
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
