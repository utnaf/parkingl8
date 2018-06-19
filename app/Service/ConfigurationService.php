<?php
declare(strict_types=1);

namespace Parking\Service;

use Parking\Service\Configuration\ConfigurationProvider;

final class ConfigurationService {

    /** @var ConfigurationProvider[] */
    private $providers = [];

    /** @var array */
    private $config = [];

    public function addProvider(ConfigurationProvider $provider) {
        $this->providers[] = $provider;
    }

    public function get(): array {
        if (empty($this->config)) {
            foreach ($this->providers as $provider) {
                if($provider->hasConfiguration()) {
                    $this->config[$provider->getConfigurationKey()] = $provider->getConfigurationValue();
                }
            }
        }

        return $this->config;
    }
}