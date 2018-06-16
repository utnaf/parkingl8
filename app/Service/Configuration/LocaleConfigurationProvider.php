<?php

namespace Parking\Service\Configuration;

use Illuminate\Support\Facades\App;

/** @codeCoverageIgnore */
final class LocaleConfigurationProvider implements ConfigurationProvider {

    const NAME = 'locale';

    const AVAILABLE_LOCALES = [
        'it', 'en'
    ];

    public function getConfigurationKey(): string {
        return static::NAME;
    }

    public function getConfigurationValue(): array {
        return [
            'current' => App::getLocale(),
            'available' => static::AVAILABLE_LOCALES,
            'timezone' => config('app.timezone')
        ];
    }
}