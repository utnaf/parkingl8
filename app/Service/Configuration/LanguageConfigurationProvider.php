<?php
declare(strict_types=1);

namespace Parking\Service\Configuration;

use Illuminate\Support\Facades\Lang;
use Parking\Service\Configuration\ConfigurationProvider;

/** @codeCoverageIgnore */
final class LanguageConfigurationProvider implements ConfigurationProvider {

    const NAME = 'translations';

    public function getConfigurationKey(): string {
        return static::NAME;
    }

    public function getConfigurationValue(): array {
        return Lang::get('frontend');
    }
}