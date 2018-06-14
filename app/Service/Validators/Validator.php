<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Symfony\Component\HttpKernel\Exception\HttpException;

interface Validator {

    public function pass(array $data): bool;

    public function getException(): HttpException;

}