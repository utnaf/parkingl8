<?php

namespace Parking\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Parking\Entry;
use Parking\Events\Models\EntryObserver;
use Parking\Repositories\EntryRepository;
use Parking\Repositories\IssueRepository;
use Parking\Repositories\ParkingLotRepository;
use Parking\Service\FreeSpotsService;
use Parking\Service\PriceCalculatorService;
use Parking\Service\Solver\EntryLateSolver;
use Parking\Service\Solver\LotFullSolver;
use Parking\Service\Solver\NotPayedSolver;
use Parking\Service\Solver\SolverService;
use Parking\Service\Validators\CanPayValidator;
use Parking\Service\Validators\ExitedAtValidator;
use Parking\Service\Validators\IsPayedValidator;
use Parking\Service\Validators\PriceValidator;
use Parking\Service\Validators\ValidatorFactory;

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
        $this->app->bind(
            EntryRepository::class,
            function($app) {
                return new EntryRepository(
                    new ParkingLotRepository,
                    new FreeSpotsService,
                    new ValidatorFactory,
                    new IssueRepository
                );
            }
        );

        $this->app->bind(CanPayValidator::class, function() {
            return new CanPayValidator(new IssueRepository);
        });
        $this->app->bind(ExitedAtValidator::class, function() {
            return new ExitedAtValidator(new IssueRepository);
        });
        $this->app->bind(IsPayedValidator::class, function() {
            return new IsPayedValidator(new IssueRepository);
        });
        $this->app->bind(PriceValidator::class, function() {
            return new PriceValidator(new IssueRepository);
        });

        $this->app->bind(SolverService::class, function($app) {
            return new SolverService(
                $app->get(NotPayedSolver::class),
                new IssueRepository
            );
        });
        $this->app->bind(LotFullSolver::class, function($app) {
            return new LotFullSolver(
                null
            );
        });
        $this->app->bind(NotPayedSolver::class, function($app) {
            return new NotPayedSolver(
                $app->get(LotFullSolver::class),
                $app->get(EntryRepository::class),
                new PriceCalculatorService
            );
        });
    }
}
