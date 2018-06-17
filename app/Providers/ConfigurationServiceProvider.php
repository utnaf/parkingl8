<?php
declare(strict_types=1);

namespace Parking\Providers;

use Illuminate\Support\ServiceProvider;
use Parking\Service\Configuration\LanguageConfigurationProvider;
use Parking\Service\Configuration\LocaleConfigurationProvider;
use Parking\Service\Configuration\UserConfigrationProvider;
use Parking\Service\ConfigurationService;

/** @codeCoverageIgnore */
final class ConfigurationServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->singleton(
            ConfigurationService::class,
            function ($app) {
                $configurationService = new ConfigurationService;
                $configurationService->addProvider(new LanguageConfigurationProvider);
                $configurationService->addProvider(new LocaleConfigurationProvider);
                $configurationService->addProvider(new UserConfigrationProvider);

                return $configurationService;
            }
        );
    }

}