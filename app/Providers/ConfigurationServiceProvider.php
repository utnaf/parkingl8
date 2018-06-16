<?php
declare(strict_types=1);

namespace Parking\Providers;

use Illuminate\Support\ServiceProvider;
use Parking\Service\Configuration\LanguageConfigurationProvider;
use Parking\Service\ConfigurationService;

final class ConfigurationServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->singleton(
            ConfigurationService::class,
            function ($app) {
                $configurationService = new ConfigurationService();
                $configurationService->addProvider(new LanguageConfigurationProvider);

                return $configurationService;
            }
        );
    }

}