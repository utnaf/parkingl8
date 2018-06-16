<?php

namespace Parking\Service\Configuration;

use Illuminate\Support\Facades\App;

final class LocaleConfigurationProvider implements ConfigurationProvider {

    const NAME = 'locale';

    public function getConfigurationKey(): string {
        return static::NAME;
    }

    public function getConfigurationValue(): array {
        return [
            'current' => App::getLocale(),
            'available' => [
                'it', 'en'
            ],
            'timezone' => config('app.timezone')
        ];
    }
}