<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

final class ExitedAtValidator implements Validator {

    public function pass(array $data): bool {
        return !validator($data, [
            'exited_at' => 'date',
            'before' => 'now'
        ])->fails();
    }

    public function getException(): HttpException {
        return new NotAcceptableHttpException(
            'You exceeded the time threshold'
        );
    }
}