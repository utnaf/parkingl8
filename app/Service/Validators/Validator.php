<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Parking\Entry;
use Symfony\Component\HttpKernel\Exception\HttpException;

interface Validator {

    public function pass(array $data): bool;

    public function getException(): HttpException;

    public function forEntry(Entry $entry): Validator;

}