<?php
declare(strict_types=1);

namespace Parking\Service\Configuration;

interface ConfigurationProvider {

    public function hasConfiguration(): bool;

    public function getConfigurationKey(): string;

    public function getConfigurationValue(): array;

}