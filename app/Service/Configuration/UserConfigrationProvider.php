<?php
declare(strict_types=1);

namespace Parking\Service\Configuration;

use Illuminate\Support\Facades\Auth;
use Parking\User;

/** @codeCoverageIgnore */
final class UserConfigrationProvider implements ConfigurationProvider {

    const NAME = 'user';

    public function getConfigurationKey(): string {
        return static::NAME;
    }

    public function getConfigurationValue(): array {
        return [
            'isAdmin' => Auth::user()->role === User::ROLE_ADMIN
        ];
    }

    public function hasConfiguration(): bool {
        return Auth::check();
    }
}